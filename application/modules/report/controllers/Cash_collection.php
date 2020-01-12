<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cash_collection extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
         $this->auth->restrict('Report.CashCollection.View');
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
    	//print_r($search_box);
    	$data['cash_collection'] = $this->getCashCollection($filter);
    	//echo '<pre>';print_r($data['cash_collection']);exit;
    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'cash_collection/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "Cash Collection List");
        Template::set($data);
       	Template::set_block('sub_nav', 'cash_collection/_sub_report');
        Template::set_view('report_template');
		Template::render();
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
				 ->select('s.id as service_id,s.service_name, IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount, IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount, tp.collection_date
				 ')
				 ->from('lib_discount_service_setup as s')
				 ->join('transaction_mst as tmst','s.id = tmst.service_id','left')
				 ->join('transaction_payment as tp','tmst.id = tp.mst_id','left')
				 ->where($where)
				 ->or_where([
				 	'tp.collection_date' => null
				 ])
				 ->group_by('s.id')
				 ->group_by('tp.transaction_type')
                 ->group_by('date(tp.collection_date)')
				 ->order_by('service_id','asc')
				 ->get()
				 ->result();
			//echo '<pre>';print_r($result);exit;
			$data_array = array();
			foreach ($result as $key => $val) {
				if (isset($data_array[$val->service_id])) {
					$data_array[$val->service_id]['paid_amount'] += $val->paid_amount;
					$data_array[$val->service_id]['due_paid_amount'] += $val->due_paid_amount;
					$data_array[$val->service_id]['refund_amount'] += $val->refund_amount;
				} else {
					$data_array[$val->service_id] = array(
						'service_id' => $val->service_id,
						'service_name' => $val->service_name,
						'paid_amount' => $val->paid_amount,
						'due_paid_amount' => $val->due_paid_amount,
						'refund_amount' => $val->refund_amount
					);
				}
			}
		return $data_array;
	}
    

}