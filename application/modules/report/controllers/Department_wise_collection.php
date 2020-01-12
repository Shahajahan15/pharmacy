<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Department_wise_collection extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
	}

    
    public function index()
    {
    	$data = array();
    	$data['department_list'] = $this->getDepartmentList();
    	$data['service_list'] = $this->getServiceList();
    	$data['service_test_detls'] = $this->getServiceDetails(1);
    	//$data['sub_service_list'] = $this->getSubServiceList();
    	//echo '<pre>';print_r($data['sub_service_list']);exit;
        Template::set("toolbar_title", "Department Wise Collection List");
        Template::set($data);
        Template::set_view('department_wise_collection/index');
		Template::render();
    }
    
    private function getServiceList()
    {
    	$where = array(2,11);
		$result = $this->db->where_not_in('id',$where)->get('lib_discount_service_setup')->result();
		return $result;
	}
	
	private function getServiceDetails($service_id)
	{
		$result = $this->db
					->select('tmst.service_id,SUM(dtls.qnty) as qnty,IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount, IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount, tname.test_department as department_id')
					->from('transaction_mst as tmst')
					->join('transaction_dtls as dtls','dtls.mst_id = tmst.id','left')
					->join('transaction_payment as tp','tp.mst_id = tmst.id')
					->join('pathology_test_name as tname','dtls.sub_service_id = tname.id and tmst.service_id = 1','left')
					->where('tmst.service_id', $service_id)
					->group_by('tmst.service_id')
					->group_by('tp.transaction_type')
					->group_by('tname.id')
					->get()
					->result();
		echo '<pre>';print_r($result);exit;
	}
	
	/*private function getSubServiceList()
	{
		$result = $this->db
					->select('dtls.*,tname.test_name,tmst.service_id,others.otherservice_name,,tname.test_department')
					->from('transaction_dtls as dtls')
					->join('transaction_mst as tmst','dtls.mst_id = tmst.id')
					->join('pathology_test_name as tname','dtls.sub_service_id = tname.id and tmst.service_id = 1','left')
					->join('lib_otherservice as others','dtls.sub_service_id = others.id and tmst.service_id = 4','left')
					->group_by('tname.id')
					->group_by('others.id')
					->get()
					->result();
		return $result;
	}
    */
    private function getDepartmentList()
    {
    	
		$result = $this->db
				->select('d.dept_id as id, d.department_name, s.id as servie_id, tname.id as test_id')
				->from('lib_department as d')
				->join('pathology_test_name as tname','d.dept_id = tname.test_department','left')
				->join('lib_discount_service_setup as s','d.dept_id = s.department_id and s.id != 2 and s.id != 11','left')
				->or_where('s.id !=',null)
				->or_where('tname.test_department !=',null)
				->group_by('d.dept_id')
				->order_by('d.dept_id','desc')
				->get()
				->result();
		return $result;
	}
    
   /* private function getMoneyReceiptWiseCollection()
    {
		$result = $this->db
					->select('tmst.patient_id,tmst.id,source_id,service_id,tot_bill,transaction_type,tp.collection_by,tp.collection_date,IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount, IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount,s.service_name,pmt.patient_id as patient_code,emp.EMP_NAME as emp_name')
					->from('transaction_payment as tp')
					->join('transaction_mst as tmst','tp.mst_id = tmst.id')
					->join('lib_discount_service_setup as s','tmst.service_id = s.id')
					->join('patient_master as pmt','tmst.patient_id = pmt.id')
					->join('users as u','tp.collection_by = u.id')
					->join('hrm_ls_employee as emp','u.employee_id = emp.EMP_ID')
					//->group_by('date(tp.collection_date)')
					->group_by('tp.transaction_type')
					//->group_by('tmst.service_id')
					->group_by('tp.mst_id')
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
	}*/
    

}