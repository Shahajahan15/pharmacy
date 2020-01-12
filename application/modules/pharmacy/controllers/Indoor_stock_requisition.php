<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * shelf controller
 */
class Indoor_stock_requisition extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    
    public function __construct() 
	{
        parent::__construct();          
        $this->load->model('indoor_stock_requisition_model', NULL, true);       
        $this->load->model('indoor_stock_requisition_dtls_model', NULL, true);
        $this->lang->load('common');
        $this->lang->load('indoor_stock_requisition');
        Assets::add_module_js('pharmacy','indoor_stock_requisition');
        Template::set_block('sub_nav', 'indoor_stock_requisition/_sub_nav');
    }

    /* Write from here */

    public function show_list()
	{
        $this->auth->restrict('Pharmacy.Indoor.Req.View');
        Template::set('toolbar_title', lang("dept_requisition_title_view"));

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.indoor.requisition.Delete');
           
        }
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'requisition No';

        $search_box['pharmacy_product_list_flag']=1;
        $condition['bf_pharmacy_indoor_requision_mst.status>=']=0;
        $pharmacy_id = ($this->current_user->role_id == 23) ? $this->current_user->pharmacy_id : 200; 
        $condition['bf_pharmacy_indoor_requision_mst.pharmacy_id'] = $pharmacy_id;
            if(count($_POST)>0){
                
            if($_POST['pharmacy_category_name']){
                $condition['bf_pharmacy_products.category_id']=$this->input->post('pharmacy_category_id');
            }
            
            if($this->input->post('store_product_id')){
                $condition['bf_pharmacy_products.id']=$this->input->post('store_product_id');
            }
               
                 if($this->input->post('from_date')){

                $condition['bf_pharmacy_indoor_requision_mst.requisition_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['bf_pharmacy_indoor_requision_mst.requisition_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }   
             
            }
        $records= $this->db->select("
                                bf_pharmacy_indoor_requision_mst.requisition_date,
                                bf_pharmacy_indoor_requision_mst.issue_date,
                                bf_pharmacy_setup.name as requisition_pharmacy_name,
                                bf_pharmacy_indoor_requision_mst.id,
                                bf_pharmacy_indoor_requision_dtls.status,
                                bf_pharmacy_indoor_requision_dtls.product_id,
                                bf_pharmacy_indoor_requision_dtls.req_qnty,
                                bf_pharmacy_indoor_requision_dtls.issue_qnty,
                                bf_pharmacy_products.product_name,
                                bf_pharmacy_category.category_name
                                ")
                    ->join('bf_pharmacy_indoor_requision_dtls','bf_pharmacy_indoor_requision_dtls.master_id=bf_pharmacy_indoor_requision_mst.id')
                    ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_indoor_requision_dtls.product_id')
                    ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id')
                    ->join('bf_pharmacy_setup','bf_pharmacy_setup.id = bf_pharmacy_indoor_requision_mst.issue_pharmacy_id','left')
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->order_by('bf_pharmacy_indoor_requision_mst.id','DESC')
                    ->get('bf_pharmacy_indoor_requision_mst')->result();

       
         $data['records']=$records;  

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/indoor_stock_requisition/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('indoor_stock_requisition/list', compact('records','sl'), true);
            exit;
        } 


        Template::set($data);
       // Template::set_view('indoor_stock_requisition/list');
        $list_view='indoor_stock_requisition/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }
    
    /**
     * Account sub category setup & list
     */
    public function create() {

        //TODO you code here
         $this->auth->restrict('Pharmacy.Indoor.Req.Add');
		$data = array();
         if (isset($_POST['save']) && count($_POST)>1) 
		{
            
            if ($insert_id = $this->save()) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'bf_pharmacy_indoor_requision_mst');

                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/indoor_stock_requisition/pharmacy/show_list');
            } else 
			{
                Template::set_message(lang('bf_msg_create_failure') . $this->shelf_model->error, 'error');
                redirect(SITE_AREA . '/indoor_stock_requisition/pharmacy/show_list');
            }
        }
        $data['categories'] = $this->db->order_by('category_name','asc')->get('bf_pharmacy_category')->result();
        $data['pharmacy_name'] = $this->getPharmacyName();
        Template::set('toolbar_title', lang("dept_requisition_title_create"));
        Template::set($data);
        Template::set_view('indoor_stock_requisition/create');

        Template::render();
    }

    private function getPharmacyName()
    {
        $con = [];
        $role_id = $this->current_user->role_id;
        $pharmacy_id = $this->current_user->pharmacy_id;
        if ($role_id == 23) {
            $con['id !='] = $pharmacy_id;
        }
        $result = $this->db
                    ->select('id, name')
                    ->where('status', 1)
                    ->where($con)
                    ->get('pharmacy_setup')
                    ->result_array();
        if ($role_id == 23) {
            $new_array = [
                'id' => 200,
                'name' => 'Main Pharmacy'
                ];
        $result[] = $new_array;
        }            
        
       return $result;
    }
    
    /**
     * Allows editing of company data.
     *
     * @return void
     */
    public function edit() 
	{
         $this->auth->restrict('Pharmacy.Indoor.Req.Edit');
		$data = array();
        $id = $this->uri->segment(5);
        if (empty($id)) 
		{
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/indoor_stock_requisition/pharmacy/show_list');
        }

        if (isset($_POST['save'])) 
		{
            $this->auth->restrict('Pharmacy.Indoor.Req.Edit');

            if ($this->save_details('update', $id)) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pharmacy_product_company');
                Template::set_message(lang('bf_msg_edit_success'), 'success');
                redirect(SITE_AREA . '/indoor_stock_requisition/pharmacy/show_list');
            } else 
			{
                Template::set_message(lang('bf_msg_edit_failure') . $this->shelf_model->error, 'error');
            }
        }
		
        //Template::set($data);
        //Template::set('toolbar_title', lang('pharmacy_shelf_update'));
        //Template::set_view('indoor_stock_requisition/show_list');
        //Template::render();
    }

     private function save($type ='insert',$id = 0)
	{
      if (!$_POST['product_id'] || !$_POST['requisition_quantity']) {

        Template::set_message(lang('bf_msg_create_failure'), 'error');
        redirect(SITE_AREA . '/indoor_stock_requisition/pharmacy/create');
      }
       $role_id = $this->current_user->role_id;
        
        if ($type == 'update')
		{
            $_POST['id'] = $id;
        }
        	
        $data = array();

        $data['requisition_date']       = date('Y-m-d');
        $data['pharmacy_id']            = ($role_id == 23) ? $this->current_user->pharmacy_id : 200;
        $data['issue_pharmacy_id'] = $this->input->post('issue_pharmacy_id', true);
        $data['requisition_no']         = date('ydm').time();
        $data['requisition_by']         = $this->current_user->id;

        if ($type == 'insert') 
		{
            $this->auth->restrict('Pharmacy.Indoor.Req.Add');
             
        	$this->db->trans_start();
            $id = $this->indoor_stock_requisition_model->insert($data);


            $dtls=array();
            $dtls['master_id']=$id;

            for($i=0;$i<count($_POST['product_id']);$i++){

                $dtls['product_id'] = $_POST['product_id'][$i];
                $dtls['req_qnty']   = $_POST['requisition_quantity'][$i];

                $did=$this->indoor_stock_requisition_dtls_model->insert($dtls);

            }
            $this->db->trans_complete();
            
            if (is_numeric($id)) 
			{
                $return = $did;
            } else 
			{
                $return = FALSE;
            }
        } elseif ($type == 'update') 
		{
            $this->auth->restrict('Pharmacy.Indoor.Req.Edit');
            $data['modified_by'] = $this->current_user->id;
            $data['modified_date'] = date('Y-m-d H:i:s');
            $return = $this->shelf_model->update($id, $data);
        }
        return $return;
    }


    public function getSubCategoryByCategoryId($category_id=0){

        $records=$this->db->where('category_id',$category_id)->get('bf_pharmacy_subcategory')->result();
       

            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->subcategory_name.'</option>';
        }
        echo $options;
    }

    public function getProductByCategoryId($category_id=0){

        $records=$this->db->where('category_id',$category_id)->order_by('product_name','asc')->get('bf_pharmacy_products')->result();

            $options='<option value="">Select Product</option>';
            foreach ($records as $record) {
                $options.='<option value="'.$record->id.'">'.$record->product_name.'</option>';
            }
            echo $options;
    }

    public function getProductBySubCategoryId($sub_cat_id=0){
        $records=$this->db->where('sub_category_id',$sub_cat_id)->get('bf_pharmacy_products')->result();
       

            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->product_name.'</option>';
        }
        echo $options;
    }
  

}
