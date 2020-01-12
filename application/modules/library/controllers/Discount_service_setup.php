<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Discount_service_setup extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('discount_service_model', NULL, true);
		$this->lang->load('common');
			
		Template::set_block('sub_nav', 'discount_service/_sub_nav_discount_service');
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

			//$this->auth->restrict('Lib.DiscountService.Delete');
			$result = FALSE;
			
			$data = array();

			$data['is_deleted'] 		= 1; 
			
            foreach ($checked as $pid){
				$result = $this->discount_service_model->update($pid,$data);
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->designation_info_model->error, 'error');
			}
		}

		$data['records'] =  $this->db
							->select('s.*, d.department_name')
							->from('lib_discount_service_setup as s')
							->join('lib_department as d', 's.department_id = d.dept_id','left')
							->where('s.is_deleted',0)
							->get()
							->result();
		
		Template::set($data);	
		Template::set('toolbar_title', 'Discount Service Lists');
        Template::set_view('discount_service/list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
        //TODO you code here
			$data = array();

			if (isset($_POST['save']))
			{

				if ($insert_id = $this->save())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_discount_service_setup');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/discount_service_setup/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->designation_info_model->error, 'error');
				}
			}
		
		$data['department_list'] = $this->db->get('lib_department')->result();
		Template::set($data);
		Template::set('toolbar_title', 'Discount Service Create');
        Template::set_view('discount_service/create');
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
			redirect(SITE_AREA .'/discount_service_setup/library/show_list');
		}
			
		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Lib.DiscountService.Edit');

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $DESIGNATION_ID .' : '. $this->input->ip_address(), 'bf_lib_discount_service_setup');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/discount_service_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->designation_info_model->error, 'error');
			}
		}
		
		
		$data['department_list'] = $this->db->get('lib_department')->result();
		
			$data['record'] = $this->discount_service_model->find($id);		
		
			Template::set($data);
			Template::set('toolbar_title', 'Discount Service Edit');
        	Template::set_view('discount_service/create');
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
		$data['service_name']       	= $this->input->post('service_name');
		$data['department_id']    		= $this->input->post('department_id');
		$data['has_discount']    		= $this->input->post('has_discount');
		$data['status']       			= $this->input->post('status');	

		if ($type == 'insert')
		{
			$data['created_by']       			= $this->current_user->id;
			$id=$this->discount_service_model->insert($data);


			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			
			
			$return = $this->discount_service_model->update($id, $data);
		}

		return $return;
	}
	
	
	
	
	
}

