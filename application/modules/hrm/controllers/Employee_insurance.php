<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Employee_Insurance extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('employee_model', NULL, TRUE);
		$this->load->model('employee_insurance_model', NULL, TRUE);	
		$this->load->model('library/bank_info_model', NULL, TRUE);
		$this->load->model('library/insurance_type_model', NULL, TRUE);	
		
		
		$this->lang->load('common');
		$this->lang->load('emp_insurance');
		
		
		Assets::add_module_js('hrm', 'employee.js');		
		Assets::add_module_css('hrm', 'employee.css');

		
		Template::set_block('sub_nav', 'employee_insurance/_sub_nav_emp_insurance');
	}

    /**
     * store company 
     */
	 
    public function show_employee_insurance_list()
    {
		$this->auth->restrict('HRM.Employee_Transfer.View');
		
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		
		if (is_array($checked) && count($checked))
		{
			
			$this->auth->restrict('HRM.Employee_Insurance.Delete');
						
			$result = FALSE;

            foreach ($checked as $pid)
			{
				$result = $this->employee_insurance_model->delete($pid);
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}
			else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->employee_insurance_model->error, 'error');
			}
		}
		
		$this->db->select("								
							insurance.*,
							employee.EMP_NAME,	
							b.BANK_NAME,
							it.INSURANCE_TYPE_NAME,
							it.INSURANCE_TYPE_CODE							
						");
						
		$this->db->from('hrm_ls_emp_insurance as insurance');	
		
		$this->db->join('hrm_ls_employee as employee', 'employee.EMP_ID = insurance.EMP_ID', 'left');
		
		$this->db->join('lib_bank_info as b', 'insurance.BANK_ID = b.ID', 'left');
		
		$this->db->join('lib_insurance_type as it', 'insurance.BANK_INSURANCE_ID = it.INSURANCE_TYPE_ID', 'left');
					
		$this->db->distinct("employee.EMP_ID");
		
        $records = $this->employee_insurance_model->find_all();	
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("hrm_employee_insurance_list"));		
        Template::set_view('employee_insurance/employee_insurance_list');
        Template::render();
    }

    /**
     * Employee Transfer Create
    */
	 
    public function emp_insurance_create()
    {   
		
		$this->auth->restrict('HRM.Employee_Insurance.Create');
				
        if (isset($_POST['save']))
		{									
			$EMP_INSURANCE_ID = $this->saveInsurance_info('insert',0);					 				
		}

		$employee_list = $this->employee_model->find_all();	
		
		$bank_list = $this->bank_info_model->find_all();	
		
		$insurance_type_list = $this->insurance_type_model->find_all();
		
		$EMP_INSURANCE_ID_UPDATE 	= (int)$this->uri->segment(5);
		
		if(isset($EMP_INSURANCE_ID_UPDATE)){
			$employee_insurance_details = $this->employee_insurance_model->find($EMP_INSURANCE_ID_UPDATE);				
		}
				
		Template::set('employee_list',$employee_list);
		Template::set('insurance_type_list',$insurance_type_list);
		Template::set('bank_list',$bank_list);
		Template::set('employee_insurance_details',$employee_insurance_details);
		
		
		Template::set('toolbar_title', lang("hrm_employee_insurance"));
        Template::set_view('employee_insurance/employee_insurance');
        Template::render();
    }
	

	
	//==============Save Transfer info =========//
	private function saveInsurance_info($type,$id)	
	{					
		// make sure we only pass in the fields we want
		$data = array();
		
		$data['EMP_ID'] 					= $this->input->post('EMP_ID');
		$data['BANK_ID'] 					= $this->input->post('BANK_ID');
		$data['BANK_INSURANCE_ID'] 			= $this->input->post('BANK_INSURANCE_ID');		
		$data['POLICY_NO'] 	  				= $this->input->post('POLICY_NO');
		$data['COMPANY_NAME'] 				= $this->input->post('COMPANY_NAME');		
		
		$type =  $this->set_type_value(); 
		
		if($type == 'insert')
		{
			$this->auth->restrict('HRM.Employee_Insurance.Create');	
			
			$data['CREATED_BY']					= $this->current_user->id;
			$data['MODIFY_BY']					= null;	
			
			$result = $this->employee_insurance_model->insert($data);
			
			
			log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $EMP_INSURANCE_ID .' : '. $this->input->ip_address(), 'hrm_ls_emp_insurance');
			Template::set_message(lang('bf_msg_create_success'), 'success');
			redirect(SITE_AREA .'/employee_insurance/hrm/show_employee_insurance_list');	
			
		}
		elseif($type == 'update')
		{
			$target_id = $this->uri->segment(5); 
			$this->auth->restrict('HRM.Employee_Insurance.Edit');	
			
			$employee_insurance_details = $this->employee_insurance_model->find_all();	
			foreach($employee_insurance_details as $row){
				$row = (object) $row;
			}
			
			$data['RECORD_CREATED_DATE_TIME'] 	= $row->RECORD_CREATED_DATE_TIME;
			date_default_timezone_set('Asia/Dhaka');			
			$data['RECORD_MODIFY_DATE_TIME'] 	= date('Y/m/d h:i:s', time());
			$data['CREATED_BY']					= $row->CREATED_BY;
			$data['MODIFY_BY']			 		= $this->current_user->id;
			
			$result = $this->employee_insurance_model->update($target_id,$data);
			
			
			log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $EMP_INSURANCE_ID .' : '. $this->input->ip_address(), 'hrm_ls_emp_insurance');
			Template::set_message(lang('bf_msg_edit_success'), 'success');
			redirect(SITE_AREA .'/employee_insurance/hrm/show_employee_insurance_list');	
			
		}
				
		return $result;			
	}	
	
	private function set_type_value(){
		$target_id = $this->uri->segment(5);
		if($target_id){
			$type = "update";			
		} else {
			$type = "insert";	
		}
		return $type;	
	}
	

}

