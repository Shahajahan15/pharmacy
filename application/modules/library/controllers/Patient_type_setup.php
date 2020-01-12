<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Patient_type_setup extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		
		$this->load->model('patient_type_model', NULL, true);
		$this->lang->load('common');
		//Assets::add_module_js('library', 'patient_discount');
		Template::set_block('sub_nav', 'patient_type/_sub_nav_patient_type_setup');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {
    	$this->auth->restrict('Discount.TypeSetup.View');
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{

			$result = FALSE;
			
			$data = array();
			$data['is_deleted'] 		= 1; 
			
            foreach ($checked as $pid){
				$result = $this->patient_type_model->update($pid,$data);
				$this->db->where('discount_mst_id', $pid)->update('bf_lib_patient_discounts_dtls', $data);
				
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->patient_type_model->error, 'error');
			}
		}

		$condition['is_deleted']=0;
		
		$data['records'] 	= $this->patient_type_model->find_all_by($condition);


		
		Template::set($data);
		Template::set('toolbar_title', 'Patient Type Lists');
        Template::set_view('patient_type/list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
        //TODO you code here
        $this->auth->restrict('Discount.TypeSetup.Add');
			

			if (isset($_POST['save']))
			{
				//echo '<pre>';print_r($_POST);die();

				if ($insert_id = $this->save())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_patient_type_setup');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/patient_type_setup/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->patient_type_model->error, 'error');
				}
			}

		$conditon['is_deleted']=0;
		
		
		
		//Template::set($data);
		Template::set('toolbar_title', 'Patient Type Create');
        Template::set_view('patient_type/create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->auth->restrict('Discount.TypeSetup.Edit');
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/patient_type_setup/library/show_list');
		}
			
		if (isset($_POST['save']))
		{
			//print_r($_POST);die();
			//$this->auth->restrict('Lib.DiscountService.Edit');

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $DESIGNATION_ID .' : '. $this->input->ip_address(), 'bf_lib_patient_type_setup');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/patient_type_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->patient_type_model->error, 'error');
			}
		}
		
		
		
			$data['record'] = $this->patient_type_model->find($id);

		
			Template::set($data);
			Template::set('toolbar_title', 'Patient Type Edit');
        	Template::set_view('patient_type/create');
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
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['type_name']       		= $this->input->post('type_name');
		$data['description']       		= $this->input->post('description');
		$data['status']       			= $this->input->post('status');
		

		if ($type == 'insert')
		{
			$data['created_by']       	= $this->current_user->id;

			$id=$this->patient_type_model->insert($data);

			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$return = $this->patient_type_model->update($id, $data);
		}

		return $return;
	}


	
}

?>