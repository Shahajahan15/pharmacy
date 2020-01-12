<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Shift controller
 */
class Shift extends Admin_Controller
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

		$this->auth->restrict('Library.Shift.View');
		$this->load->model('shift_model', NULL, TRUE);
		$this->load->config('config_library');
		$this->lang->load('initial');

		Template::set_block('sub_nav', 'initial/_sub_nav_shift');
		
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
		$this->auth->restrict('Library.Shift.View');
		Template::set('toolbar_title', lang("library_initial_shift_view"));
		Template::set_block('sub_nav', 'initial/_sub_nav_shift');	
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Shift.Delete');
			$result = FALSE;
			$data=array();
			$data['is_deleted']=1;
			$data['deleted_by']=$this->current_user->id;
			$data['deleted_date']=date('Y-m-d H-i-s');
			foreach ($checked as $shift_id){
					$result = $this->shift_model->update($shift_id,$data);
			}
			if ($result){
			Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->shift_model->error, 'error');
			}
		}				
		$records = $this->shift_model->find_all_by('is_deleted',0);
		Template::set('records', $records);	
		
		Template::set_view('initial/shift_list');			
		Template::render();
    }

              
	//--------------------------------------------------------------------
	/**
	 * Creates a shift object.
	 *
	 * @return void
	 **/
	//--------------------------------------------------------------------
	
	public function shift_create()
    {
        //TODO you code here
		
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_shift_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_shift');

				Template::set_message(lang('shift_create_success'), 'success');
				redirect(SITE_AREA .'/shift/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->shift_model->error, 'error');
			}
		}else
		{
			$this->auth->restrict('Library.Shift.Delete');
			$checked = $this->input->post('checked');
			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $shift_id){
						$result = $this->shift_model->delete($shift_id);
				}
				if ($result){
				Template::set_message(count($checked) .' '. lang('record_delete_success'), 'success');
				}else{
				Template::set_message(lang('record_create_failure') . $this->shift_model->error, 'error');
				}
			}			
		}
		$time_options = $this->config->item('time_options');
		
		$library_shift['shift_name'] 	  = $this->input->post('lib_shift_shift_name'); 
		$library_shift['shift_from']      = $this->input->post('lib_shift_shift_from');
		$library_shift['shift_to']        = $this->input->post('lib_shift_shift_to');
		$library_shift['shift_status']    = $this->input->post('lib_shift_shift_status');
		
		Template::set('toolbar_title', lang("library_initial_shift_new"));
		Template::set('library_shift', $library_shift);
		Template::set('time_options', $time_options);
		
		Template::set_view('initial/shift_create');		
		Template::render();
    }

	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Shift Details data.
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
			redirect(SITE_AREA .'/shift/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Shift.Edit');

			if ($this->save_shift_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_shift');

				Template::set_message(lang('shift_update_success'), 'success');
				redirect(SITE_AREA .'/shift/library/show_list');
			}
			else
			{
				Template::set_message(lang('shift_details_edit_failure') . $this->shift_model->error, 'error');
			}
		}
		else if (isset($_POST['delete']))
		{
			$this->auth->restrict('Library.Shift.Delete');

			if ($this->shift_model->delete($id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'library_shift');

				Template::set_message(lang('record_delete_success'), 'success');
				redirect(SITE_AREA .'/shift/library/show_list');
			}
			else
			{
				Template::set_message(lang('group_details_delete_failure') . $this->shift_model->error, 'error');
			}
		}
		
		$time_options = $this->config->item('time_options');
		Template::set('library_shift', $this->shift_model->find($id));
		Template::set('time_options', $time_options);
		Template::set('toolbar_title', lang('library_initial_shift_edit'));
		
        Template::set_view('initial/shift_create');
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
	private function save_shift_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['shift_name']            	= $this->input->post('lib_shift_shift_name');
		$data['shift_from']          	= $this->input->post('lib_shift_shift_from');
		$data['shift_to']          		= $this->input->post('lib_shift_shift_to');
		$data['shift_status']          	= $this->input->post('lib_shift_shift_status');
		
		if ($type == 'insert')
		{
			$this->auth->restrict('Library.Shift.Create');
			$id = $this->shift_model->insert($data);

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
			$this->auth->restrict('Library.Shift.Edit');
			$return = $this->shift_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}