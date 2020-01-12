<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * subcategory controller
 */
class Sub_category extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct() 
	{
        parent::__construct();          
        $this->load->model('category_model', NULL, true);       
        $this->load->model('sub_category_model', NULL, true);       
        $this->lang->load('subcategory');
        //Template::set_block('sub_nav', 'sub_category/_sub_nav_sub_category');
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
    $data['form_action_url']=site_url('admin/sub_category/pharmacy/create');
    $data['list_action_url']=site_url('admin/sub_category/pharmacy/show_list');
    Template::set($data);
    Template::set_view('form_list_template');
    Template::set('toolbar_title', lang("pharmacy_sub_category_create"));
    Template::render();

}
    public function show_list()
	{
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }

        $this->auth->restrict('Pharmacy.SubCategory.View');
		$data = array();
        Template::set('toolbar_title', lang("subcategory_pharmacy_view"));

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.SubCategory.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->sub_category_model->delete($pid);
            }

            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('account_delete_success'), 'success');
            } else {
                Template::set_message(lang('account_delete_failure') . $this->sub_category_model->error, 'error');
            }
        }

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'subcategory';
        $search_box['ticket_no_flag'] = 0;
        $search_box['sex_list_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['product_name_flag']=0;
        $search_box['company_name_flag']=0;
        $search_box['category_name_flag']=1;
        $search_box['by_date_flag'] = 0;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;

        $con['pharmacy_subcategory.status >=']=0;

      
        if($this->input->post('category_name')){
            $con['pharmacy_category.category_name']=$this->input->post('category_name');
        }
         if($this->input->post('common_text_search')){
            $con['pharmacy_subcategory.subcategory_name']=$this->input->post('common_text_search');
        }
		$data['category_name'] = $this->get_category_name();	   
        //$records= $this->sub_category_model->find_all();
        
       $this->db->select('SQL_CALC_FOUND_ROWS bf_pharmacy_subcategory.*',false);
        //$this->db->join('_category','store_category.id=store_subcategory.category_id');
        $this->db->where($con);
        $this->db->limit( $limit, $offset);

        $records=$this->db->get('bf_pharmacy_subcategory')->result();
        $data['records']=$records;

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/sub_category/pharmacy/form_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $form_list='sub_category/sub_category_list';




        //handled ajax request
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_list, $data, true);
            exit;
        }
        Template::set($data);
        Template::set_view($form_list);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();


     
    }
    
    public function get_category_name(){
		$result = $this->category_model->find_all();
		$category_name = array();
		if($result) {
				foreach ($result as $row) {
					$category_name[$row->id] = $row->category_name;
				}
			
		}
		return $category_name;
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
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'Pharmacy_product_company');
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
                redirect(SITE_AREA . '/sub_category/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_create_failure') . $this->sub_category_model->error, 'error');
            }
        }
        $data['category_name'] = $this->category_model->find_all();
         $form_view='sub_category/sub_category_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view,$data,true);
            exit;
        }
        Template::set('toolbar_title', lang("pharmacy_sub_category_create"));
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
            redirect(SITE_AREA . '/sub_category/pharmacy/show_list');
        }

        if (isset($_POST['save'])) 
		{
            $this->auth->restrict('Pharmacy.SubCategory.Edit');

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
                redirect(SITE_AREA . '/sub_category/pharmacy/show_list');
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
                Template::set_message(lang('bf_msg_edit_failure') . $this->company_model->error, 'error');
            }
        }
		$data['category_name'] = $this->category_model->find_all();
        $data['sub_category_details'] = $this->sub_category_model->find($id);
        $form_view = 'sub_category/sub_category_create';

          if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
           }
        Template::set($data);
        Template::set('toolbar_title', lang('pharmacy_sub_category_update')); 
      	Template::set_view($form_view);
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
        $data['subcategory_name'] = $this->input->post('pharmacy_sub_category_name');
        $data['category_id'] = $this->input->post('pharmacy_category_name');
        $data['status'] = $this->input->post('pharmacy_sub_category_status');

        if ($type == 'insert') 
		{
            $this->auth->restrict('Pharmacy.SubCategory.Create');
             $data['created_by'] = $this->current_user->id;
        	 $data['created_date'] = date('Y-m-d H:i:s');
            $id = $this->sub_category_model->insert($data);
            if (is_numeric($id)) 
			{
                $return = $id;
            } else 
			{
                $return = FALSE;
            }
        } elseif ($type == 'update') 
		{
            $this->auth->restrict('Pharmacy.SubCategory.Edit');
            $data['modify_by'] = $this->current_user->id;
            $data['modify_date'] = date('Y-m-d H:i:s');
            $return = $this->sub_category_model->update($id, $data);
        }
        return $return;
    }

}
