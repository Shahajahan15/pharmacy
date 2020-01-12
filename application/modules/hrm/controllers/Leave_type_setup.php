<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * category controller
 */
class Leave_type_setup extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct() 
	{
        parent::__construct();          
       	$this->load->model('leave_type_model', NULL, true);    
        $this->lang->load('common');
        Template::set_block('sub_nav', 'leave_type/_sub_nav');
    }

    /* Write from here */

    public function show_list()
	{
        $this->auth->restrict('Hrm.LeaveType.View');

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Hrm.LeaveType.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
            	$data['is_deleted']=1;
            	$data['deleted_by']=$this->current_user->id;
            	$data['deleted_at']=date('Y-m-d H:i:s');
                $result = $this->db->where('id',$pid)->update('bf_hrm_Leave_type_setup',$data);
            }

            if ($result) {
                Template::set_message(count($checked) . ' Records Delete Successfully', 'success');
            } else {
                Template::set_message(lang('category_delete_failure') . $this->leave_type_model->error, 'error');
            }
        }

        $cond['is_deleted']=0;
        $records = $this->leave_type_model->find_all_by($cond);

        Template::set('records', $records);
        Template::set('toolbar_title','Leave Type List');
        Template::set_view('leave_type/list');
        Template::render();
    }
    
    /**
     * Account sub category setup & list
     */
    public function create() {
        //TODO you code here
		$data = array();
         if (isset($_POST['save'])) 
		{
            if ($insert_id = $this->save()) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'Lib_product_company');

                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/leave_type_setup/hrm/show_list');
            } else 
			{
                Template::set_message(lang('bf_msg_create_failure') . $this->leave_type_model->error, 'error');
            }
        }
        Template::set('toolbar_title', 'New Leave Type Create');
        Template::set($data);
        Template::set_view('leave_type/create');

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
            redirect(SITE_AREA . '/leave_type_setup/hrm/show_list');
        }

        if (isset($_POST['save'])) 
		{
            $this->auth->restrict('Hrm.LeaveType.Edit');

            if ($this->save('update', $id)) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'Lib_product_company');
                Template::set_message(lang('bf_msg_edit_success'), 'success');
                redirect(SITE_AREA . '/leave_type_setup/hrm/show_list');
            } else 
			{
                Template::set_message(lang('bf_msg_edit_failure'), 'error');
            }
        }
		
        $data['record'] = $this->leave_type_model->find($id);
        Template::set($data);
        Template::set('toolbar_title', 'Leave Type Update');
       	Template::set_view('leave_type/create');
        Template::render();
    }

     private function save($type = 'insert', $id = 0) 
	{
        //echo '<pre>';print_r($_POST);die();
        if ($type == 'update') 
		{
            $_POST['id'] = $id;
        }
        // make sure we only pass in the fields we want		
        $data = array();
        $data['leave_type'] = $this->input->post('leave_type');
        $data['description']= $this->input->post('description');
        $data['total_leave_days'] = $this->input->post('total_leave_days');
        $data['status'] 	= $this->input->post('status');

        if ($type == 'insert') 
		{
            $this->auth->restrict('Lib.LeaveType.Create');
            $data['created_by'] 	= $this->current_user->id;
        	$data['created_at'] 	= date('Y-m-d H:i:s');
            $id = $this->leave_type_model->insert($data);
            if (is_numeric($id)) 
			{
                $return = $id;
            } else 
			{
                $return = FALSE;
            }
        } elseif ($type == 'update') 
		{
            $this->auth->restrict('Hrm.LeaveType.Edit');
            $data['updated_by'] = $this->current_user->id;
            $data['updated_at'] = date('Y-m-d H:i:s');
            $return = $this->leave_type_model->update($id, $data);
        }
        return $return;
    }

}
