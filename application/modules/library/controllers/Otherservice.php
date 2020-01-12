<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Measurement_unit controller
 */
class Otherservice extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void   otherservice_model
	 */  
	//--------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		//$this->auth->restrict('Lib.Otherservice.View');
		$this->load->model('otherservice_model', NULL, TRUE);
		$this->lang->load('otherservice');
		$this->lang->load('common');
		$this->config->load('config_otherservice');

		Template::set_block('sub_nav', 'secondary/_sub_nav_otherservice');
		
	}

	//--------------------------------------------------------------------
	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function show_list()
    {  
		$this->auth->restrict('Lib.Otherservice.View');
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Otherservice.Delete');
			$result = FALSE;
			foreach ($checked as $building_id){
					$result = $this->otherservice_model->delete($building_id);
			}
			if ($result){
			// Log the activity
			log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $building_id .' : '. $this->input->ip_address(), 'lib_otherservice');

			Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			Template::set_message(lang('bf_msg_record_delete_fail') . $this->otherservice_model->error, 'error');
			}
		}	
		
		$records = $this->otherservice_model->find_all();
		Template::set('records', $records);			
		Template::set('toolbar_title', lang("lib_second_otherservice_view"));
		
		Template::set_view('secondary/otherservice_list');			
		Template::render();
    }

              

	/**
	 * Creates a building object.
	 *
	 * @return void
	 **/
	
	public function create_new()
    {
        //TODO you code here
		
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_service_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_measurement_unit');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/otherservice/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->otherservice_model->error, 'error');
			}
		}
			
		
		Template::set('toolbar_title', lang("lib_second_otherservice_create"));	
		Template::set('status',$this->config->item('status'));				
		Template::set_view('/secondary/otherservice_create');		
		Template::render();
    }

	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Building Details data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/otherservice/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Otherservice.Edit');

			if ($this->save_service_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_otherservice');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/otherservice/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_record_delete_fail') . $this->otherservice_model->error, 'error');
			}
		}
		
		Template::set('lib_otherservice', $this->otherservice_model->find($id));
		Template::set('toolbar_title', lang('lib_second_otherservice_edit'));
		Template::set('status',$this->config->item('status'));		
        Template::set_view('/secondary/otherservice_create');
		Template::render();
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_service_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
			$data['otherservice_name'] 	  	= $this->input->post('lib_second_otherservice_name'); 
			$data['other_service_price'] 	= $this->input->post('lib_second_otherservice_price'); 
			$data['description']  			= $this->input->post('lib_second_otherservice_description');
			$data['status']  				= $this->input->post('lib_second_otherservice_status');
			$data['created_by']  			= $this->current_user->id;
		
		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Otherservice.Create');
			$id = $this->otherservice_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			}
			else
			{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$this->auth->restrict('Lib.Otherservice.Edit');
			$return = $this->otherservice_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}