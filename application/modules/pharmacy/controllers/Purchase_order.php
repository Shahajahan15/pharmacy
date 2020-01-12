<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_order extends Admin_Controller 
{

    public function __construct() 
	{
        parent::__construct();
        $this->load->model('purchase_order_model', NULL, TRUE);       
        $this->load->model('supplier_model', NULL, TRUE);
		$this->load->model('hrm/employee_model', NULL, TRUE);
        $this->load->model('purchase_Order_Model', NULL, TRUE);
        $this->load->model('purchase_Order_received_Model', NULL, TRUE);
		$this->load->model('stock_in_model', NULL, TRUE);
        $this->lang->load('common');
		
        Assets::add_module_js('pharmacy', 'purchase_order.js');
        Template::set_block('sub_nav', 'purchase_order/_sub_nav_purchase');
		
    }

   
    public function show_list() 
	{
        $this->auth->restrict('pharmacy.Product_purchase.View');
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Order No';
        $search_box['pharmacy_product_list_flag']=1;
        $search_box['pharmacy_supplier_list_flag']=0;
        $search_box['store_name_list_flag']=0;

        $condition['bf_pharmacy_purchase_requision_mst.status>=']=0;
            if(count($_POST)>0){
               if($_POST['pharmacy_category_name']){
                $condition['bf_pharmacy_products.category_id like']='%'.$this->input->post('pharmacy_category_name').'%';
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
              
            
            }
		$records = $this->db->select("
              SQL_CALC_FOUND_ROWS
                                bf_pharmacy_purchase_requision_mst.requisition_no,
                                bf_pharmacy_purchase_requision_mst.requisition_date,
                                bf_pharmacy_purchase_requision_mst.id as mster_id,
                                bf_pharmacy_purchase_requision_dtls.id,
                                bf_pharmacy_purchase_requision_dtls.status,
                                bf_pharmacy_purchase_requision_dtls.product_id,
                                bf_pharmacy_purchase_requision_dtls.req_qnty,
                                bf_pharmacy_products.purchase_price,
                                bf_pharmacy_purchase_requision_dtls.approve_qnty,
                                bf_pharmacy_purchase_requision_dtls.issue_qnty,
                                bf_pharmacy_purchase_requision_dtls.requisition_approve_date,
                                bf_pharmacy_products.product_name,
                                bf_pharmacy_products.id as product_code,
                                bf_pharmacy_category.category_name
                                ",false)
                    ->join('bf_pharmacy_purchase_requision_dtls','bf_pharmacy_purchase_requision_dtls.pur_requision_id=bf_pharmacy_purchase_requision_mst.id and bf_pharmacy_purchase_requision_dtls.status = 2')
                    ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_requision_dtls.product_id')
                    ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id')
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->get('bf_pharmacy_purchase_requision_mst')->result();
       
       //echo '<pre>';print_r($data['records']);die();
		$data['records']=$records;  
		$total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/purchase_order/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('purchase_order/purchase_aprove_list', compact('records','sl'), true);
            exit;
        } 
	
		Template::set($data);
        Template::set('toolbar_title','Purchase Approve List');
        $list_view='purchase_order/purchase_aprove_list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }
	

	public function create() 
	{
        $this->auth->restrict('pharmacy.Product_purchase.Add');
		if(isset($_POST['save'])){
			//echo '<pre>';print_r($_POST);die();
			if($order_id=$this->save()){
				 Template::set_message('Successfully Ordered', 'success');
				 $print_page=$this->print_order($order_id);
				 $data['print_page']=$print_page;
                 redirect(SITE_AREA . '/purchase_order/pharmacy/show_list');
			}else{
				Template::set_message('Order Failed ! Try again', 'error');
                redirect(SITE_AREA . '/purchase_order/pharmacy/show_list');
			}
		}
        
        $data['records'] = $this->db->select("
                            bf_pharmacy_purchase_requision_mst.requisition_no,
                            bf_pharmacy_purchase_requision_mst.requisition_date,
                            bf_pharmacy_purchase_requision_mst.id as mster_id,
                            bf_pharmacy_purchase_requision_dtls.id,
                            bf_pharmacy_purchase_requision_dtls.status,
                            bf_pharmacy_purchase_requision_dtls.product_id,
                            bf_pharmacy_purchase_requision_dtls.req_qnty,
                            bf_pharmacy_products.purchase_price,
                            bf_pharmacy_purchase_requision_dtls.approve_qnty,
                            bf_pharmacy_purchase_requision_dtls.issue_qnty,
                            bf_pharmacy_purchase_requision_dtls.purchase_order_qnty,
                            bf_pharmacy_purchase_requision_dtls.requisition_approve_date,
                            bf_pharmacy_products.product_name,
                            bf_pharmacy_products.id as product_code,
                            bf_pharmacy_category.category_name
                        ")
                    ->join('bf_pharmacy_purchase_requision_dtls','bf_pharmacy_purchase_requision_dtls.pur_requision_id=bf_pharmacy_purchase_requision_mst.id and bf_pharmacy_purchase_requision_dtls.status = 2')
                    ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_requision_dtls.product_id')
                    ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id')
                    ->get('bf_pharmacy_purchase_requision_mst')->result();

            $data['supliers']=$this->db
            					->where('is_deleted',0)
            					->get('bf_pharmacy_supplier')
            					->result();
       // echo "<pre>";
       // print_r($data['records']);
       // exit();
		Template::set($data);
        Template::set('toolbar_title','Order Placed');
        Template::set_view('purchase_order/purchase_create');
        Template::render();
    }



    public function getProductInfoByOrderId($id=0){

    	$record=$this->db->select("
    								bf_pharmacy_purchase_requision_dtls.*,
    								bf_pharmacy_products.product_name,
    								bf_pharmacy_products.purchase_price,
                                    bf_pharmacy_category.category_name
    								")
    					 ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_requision_dtls.product_id','left')
                         ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id')
    					 ->where('bf_pharmacy_purchase_requision_dtls.id',$id)
    					 ->get('bf_pharmacy_purchase_requision_dtls')
    					 ->row();

    	echo json_encode($record);

    }


    public function save($type='insert',$id=0){
        //echo '<pre>';print_r($_POST);die();

        // echo "<pre>";
        // print_r($_POST);
        // exit();

        if(isset($_POST['product_id']) && count($_POST['product_id'])>0){}else{
            return false;
            exit;
        }

    	$data['supplier_id']=$this->input->post('supplier_id');
        $data['supply_date_from']=date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('supply_from'))));
        $data['supply_date_to']=date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('supply_to'))));

    	$data['purchase_order_no']=date('y').$this->input->post('supplier_id').time();
    	$data['created_by']=$this->current_user->id;

    	if($type=='insert'){
            $this->auth->restrict('pharmacy.Product_purchase.Add');
    		$this->db->trans_start();

    		$dtls['purchase_order_mst_id']=$this->purchase_Order_Model->insert($data);
    		for($i=0;$i<count($_POST['product_id']);$i++){
    			$dtls['product_id']			= $_POST['product_id'][$i];
    			$dtls['order_qnty']			= $_POST['order_qnty'][$i];
    			$dtls['order_unit_price']	= $_POST['order_unit_price'][$i];
    			$dtls['total_order_price']	= $_POST['order_total_price'][$i];
    			$this->db->insert('bf_pharmacy_purchase_order_dtls',$dtls);

    			$requisitionData=$this->db->where('id',$_POST['product_requisition_id'][$i])->get('bf_pharmacy_purchase_requision_dtls')->row();

    			$requisition['purchase_order_qnty']=$_POST['order_qnty'][$i]+$requisitionData->purchase_order_qnty;

    			if($requisition['purchase_order_qnty']==$requisitionData->approve_qnty){
    				$requisition['status']=3;
    			}elseif ($requisition['purchase_order_qnty']<$requisitionData->approve_qnty) {
    				$requisition['status']=2;
    			}else{
    				$requisition['status']=4;
    			}

    			$this->db->where('id',$_POST['product_requisition_id'][$i])->update('bf_pharmacy_purchase_requision_dtls',$requisition);
    		}

    		$this->db->trans_complete();
    	}


    	return $dtls['purchase_order_mst_id'];



    	
    }

    public function print_order($order_id){
        $hospital=$this->db->Select('lib_hospital.*')->get('lib_hospital')->row();

    	$records=$this->db->select("
    					bf_pharmacy_purchase_order_dtls.*,
    					bf_pharmacy_purchase_order_mst.supplier_id,
    					bf_pharmacy_purchase_order_mst.supply_date_from,
    					bf_pharmacy_purchase_order_mst.supply_date_to,
    					bf_pharmacy_purchase_order_mst.purchase_order_no,
    					bf_pharmacy_supplier.supplier_name,
    					bf_pharmacy_supplier.contact_no1,
    					bf_pharmacy_products.product_name,
                        bf_users.username
    					")
    				->join('bf_pharmacy_purchase_order_dtls','bf_pharmacy_purchase_order_dtls.purchase_order_mst_id=bf_pharmacy_purchase_order_mst.id')
    				->join('bf_pharmacy_supplier','bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id','left')
    				->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_order_dtls.product_id','left')
                    ->join('bf_users','bf_users.id=bf_pharmacy_purchase_order_mst.created_by')
    				->where('bf_pharmacy_purchase_order_mst.id',$order_id)
    				->get('bf_pharmacy_purchase_order_mst')
    				->result_array();

    		//echo '<pre>';print_r($records);

    		$data['records']=$records;

    		//$crr_user=$this->current_user->display_name;

            if ($this->input->is_ajax_request()) {
                echo $this->load->view('purchase_order/order_print', compact('records','hospital'), true);
             }

    		
    		return $this->load->view('purchase_order/order_print', compact('records','hospital'), true);



    }

    public function ordered_list(){

        $this->auth->restrict('pharmacy.Product_purchase.View');

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;


        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Order No';
        $search_box['supplier_name_flag']=1;
        $search_box['pharmacy_supplier_list_flag']=0;
        $search_box['store_name_list_flag']=0;
         $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;

        $condition['bf_pharmacy_purchase_order_mst.status>=']=0;
            if(count($_POST)>0){
               if($_POST['common_text_search']){
                $condition['bf_pharmacy_purchase_order_mst.purchase_order_no  like']='%'.trim($this->input->post('common_text_search')).'%';
            }
           
              if($_POST['supplier_name']){
                $condition['bf_pharmacy_supplier.SUPPLIER_NAME  like']='%'.$this->input->post('supplier_name').'%';
            }
           
               
                  if($this->input->post('from_date')){
                $from_date = custom_date_format(trim($this->input->post('from_date')));
                $condition['bf_pharmacy_purchase_order_mst.created_date >='] = $from_date." 00:00:00";
               
            }
 
            if($this->input->post('to_date')){
                $to_date = custom_date_format(trim($this->input->post('to_date')));
                $condition['bf_pharmacy_purchase_order_mst.created_date <='] = $to_date." 23:59:59";
            } 
              
            
            }
        $records=$this->db->select("
                        SQL_CALC_FOUND_ROWS
                        bf_pharmacy_purchase_order_mst.*,
                        bf_pharmacy_supplier.supplier_name as SUPPLIER_NAME,
                        bf_pharmacy_supplier.contact_no1 as SUPPLIER_CONTACT_PHONENO
                        ",false)
                    ->join('bf_pharmacy_supplier','bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id','left')
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->order_by('bf_pharmacy_purchase_order_mst.created_date','DESC')
                    ->get('bf_pharmacy_purchase_order_mst')
                    ->result();

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/purchase_order/pharmacy/ordered_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records']=$records;
        //echo '<pre>';print_r($records);die();
        if ($this->input->is_ajax_request()) {
            echo $this->load->view('purchase_order/ordered_reprint_list', compact('records','sl'), true);
            exit;
        } 
        $data['records']=$records;
        //echo '<pre>';print_r($records);die();a
        $list_view='purchase_order/ordered_reprint_list';
        Template::set($data);
        Template::set('list_view',$list_view);
        Template::set('search_box',$search_box);

        Template::set('toolbar_title','Order List');
        Template::set_view('report_template');
        Template::render();
    }
}
