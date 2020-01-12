<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Money_receipt_wise_cash_collection extends Admin_Controller {

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
        $data['sl']=$offset;
        $limit = (int) $this->input->post('per_page') ?: 25;
    	$search_box = $this->searchpanel->getSearchBox($this->current_user);
    	$search_box['common_text_search_flag'] = 0;
        $search_box['contact_no_flag'] = 1;
        $search_box['patient_name_flag'] = 1;
        $search_box['patient_id_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $search_box['cash_users_flag'] = 1;
        $search_box['to_date_flag'] = 1;     
        $search_box['by_date_flag'] = 0; 




         if(isset($_POST['print'])){
            $limit=1000;
            $offset=0;
         }
          $filter=[];
            $first_date=date('Y-m-d 00:00:00');
            $second_date=date('Y-m-d 23:59:59');
            if(count($_POST)>0){

           if($this->input->post('from_date')){
                $first_date=date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$this->input->post('from_date'))));
                }
        else{
               $first_date=date("Y-m-d 00:00:00"); 
                }
        if($this->input->post('to_date')){
                $second_date=date('Y-m-d 23:59:59',strtotime(str_replace('/','-',$this->input->post('to_date'))));
                }
        else{
                $second_date=date("Y-m-d 23:59:59");
                }

    	//$data['mr_wise_collection'] = $this->getMoneyReceiptWiseCollection($_POST, $limit, $offset);
    	//$data['mr_wise_collection_dtls'] = $this->getMoneyReceiptWiseDtlsCollection();

        if(isset($filter['user_id']) && $filter['user_id']){
            $where['tp.collection_by']=$filter['user_id'];
        }
        //echo '<pre>';print_r($where);die();

        if(trim($this->input->post('mr_num'))){
                $mr_no=trim($this->input->post('mr_num'));
                $conditionLike="tmst.mr_no Like '%".$mr_no."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('patient_id'))){
                $patient_id=trim($this->input->post('patient_id'));
                $conditionLike="pmt.patient_id Like '%".$patient_id."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('patient_name'))){
                $patient_name=trim($this->input->post('patient_name'));
                $conditionLike="pmt.patient_name Like '%".$patient_name."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('contact_no'))){
                $contact_no=trim($this->input->post('contact_no'));
                $conditionLike="pmt.contact_no Like '%".$contact_no."%'";
                $this->db->where($conditionLike);

        }
    }

        $mr_wise_collection = $this->db
                    ->select('
                        SQL_CALC_FOUND_ROWS
                        tmst.patient_id,
                        tmst.id,
                        source_id,
                        service_id,
                        tot_bill,
                        transaction_type,
                        tp.collection_by,
                        tp.collection_date,
                        IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount,
                         IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,
                         IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount,
                         s.service_name,
                         pmt.patient_id as patient_code,
                         pmt.patient_name,
                         pmt.contact_no,
                         emp.EMP_NAME as emp_name,
                         tmst.mr_no,
                         tmst.refund_bill_amount'
                        ,false)
                    ->from('transaction_payment as tp')
                    ->join('transaction_mst as tmst','tp.mst_id = tmst.id')
                    ->join('lib_discount_service_setup as s','tmst.service_id = s.id')
                    ->join('patient_master as pmt','tmst.patient_id = pmt.id')
                    ->join('users as u','tp.collection_by = u.id')
                    ->join('hrm_ls_employee as emp','u.employee_id = emp.EMP_ID')
                    
                    ->where('tp.collection_date >=',$first_date)
                   ->where('tp.collection_date <=', $second_date)
                   ->where($filter)
                    //->group_by('date(tp.collection_date)')
                    ->group_by('tp.transaction_type')
                    //->group_by('tmst.service_id')
                    ->group_by('tp.mst_id')
                    ->order_by('tp.collection_date','DESC')
                    ->limit($limit, $offset)
                    ->get()
                    ->result();
        //echo $this->db->last_query();
        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/money_receipt_wise_cash_collection/report/index');
        $this->pager['total_rows'] = $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);


    	$data['mr_wise_collection']=$mr_wise_collection;

    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'money_receipt_wise_cash_collection/index';
        $data['first_date']=$first_date;
        $data['second_date']=$second_date;
        if ($this->input->is_ajax_request()) {
            if(isset($_POST['print'])){
                echo $this->load->view('money_receipt_wise_cash_collection/print_report', $data, true);
                exit;
            }
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
    	//echo '<pre>';print_r($data['user_wise_collection_last']);exit;

        Template::set("toolbar_title", "Money Receipt Wise Collection List");
        Template::set($data);

        Template::set_block('sub_nav', 'money_receipt_wise_cash_collection/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }



    
    private function getMoneyReceiptWiseCollection($filter = array(), $limit = null, $offset = null)
    {
    	 
    	if (isset($filter['from_date']) && $filter['from_date']) {
    		$where['tp.collection_date >='] = date('Y-m-d',strtotime(str_replace('/','-',$filter['from_date'])))." 00:00:00";
    	}
    	if (isset($filter['to_date']) && $filter['to_date']){
    		$where['tp.collection_date <='] = date('Y-m-d',strtotime(str_replace('/','-',$filter['to_date'])))." 23:59:59";
    	}else{
            $where['tp.collection_date <='] = date('Y-m-d')." 23:59:59";
        }
        if(isset($filter['user_id']) && $filter['user_id']){
            $where['tp.collection_by']=$filter['user_id'];
        }
        //echo '<pre>';print_r($where);die();

        if(trim($this->input->post('mr_num'))){
                $mr_no=trim($this->input->post('mr_num'));
                $conditionLike="tmst.mr_no Like '%".$mr_no."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('patient_id'))){
                $patient_id=trim($this->input->post('patient_id'));
                $conditionLike="pmt.patient_id Like '%".$patient_id."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('patient_name'))){
                $patient_name=trim($this->input->post('patient_name'));
                $conditionLike="pmt.patient_name Like '%".$patient_name."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('contact_no'))){
                $contact_no=trim($this->input->post('contact_no'));
                $conditionLike="pmt.contact_no Like '%".$contact_no."%'";
                $this->db->where($conditionLike);

        }

		$result = $this->db
					->select('
                        SQL_CALC_FOUND_ROWS
                        tmst.patient_id,
                        tmst.id,
                        source_id,
                        service_id,
                        tot_bill,
                        transaction_type,
                        tp.collection_by,
                        tp.collection_date,
                        IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount,
                         IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,
                         IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount,
                         s.service_name,
                         pmt.patient_id as patient_code,
                         pmt.patient_name,
                         pmt.contact_no,
                         emp.EMP_NAME as emp_name,
                         tmst.mr_no,
                         tmst.refund_bill_amount'
                        ,false)
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
                    ->order_by('tp.collection_date','DESC')
					->limit($limit, $offset)
					->get()
					->result();
        //echo $this->db->last_query();
        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/money_receipt_wise_cash_collection/report/index');
        $this->pager['total_rows'] = $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

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