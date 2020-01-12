<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Insurance_Type extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		
		$this->load->model('insurance_type_model', NULL, TRUE);					
		
		$this->lang->load('common');
		$this->lang->load('insurance_type');
		
		
		Assets::add_module_js('hrm', 'employee.js');		
		Assets::add_module_css('hrm', 'employee.css');

		
		Template::set_block('sub_nav', 'insurance_type/_sub_nav_insurance_type');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {
		$this->auth->restrict('Lib.Insurance_Type.View');
		
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		
		if (is_array($checked) && count($checked))
		{
			
			$this->auth->restrict('Lib.Insurance_Type.Delete');
						
			$result = FALSE;

            foreach ($checked as $pid)
			{
				$result = $this->insurance_type_model->delete($pid);
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}
			else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->insurance_type_model->error, 'error');
			}
		}
		
        $records = $this->insurance_type_model->find_all();	
		 
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("insurance_type_list"));		
        Template::set_view('insurance_type/insurance_type_list');
        Template::render();
    }

    /**
     * Employee Transfer Create
    */
	 
    public function insurance_type_create()
    {   
		
		$this->auth->restrict('Lib.Insurance_Type.Create');
		
		
		$INSURANCE_TYPE_ID_UPDATE 	= (int)$this->uri->segment(5);
		
		if(isset($INSURANCE_TYPE_ID_UPDATE)){
			$insurance_type_details = $this->insurance_type_model->find($INSURANCE_TYPE_ID_UPDATE);				
		}
		
        if (isset($_POST['save']))
		{									
			$INSURANCE_TYPE_ID = $this->saveInsuranceType('insert',0);
		}
		
		Template::set('insurance_type_details', $insurance_type_details);
		Template::set('toolbar_title', lang("insurance_type_create"));
        Template::set_view('insurance_type/insurance_type_form');
        Template::render();
    }
	

	
	//==============Save Transfer info =========//
	private function saveInsuranceType($type,$id)	
	{					
		// make sure we only pass in the fields we want
		$data = array();
		
		$data['INSURANCE_TYPE_NAME'] 	= $this->input->post('INSURANCE_TYPE_NAME');
		$data['INSURANCE_TYPE_CODE'] 	= $this->input->post('INSURANCE_TYPE_CODE');
		$data['status']      		    = $this->input->post('bf_status');
		$type =  $this->set_type_value(); 
		
		if($type == 'insert')
		{
			$this->auth->restrict('Lib.Insurance_Type.Create');
			
			$data['CREATED_BY']					= $this->current_user->id;
			$data['MODIFY_BY']					= null;	
			$data['RECORD_MODIFY_DATE_TIME'] 	= null;	
			
			$result = $this->insurance_type_model->insert($data);

			log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $INSURANCE_TYPE_ID .' : '. $this->input->ip_address(), 'lib_insurance_type');
			Template::set_message(lang('bf_msg_create_success'), 'success');
			redirect(SITE_AREA .'/insurance_type/library/show_list');									 				
		}
		elseif($type == 'update')
		{
			$target_id = $this->uri->segment(5);
			
			$this->auth->restrict('Lib.Insurance_Type.Edit');	
			
			$insurance_type_details = $this->insurance_type_model->find_all();
			
			foreach($insurance_type_details as $row){
				$row = (object) $row;
			}
			
			
			$data['RECORD_CREATED_DATE_TIME'] 	= $row->RECORD_CREATED_DATE_TIME;
			date_default_timezone_set('Asia/Dhaka');			
			$data['RECORD_MODIFY_DATE_TIME'] 	= date('Y/m/d h:i:s', time());
			$data['CREATED_BY']					= $row->CREATED_BY;
			$data['MODIFY_BY']			 		= $this->current_user->id;
			
			$result = $this->insurance_type_model->update($target_id,$data);
			
			log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $INSURANCE_TYPE_ID .' : '. $this->input->ip_address(), 'lib_insurance_type');
			Template::set_message(lang('bf_msg_edit_success'), 'success');
			redirect(SITE_AREA .'/insurance_type/library/show_list');
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

