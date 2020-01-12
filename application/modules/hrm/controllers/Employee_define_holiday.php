<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Employee_Define_Holiday extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('employee_define_holiday_model', NULL, TRUE);	
		$this->load->config('config_employee');	
		$this->lang->load('common');		
		$this->lang->load('emp_define_holiday');
		
		
		Assets::add_module_js('hrm', 'employee.js');		
		Assets::add_module_css('hrm', 'employee.css');

		
		Template::set_block('sub_nav', 'emp_define_holiday/_sub_nav_emp_holiday_define');
	}

    /**
     * Holiday list
     */
	 
    public function show_list()
    {
		$this->auth->restrict('HRM.Employee_Define_Holiday.View');
		
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		
		if (is_array($checked) && count($checked))
		{
			
			$this->auth->restrict('HRM.Employee_Define_Holiday.Delete');
						
			$result = FALSE;

            foreach ($checked as $pid)
			{
				$result = $this->employee_define_holiday_model->delete($pid);
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}
			else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->employee_define_holiday_model->error, 'error');
			}
		}
		
		
        $records = $this->employee_define_holiday_model->find_all();	
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("hrm_employee_insurance_list"));		
        Template::set_view('emp_define_holiday/employee_holiday_list');
        Template::render();
    }

    /**
     * Holiday define
    */
	 
    public function emp_holiday_define()
    {   
		
		$this->auth->restrict('HRM.Employee_Define_Holiday.Create');

        if (isset($_POST['save']))
		{									
			$HOLIDAY_ID = $this->saveHoliday_info('insert',0);					 				
		}
 
		$HOLIDAY_ID_UPDATE 	= (int)$this->uri->segment(5);
		
		if(isset($HOLIDAY_ID_UPDATE)){
			$employee_holiday_details = $this->employee_define_holiday_model->find($HOLIDAY_ID_UPDATE);				
		}	
		
		$holiday_type		= $this->config->item('holiday_type');
		
		Template::set('holiday_type',$holiday_type);
		Template::set('employee_holiday_details',$employee_holiday_details);
		Template::set('toolbar_title', lang("hrm_employee_insurance"));
        Template::set_view('emp_define_holiday/employee_holiday_define_form');
        Template::render();
    }
	

	
	//==============Save Holiday info =========//
	private function saveHoliday_info($type,$id)	
	{					
		// make sure we only pass in the fields we want
		$data = array();	
		$data['HOLIDAY_DATE'] 			= date("Y-m-d", strtotime($this->input->post('HOLIDAY_DATE')));
		$data['HOLIDAY_NAME'] 			= $this->input->post('HOLIDAY_NAME');
		$data['HOLIDAY_TYPE'] 			= $this->input->post('TYPE');		
			
		$type =  $this->set_type_value(); 
		
		if($type == 'insert')
		{
			$this->auth->restrict('HRM.Employee_Define_Holiday.Create');					
			$data['CREATED_BY']		= $this->current_user->id;
			$data['MODIFY_BY']		= null;	
			$result = $this->employee_define_holiday_model->insert($data);
			
			
			log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $HOLIDAY_ID .' : '. $this->input->ip_address(), 'hrm_ls_holiday_define');
			Template::set_message(lang('bf_msg_create_success'), 'success');
			redirect(SITE_AREA .'/employee_define_holiday/hrm/show_list');	
			
		}
		elseif($type == 'update')
		{
			$target_id = $this->uri->segment(5); 
			
			$this->auth->restrict('HRM.Employee_Define_Holiday.Edit');	
			
			$employee_holiday_details = $this->employee_define_holiday_model->find_all();	
			
			foreach($employee_holiday_details as $row){
				$row = (object) $row;
			}
			
			$data['CREATED_BY']		= $row->CREATED_BY;
			$data['MODIFY_BY']		= $this->current_user->id;
			
			$result = $this->employee_define_holiday_model->update($target_id,$data);
						
			log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $HOLIDAY_ID .' : '. $this->input->ip_address(), 'hrm_ls_holiday_define');
			Template::set_message(lang('bf_msg_edit_success'), 'success');
			redirect(SITE_AREA .'/employee_define_holiday/hrm/show_list');				
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

