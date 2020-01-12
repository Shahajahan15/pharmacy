<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Company controller
 */
class Company extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Library.Company.View');
		$this->load->model('comp_model', null, true);
		$this->lang->load('initial');

		Template::set_block('sub_nav', 'initial/_sub_nav_comp');
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
		$this->auth->restrict('Library.Company.View');
		Template::set('toolbar_title', lang("library_initial_project_view"));
		Template::set_block('sub_nav', 'initial/_sub_nav_comp');	
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Company.Delete');
			$result = FALSE;
			foreach ($checked as $project_id){
					$result = $this->comp_model->delete($project_id);
			}
			if ($result){
			Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->comp_model->error, 'error');
			}
		}				
		$records = $this->comp_model->find_all();
		Template::set('records', $records);	
		
		Template::set_view('initial/comp_list');
		Template::render();
	}

        
        

	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	**/
	
	public function comp_create()
    {
        //TODO you code here
		Template::set_block('sub_nav', 'initial/_sub_nav_comp');
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->saveAccountGroup())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_comp');

				Template::set_message(lang('comp_create_success'), 'success');
				redirect(SITE_AREA .'initial/comp_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->comp_model->error, 'error');
			}
		}else
		{
			$this->auth->restrict('Library.Company.Delete');
			$checked = $this->input->post('checked');
			if (is_array($checked) && count($checked))
			{
					$result = FALSE;
					foreach ($checked as $comp_id){
							$result = $this->comp_model->delete($comp_id);
					}
					if ($result){
					Template::set_message(count($checked) .' '. lang('record_delete_success'), 'success');
					}else{
					Template::set_message(lang('record_create_failure') . $this->comp_model->error, 'error');
					}
			}			
		}
		
		$library_comp['comp_name'] 		= $this->input->post('ac_group_group_name'); 
		$library_comp['comp_status']    = $this->input->post('ac_group_project_name');           
		Template::set('toolbar_title', lang("library_initial_project_new"));
		Template::set('library_comp', $library_comp);
		
		Template::set_view('initial/comp_create');
		Template::render();
    }

	//--------------------------------------------------------------------
	 /**
	 * Allows editing of Group Details data.
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
			redirect(SITE_AREA .'/library/group_details');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Company.Edit');

			if ($this->save_group_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'group_details');

				Template::set_message(lang('group_details_edit_success'), 'success');
			}
			else
			{
				Template::set_message(lang('group_details_edit_failure') . $this->group_details_model->error, 'error');
			}
		}
		else if (isset($_POST['delete']))
		{
			$this->auth->restrict('Library.Company.Delete');

			if ($this->group_details_model->delete($id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'group_details');

				Template::set_message(lang('group_details_delete_success'), 'success');

				redirect(SITE_AREA .'/library/group_details');
			}
			else
			{
				Template::set_message(lang('group_details_delete_failure') . $this->group_details_model->error, 'error');
			}
		}
		Template::set('group_details', $this->group_details_model->find($id));
		Template::set('toolbar_title', lang('group_details_edit') .' Group Details');
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
	private function save_comp_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['group_name']        = $this->input->post('group_details_group_name');
		$data['contact_person']        = $this->input->post('group_details_contact_person');
		$data['contact_number']        = $this->input->post('group_details_contact_number');
		$data['website']        = $this->input->post('group_details_website');
		$data['email']        = $this->input->post('group_details_email');
		$data['address']        = $this->input->post('group_details_address');
		$data['status']        = $this->input->post('group_details_status');

		if ($type == 'insert')
		{
			$id = $this->group_details_model->insert($data);

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
			$return = $this->group_details_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}