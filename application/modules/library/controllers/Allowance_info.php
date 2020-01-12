<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Allowance_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Lib.Allowance.View');
		$this->load->model('allowance_model', NULL, true);
        $this->lang->load('allowance');	
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');		
		Template::set_block('sub_nav', 'allowance_info/_sub_nav_allowanceinfo');
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
			$this->auth->restrict('Lib.Allowance.Delete');
			$result = FALSE;

            foreach ($checked as $aid){
				$result = $this->allowance_model->delete($aid);
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->allowance_model->error, 'error');
			}
		}

        $records = $this->allowance_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_allowance_view"));		
        Template::set_view('allowance_info/allowance_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function allowance_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_allowance_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_allowance_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/allowance_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->allowance_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("Library_allowance_create"));
        Template::set_view('allowance_info/allowance_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function allowance_edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/allowance_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Allowance.Edit');

			if ($this->save_allowance_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_allowance_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/allowance_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->allowance_model->error, 'error');
			}
		}
		
		
			$lib_allowance = $this->allowance_model->find($id);		
		
			Template::set('lib_allowance',$lib_allowance);		
			Template::set('toolbar_title', lang('library_allowance_update'));		
			Template::set_view('allowance_info/allowance_create');
			Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_allowance_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['ALLOWANCE_NAME']       		= $this->input->post('library_allowance_name');
		$data['DESCRIPTION']       			= $this->input->post('library_allowance_description');
		//$data['BUSINESS_DATE'] 		    = date("Y-m-d");
		$data['CREATED_BY'] 		    	= $this->current_user->id; 
		$data['STATUS']      		    	= $this->input->post('bf_status');
		      

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Allowance.Create');
			$id = $this->allowance_model->insert($data);
			//print($this->db->last_query()); die; 
			
			
			
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.Allowance.Edit');
			$return = $this->allowance_model->update($id, $data);
		}

		return $return;
	}
	
}

