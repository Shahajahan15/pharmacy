<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * category controller
 */
class Test extends Admin_Controller
{

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('test_model', NULL, true);
        $this->lang->load('category');
        //Template::set_block('sub_nav', 'category/_sub_nav_category');
    }

    /* Write from here */
    public function form_list()
    {
        $this->auth->restrict('Pharmacy.Test.Create');
        $list_data = $this->show_list();
        $form_data = $this->create();
        $data = array();
        if (is_array($list_data))
            $data = array_merge($data, $list_data);
        if (is_array($form_data))
            $data = array_merge($data, $form_data);
        $data['form_action_url'] = site_url('admin/test/pharmacy/create');
        $data['list_action_url'] = site_url('admin/test/pharmacy/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("category_pharmacy_view"));
        Template::render();
    }

    public function show_list()
    {
        if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }

        $this->auth->restrict('Pharmacy.test.View');

        Template::set('toolbar_title', lang("category_pharmacy_view"));
        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.test.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->test_model->delete($pid);
            }
            if ($result) {

                Template::set_message(count($checked) . ' ' . lang('category_delete_success'), 'success');
            } else {

                Template::set_message(lang('category_delete_failure') . $this->test_model->error, 'error');
            }
        }

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;
        $records = $this->db->select("*    
                          ", false)
            ->from('bf_pharmacy_test as pc')
            ->limit($limit, $offset)
            ->order_by('pc.id', 'asc')
            ->get()
            ->result();
        //echo '<pre>'; print_r($records); exit();
        $data['records'] = $records;

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/test/pharmacy/form_list' . '?';

        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);


        $form_view = 'test/test_list';

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
    public function create()
    {
        if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'create') {
            show_404();
        }

        //TODO you code here
        $data = array();
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_details()) {
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
                redirect(SITE_AREA . '/test/pharmacy/show_list');
            } else {
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
                Template::set_message(lang('bf_msg_create_failure') . $this->test_model->error, 'error');
            }
        }
        $form_view = 'test/test_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
        Template::set('toolbar_title', lang("pharmacy_category_create"));
        Template::set($data);
        Template::set_view($form_view);

        Template::render();
    }

    /**
     * Allows editing of company data.
     *
     * @return void
     */


    private function save_details($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }
        // make sure we only pass in the fields we want		
        $data = array();
        $data['name'] = $this->input->post('pharmacy_category_name');
        $data['status'] = $this->input->post('pharmacy_category_status');

        if ($type == 'insert') {
            $this->auth->restrict('Pharmacy.Test.Edit');
            // $data['created_by'] = $this->current_user->id;
            //$data['created_date'] = date('Y-m-d H:i:s');
            $id = $this->test_model->insert($data);
            if (is_numeric($id)) {
                $return = $id;
            } else {
                $return = FALSE;
            }
        } elseif ($type == 'update') {
            //$this->auth->restrict('Pharmacy.Test.Edit');
            //$data['modify_by'] = $this->current_user->id;
            //$data['modify_date'] = date('Y-m-d H:i:s');
            $return = $this->test_model->update($id, $data);
        }
        return $return;
    }

    public function edit()
    {
        $data = array();
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/test/pharmacy/show_list');
        }

        if (isset($_POST['save'])) {
            $this->auth->restrict('Pharmacy.Test.Edit');

            if ($this->save_details('update', $id)) {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pharmacy_product_test');
                if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_edit_success'),
                        'inserted_id' => $id,
                    );

                    return $this->output->set_status_header(200)
                        ->set_content_type('application/json')
                        ->set_output(json_encode($json));
                }

                Template::set_message(lang('bf_msg_edit_success'), 'success');
                redirect(SITE_AREA . '/test/pharmacy/show_list');
            } else {
                if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_edit_success'),
                        'inserted_id' => $id,
                    );

                    return $this->output->set_status_header(200)
                        ->set_content_type('application/json')
                        ->set_output(json_encode($json));
                }

                Template::set_message(lang('bf_msg_edit_failure') . $this->test_model->error, 'error');
            }
        }

        //$data['category_details'] = $this->test_model->find($id);
        $form_view = 'test/test_create';
        $data['category_details'] = $this->test_model->select('id, name as category_name, status')->as_array()->find_by(['id' => $id]);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }

        Template::set($data);
        Template::set('toolbar_title', lang('pharmacy_category_update'));
        Template::set_view($form_view);
        Template::render();
    }
}
