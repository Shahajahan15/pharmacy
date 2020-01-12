<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * supplier_setup controller
 */
class Supplier extends Admin_Controller 
{
    /* ========================================================================================
      Start Main Store
      ======================================================================================== */

    //--------------------------------------------------------------------
    /**
     * Constructor
     *
     * @return void
     */
    //--------------------------------------------------------------------

    public function __construct() 
    {
        parent::__construct();

        $this->load->model('supplier_model', NULL, TRUE);
        $this->load->model('company_model', NULL, TRUE);
        $this->lang->load('common');
        $this->lang->load('supplier');
        Assets::add_module_js('store', 'supplier.js');
        //Template::set_block('sub_nav', 'supplier/_sub_nav_supplier');
    }
  public function form_list(){
    $this->auth->restrict('Pharmacy.Supplier.View');
    $list_data=$this->show_list();
    $form_data=$this->create();
    $data=array();
    if(is_array($list_data))
        $data=array_merge($data,$list_data);
    if(is_array($form_data))
        $data=array_merge($data,$form_data);
    $data['form_action_url']=site_url('admin/supplier/pharmacy/create');
    $data['list_action_url']=site_url('admin/supplier/pharmacy/show_list');
    Template::set($data);
    Template::set_view('form_list_template');
    Template::set('toolbar_title', lang("pharmacy_supplier_view"));
    Template::render();
}
    public function show_list() 
    {
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }

        $this->auth->restrict('Pharmacy.Supplier.View');

        //======= Delete Multiple =======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) 
        {
            $this->auth->restrict('Pharmacy.Supplier.Delete');
            $result = FALSE;
            foreach ($checked as $store_id) 
            {
                $result = $this->supplier_model->delete($store_id);
            }
            if ($result) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_delete_record') . ': ' . $store_id . ' : ' . $this->input->ip_address(), 'pharmacy_supplier');

                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
            {
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->supplier_model->error, 'error');
            }
        }
            $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['supplier_name_flag'] = 1;
        $search_box['company_name_flag'] = 1;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'contact person';
        $search_box['company_list_flag']=1;

        $condition[]='';
        //$con[]= '1 = 0';

        if(count($_POST)>0){

           if($_POST['company_name']){
                $condition['pharmacy_product_company.company_name']=$this->input->post('company_name');
            } 
           if($_POST['supplier_name']){
                $condition['pharmacy_supplier.supplier_name']=$this->input->post('supplier_name');
            }
              if($this->input->post('common_text_search')){
                $condition['pharmacy_supplier.contact_person'] =$this->input->post('common_text_search');

            }

        }    

	   $records= $this->db->select('SQL_CALC_FOUND_ROWS 
           bf_pharmacy_supplier.*,pharmacy_product_company.company_name',false)
	    ->join('pharmacy_product_company','pharmacy_product_company.id=bf_pharmacy_supplier.company_id')
        ->where($condition)
	    ->get('bf_pharmacy_supplier')
        ->result_array();
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/supplier/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records']=$records;

       
        $list_view='supplier/supplier_list';
        if($this->input->is_ajax_request()){
            echo $this->load->View($list_view,compact('records','sl'),true);
            exit();

        }
        Template::set($data);
        Template::set('list_view',$list_view);
        //Template::set($data);
        Template::set('search_box',$search_box);
        Template::set('records', $records);
        Template::set('toolbar_title', lang("pharmacy_supplier_view"));
        Template::set_view('report_template');
        Template::render();
    }

    //--------------------------------------------------------------------
    /**
     * Creates a building object.
     *
     * @return void
     * */
    //--------------------------------------------------------------------

    public function create() 
    {
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'create') {
            show_404();
        }

        //TODO you code here

        if (isset($_POST['save'])) 
        {
            if ($insert_id = $this->save_details()) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pharmacy_supplier');
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
                redirect(SITE_AREA . '/supplier/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_create_failure') . $this->supplier_model->error, 'error');
            }
        }



        $data['company_details'] = $this->company_model->find_all();
     
        $form_view='supplier/supplier_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view,$data,true);
            exit;
        }
        Template::set('toolbar_title', lang("pharmacy_supplier_create"));
        Template::set($data);
        Template::set_view($form_view);
        Template::render();
    }

    //--------------------------------------------------------------------
    /**
     * Allows editing of Building Details data.
     *
     * @return void
     */
    //--------------------------------------------------------------------

    public function edit() 
    {
        $id = $this->uri->segment(5);
        if (empty($id)) 
        {
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/supplier/pharmacy/show_list');
        }if (isset($_POST['save'])) 
        {
            $this->auth->restrict('Pharmacy.Supplier.Edit');
            $data['updated_by'] = $this->current_user->id;
            $data['updated_date'] = date('Y-m-d H:i:s');
            if ($this->save_details('update', $id)) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pharmacy_supplier');
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
                redirect(SITE_AREA . '/supplier/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_edit_failure') . $this->supplier_model->error, 'error');
            }
        }
        
        $data['company_details'] = $this->company_model->find_all();
        $records= $this->supplier_model->find($id);
        $data['records']=$records;

        $form_view = 'supplier/supplier_create';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
        Template::set($data);
        Template::set('toolbar_title', lang('pharmacy_supplier_update'));
        Template::set_view($form_view);
        Template::render();
    }

    //--------------------------------------------------------------------
    //--------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------

    /**
     * Summary
     *
     * @param String $type Either "insert" or "update"
     * @param Int    $id    The ID of the record to update, ignored on inserts
     *
     * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
     */
   
         private function save_details($type = 'insert', $id = 0) 
		    {
		        if ($type == 'update') 
		        {
		            $_POST['id'] = $id;
		        }

		        $data = array();
		        $data['company_id'] = $this->input->post('company_id');

		        $data['supplier_code'] = $this->input->post('pharmacy_supplier_code');
		        $data['supplier_name'] = $this->input->post('pharmacy_supplier_name');
		        $data['contact_no1'] = $this->input->post('pharmacy_supplier_contact_no_1');
		        $data['contact_no2'] = $this->input->post('pharmacy_supplier_contact_no_2');
		        $data['contact_person'] = $this->input->post('pharmacy_supplier_contact_person');
		        $data['email'] = $this->input->post('pharmacy_supplier_email');
		        $data['status'] = $this->input->post('pharmacy_supplier_status');
		        $data['created_by'] = $this->current_user->id;
		        $data['created_date'] = date('Y-m-d H:i:s');
		         if ($type == 'insert') 
		        {
		            $this->auth->restrict('pharmacy.Supplier.Create');
		            $id = $this->supplier_model->insert($data);
		            if (is_numeric($id)) 
		            {
		                $return = $id;
		            } else 
		            {
		                $return = FALSE;
		            }
		        }  elseif ($type == 'update') 
		        {
		            $this->auth->restrict('Pharmacy.Supplier.Edit');
		            $data['updated_by'] = $this->current_user->id;
		            $data['updated_date'] = date('Y-m-d H:i:s');
		            $return = $this->supplier_model->update($id, $data);
		        }
		        return $return;
		        
		    }
    

    /* ========================================================================================
      End Main Store
      ======================================================================================== */
}
