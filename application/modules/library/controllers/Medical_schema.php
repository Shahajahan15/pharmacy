<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Medical_schema extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Lib.Schema.View');
		$this->load->model('medical_schema_model', NULL, true);
        $this->lang->load('medical_schema');	
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');		
		Template::set_block('sub_nav', 'medical_schema/_sub_nav_medicalschema');
	}

   
	 //===========Function start==========//
    public function show_list()
    {
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Schema.Delete');
			$result = FALSE;

            foreach ($checked as $sid){
				$result = $this->medical_schema_model->delete($sid);
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->medical_schema_model->error, 'error');
			}
		}

        $records = $this->medical_schema_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("medical_schema_view"));		
        Template::set_view('medical_schema/medical_schema_list');
        Template::render();
    }

    //===========Function End==========//
	
	
	
	//===========Function create start==========// 
    public function medical_schema_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_medical_schema_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_hrm_ls_medical_schema');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/medical_schema/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->medical_schema_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("medical_schema_create"));
        Template::set_view('medical_schema/medical_schema_create');
        Template::render();
    }

	//===========Function create End==========// 
	
	
	
	
	//===========Function Edit start==========// 
	public function medical_schema_edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/medical_schema/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Policy.Edit');

			if ($this->save_medical_schema_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_hrm_ls_medical_schema');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/medical_schema/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->medical_schema_model->error, 'error');
			}
		}
		
		
			$medical_schema = $this->medical_schema_model->find($id);		
		
			Template::set('medical_schema',$medical_schema);		
			Template::set('toolbar_title', lang('medical_schema_update'));		
			Template::set_view('medical_schema/medical_schema_create');
			Template::render();
	}
	
			//===========Function Edit End==========// 
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	 
	 //===========Insert & Save Function start==========// 
	private function save_medical_schema_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['MEDICAL_SCHEMA_NAME']    = $this->input->post('medical_schema_name');
		$data['BUSINESS_DATE'] 		    = date("Y-m-d");
		$data['CREATED_BY'] 		    = $this->current_user->id; 
		$data['STATUS']      		    = $this->input->post('medical_schema_status');
		      

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Schema.Create');
			$id = $this->medical_schema_model->insert($data);
			//print($this->db->last_query()); die; 
			
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$this->auth->restrict('Lib.Schema.Edit');
			$return = $this->medical_schema_model->update($id, $data);
		}

		return $return;
	}
		//===========Insert & Save Function start==========// 
}

