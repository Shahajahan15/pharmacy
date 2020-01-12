<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * library controller
 */
class library extends Admin_Controller
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

		$this->auth->restrict('Library.Library.View');
		$this->load->model('group_details_model', null, true);
		$this->lang->load('library');

		Template::set_block('sub_nav', 'library/library/_sub_nav');

		//Assets::add_module_js('library', 'group_details.js');
	}

	//--------------------------------------------------------------------


	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{

		$records = $this->group_details_model->find_all();

		Template::set('records', $records);
		Template::set('toolbar_title', 'Manage Group Details');

        Template::set_view('library/library/index');
		Template::render();
	}

	//--------------------------------------------------------------------


	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict('Group_Details.Library.Create');

		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_group_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'group_details');

				Template::set_message(lang('group_details_create_success'), 'success');
				redirect(SITE_AREA .'/library/group_details');
			}
			else
			{
				Template::set_message(lang('group_details_create_failure') . $this->group_details_model->error, 'error');
			}
		}
		Assets::add_module_js('group_details', 'group_details.js');

		Template::set('toolbar_title', lang('group_details_create') . ' Group Details');
		Template::render();
	}

	//--------------------------------------------------------------------


	/**
	 * Allows editing of Group Details data.
	 *
	 * @return void
	 */
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
			$this->auth->restrict('Group_Details.Library.Edit');

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
			$this->auth->restrict('Group_Details.Library.Delete');

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
	private function save_group_details($type='insert', $id=0)
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