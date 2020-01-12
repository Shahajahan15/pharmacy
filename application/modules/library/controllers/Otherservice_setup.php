<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Otherservice_setup extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Otherservice_model', NULL, true);
		$this->load->model('Discount_service_model', NULL, true);
        $this->lang->load('otherservice');	
		$this->lang->load('common');
		//Template::set_block('sub_nav', 'initial/_sub_nav_otherservice');
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
        $data['form_action_url']=site_url('admin/otherservice_setup/library/create');
        $data['list_action_url']=site_url('admin/otherservice_setup/library/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("Library_otherservice_list"));
        Template::render();

	 }
    public function show_list()
    {	
    	 if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }
		$this->auth->restrict('Lib.OtherService.View'); 
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.OtherService.Delete');
			$result = FALSE;
           
            foreach ($checked as $id) 
			{
                 $data = [];
                 $data['is_deleted'] = 1;
                 $data['deleted_by'] = $this->current_user->id;
                 $data['deleted_date'] = date('Y-m-d H:i:s');
                $result = $this->Otherservice_model->update($id,$data);
            }
            if ($result) 
			{
                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
			{
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->Otherservice_model->error, 'error');
            }
		}
		$this->db->select('lib_otherservice.*,lib_discount_service_setup.service_name');
	    $this->db->join('lib_discount_service_setup','lib_discount_service_setup.id=lib_otherservice.service_id');
        $this->db->where('lib_otherservice.is_deleted',0);
	    $records=$this->db->get('lib_otherservice')->result_array();
        // print_r($records);exit();
		$data['records']=$records;
        $form_view = 'initial/otherservice_list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
        Template::set($data);
        Template::set_view($form_view);
		Template::set('toolbar_title', lang("Library_otherservice_list"));		
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
    	 if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'create') {
            show_404();
        }
        //TODO you code here	

         if (isset($_POST['save'])) 
        {
            if ($insert_id = $this->save()) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'lib_otherservice');
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
                redirect(SITE_AREA . '/otherservice_setup/library/show_list');
            } else 
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
                Template::set_message(lang('bf_msg_create_failure') . $this->otherservice_model->error, 'error');
            }
        }
		$data['service_details']=$this->Discount_service_model->find_all_by(['sub_in_others'=>1]);
		$form_view='initial/otherservice_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
		Template::set($data);	
		Template::set('toolbar_title', lang("Library_OtherService_create"));
        Template::set_view($form_view);
        Template::render();
    }
	
public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/otherservice_setup/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.OtherService.Edit');
           
			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_otherservice');
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
				redirect(SITE_AREA .'/otherservice_setup/library/show_list');
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
				Template::set_message(lang('bf_msg_edit_failure') . $this->otherservice_model->error, 'error');
			}
		}
		
		
		$records = $this->Otherservice_model->find($id);	

		$data['service_details']= $this->Discount_service_model->find_all();
		$data['records']=$records;
		$form_view='initial/otherservice_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
		Template::set($data);		
		Template::set('toolbar_title', lang('library_otheservice_update'));		
        Template::set_view($form_view);
		Template::render();
	}
	
	
	private function save($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['service_id']        	= $this->input->post('service_id');	
		$data['otherservice_name']        	= $this->input->post('library_otherservice_name');	
		$data['other_service_price']        	= $this->input->post('other_service_price');	
		$data['Description']      		    = $this->input->post('Description');
		$data['status']      		    = $this->input->post('bf_status');

		$data['created_by'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.OtherService.Create');
			$id = $this->Otherservice_model->insert($data);
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.OtherService.Edit');
			
		
			$return = $this->Otherservice_model->update($id, $data);
		}

		return $return;
	}
	
}

