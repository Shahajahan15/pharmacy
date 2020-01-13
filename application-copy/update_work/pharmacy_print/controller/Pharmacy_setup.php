<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Setup controller
 */
class Pharmacy_setup extends Admin_Controller
{
    /**
     * Constructor
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pharmacy_setup_model', NULL, true);
        $this->lang->load('common');
        $this->lang->load('pharmacy');

    }

    /**
     * store company
     */

    public function form_list(){
        $this->auth->restrict('Lib.Hospital.View');
        $list_data=$this->show_list();
        $form_data=$this->create();
        $data=array();
        if(is_array($list_data))
            $data=array_merge($data,$list_data);
        if(is_array($form_data))
            $data=array_merge($data,$form_data);
        $data['form_action_url']=site_url('admin/pharmacy_setup/pharmacy/create');
        $data['list_action_url']=site_url('admin/pharmacy_setup/pharmacy/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("hospital_name_view"));
        Template::render();
    
    }

    public function show_list() 
	{
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }
        $this->auth->restrict('Lib.Hospital.View');
        //======= Delete Multiple or single=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) 
		{
            $this->auth->restrict('Lib.Hospital.Delete');
            $result = FALSE;

            foreach ($checked as $pharmacy_setup_id) 
			{
                $data = [];
                // $data['is_deleted'] = 1;
                $data['deleted_by'] = $this->current_user->id;
                $data['deleted_date'] = date('Y-m-d H:i:s');
                $result = $this->pharmacy_setup_model->update($pharmacy_setup_id, $data);
            }
            if ($result) 
			{
                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
			{
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->pharmacy_setup_model->error, 'error');
            }
        }
		
        $records = $this->db->select('SQL_CALC_FOUND_ROWS  bf_pharmacy_setup.*',false)
        ->get('bf_pharmacy_setup')
        ->result();
        
        $list_view='pharmacy_setup/list';
        if($this->input->is_ajax_request()){
            echo $this->load->View($list_view,compact('records'),true);
            exit();

        }
        Template::set('list_view',$list_view);
        Template::set('toolbar_title', lang("hospital_name_view"));
        Template::set_view('report_template');
        Template::render();
    }

    public function create() 
	{
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'create') {
            show_404();
        }
        // echo '<pre>';
        // print_r($this->session->all_userdata());
        // die();

        //TODO you code here	
        $data=array();
        if (isset($_POST['save'])) 
		{
            if ($insert_id = $this->save_hospital_details()) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pharmacy_setup');
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
                redirect(SITE_AREA . '/pharmacy_setup/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_create_failure') . $this->pharmacy_setup_model->error, 'error');
            }
        }
		 $form_view='pharmacy_setup/create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view,$data,true);
            exit;
        }
        Template::set('toolbar_title', lang("pharmacy_setup_create"));
        Template::set_view($form_view);
        Template::render();
    }

    public function edit() 
	{
        $id = $this->uri->segment(5);
        
        if (empty($id)) 
		{
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/pharmacy_setup/pharmacy/show_list');
        }

        if (isset($_POST['save'])) 
		{
            $this->auth->restrict('pharmacy_setup.Edit');

            if ($this->save_hospital_details('update', $id)) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pharmacy_setup');
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
                redirect(SITE_AREA . '/pharmacy_setup/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_edit_failure') . $this->pharmacy_setup_model->error, 'error');
            }
        }
		
        $data['records'] = $this->pharmacy_setup_model->find($id);
        $form_view = 'pharmacy_setup/create';

          if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }

        Template::set($data);
        Template::set('toolbar_title', lang('hospital_info_updateds'));
        Template::set_view($form_view);
        Template::render();
    }


    /**
     * Summary
     *
     * @param String $type Either "insert" or "update"
     * @param Int $id The ID of the record to update, ignored on inserts
     * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
     */
    private function save_hospital_details($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // make sure we only pass in the fields we want
        $data = array();
        $data['name'] = $this->input->post('name');
        $data['mobile'] = $this->input->post('mobile');
        $data['phone'] = $this->input->post('phone');
        $data['email'] = $this->input->post('email');
        $data['address'] = $this->input->post('address');
        $data['status'] = $this->input->post('status');

        if ($type == 'insert') {
            $return = $this->pharmacy_setup_model->insert($data);
                if ($_FILES['logo']['name'] && $return) {
                    $this->do_upload($return);
                }
                return $return;
        } elseif ($type == 'update') {
            $setup_id = $id;
            $return = $this->pharmacy_setup_model->update($setup_id, $data);

            if ($_FILES['logo']['name'] && $return) {
                $this->do_upload($setup_id);
            }
            return $setup_id;
        }
    }

    public function do_upload($setup_id)
    {
        $config['upload_path'] = "../public/assets/images/hospital/";
        $config['allowed_types'] = 'jpg|jpeg|gif|png';
        //$config['max_size'] = '1000';
        //$config['max_width'] = '1024';
        //$config['max_height'] = '768';

        $file_name = $_FILES['logo']['name'];
        $file_ex = explode(".", $file_name);
        $ext = end($file_ex);
        $config['file_name'] = $setup_id . '.' . $ext;
        $logo = $this->db->where('id', $setup_id)->get('pharmacy_setup')->row()->logo;
        
        if ($logo) {
            $path = FCPATH . 'assets/images/hospital/' . $logo;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('logo')) {
            //echo 'hi';exit;
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('admin/pharmacy_setup/pharmacy/form_list', $error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            $imageName = $data['upload_data']['file_name'];

            $pictureName = array(
                //'EMP_PHOTO' => $setup_id.'.'.$ext
                'logo' => $imageName
            );
            $this->db->update('pharmacy_setup', $pictureName, array('id' => $setup_id));
        }
    }

    public function checkHospitalNameAjax()
    {

        $hospitalName = $this->input->post('hospitalName');

        if (trim($hospitalName) != '') {
            $res = $this->db->query("SELECT name FROM bf_pharmacy_setup WHERE  name  LIKE '%$hospitalName%'");

            $result = $res->num_rows();


            if ($result > 0) {
                echo "hospital name already exit";

            }
        }
    }


}