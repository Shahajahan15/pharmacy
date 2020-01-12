<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Leave_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Lib.Leave.View ');
		$this->load->model('emp_leave_model', NULL, true);
        $this->lang->load('leave');	
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');		
		Template::set_block('sub_nav', 'leave_info/_sub_nav_leave');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Leave.Delete');
			$result = FALSE;

            foreach ($checked as $pid){
				$result = $this->emp_leave_model->delete($pid);
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->emp_leave_model->error, 'error');
			}
		}

        $records = $this->emp_leave_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_leave_view"));		
        Template::set_view('leave_info/leave_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function leave_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_leave_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_hrm_ls_leave');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/leave_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->emp_leave_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("Library_leave_create"));
        Template::set_view('leave_info/leave_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function leave_edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/leave_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Leave.Edit');

			if ($this->save_leave_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_hrm_ls_leave');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/leave_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->emp_leave_model->error, 'error');
			}
		}
		
		
			$lib_leave = $this->emp_leave_model->find($id);		
		
			Template::set('lib_leave',$lib_leave);		
			Template::set('toolbar_title', lang('library_leave_update'));		
			Template::set_view('leave_info/leave_create');
			Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_leave_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['LEAVE_TYPE']       		= $this->input->post('library_leave_name');
		$data['BUSINESS_DATE'] 		    = date("Y-m-d");
		$data['CREATED_BY'] 		    = $this->current_user->id; 
		$data['STATUS']      		    = $this->input->post('library_leave_status');
		      

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Leave.Create');
			$id = $this->emp_leave_model->insert($data);
			//print($this->db->last_query()); die; 
			
			
			
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.Leave.Edit');
			$return = $this->emp_leave_model->update($id, $data);
		}

		return $return;
	}
	
}

