<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_wise_collection extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        //$this->lang->load('userwise');
        $this->auth->restrict('Report.CanteenUserCollection.View');
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
        //echo "<pre>";print_r($data['canteen_collectin']);exit();
    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'canteen/user_wise_collection/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "User Wise Cash Collection List(Canteen)");
        Template::set($data);
        Template::set_block('sub_nav', 'canteen/user_wise_collection/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }

    protected function getCanteenCashCollection($filter = array()) {

      
        $result = $this->db
                    ->select('ct.collection_by,IF(ct.transaction_type = 1,SUM(ct.amount),0) as collectin_amount, IF(ct.transaction_type = 3,SUM(ct.amount),0) as return_amount, emp.EMP_NAME as user_name')
                    ->from('canteen_transaction as ct')
                    ->join('users as u', 'u.id = ct.collection_by', 'left')
                    ->join('hrm_ls_employee as emp','emp.EMP_ID = u.employee_id','left')
                    ->where('ct.transaction_type !=', 2)
                    //->where($where)
                    ->group_by('ct.transaction_type')
                    ->group_by('ct.collection_by')
                    ->get()
                    ->result();

            // echo '<pre>';
            // print_r($result);
            // exit();



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