<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Counter controller
 */
class Counter extends Admin_Controller
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

		$this->auth->restrict('Library.Counter.View');
		$this->load->model('counter_model', NULL, TRUE);
		$this->lang->load('initial');

		Template::set_block('sub_nav', 'initial/_sub_nav_counter');
		
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
		$this->auth->restrict('Library.Counter.View');
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Counter.Delete');
			$result = FALSE;
			$data=array();
			$data['is_deleted']=1;
			foreach ($checked as $counter_id){
					$result = $this->counter_model->update($counter_id,$data);
			}
			if ($result){
			// Log the activity
			log_activity($this->current_user->id, lang('library_act_delete_record') .': '. $counter_id .' : '. $this->input->ip_address(), 'library_counter');

			Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->counter_model->error, 'error');
			}
		}				
		$records = $this->counter_model->find_all_by('is_deleted',0);
		Template::set('toolbar_title', lang("library_initial_counter_view"));
		Template::set('records', $records);	
		
		Template::set_view('initial/counter_list');			
		Template::render();
    }

              
	//--------------------------------------------------------------------
	/**
	 * Creates a Counter object.
	 *
	 * @return void
	 **/
	//--------------------------------------------------------------------
	
	public function counter_create()
    {
        //TODO you code here
		
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_counter_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_counter');

				Template::set_message(lang('counter_create_success'), 'success');
				redirect(SITE_AREA .'/counter/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->counter_model->error, 'error');
			}
		}
		
		$library_counter['counter_name'] 	  = $this->input->post('lib_counter_counter_name'); 
		$library_counter['counter_status']    = $this->input->post('lib_counter_counter_status');
		
		Template::set('toolbar_title', lang("library_initial_counter_new"));
		Template::set('library_counter', $library_counter);
		
		Template::set_view('initial/counter_create');		
		Template::render();
    }

	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of counter Details data.
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
			redirect(SITE_AREA .'/counter/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Counter.Edit');

			if ($this->save_counter_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_counter');

				Template::set_message(lang('counter_update_success'), 'success');
				redirect(SITE_AREA .'/counter/library/show_list');
			}
			else
			{
				Template::set_message(lang('counter_details_edit_failure') . $this->counter_model->error, 'error');
			}
		}
		Template::set('library_counter', $this->counter_model->find($id));
		Template::set('toolbar_title', lang('library_initial_counter_edit'));
		
        Template::set_view('initial/counter_create');
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
	private function save_counter_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['counter_name']            = $this->input->post('lib_counter_counter_name');
		$data['counter_status']          = $this->input->post('lib_counter_counter_status');
		
		if ($type == 'insert')
		{
			$this->auth->restrict('Library.Counter.Create');
			$id = $this->counter_model->insert($data);

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
			$this->auth->restrict('Library.Counter.Edit');
			$return = $this->counter_model->update($id, $data);
		}

		return $return;
	}



    //--------------------------------------------------------------------


}