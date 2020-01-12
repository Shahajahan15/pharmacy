<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Policy_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Lib.Policy.View');
		$this->load->model('emp_policy_model', NULL, true);
        $this->lang->load('policy');	
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');		
		Template::set_block('sub_nav', 'policy_info/_sub_nav_policy');
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
			$this->auth->restrict('Lib.Policy.Delete');
			$result = FALSE;

            foreach ($checked as $pid){
				$result = $this->emp_policy_model->delete($pid);
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->emp_policy_model->error, 'error');
			}
		}

        $records = $this->emp_policy_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_policy_view"));		
        Template::set_view('policy_info/policy_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function policy_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_policy_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_hrm_ls_policy');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/policy_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->emp_policy_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("Library_policy_create"));
        Template::set_view('policy_info/policy_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function policy_edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/policy_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Policy.Edit');

			if ($this->save_policy_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_hrm_ls_policy');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/policy_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->emp_policy_model->error, 'error');
			}
		}
		
		
			$lib_policy = $this->emp_policy_model->find($id);		
		
			Template::set('lib_policy',$lib_policy);		
			Template::set('toolbar_title', lang('library_policy_update'));		
			Template::set_view('policy_info/policy_create');
			Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_policy_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['POLICY_NAME']       		= $this->input->post('library_policy_name');
		$data['BUSINESS_DATE'] 		    = date("Y-m-d");
		$data['CREATED_BY'] 		    = $this->current_user->id; 
		$data['STATUS']      		    = $this->input->post('library_policy_status');
		      

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Policy.Create');
			$id = $this->emp_policy_model->insert($data);
			//print($this->db->last_query()); die; 
			
			
			
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.Policy.Edit');
			$return = $this->emp_policy_model->update($id, $data);
		}

		return $return;
	}
	
}

