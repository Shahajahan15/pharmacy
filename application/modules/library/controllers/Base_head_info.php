<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Base_head_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('base_head_model', NULL, true);
        $this->lang->load('base_head');	
		$this->lang->load('common');
		$this->load->config('config_base_head');
		
		//Assets::add_module_js('store', 'brand.js');		
		Template::set_block('sub_nav', 'base_head_info/_sub_nav_baseheadinfo');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {
		$this->auth->restrict('Lib.Basehead.View');
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Basehead.Delete');
			$result = FALSE;
			$data = array();
			$data['IS_DELETED'] 		= 1; 
			$data['DELETED_BY']			= $this->current_user->id;	
			$data['DELETED_DATE']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $aid)
			{				
				$result = $this->base_head_model->update($aid,$data);				
			}
			
			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $aid .' : '. $this->input->ip_address(), 'lib_base_head_info');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->base_head_model->error, 'error');
			}
		}

        $records = $this->base_head_model->find_all_by('IS_DELETED', 0);
		$head_type	= $this->config->item('head_type');	
		Template::set('records', $records);	
		Template::set('head_type', $head_type);	
		Template::set('toolbar_title', lang("lib_base_head_view"));		
        Template::set_view('base_head_info/base_head_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function base_head_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_base_head_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_base_head_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/base_head_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->base_head_model->error, 'error');
			}
        }
		
		
		$head_type	= $this->config->item('head_type');	
		Template::set('head_type',$head_type);
		Template::set('toolbar_title', lang("Lib_base_head_create"));
        Template::set_view('base_head_info/base_head_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function base_head_edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/base_head_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Basehead.Edit');

			if ($this->save_base_head_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_base_head_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/base_head_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->base_head_model->error, 'error');
			}
		}
		
			$head_type	= $this->config->item('head_type');			
			
			$lib_base_head_details = $this->base_head_model->find($id);	
		
			Template::set('lib_base_head_details',$lib_base_head_details);	
			Template::set('head_type',$head_type);
			Template::set('toolbar_title', lang('lib_base_head_update'));		
			Template::set_view('base_head_info/base_head_create');
			Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_base_head_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['BASE_SYSTEM_HEAD']       		= $this->input->post('lib_base_system_head');
		$data['BASE_HEAD_CUSTOM_NAME']       	= $this->input->post('lib_base_head_custom_name');
		$data['BASE_HEAD_ABBREBIATION']       	= $this->input->post('lib_base_head_abrebiation');
		$data['BASE_HEAD_LOCAL_LANG']       	= $this->input->post('lib_base_head_local_language');
		$data['BASE_HEAD_TYPE']       			= $this->input->post('lib_base_head_type');
		$data['IS_SALARY_HEAD']       			= $this->input->post('lib_base_is_salary_head');
		$data['IS_BASE_HEAD']       			= $this->input->post('lib_base_is_base_head');
		$data['DESCRIPTION']       				= $this->input->post('lib_base_head_description');			
		$data['STATUS']      		    		= $this->input->post('bf_status');
		      
//	
		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Basehead.Create');
			$data['CREATED_BY'] 		    	= $this->current_user->id; 
			$id = $this->base_head_model->insert($data);		
			
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.Basehead.Edit');
			$data['MODIFY_BY'] 		    	= $this->current_user->id; 
			$data['MODIFY_DATE'] 		    = date('Y-m-d H:i:s'); 
			$return = $this->base_head_model->update($id, $data);
		}

		return $return;
	}
	
}

