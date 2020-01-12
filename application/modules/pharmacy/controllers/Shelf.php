<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * shelf controller
 */
class Shelf extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct() 
	{
        parent::__construct();          
        $this->load->model('shelf_model', NULL, true);    
         $this->lang->load('shelf');
        //Template::set_block('sub_nav', 'shelf/_sub_nav_shelf');
    }

    /* Write from here */
  public function form_list(){
    $list_data=$this->show_list();
    $form_data=$this->create();
    $data=array();
    if(is_array($list_data))
        $data=array_merge($data,$list_data);
    if(is_array($form_data))
        $data=array_merge($data,$form_data);
    $data['form_action_url']=site_url('admin/shelf/pharmacy/create');
    $data['list_action_url']=site_url('admin/shelf/pharmacy/show_list');
    Template::set($data);
    Template::set_view('form_list_template');
    Template::set('toolbar_title', lang("pharmacy_shelf_view"));
    Template::render();
}
    public function show_list()
	{
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }

        $this->auth->restrict('Pharmacy.Shelf.View');
        Template::set('toolbar_title', lang("shelf_pharmacy_view"));

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.Shelf.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->shelf_model->delete($pid);
            }

            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('shelf_delete_success'), 'success');
            } else {
                Template::set_message(lang('shelf_delete_failure') . $this->shelf_model->error, 'error');
            }
        }

        $records = $this->shelf_model->find_all();
        $data['records']=$records;

        $form_view = 'shelf/shelf_list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
        Template::set($data);
        Template::set_view($form_view);

        Template::render();
    }
    
    /**
     * Account sub category setup & list
     */
    public function create() {
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'create') {
            show_404();
        }

        //TODO you code here
		$data = array();
         if (isset($_POST['save'])) 
		{
            if ($insert_id = $this->save_details()) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pharmacy_product_company');
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
                redirect(SITE_AREA . '/shelf/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_create_failure') . $this->shelf_model->error, 'error');
            }
        }
          $form_view='shelf/shelf_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view,$data,true);
            exit;
        }
        Template::set('toolbar_title', lang("pharmacy_shelf_create"));
        Template::set($data);
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
		$data = array();
        $id = $this->uri->segment(5);
        if (empty($id)) 
		{
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/shelf/pharmacy/show_list');
        }

        if (isset($_POST['save'])) 
		{
            $this->auth->restrict('Pharmacy.Shelf.Edit');

            if ($this->save_details('update', $id)) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pharmacy_product_company');
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
                redirect(SITE_AREA . '/shelf/pharmacy/show_list');
            } else 
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
                Template::set_message(lang('bf_msg_edit_failure') . $this->shelf_model->error, 'error');
            }
        }
		$data['shelf_details'] = $this->shelf_model->find($id);
        //$data['records']=$records;

        $form_view = 'shelf/shelf_create';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
        Template::set($data);
        Template::set('toolbar_title', lang('pharmacy_shelf_update'));
       Template::set_view('shelf/shelf_create');
        Template::render();
    }

     private function save_details($type = 'insert', $id = 0) 
	{
        if ($type == 'update') 
		{
            $_POST['id'] = $id;
        }
        // make sure we only pass in the fields we want		
        $data = array();
        $data['self_name'] = $this->input->post('pharmacy_shelf_name');
        $data['self_desc'] = $this->input->post('pharmacy_shelf_des');
        $data['status'] = $this->input->post('pharmacy_shelf_status');

        if ($type == 'insert') 
		{
            $this->auth->restrict('Pharmacy.Shelf.Create');
             $data['created_by'] = $this->current_user->id;
        	 $data['created_date'] = date('Y-m-d H:i:s');
            $id = $this->shelf_model->insert($data);
            if (is_numeric($id)) 
			{
                $return = $id;
            } else 
			{
                $return = FALSE;
            }
        } elseif ($type == 'update') 
		{
            $this->auth->restrict('Pharmacy.Shelf.Edit');
            $data['modified_by'] = $this->current_user->id;
            $data['modified_date'] = date('Y-m-d H:i:s');
            $return = $this->shelf_model->update($id, $data);
        }
        return $return;
    }

}
