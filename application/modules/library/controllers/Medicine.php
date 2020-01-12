<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Medicine extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('initial');
		$this->load->model('medicine_model', null, true);	
		
		
		Template::set_block('sub_nav', 'medicine/_sub_nav_medicine');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {

    	$data['records']=$this->db
    		->select("bf_lib_medicines.*,
    			bf_lib_company_info.company_name,
    			bf_medicine_categories.category_name,
    			bf_store_product_generic_group.GROUP_NAME as group_name,
    			bf_store_product_generic_name.GENERIC_NAME as generic_name")
    		->from('bf_lib_medicines')
    		->join('bf_lib_company_info','bf_lib_company_info.id=bf_lib_medicines.company_id')
    		->join('bf_medicine_categories','bf_medicine_categories.id=bf_lib_medicines.category_id')

    		->join('bf_store_product_generic_group','bf_store_product_generic_group.GROUP_ID=bf_lib_medicines.group_id')
    		->join('bf_store_product_generic_name','bf_store_product_generic_name.GENERIC_ID=bf_lib_medicines.generic_id')
    		->where('bf_lib_medicines.status',1)
    		->where('bf_lib_medicines.is_deleted',0)
    		->get()
    		->result();


    	Template::set('toolbar_title','Category List');		
        Template::set_view('medicine/show_list');
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
				redirect(SITE_AREA .'/medicine/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->company_info_model->error, 'error');
			}
        }

        $data['companies']=$this->db->where('is_deleted',0)->where('status',1)->get('bf_lib_company_info')->result();
        $data['cetegories']=$this->db->where('is_deleted',0)->where('status',1)->get('bf_medicine_categories')->result();
        $data['gen_groups']=$this->db->where('IS_DELETED',0)->get('bf_store_product_generic_group')->result();
        $data['generic_names']=$this->db->where('IS_DELETED',0)->get('bf_store_product_generic_name')->result();

        //print_r($data['cetegories']);die();
		
		
		Template::set('toolbar_title','Add new Medicine Here');
        Template::set_view('medicine/create');
        Template::set($data);
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
			redirect(SITE_AREA .'/medicine/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Company.Edit');

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'company_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/medicine/library/show_list');
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
		$data['group_id']        	= $this->input->post('group_id');
		$data['category_id']      	= (int)$this->input->post('category_id');
		$data['status']      		= (int)$this->input->post('bf_status');
		$data['generic_id'] 		= $this->input->post('generic_name_id');
		$data['medicine_name'] 		= $this->input->post('medicine_name');
		$data['company_id'] 		= $this->input->post('company_id');
		$data['added_by'] 			= $this->current_user->id;

		if ($type == 'insert')
		{
			//$this->auth->restrict('Library.Company.Create');
			$id = $this->medicine_model->insert($data);
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
			$return = $this->medicine_model->update($id, $data);
		}

		return $return;
	}
	
}
