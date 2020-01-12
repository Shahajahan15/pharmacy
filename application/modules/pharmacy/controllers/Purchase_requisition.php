<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * purchase controller
 */
class Purchase_requisition extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct() 
	{
        parent::__construct();          
        $this->load->model('purchase_requisition_model', NULL, true);       
        $this->load->model('purchase_requisition_dtls_model', NULL, true);       
      //  $this->lang->load('common');
        $this->lang->load('purchase_requisition');
        Assets::add_module_js('pharmacy','purchase_requisition');
        //$this->lang->load('shelf');
        Template::set_block('sub_nav', 'purchase_requisition/_sub_nav');
    }

    /* Write from here */

    public function show_list()
	{
        $this->auth->restrict('Pharmacy.P.Requisition.View');
        Template::set('toolbar_title', lang("pur_requisition_title_view"));

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.P.Requisition.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->purchase_requisition_model->delete($pid);
            }

            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('shelf_delete_success'), 'success');
            } else {
                Template::set_message(lang('shelf_delete_failure') . $this->purchase_requisition_model->error, 'error');
            }
        }

        echo '<pre>'; print_r($this->session->set_userdata('role_id'));exit;
        

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Requisition No';
        $search_box['requisition_no_flag'] = 1;
        $search_box['sex_list_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=1;

        $condition['bf_pharmacy_purchase_requision_mst.status >=']=0;

        if(count($_POST)>0){

            if($_POST['pharmacy_category_name']){
                $condition['bf_pharmacy_products.category_id like']='%'.$this->input->post('pharmacy_category_name').'%';
            }
            if($this->input->post('pharmacy_sub_category_id')){
                $condition['bf_pharmacy_products.sub_category_id']=$this->input->post('pharmacy_sub_category_id');
            }
            if($this->input->post('pharmacy_product_id')){
                $condition['bf_pharmacy_products.id like']='%'.trim($this->input->post('pharmacy_product_id')).'%';
            }
            if($this->input->post('from_date')){

                $condition['bf_pharmacy_purchase_requision_mst.requisition_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['bf_pharmacy_purchase_requision_mst.requisition_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }    
              if(trim($this->input->post('requisition_no'))){
                $condition['bf_pharmacy_purchase_requision_mst.requisition_no like']='%'.trim($this->input->post('requisition_no')).'%';
            }       

        }

        $records = $this->db->select("
                                SQL_CALC_FOUND_ROWS 
                                bf_pharmacy_purchase_requision_mst.requisition_no,
                                bf_pharmacy_purchase_requision_mst.requisition_date,
                                bf_pharmacy_purchase_requision_mst.id,
                                bf_pharmacy_purchase_requision_dtls.status,
                                bf_pharmacy_purchase_requision_dtls.product_id,
                                bf_pharmacy_purchase_requision_dtls.req_qnty,
                                bf_pharmacy_purchase_requision_dtls.approve_qnty,
                                bf_pharmacy_purchase_requision_dtls.issue_qnty,
                                bf_pharmacy_products.product_name,
                                bf_pharmacy_category.category_name
                                ",false)
                    ->join('bf_pharmacy_purchase_requision_dtls','bf_pharmacy_purchase_requision_dtls.pur_requision_id=bf_pharmacy_purchase_requision_mst.id')
                    ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_requision_dtls.product_id')
                    ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id')
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->order_by('bf_pharmacy_purchase_requision_mst.requisition_date','desc')
                    ->get('bf_pharmacy_purchase_requision_mst')->result();

        $data['records']=$records;    
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/purchase_requisition/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('purchase_requisition/list', compact('records','sl'), true);
            exit;
        } 
        Template::set($data);
        $list_view='purchase_requisition/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }
    
    /**
     * Account purchase requistion & list
     */
    public function create() {

        $this->auth->restrict('Pharmacy.P.Requisition.Create');
        //TODO you code here
		$data = array();
         if (isset($_POST['save'])) 
		{
           //echo '<pre>'; print_r($_POST);die();
			$this->db->trans_begin();
			$insert_id = $this->save();
           // echo $insert_id;
			if ($this->db->trans_status() === FALSE) {
            	$this->db->trans_rollback();

                  
            	 Template::set_message(lang('bf_msg_create_failure') . $this->shelf_model->error, 'error');
        	} else {
        		$this->db->trans_commit();
        		log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'bf_pharmacy_department_requision_mst');
  

                Template::set_message(lang('bf_msg_create_success'), 'success');
                $print_page=$this->print_purchase($insert_id);
                $data['print_page']=$print_page;
                redirect(SITE_AREA . '/purchase_requisition/pharmacy/show_list');
        	}
        }
        $data['categories'] = $this->db->order_by('category_name','asc')->get('bf_pharmacy_category')->result();
        $data['stock_less_product'] = $this->GetStockLessProduct();
        //$data['print_page']=$print_page;
        $list_view='purchase_requisition/create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view,$data,true);
            exit;
        }
        Template::set('toolbar_title', lang("pur_requisition_title_create"));
        Template::set($data);
                Template::set('list_view',$list_view);

        Template::set_view('report_template');

        Template::render();
    }
    
   public function GetStockLessProduct()
    {
		$result = $this->db
		->select('p.id,p.product_name,sc.id as sub_category_id,sc.subcategory_name,c.id as category_id,c.category_name,IFNULL(s.quantity_level,0) as stock')
			->from('bf_pharmacy_products as p')
			->join('bf_pharmacy_stock as s','p.id = s.product_id and p.reorder_lebel >= s.quantity_level','left')
			->join('bf_pharmacy_subcategory as sc','p.sub_category_id = sc.id')
			->join('bf_pharmacy_category as c','sc.category_id = c.id')
			->get()
			->result();
		return $result;
	}

     private function save($type ='insert',$id = 0)
	{
        
        if ($type == 'update')
		{
            $_POST['id'] = $id;
        }
        	
        $data = array();
        $data['pharmacy_id']           = 1;
        $data['requisition_date']   = date('Y-m-d');
        $data['requisition_by']     =  $this->current_user->id;
        $data['requisition_no']     =  $this->get_requisition_no();

        if ($type == 'insert') 
		{
            $this->auth->restrict('Pharmacy.P.Requisition.Create');
             
        	
            $id = $this->purchase_requisition_model->insert($data);


            $dtls=array();
            $dtls['pur_requision_id']=$id;

            for($i=0;$i<count($_POST['product_id']);$i++){

                $dtls['product_id'] = $_POST['product_id'][$i];
                $dtls['req_qnty']   = $_POST['requisition_quantity'][$i];

                $did=$this->purchase_requisition_dtls_model->insert($dtls);

            }
            if (is_numeric($id)) 
			{
                $return = $did;
            } else 
			{
                $return = FALSE;
            }
        } elseif ($type == 'update') 
		{
            $this->auth->restrict('Pharmacy.P.Requisition.Edit');
            $data['modified_by'] = $this->current_user->id;
            $data['modified_date'] = date('Y-m-d H:i:s');
            $return = $this->shelf_model->update($id, $data);
        }
        return $return;
    }
    public function print_purchase($pur_requision_id){
       // echo $pur_requision_id;
        $data=array();
        $data['hospital']=$this->db->Select('lib_hospital.*')->get('lib_hospital')->row();
        $data['crr_user']=$this->current_user->username;
        $data['records']=$this->db
              ->select('pprm.requisition_no,pprm.requisition_date,pp.product_name,pprd.req_qnty')
              ->from('bf_pharmacy_purchase_requision_dtls as pprd')
              ->join('bf_pharmacy_purchase_requision_mst as pprm','pprd.pur_requision_id=pprm.id')
              ->join('bf_pharmacy_products as pp','pp.id=pprd.product_id')
              ->where('pprd.id',$pur_requision_id)
              ->get()
              ->result_array();
       //echo '<pre>';print_r($data['records']);exit();
        echo $this->load->view('purchase_requisition/print',$data,true);

    }
    public function get_requisition_no()
    {
    	$type_text = "PP-";
		$row = $this->db
		    ->select('requisition_no','id')
			->where('requisition_date',date('Y-m-d'))
			->order_by('id','desc')
			->get('bf_pharmacy_purchase_requision_mst')
			->row();
			if(!$row){
				$p_requistion_no = $type_text.date('ymd')."1";
			} else {
				$incr_id = substr($row->requisition_no,9);
				$incr_id++;
				$p_requistion_no = $type_text.date('ymd').$incr_id;
		}
		return $p_requistion_no;
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
    
    public function getProductStock($pur_id = 0)
    {
		$row = $this->db->where('product_id',$pur_id)->get('bf_pharmacy_stock')->row();
		$stock = 0;
		if($row) {
			$stock = $row->quantity_level;
		}
		echo $stock;exit;
	}
}
