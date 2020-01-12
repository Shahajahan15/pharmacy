<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Discount extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        //$this->lang->load('userwise');
        $this->auth->restrict('Report.CanteenDiscount.View');
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

        $data['canteen_collectin'] = $this->getCanteenCashCollection($filter);
        $result = $this->getCanteenDiscount();
    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'canteen/discount/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "Discount List(Canteen)");
        Template::set($data);
        Template::set_block('sub_nav', 'canteen/discount/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }

    protected function getCanteenDiscount() {
        $result = $this->db
                    //->select('')
                    ->from('canteen_sales_mst as csm')
                    ->join('canteen_sales_dtls as csd','csm.id = csd.master_id')
                    ->get()
                    ->result();
    }

    protected function getCanteenCashCollection($filter = array()) {

        if (isset($filter['from_date'])) {
            $where['ct.collection_paid_at >='] = $filter['from_date']." 00:00:00";
        }
        if (isset($filter['to_date'])) {
            $where['ct.collection_paid_at <='] = $filter['to_date']." 23:59:59";
        }

        if (!isset($filter['from_date']) && !isset($filter['to_date'])) {
            $where['ct.collection_paid_at >='] = date('Y-m-d 00:00:00');
            $where['ct.collection_paid_at <='] = date('Y-m-d 23:59:59');
        }

        $result = $this->db
                    ->select('ct.collection_by,IF(ct.transaction_type = 1,SUM(ct.amount),0) as collectin_amount, IF(ct.transaction_type = 3,SUM(ct.amount),0) as return_amount, emp.EMP_NAME as user_name')
                    ->from('canteen_transaction as ct')
                    ->join('users as u', 'u.id = ct.collection_by', 'left')
                    ->join('hrm_ls_employee as emp','emp.EMP_ID = u.employee_id','left')
                    ->where('ct.transaction_type !=', 2)
                    ->where($where)
                    ->group_by('ct.transaction_type')
                    ->group_by('ct.collection_by')
                    ->get()
                    ->result();
        $data_array = [];
        if ($result) {
            foreach ($result as $key => $row) {
                if (isset($data_array[$row->collection_by])) {
                    $data_array[$row->collection_by]['collectin_amount'] += $row->collectin_amount;
                    $data_array[$row->collection_by]['return_amount'] += $row->return_amount;
                } else {
                    $data_array[$row->collection_by]['user_name'] = $row->user_name;
                    $data_array[$row->collection_by]['collection_by'] = $row->collection_by;
                    $data_array[$row->collection_by]['collectin_amount'] = $row->collectin_amount;
                    $data_array[$row->collection_by]['return_amount'] = $row->return_amount;
                }
            }
        }

        return $data_array;
    }

}