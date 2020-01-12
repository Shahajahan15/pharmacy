<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * company controller
 */
class Dummy extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();


        $this->load->model('Dummy_table_model', NULL, true);

        //$this->lang->load('company');
        $this->lang->load('common');

        // Template::set_block('sub_nav', 'company/_sub_nav_company');
    }

    /**
     * pharmacy company
     */
    public function form_list()
    {

        $this->auth->restrict('Pharmacy.dummy.View');

        $list_data = $this->show_list();
        $form_data = $this->create();
        $data = array();
        if (is_array($list_data))
            $data = array_merge($data, $list_data);

        $data['form_action_url'] = site_url('admin/dummy/pharmacy/create');
        $data['list_action_url'] = site_url('admin/dummy/pharmacy/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        //Template::set('toolbar_title');
        Template::render();

    }

    public function show_list()
    {

        if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }

        $this->auth->restrict('Pharmacy.dummy.View');
        //======= Delete Multiple or single=======
        $checked = $this->input->post('checked');

        /*   if (is_array($checked) && count($checked))
           {

               $this->auth->restrict('Pharmacy.dummy.Delete');
               $result = FALSE;

               foreach ($checked as $company_id)
               {
                   $data = [];
                   $data['is_deleted'] = 1;
                   $data['deleted_by'] = $this->current_user->id;
                   $data['deleted_date'] = date('Y-m-d H:i:s');
                   $result = $this->company_model->update($company_id, $data);
               }
               if ($result)
               {
                   Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
               } else
               {

                   Template::set_message(lang('bf_msg_record_delete_fail') . $this->company_model->error, 'error');
               }
           }*/

        $this->db->where("is_deleted != ", 1);
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['ticket_no_flag'] = 0;
        $search_box['sex_list_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag'] = 0;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Location/Country';
        $search_box['company_name_flag'] = 1;

        $condition[] = '';
        //$con[]= '1 = 0';

        /*    if(count($_POST)>0){

              if($_POST['company_name']){
                  $condition['pharmacy_product_company.company_name']=$this->input->post('company_name');
              }

              if($this->input->post('common_text_search')){
                  $condition['pharmacy_product_company.company_location'] =$this->input->post('common_text_search');

              }

          }
          //$records = $this->company_model->find_all_by('is_deleted',0);
  //        $records = $this->db->query("SELECT * FROM bf_dummy_table;")
          $records = $this->Dummy_table_model->find_all();*/
        //->where('is_deleted',0)
        //->where($condition)

        /*->limit($limit,$offset)
        ->get('bf_dummy_table')
        ->result();*/

        //$records = $this->dummy_table_model->find_all();
        //$records =  $this->db->query("SELECT * FROM bf_dummy_table;");
        //$records =  $this->db->query("SELECT * FROM bf_dummy_table;");

        $records = $this->db
            ->select('*')
            ->from('bf_dummy_table as dt')
            ->get()
            ->result();

        //
        //echo '<pre>'; print_r($records); exit();
        /*
                        $total = 3;
                        $this->pager['base_url'] = site_url() . '/admin/dummy/pharmacy/show_list' . '?';
                        $this->pager['total_rows'] = $total;
                        //$this->pager['per_page'] = $limit;
                        $this->pager['page_query_string'] = TRUE;*/
        //$this->pagination->initialize($this->pager);
        $data['records'] = $records;
        $list_view = 'dummy/dummy_list';
        if ($this->input->is_ajax_request()) {
            echo $this->load->View($list_view, compact('records'), true);

        }


        Template::set($data);
        Template::set('search_box', $search_box);
        Template::set('list_view', $list_view);

        Template::set('toolbar_title', lang("pharmacy_company_view"));
        Template::set_view('report_template');
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
                redirect(SITE_AREA . '/company/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_create_failure') . $this->company_model->error, 'error');
            }
        }
        $form_view = 'company/company_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
        Template::set('toolbar_title', lang("pharmacy_company_create"));
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
        if (empty($id)) {
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/company/pharmacy/show_list');
        }

        if (isset($_POST['save'])) {
            $this->auth->restrict('Pharmacy.Company.Edit');

            if ($this->save_details('update', $id)) {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'store_product_company');
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
                redirect(SITE_AREA . '/company/pharmacy/show_list');
            } else {
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
                Template::set_message(lang('bf_msg_edit_failure') . $this->company_model->error, 'error');
            }
        }

        $data['company_details'] = $this->company_model->find($id);
        $form_view = 'company/company_create';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }

        Template::set($data);
        Template::set('toolbar_title', lang('pharmacy_company_update'));
        Template::set_view($form_view);
        Template::render();
    }

    private function save_details($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }
        // make sure we only pass in the fields we want
        $data = array();
        $data['company_name'] = $this->input->post('pharmacy_company_name');
        $data['company_code'] = $this->input->post('pharmacy_company_code');
        $data['company_location'] = $this->input->post('pharmacy_company_location');
        $data['company_country'] = $this->input->post('pharmacy_company_country');
        $data['status'] = $this->input->post('pharmacy_company_status');
        $data['created_by'] = $this->current_user->id;
        $data['created_date'] = date('Y-m-d H:i:s');

        if ($type == 'insert') {
            $this->auth->restrict('Pharmacy.Company.Create');
            $id = $this->company_model->insert($data);
            if (is_numeric($id)) {
                $return = $id;
            } else {
                $return = FALSE;
            }
        } elseif ($type == 'update') {
            $this->auth->restrict('Pharmacy.Company.Edit');
            $data['modified_by'] = $this->current_user->id;
            $data['modified_date'] = date('Y-m-d H:i:s');
            $return = $this->company_model->update($id, $data);
        }
        return $return;
    }

}
