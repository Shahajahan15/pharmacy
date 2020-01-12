<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * product controller
 */ 
class Product extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct()
	{
        parent::__construct();
        $this->load->model('product_model', NULL, true);
        $this->load->model('company_model', NULL, true);
        $this->load->model('category_model', NULL, true);
        $this->load->model('sub_category_model', NULL, true);
        $this->load->model('shelf_model', NULL, true);
        $this->load->model('library/measurementunit_model', NULL, true);
        $this->lang->load('product');
        Assets::add_module_js('pharmacy', 'product.js');
        //Template::set_block('sub_nav', 'product/_sub_nav_product');
    }


    public function form_list()
    {
        $this->auth->restrict('Pharmacy.Product.View');
        $list_data = $this->show_list();
        $form_data = $this->create();
     
        $data = array();
        if (is_array($list_data))
            $data = array_merge($data, $list_data);
        if (is_array($form_data))
            $data = array_merge($data, $form_data);

        $data['form_action_url'] = site_url('admin/product/pharmacy/create');
        $data['list_action_url'] = site_url('admin/product/pharmacy/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("product_pharmacy_view"));
        Template::render();
    }


    /* Write from here */
    public function show_list()
    {
          if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }


        $this->auth->restrict('Pharmacy.Product.View');
        Template::set('toolbar_title', lang("product_pharmacy_view"));

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.Product.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->product_model->delete($pid);
            }

            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('product_delete_success'), 'success');
            } else {
                Template::set_message(lang('product_delete_failure') . $this->product_model->error, 'error');
            }
        }
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['ticket_no_flag'] = 0;
        $search_box['sex_list_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=1;
        $search_box['p_product_list_flag']=0;
        $search_box['product_name_flag'] = 1;

        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['common_text_search_flag'] = 0;
        $search_box['pharmacy_company_list_flag']=1;

        $condition['pharmacy_products.status >=']=0;


        if(count($_POST)>0){
            if($this->input->post('product_name')){            
                $condition['pharmacy_products.product_name like'] = '%'.trim($this->input->post('product_name')).'%';
            }
            if($this->input->post('pharmacy_category_name')) {
                $condition['pharmacy_products.category_id']=$this->input->post('pharmacy_category_name');
            }
            if($this->input->post('pharmacy_sub_category_id')){
                $condition['bf_pharmacy_products.sub_category_id']=$this->input->post('pharmacy_sub_category_id');
            }
            if($this->input->post('pharmacy_product_id')){
                $condition['bf_pharmacy_products.id']=$this->input->post('pharmacy_product_id');
            }
            if($this->input->post('pharmacy_company_id')){
                $condition['pharmacy_products.company_id']=$this->input->post('pharmacy_company_id');
            }
            if($this->input->post('pharmacy_shelf_id_select')) {
                $condition['pharmacy_products.shelf_id']=$this->input->post('pharmacy_shelf_id_select');
            }
            if($this->input->post('from_date')){

                $condition['pharmacy_products.requisition_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));

            }
            if($this->input->post('to_date')){

                $condition['pharmacy_products.requisition_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }
              if($this->input->post('common_text_search')){
                $condition['pharmacy_products.requisition_no like']='%'.$this->input->post('common_text_search').'%';
            }

        }
       // print_r($condition);
        $records = $this->db->select(
            'SQL_CALC_FOUND_ROWS 
            bf_pharmacy_products.*,pharmacy_product_company.company_name,pharmacy_category.category_name,pharmacy_shelf.self_name',false)
                    ->join('pharmacy_product_company','pharmacy_product_company.id=bf_pharmacy_products.company_id','left')
                    ->join('pharmacy_category','pharmacy_category.id=bf_pharmacy_products.category_id','left')
                    ->join('pharmacy_shelf','pharmacy_shelf.id=bf_pharmacy_products.shelf_id','left')
                    ->where($condition)
                    ->order_by('id asc')
                    ->limit($limit, $offset)
                    ->get('bf_pharmacy_products')
                    ->result();
                           // echo $this->db->last_query();

        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/product/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view='product/product_list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, compact('records','sl'), true);
            exit;
        }

        //echo '<pre>';print_r($records);exit;
        Template::set('records', $records);
        Template::set($data);
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

    /**
     * Account sub product setup & list
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
                redirect(SITE_AREA . '/product/pharmacy/show_list');
            }
            else
			{
                if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => false,
                        'message' => lang('bf_msg_create_failure'),
                    );

                    return $this->output->set_status_header(500)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }

                Template::set_message(lang('bf_msg_create_failure') . $this->product_model->error, 'error');
            }
        }

        $data['company_name'] = $this->company_model->find_all();

        $data['category_name'] = $this->category_model->find_all();
        $data['sub_category_name'] = $this->sub_category_model->find_all();
        $data['unit_name'] = $this->measurementunit_model->find_all();
        $data['shelf_name'] = $this->shelf_model->find_all();

        $form_view = 'product/product_create';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }

        Template::set('toolbar_title', lang("pharmacy_product_create"));
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
            redirect(SITE_AREA . '/category/pharmacy/show_list');
        }

        if (isset($_POST['save']))
		{
            $this->auth->restrict('Pharmacy.Product.Edit');

            if ($this->save_details('update', $id))
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pharmacy_product_company');

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
                redirect(SITE_AREA . '/product/pharmacy/show_list');
            }
            else
			{
                if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => false,
                        'message' => lang('bf_msg_edit_failure'),
                    );

                    return $this->output->set_status_header(500)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }

                Template::set_message(lang('bf_msg_edit_failure') . $this->product_model->error, 'error');
            }
        }

		$data['company_name'] = $this->company_model->find_all();
        $data['category_name'] = $this->category_model->find_all();
        $data['unit_name'] = $this->measurementunit_model->find_all();
        $data['shelf_name'] = $this->shelf_model->find_all();

        $data['product_details'] = $this->product_model->find($id);

        if($data['product_details']) {
	   	    $data['sub_category_name'] = $this->sub_category_model->find_all_by(array('category_id' => $data['product_details']->category_id));
	    }

        $form_view = 'product/product_create';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }

        Template::set($data);
        Template::set('toolbar_title', lang('pharmacy_product_update'));
       	Template::set_view($form_view);
        Template::render();
    }

     private function save_details($type = 'insert', $id = 0)
	{
		//echo '<pre>';print_r($_POST);exit;
        if ($type == 'update')
		{
            $_POST['id'] = $id;
        }
        // make sure we only pass in the fields we want
        $data = array();
        $data['company_id'] = $this->input->post('pharmacy_company_name');
        $data['category_id'] = $this->input->post('pharmacy_category_name');
        $data['sub_category_id'] = 0;
        $data['product_name'] = $this->input->post('pharmacy_product_name');
        $data['unit_id'] = $this->input->post('pharmacy_unit');
        $data['opening_balance'] = $this->input->post('pharmacy_opening_balance');
        $data['opening_price'] = $this->input->post('pharmacy_opening_price');
        $data['purchase_price'] = $this->input->post('pharmacy_purchase_price');
        $data['sale_price'] = $this->input->post('pharmacy_sale_price');
        $data['shelf_id'] = 0;
        $data['reorder_lebel'] = $this->input->post('pharmacy_reorder');
        $data['free_status'] = 0;
        if($this->input->post('pharmacy_free')){
			$data['free_status'] = $this->input->post('pharmacy_free');
		}
        $data['lead_time'] = $this->input->post('pharmacy_lead_time') ?: 0;
        $data['depreciation'] = $this->input->post('pharmacy_deprecitation') ?: 0;
        $data['yearly_indent'] = $this->input->post('pharmacy_yearly_indent') ?: 0;
        $data['account_head_id'] = $this->input->post('pharmacy_account_head') ?: 0;
        $data['status'] = $this->input->post('pharmacy_product_status');

        if ($type == 'insert')
		{
            $this->auth->restrict('Pharmacy.Product.Create');
            $data['created_by'] = $this->current_user->id;
        	$data['created_date'] = date('Y-m-d H:i:s');
            
            $id = $this->product_model->insert($data);

            $this->load->model('pharmacy/stock_model');

            $this->stock_model->add_opening_product($id, $data['opening_balance'], $data['created_by']);

            if (is_numeric($id))
			{
                $return = $id;
            } else
			{
                $return = FALSE;
            }
        } elseif ($type == 'update')
		{
            $this->auth->restrict('Pharmacy.Product.Edit');
            $data['updated_by'] = $this->current_user->id;
            $data['updated_date'] = date('Y-m-d H:i:s');
            $return = $this->product_model->update($id, $data);
        }
        return $return;
    }
    /* get subcategory by category id */

    public function getSubCategoryAjax(){
		$id = $this->input->post("id");
		$result = $this->sub_category_model->find_all_by(array('category_id' => $id));
		// print_r($result);exit;
		$options = "";
		$options .= "<select name='pharmacy_sub_category_name' id='pharmacy_sub_category_name' class='form-control' required=''>";
		$options .= "<option value=''>-- Select a one --</option>";
		if ($result) {
			foreach ($result as $row) {
            $options .= "<option value='$row->id' >".$row->subcategory_name."</option>";
        	}
		}
        $options .= "</select>";
        echo $options;
	}

    public function search_products()
    {
        $json = array();
        $search_term = $this->input->get('q') ?: '';
        if (empty($search_term)) {
            $json['error'] = 'No Search input given.';
            return $this->output->set_status_header(422)
							->set_content_type('application/json')
							->set_output(json_encode($json));
        }

        $filter = array('product_name LIKE' => '%'.$search_term.'%');
        $products = $this->product_model->as_array()->where($filter)->limit(25)->find_all() ?: array();
        $json = array(
            'products' => array(),
            'assoc' => array(),
        );
        foreach($products as $product) {
            $json['assoc'][$product['id']] = $product;
            $json['products'][] = array(
                'id' => $product['id'],
                'text' => $product['product_name'],
            );
        }

        return $this->output->set_status_header(200)
							->set_content_type('application/json')
							->set_output(json_encode($json));
    }

}
