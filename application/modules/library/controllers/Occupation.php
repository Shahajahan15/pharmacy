<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Occupation controller
 */
class Occupation extends Admin_Controller
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
		
		$this->load->model('occupation_model', NULL, TRUE);
		$this->lang->load('initial');

		Template::set_block('sub_nav', 'initial/_sub_nav_occupation');
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
		$this->auth->restrict('Lib.Occupation.View');
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Occupation.Delete');
			
			$result = FALSE;			
			$data = array();
			$data['is_deleted'] 		= 1; 
			$data['deleted_by']			= $this->current_user->id;	
			$data['deleted_date']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $occupation_id)
			{			
				$result = $this->occupation_model->update($occupation_id,$data);				
			}			
			
			if ($result){
			// Log the activity
			log_activity($this->current_user->id, lang('library_act_delete_record') .': '. $occupation_id .' : '. $this->input->ip_address(), 'library_occupation');

			Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->occupation_model->error, 'error');
			}
		}	
		
		Template::set('toolbar_title', lang("library_initial_occupation_view"));			
		
		Template::set('records', $this->occupation_model->find_all_by('is_deleted',0));	
		Template::set_view('initial/occupation_list');
		Template::render();
	}       
        

		
	//--------------------------------------------------------------------
	/**
	 * Creates a occupation object.
	 *
	 * @return void
	**/
	//--------------------------------------------------------------------
	
	
	public function occupation_create()
    {
        //TODO you code here
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_occupation_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_occupation');

				Template::set_message(lang('occupation_create_success'), 'success');
				redirect(SITE_AREA .'/occupation/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->occupation_model->error, 'error');
			}
		}
		
		$library_occupation['occupation_name'] 	= $this->input->post('lib_occupation_occupation_name');           
		Template::set('toolbar_title', lang("library_initial_occupation_new"));
		Template::set('library_occupation', $library_occupation);
		
		Template::set_view('initial/occupation_create');
		Template::render();
    }

	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Occupation data.
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
			redirect(SITE_AREA .'/occupation/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Occupation.Edit');

			if ($this->save_occupation_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_occupation');

				Template::set_message(lang('occupation_update_success'), 'success');
                redirect(SITE_AREA .'/occupation/library/show_list');
			}
			else
			{
				Template::set_message(lang('occupation_details_edit_failure') . $this->occupation_model->error, 'error');
			}
		}
		
		Template::set('library_occupation', $this->occupation_model->find($id));
		Template::set('toolbar_title', lang('occupation_details_edit'));
		
        Template::set_view('initial/occupation_create');
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
	private function save_occupation_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['occupation_name']      	 	= $this->input->post('lib_occupation_occupation_name');
		$data['occupation_name_bangla']     = $this->input->post('occupation_name_bangla');
		$data['occupation_status'] 			= $this->input->post('lib_occupation_status');
		
		
		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Occupation.Create');			
			$data['created_by'] 		 = $this->current_user->id;  
			$id = $this->occupation_model->insert($data);

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
			$this->auth->restrict('Lib.Occupation.Edit');
			
			$data['modify_by'] 		    = $this->current_user->id;   
			$data['modify_date'] 		= date('Y-m-d H:i:s');
			$return = $this->occupation_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------

}