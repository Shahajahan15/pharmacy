<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_return extends Admin_Controller 
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
		$this->load->model('product_model', NULL, TRUE);
		$this->load->model('stock_in_model', NULL, TRUE);
        $this->lang->load('common');
        
        Assets::add_module_js('pharmacy', 'product_return.js');
        Template::set_block('sub_nav', 'return_received/_sub_nav');		
    }

   
    

    

    public function receive_list($print_order_id=0){

        $this->auth->restrict('pharmacy.purchase.return.View');
        if($print_order_id){
            
        }


        if(isset($_POST['save'])){
            $this->auth->restrict('pharmacy.purchase.return.Add');
        	if($insert_id=$this->save()){
        		//request for return is done here
        		Template::set_message('Request for return Successfully done', 'success');
                redirect(SITE_AREA .'/product_return/pharmacy/receive_list');

        	}else{
        		Template::set_message('Request for return failed', 'error');
                redirect(SITE_AREA .'/product_return/pharmacy/receive_list');
        	}
        }

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Order No';

        $search_box['supplier_name_flag']=1;
        $search_box['contact_no_flag']=1;
        $search_box['pharmacy_supplier_list_flag']=1;
        $search_box['pharmacy_received_no_flag']=1;
        $condition['bf_pharmacy_purchase_order_received_mst.status>=']=0;
            if(count($_POST)>0){
               
                if($this->input->post('from_date')){

                    $condition['bf_pharmacy_purchase_order_received_mst.received_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
                }
 
                if($this->input->post('to_date')){

                    $condition['bf_pharmacy_purchase_order_received_mst.received_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
                }
                if($this->input->post('common_text_search')){
                    $condition['bf_pharmacy_purchase_order_mst.purchase_order_no like']='%'.$this->input->post('common_text_search').'%';
                }
          
                if($this->input->post('supplier_name')){
                    $condition['bf_pharmacy_supplier.supplier_name like']='%'.trim($this->input->post('supplier_name')).'%';
                }  
                 if($this->input->post('contact_no')){
                    $condition['bf_pharmacy_supplier.contact_no1 like']='%'.trim($this->input->post('contact_no')).'%';
                }   
            }


        $records=$this->db->select("
                        SQL_CALC_FOUND_ROWS 
                        bf_pharmacy_purchase_order_received_mst.*,
                        bf_pharmacy_purchase_order_mst.purchase_order_no,
                        bf_pharmacy_purchase_order_mst.supply_date_from,
                        bf_pharmacy_purchase_order_mst.supply_date_to,
                        bf_pharmacy_supplier.supplier_name,
                        bf_pharmacy_supplier.contact_no1
                        ",false)
        			->join('bf_pharmacy_purchase_order_mst','bf_pharmacy_purchase_order_mst.id=bf_pharmacy_purchase_order_received_mst.order_id','left')
                    ->join('bf_pharmacy_supplier','bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id','left')
                    ->where($condition)
                    ->order_by('bf_pharmacy_purchase_order_received_mst.received_date','DESC')
                    ->limit($limit, $offset)
                    ->get('bf_pharmacy_purchase_order_received_mst')
                    ->result();

        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/product_return/pharmacy/receive_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('return_received/receive_list', compact('records','sl'), true);
            exit;
        } 
       // echo '<pre>'; print_r($records);die();

        Template::set($data);
        Template::set('toolbar_title','Purchase Order List');
        $list_view='return_received/receive_list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }


   
   


    public function receiving_report($order_received_id){

        $records=$this->db->select("
                            bf_pharmacy_purchase_order_received_dtls.*,
                            bf_pharmacy_purchase_order_received_mst.id as receive_master_id,
                            bf_pharmacy_purchase_order_received_mst.bill_no,
                            bf_pharmacy_purchase_order_received_mst.received_date,
                            bf_pharmacy_purchase_order_received_mst.received_by,
                            bf_pharmacy_purchase_order_received_mst.order_id,
                            bf_pharmacy_products.product_name,
                            bf_pharmacy_purchase_order_mst.supplier_id,
                            bf_pharmacy_supplier.supplier_code,
                            bf_pharmacy_supplier.supplier_name,
                            bf_pharmacy_supplier.contact_no1
                            ")
                    ->join('bf_pharmacy_purchase_order_received_dtls','bf_pharmacy_purchase_order_received_dtls.master_id=bf_pharmacy_purchase_order_received_mst.id')
                    ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_order_received_dtls.product_id','left')
                    ->join('bf_pharmacy_purchase_order_mst','bf_pharmacy_purchase_order_mst.id=bf_pharmacy_purchase_order_received_mst.order_id','left')

                    ->join('bf_pharmacy_supplier','bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id','left')

                    ->where('bf_pharmacy_purchase_order_received_mst.id',$order_received_id)
                    ->get('bf_pharmacy_purchase_order_received_mst')
                    ->result();

       // echo '<pre>'; print_r($records); die();
                    $reason=$this->product_model->prodcut_return_reasons();

        $data['records']=$records;
        $data['reason']=$reason;

        json_encode($this->load->view('return_received/receive_details',$data));

    }

    public function save($type='insert',$id=0){
         $this->auth->restrict('pharmacy.purchase.return.Add');
    	$this->db->trans_start();

    	for($i=0;$i<count($_POST['return_qnty_requested']);$i++){

    		$data['received_mst_id']=$_POST['receive_master_id'];
    		$data['order_id']=$_POST['order_id'];
    		$data['supplier_id']=$_POST['supplier_id'];

    		if($_POST['return_qnty_requested'][$i]>0){    			
    			
    			$data['receive_dtls_id']=$_POST['recv_dtls_id'][$i];    			
    			$data['product_id']=$_POST['product_id'][$i];
    			$data['return_requst_qnty']=$_POST['return_qnty_requested'][$i];
    			$data['is_confirm']=0;//Need to return 

    			$this->db->insert('bf_pharmacy_return_products',$data);

    			/*
    				@ Update Receive product dlts by return quntity and status 
    				@ It will be change again after return product will received
    			*/

    			$update_dtls=$this->db->where('id',$_POST['recv_dtls_id'][$i])->get('bf_pharmacy_purchase_order_received_dtls')->row();
    			$recv_dtls['return_qnty_requested']=$update_dtls->return_qnty_requested+$_POST['return_qnty_requested'][$i];
    			$recv_dtls['status']=1; //has product return    			    			
    			$this->db->where('id',$update_dtls->id)->update('bf_pharmacy_purchase_order_received_dtls',$recv_dtls);
    			/*
    				@return 
    			*/
    		}
    		
    	}
    	$this->db->trans_complete();
    	//echo '<pre>'; print_r($_POST);die();
    	return true;

    }






    public function return_request_list(){

        $this->auth->restrict('pharmacy.PReturn.confirm.View');
    	if(isset($_POST['save'])){
             $this->auth->restrict('pharmacy.PReturn.confirm.Add');
    		if($approved=$this->return_approved_save()){
    			//approved
    			Template::set_message('Return approved Successfully', 'success');
                redirect(SITE_AREA .'/product_return/pharmacy/return_request_list');

        	}else{
        		Template::set_message('Return approved failed', 'error');
                redirect(SITE_AREA .'/product_return/pharmacy/return_request_list');
        	}
    	}
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = $this->input->post('per_page') ?: 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'supplier Code';
        $search_box['supplier_name_flag']=1;
        $search_box['product_name_flag']=1;
        $search_box['pharmacy_supplier_list_flag']=1;
        $search_box['pharmacy_received_no_flag']=1;
        $condition['bf_pharmacy_return_products.confirm_by>=']=0;
            if(count($_POST)>0){
               
                if($this->input->post('from_date')){

                    $condition['bf_pharmacy_purchase_order_received_mst.received_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
                }
 
                if($this->input->post('to_date')){

                    $condition['bf_pharmacy_purchase_order_received_mst.received_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
                }
                if($this->input->post('common_text_search')){
                    $condition['pharmacy_supplier.supplier_code like']='%'.$this->input->post('common_text_search').'%';
                }
                if($this->input->post('supplier_name')){
                    $condition['bf_pharmacy_supplier.supplier_name like']='%'.trim($this->input->post('supplier_name')).'%';
                } 
                if($this->input->post('product_name')){
                    $condition['bf_pharmacy_products.product_name like']='%'.trim($this->input->post('product_name')).'%';
                }
                  
            }
    	$records=$this->db->select("
            SQL_CALC_FOUND_ROWS 
    								bf_pharmacy_return_products.*,
    								bf_pharmacy_products.product_name,
    								bf_pharmacy_supplier.supplier_name,
                            		bf_pharmacy_supplier.contact_no1,
                            		bf_pharmacy_supplier.supplier_code,
                            		bf_pharmacy_purchase_order_received_mst.received_date,
                            		bf_pharmacy_purchase_order_received_dtls.received_qnty
    							",false)
    					->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_return_products.product_id','left')
    					->join('bf_pharmacy_supplier','bf_pharmacy_return_products.supplier_id=bf_pharmacy_supplier.id','left')
    					->join('bf_pharmacy_purchase_order_received_mst','bf_pharmacy_return_products.received_mst_id=bf_pharmacy_purchase_order_received_mst.id','left')
    					->join('bf_pharmacy_purchase_order_received_dtls','bf_pharmacy_return_products.receive_dtls_id=bf_pharmacy_purchase_order_received_dtls.id','left')
    					->where('is_confirm',0)
                        ->where($condition)
                        ->limit($limit, $offset)
                        ->order_by('bf_pharmacy_return_products.id','DESC')
    					->get('bf_pharmacy_return_products')
    					->result();

    	//echo '<pre>';print_r($records);die();
    	$data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/product_return/pharmacy/return_request_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('return_received/return_request_list', compact('records','sl'), true);
            exit;
        } 
    	Template::set($data);
        Template::set('toolbar_title','Return Reqest List');
        $list_view='return_received/return_request_list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }




    /*
    	@ return requist approved form shown from the return_requst_list method
    */


    public function return_request_approved($return_id=0){
    	$record=$this->db->select("
    								bf_pharmacy_return_products.*,
    								bf_pharmacy_products.product_name,
    								bf_pharmacy_supplier.supplier_name,
                            		bf_pharmacy_supplier.contact_no1,
                            		bf_pharmacy_supplier.supplier_code,
                            		bf_pharmacy_purchase_order_received_mst.received_date,
                            		bf_pharmacy_purchase_order_received_dtls.received_qnty
    							")
    					->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_return_products.product_id','left')
    					->join('bf_pharmacy_supplier','bf_pharmacy_return_products.supplier_id=bf_pharmacy_supplier.id','left')
    					->join('bf_pharmacy_purchase_order_received_mst','bf_pharmacy_return_products.received_mst_id=bf_pharmacy_purchase_order_received_mst.id','left')
    					->join('bf_pharmacy_purchase_order_received_dtls','bf_pharmacy_return_products.receive_dtls_id=bf_pharmacy_purchase_order_received_dtls.id','left')
    					->where('bf_pharmacy_return_products.is_confirm',0)
    					->where('bf_pharmacy_return_products.id',$return_id)
    					->get('bf_pharmacy_return_products')
    					->row();
    	$data['record']=$record;
    	json_encode($this->load->view('return_received/approved_form',$data));
    }
        /*
        	@ return_approved_save
        	@ purpose: save when retuquest is approved
    	*/

    public function return_approved_save(){

    	//echo '<pre>'; print_r($_POST);die();
    	$this->db->trans_start();

    	$data['return_approved_qnty']=$this->input->post('approved_qnty');
    	$data['is_confirm']=1; //approved
    	$data['confirm_date']=date('Y-m-d'); 
    	$data['confirm_by']=$this->current_user->id;
    	if($data['return_approved_qnty']==0){
    		$data['is_received']=3;//cancel
    	}

    	$this->db->where('id',$_POST['return_id'])->update('bf_pharmacy_return_products',$data);

    	$approved_row=$this->db->where('id',$_POST['return_id'])->get('bf_pharmacy_return_products')->row();

    	$approved_total_qntity=$this->db->select("SUM(return_approved_qnty) as total_approved")
    				->where('receive_dtls_id',$_POST['receive_dtls_id'])
    				->where('is_received',0)
    				->where('is_confirm',1)
    				->get('bf_pharmacy_return_products')
    				->row(); //total approved qnty

    	$approved_pending_total_qntity=$this->db->select("SUM(return_requst_qnty) as total_pending")
    				->where('receive_dtls_id',$_POST['receive_dtls_id'])
    				->where('is_received',0)
    				->where('is_confirm',0)
    				->get('bf_pharmacy_return_products')
    				->row(); //current pending approve qnty

    	

    	$receive_up['return_qnty_requested']=$approved_total_qntity->total_approved+$approved_pending_total_qntity->total_pending;
    	$receive_up['return_qnty_approved']=$approved_total_qntity->total_approved;
    	$receive_up['status']=2; //return request approved to supplier

    	$this->db->where('id',$_POST['receive_dtls_id'])->update('bf_pharmacy_purchase_order_received_dtls',$receive_up);

    	

    	//stock udpate @ decrease approve qnty product @stock out
    	$product_stock=$this->db->where('product_id',$approved_row->product_id)->get('bf_pharmacy_stock')->row();
    	$stock_update['quantity_level']=$product_stock->quantity_level-$_POST['approved_qnty'];
    	$this->db->where('id',$product_stock->id)->update('bf_pharmacy_stock',$stock_update);
    	//insert stock out history in stock history table

    	// stock mst
    	$stock_history['stock_source_out_id']=$approved_row->id;
    	$stock_history['source'] = 4;
    	$stock_history['created_by']=$this->current_user->id;
    	$stock_history['type']=2;
    	$stock_mst_id=$this->stock_in_model->insert($stock_history);
    	// stock dtsl
    	$stock_dtls['stock_mst_id']=$stock_mst_id;
    	$stock_dtls['product_id']=$approved_row->product_id;
    	$stock_dtls['quantity']=$_POST['approved_qnty'];
    	$this->db->insert('bf_pharmacy_stock_dtls',$stock_dtls);

    	$this->db->trans_complete();
    	
    	return true;
    }


    



	
}
