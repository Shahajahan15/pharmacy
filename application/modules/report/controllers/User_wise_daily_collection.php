<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_wise_daily_collection extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
        $this->auth->restrict('Report.UserCollection.View');
    }

    
    public function index()
    {
        $data = array();
        $filter = array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['per_page_flag'] = 0;

        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }

        $result = $this->getCashCollection($filter);
        //echo '<pre>';print_r($result);exit;
        $data['user_wise_collection'] = $this->getUserWiseCollection($result, 1);
        $data['user_wise_refund'] = $this->getUserWiseCollection($result, 2);
        $data['user_wise_collection_last'] = $this->getUserWiseCollectionLast($result);
        //echo '<pre>';print_r($data['user_wise_refund']);
        //echo '<pre>';print_r($data['user_wise_collection']);exit;
        $data['service_list'] = $this->db
        ->where('lib_discount_service_setup.id!=',2)
        ->where('lib_discount_service_setup.id!=',5)
        ->where('lib_discount_service_setup.id!=',6)

        ->get('lib_discount_service_setup')
        ->result();
        $data['user_list'] = $this->db->where('role_id',10)->get('users')->result();
        $data['search_box'] = $search_box;
        $data['list_view'] = 'user_wise_collection/index';

        if ($this->input->is_ajax_request()) {
            if(isset($_POST['print'])){
                echo $this->load->view('user_wise_collection/print_report', $data, true);
                exit;
            }
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "User Wise Cash Collection List");
        Template::set($data);
        Template::set_block('sub_nav', 'user_wise_collection/daily_sub_report');
        Template::set_view('user_wise_collection/user_daily_collection');
        Template::render();
    }
    
    private function getUserWiseCollectionLast($result)
    {
        $data_array = array();
            foreach ($result as $key => $row) {
                    if (isset($data_array[$row->service_id])){
                    $data_array[$row->service_id]['paid_amount'] += $row->paid_amount;
                    $data_array[$row->service_id]['due_paid_amount'] += $row->due_paid_amount;
                    $data_array[$row->service_id]['refund_amount'] += $row->refund_amount;
                } else {
                    $data_array[$row->service_id]['paid_amount'] = $row->paid_amount;
                    $data_array[$row->service_id]['due_paid_amount'] = $row->due_paid_amount;
                    $data_array[$row->service_id]['refund_amount'] = $row->refund_amount;
                }
            }
        return $data_array;
    }
    
    private function getUserWiseCollection($result, $type)
    {
        $data_array = array();
            foreach ($result as $key => $row) {
                if ($row->collection_by) {
                    if ($type == 1) {
                        if (isset($data_array[$row->collection_by][$row->service_id])){
                    $data_array[$row->collection_by][$row->service_id]['paid_amount'] += $row->paid_amount;
                    $data_array[$row->collection_by][$row->service_id]['due_paid_amount'] += $row->due_paid_amount;
                } else {
                    $data_array[$row->collection_by][$row->service_id]['paid_amount'] = $row->paid_amount;
                    $data_array[$row->collection_by][$row->service_id]['due_paid_amount'] = $row->due_paid_amount;
                }
            } elseif ($type == 2) {
                 if (isset($data_array[$row->collection_by])){
                    $data_array[$row->collection_by]['refund_amount'] += $row->refund_amount;
                } else {
                    $data_array[$row->collection_by]['refund_amount'] = $row->refund_amount;
                }
            }
                    
                }
            }
        return $data_array;
    }
    
    private function getCashCollection($filter = array())
    {
        if (isset($filter['from_date'])) {
            $where['tp.collection_date >='] = $filter['from_date']." 00:00:00";
        }
        if (isset($filter['to_date'])) {
            $where['tp.collection_date <='] = $filter['to_date']." 23:59:59";
        }

        if (!isset($filter['from_date']) && !isset($filter['to_date'])) {
            $where['tp.collection_date >='] = date('Y-m-d 00:00:00');
            $where['tp.collection_date <='] = date('Y-m-d 23:59:59');
        }

        $result = $this->db
                 ->select('s.id as service_id,s.service_name, tp.collection_by, IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount, IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount')
                 ->from('lib_discount_service_setup as s')
                 ->join('transaction_mst as tmst','s.id = tmst.service_id','left')
                 ->join('transaction_payment as tp','tmst.id = tp.mst_id','left')
                 ->where($where)
                 ->or_where([
                    'tp.collection_date' => null
                 ])
                 ->group_by('s.id')
                 ->group_by('tp.transaction_type')
                 ->group_by('tp.collection_by')
                 ->group_by('date(tp.collection_date)')
                 ->order_by('service_id','asc')
                 ->get()
                 ->result();
        return $result;
    }
    public function report_print(){
       
        return $this->index();
    }

}