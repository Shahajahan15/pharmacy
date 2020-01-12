<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Specialization controller
 */
class Specialization extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	 //--------------------------------------------------------------------
	 
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Lib.Specialization.View');
		$this->load->model('specialization_model', NULL, TRUE);
		$this->lang->load('initial');

		Template::set_block('sub_nav', 'initial/_sub_nav_specialization');
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
		$this->auth->restrict('Lib.Specialization.View');
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Specialization.Delete');
			$result = FALSE;
			$data=array();
			$data['is_deleted']=1;
			foreach ($checked as $specialization_id){
				$result = $this->specialization_model->update($specialization_id,$data);
			}
			if ($result){
			// Log the activity
			log_activity($this->current_user->id, lang('library_act_delete_record') .': '. $specialization_id .' : '. $this->input->ip_address(), 'library_specialization');

			Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->specialization_model->error, 'error');
			}
		}
		
		Template::set('toolbar_title', lang("library_initial_specialization_view"));			
		
		Template::set('records', $this->specialization_model->find_all_by('is_deleted',0));	
		
		Template::set_view('initial/specialization_list');
		Template::render();
	}

                

	//--------------------------------------------------------------------
	/**
	 * Creates a specialization object.
	 *
	 * @return void
	**/
	//--------------------------------------------------------------------
	
	
	public function specialization_create()
    {
        //TODO you code here
		
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_specialization())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_specialization');

				Template::set_message(lang('specialization_create_success'), 'success');
				redirect(SITE_AREA .'/specialization/library/show_list');
			}else{
				Template::set_message(lang('record_create_failure').$this->specialization_model->error, 'error');
			}
		}
		
		$library_specialization['specialization_name'] 	= $this->input->post('lib_specialization_specialization_name');           
		Template::set('toolbar_title', lang("library_initial_specialization_new"));
		Template::set('library_specialization', $library_specialization);
		
		Template::set_view('initial/specialization_create');
		Template::render();
    }
	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of specialization data.
	 *
	 * @return void
	 */
	 //--------------------------------------------------------------------
	 
	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('group_details_invalid_id'), 'error');
			redirect(SITE_AREA .'/specialization/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Specialization.Edit');

			if ($this->save_specialization('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_specialization');

				Template::set_message(lang('specialization_update_success'), 'success');
				redirect(SITE_AREA .'/specialization/library/show_list');
			}
			else
			{
				Template::set_message(lang('specialization_details_edit_failure') . $this->specialization_model->error, 'error');
			}
		}
		
		Template::set('library_specialization', $this->specialization_model->find($id));
		Template::set('toolbar_title', lang('specialization_details_edit'));
        
		Template::set_view('initial/specialization_create');
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
	 
	private function save_specialization($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['specialization_name']  = $this->input->post('lib_specialization_specialization_name');
		if($this->input->post('lib_specialization_status')==""){
			$data['status']=1;
		}else{
			$data['status']        	  = $this->input->post('lib_specialization_status');
		}

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Specialization.Create');
			$id = $this->specialization_model->insert($data);

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
			$this->auth->restrict('Lib.Specialization.Edit');
			$return = $this->specialization_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}