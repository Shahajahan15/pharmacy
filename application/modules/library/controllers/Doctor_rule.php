<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Doctor_rule extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('initial');
		$this->lang->load('medicine_category');
		$this->load->model('doctor_rule_model', null, true);
		
		Template::set_block('sub_nav', 'doctor_rule/_sub_nav_doctor_rule');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {

    	$data['records']=$this->db->where('status',1)->where('is_deleted',0)->get('bf_doctor_rule')->result();

    	Template::set('toolbar_title','Rule List');		
        Template::set_view('doctor_rule/show_list');
        Template::set($data);
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save())
			{
				
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'company_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/doctor_rule/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->company_info_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title','Add new Rule');
        Template::set_view('doctor_rule/create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/doctor_rule/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Company.Edit');

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'company_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/doctor_rule/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->company_info_model->error, 'error');
			}
		}
		
		
		$company_details = $this->company_info_model->find($id);		
		
		Template::set('company_details',$company_details);		
		Template::set('toolbar_title', lang('store_company_update'));		
        Template::set_view('company_info/company_info_create');
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
		
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['rule_name']        	= $this->input->post('rule_name');	
		$data['status']      		    = (int)$this->input->post('bf_status');
		$data['added_by'] 		    = $this->current_user->id;    
 

		if ($type == 'insert')
		{
			//$this->auth->restrict('Library.Company.Create');
			$id = $this->doctor_rule_model->insert($data);
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Library.Company.Edit');
			
			$data['modified_by'] 		    = $this->current_user->id;   
			$data['modified_date'] 		    = date('Y-m-d H:i:s');   
			$return = $this->doctor_rule_model->update($id, $data);
		}

		return $return;
	}
	
}

