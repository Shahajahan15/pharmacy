<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Customer_discount_setup extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Customer_discount_model', NULL, true);
				$this->load->model('bank_info_model', NULL, true);

        $this->lang->load('customer_discount');	
		$this->lang->load('common');
		Template::set_block('sub_nav', 'customer_discount/_sub_nav_customer_discount');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {	
		//$this->auth->restrict('Lib.Branch.View'); 
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		
		if (is_array($checked) && count($checked))
		{
			//$this->auth->restrict('Lib.Branch.Delete');
			$result = FALSE;
           
            foreach ($checked as $id) 
			{
                 $data = [];
                 $data['is_deleted'] = 0;
                 $data['deleted_by'] = $this->current_user->id;
                 $data['deleted_date'] = date('Y-m-d H:i:s');
                $result = $this->Customer_discount_model->delete($id);
            }
            if ($result) 
			{
                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
			{
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->Customer_discount_model->error, 'error');
            }
		}

	    $records=$this->db->get('lib_customer_discount')->result_array();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("Library_customer_discount_list"));		
        Template::set_view('customer_discount/list');
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
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'lib_customer_discount');

                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/customer_discount_setup/library/show_list');
            } else 
            {
                Template::set_message(lang('bf_msg_create_failure') . $this->Customer_discount_model->error, 'error');
            }
        }
	
		Template::set('toolbar_title', lang("Library_customer_discount_create"));
        Template::set_view('customer_discount/create');
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
			redirect(SITE_AREA .'/customer_discount_setup/library/show_list');
		}

		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Lib.Branch.Edit');
           // $data['modify_by'] = $this->current_user->id;
            //$data['modify_date'] = date('Y-m-d H:i:s');
			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_customer_discount');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/customer_discount_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->Customer_discount_model->error, 'error');
			}
		}
		
		
		$records = $this->Customer_discount_model->find($id);		
        Template::set('records', $records);
		Template::set('toolbar_title', lang('library_branch_update'));		
        Template::set_view('customer_discount/create');
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
		$data['customer_type']        	= $this->input->post('library_customer_type');	
		$data['discount_for']        	= $this->input->post('library_discount_for');	
		$data['discount_parcent']       = $this->input->post('library_discount_parcent');	
		$data['start_date']          	= date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('date_start'))));
		$data['end_date']        	    = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('date_end'))));

		$data['status']      		    = $this->input->post('bf_status');
		$data['created_by'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			//$this->auth->restrict('Lib.Branch.Create');
			$id = $this->Customer_discount_model->insert($data);
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			//$this->auth->restrict('Lib.Branch.Edit');
			
		
			$return = $this->Customer_discount_model->update($id, $data);
		}

		return $return;
	}
	
}

