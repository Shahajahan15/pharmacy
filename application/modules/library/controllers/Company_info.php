<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Company_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('company_info_model', NULL, true);
        $this->lang->load('company');	
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');	
		
		//Template::set_block('sub_nav', 'company_info/_sub_nav_companyinfo');
	}

    /**
     * store company 
     */
     public function form_list(){
	 	$list_data=$this->show_list();
	 	$form_data=$this->create();
	 	$data=array();
	 	if(is_array($list_data))
        $data=array_merge($data,$list_data);
        if(is_array($form_data))
        $data=array_merge($data,$form_data);
        $data['form_action_url']=site_url('admin/company_info/library/create');
        $data['list_action_url']=site_url('admin/company_info/library/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("library_company_view"));
        Template::render();

	 } 
	 
    public function show_list()
    {	

    if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }
		$this->auth->restrict('Library.Company.View'); 
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Company.Delete');
			$result = FALSE;

			$data = array();
			$data['is_deleted'] 		= 1; 
			
			
            foreach ($checked as $pid){
				
				$result = $this->company_info_model->update($pid,$data);
				
			}
			
			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->company_info_model->error, 'error');
			}
		}

        $records = $this->company_info_model->find_all_by('is_deleted', 0 );
		$data['records']=$records;
		$form_list='company_info/company_info_list';
		if($this->input->is_ajax_request()){
			echo $this->load->View($form_list,$data,true);
			exit();
		}
		Template::set($data);	
		Template::set('toolbar_title', lang("library_company_view"));		
        Template::set_view($form_list);
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
        //TODO you code here	
        $data=array();
		if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'company_create') {
            show_404();
        }
        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_company_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'company_info');
 				if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $insert_id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/company_info/library/show_list');
			}
			else
			{
				 if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $insert_id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('bf_msg_create_failure').$this->company_info_model->error, 'error');
			}
        }
		$form_view='company_info/company_info_create';
		if($this->input->is_ajax_request()){
			echo $this->load->View($form_view,$data,true);
			exit();
		}
		Template::set($data);
		Template::set('toolbar_title', lang("library_company_create"));
        Template::set_view($form_view);
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
			redirect(SITE_AREA .'/company_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Company.Edit');

			if ($this->save_company_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'company_info');
					if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/company_info/library/show_list');
			}
			else
			{
				if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('bf_msg_edit_failure') . $this->company_info_model->error, 'error');
			}
		}
		
		
		$data['company_details'] = $this->company_info_model->find($id);		
		$form_view='company_info/company_info_create';
		if($this->input->is_ajax_request()){
			echo $this->load->View($form_view,$data,true);
			exit();
		}
		Template::set($data);		
		Template::set('toolbar_title', lang('store_company_update'));		
        Template::set_view($form_view);
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_company_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['company_name']        	= $this->input->post('library_company_name');	
		$data['address']      			= $this->input->post('library_company_address');
		$data['status']      		    = $this->input->post('bf_status');
		$data['created_by'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Library.Company.Create');
			$id = $this->company_info_model->insert($data);
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
			$return = $this->company_info_model->update($id, $data);
		}

		return $return;
	}
	
}

