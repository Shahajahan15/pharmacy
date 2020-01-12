<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Money_receipt_wise_collection extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
        $this->auth->restrict('Report.MReceipt.View');
	}

    
    public function index($offset = 0)
    {

    	$data = array();
    	$filter = array();
    	 $offset = (int) $this->input->get('per_page');
        $limit = (int) $this->input->post('per_page') ?: 25;
    	$search_box = $this->searchpanel->getSearchBox($this->current_user);
       
    	$search_box['common_text_search_flag'] = 0;
        $search_box['discount_service_list_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $search_box['patient_id_flag'] = 1;

       // echo '<pre>';print_r($search_box);exit;

       


    	if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
        if ($this->input->is_ajax_request()) {
           // print_r($_POST);exit;
            $filter['service_id'] = $this->input->post('discount_service_id');
            $filter['mr_no'] = $this->input->post('mr_num');
            $filter['patient_id'] = $this->input->post('patient_id');

        }

    	$data['mr_wise_collection'] = $this->getMoneyReceiptWiseCollection($filter, $limit, $offset);
    	$data['mr_wise_collection_dtls'] = $this->getMoneyReceiptWiseDtlsCollection();

    	$this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/money_receipt_wise_collection/report/index');
        $this->pager['total_rows'] = count($this->getMoneyReceiptWiseCollection($filter, 0));
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'money_receipt_wise_collection/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
    	//echo '<pre>';print_r(count($data['mr_wise_collection']));exit;
        Template::set("toolbar_title", "Money Receipt Wise Collection List");
        Template::set($data);
        Template::set_block('sub_nav', 'money_receipt_wise_collection/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }
    
    private function getMoneyReceiptWiseCollection($filter = array(), $limit = null, $offset = null)
    {
        $like = array();
    	if (!isset($filter['from_date'])) {
			$filter['from_date'] = date('Y-m-d');
		}    	
		if (!isset($filter['to_date'])) {
			//$filter['to_date'] = date('Y-m-d');
		}    	

    	if (isset($filter['from_date'])) {
    		$where['tp.collection_date >='] = $filter['from_date']." 00:00:00";
    	}
    	if (isset($filter['to_date'])) {
    		$where['tp.collection_date <='] = $filter['to_date']." 23:59:59";
    	}

        if ($this->input->is_ajax_request()) {
            if ($filter['service_id']) {
                $where['tmst.service_id'] = $filter['service_id'];
            }
            if ($filter['mr_no']) {
            $where['tmst.mr_no like']='%'.trim($filter['mr_no']).'%';
            }
            if ($filter['patient_id']) {
            $where['pmt.patient_id like']='%'.trim($filter['patient_id']).'%';
            }
        }

		$result = $this->db
					->select('tmst.patient_id,tmst.id,source_id,service_id,tot_bill,transaction_type,tp.collection_by,tp.collection_date,IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount, IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount,s.service_name,pmt.patient_id as patient_code,emp.EMP_NAME as emp_name,tmst.mr_no')
					->from('transaction_payment as tp')
					->join('transaction_mst as tmst','tp.mst_id = tmst.id')
					->join('lib_discount_service_setup as s','tmst.service_id = s.id')
					->join('patient_master as pmt','tmst.patient_id = pmt.id')
					->join('users as u','tp.collection_by = u.id')
					->join('hrm_ls_employee as emp','u.employee_id = emp.EMP_ID')
					->where($where)
					//->group_by('date(tp.collection_date)')
					->group_by('tp.transaction_type')
					//->group_by('tmst.service_id')
					->group_by('tp.mst_id')
					->limit($limit, $offset)
					->get()
					->result();
			return $result;
	}
	
	private function getMoneyReceiptWiseDtlsCollection()
	{
		$result = $this->db
					->select('dtls.*,tname.test_name,tmst.service_id,others.otherservice_name')
					->from('transaction_dtls as dtls')
					->join('transaction_mst as tmst','dtls.mst_id = tmst.id')
					->join('pathology_test_name as tname','dtls.sub_service_id = tname.id and tmst.service_id = 1','left')
					->join('lib_otherservice as others','dtls.sub_service_id = others.id and tmst.service_id = 4','left')
					->get()
					->result();
		//echo '<pre>';print_r($result);exit;
		return $result;
	}
    

}