<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Generic_group extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Generic_group_model', NULL, true);
        $this->lang->load('generic_group');	
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');	
		
		Template::set_block('sub_nav', 'generic_group/_sub_nav_genericinfo');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {	
		$this->auth->restrict('Pharmacy.Generic.View'); 
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Pharmacy.Generic.Delete');
			$result = FALSE;
           
            foreach ($checked as $id) 
			{
                $data = [];
                $data['is_deleted'] = 0;
                $data['deleted_by'] = $this->current_user->id;
                $data['deleted_date'] = date('Y-m-d H:i:s');
                $result = $this->Generic_group_model->delete($id, $data);
            }
            if ($result) 
			{
                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
			{
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->Generic_group_model->error, 'error');
            }
		}
        $records = $this->Generic_group_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("pharmacy_group_view"));		
        Template::set_view('generic_group/generic_group_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'pharmacy_product_generic_group');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/generic_group/pharmacy/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("pharmacy_group_create"));
        Template::set_view('generic_group/generic_group_create');
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
			redirect(SITE_AREA .'/generic_group/pharmacy/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Pharmacy.Generic.Edit');
            $data['modify_by'] = $this->current_user->id;
            $data['modified_date'] = date('Y-m-d H:i:s');
			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'pharmacy_product_generic_group');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/generic_group/pharmacy/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->Generic_group_model->error, 'error');
			}
		}
		
		
		$group_details = $this->Generic_group_model->find($id);		
		
		Template::set('group_details',$group_details);		
		Template::set('toolbar_title', lang('pharmacy_generic_group_update'));		
        Template::set_view('generic_group/generic_group_create');
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
		$data['group_name']        	= $this->input->post('pharmacy_group_name');	
		$data['status']      		    = $this->input->post('bf_status');
		$data['created_by'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Pharmacy.Generic.Create');
			$id = $this->Generic_group_model->insert($data);
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Pharmacy.Generic.Edit');
			
			$data['modify_by'] 		    = $this->current_user->id;   
			$data['modified_date'] 		    = date('Y-m-d H:i:s');   
			$return = $this->Generic_group_model->update($id, $data);
		}

		return $return;
	}
	
}

