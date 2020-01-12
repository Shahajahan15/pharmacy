<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_order_receive extends Admin_Controller 
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
        Assets::add_module_js('pharmacy', 'product_return.js');
        $this->load->library('returnApproved');

        Template::set_block('sub_nav', 'purchase_order_receive/_sub_nav');
		
    }

   
    

    

    public function order_list($print_order_id=0){

        $this->auth->restrict('pharmacy.order.receive.View');
        if($print_order_id){
            $print_view=$this->receiving_report_print($print_order_id);
            $data['print_page']=$print_view;
        }
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit =isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Order No';
        $search_box['contact_no_flag']=1;
        $search_box['supplier_name_flag']=1;
        $search_box['pharmacy_supplier_list_flag']=1;

        $condition['bf_pharmacy_purchase_order_mst.status >=']=0;
            if(count($_POST)>0){
               
                 if($this->input->post('from_date')){

                $condition['bf_pharmacy_purchase_order_mst.supply_date_from >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['bf_pharmacy_purchase_order_mst.supply_date_to <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }    
              if($this->input->post('common_text_search')){
                $condition['bf_pharmacy_purchase_order_mst.purchase_order_no like']='%'.trim($this->input->post('common_text_search')).'%';
            }   
            if($this->input->post('supplier_name')){
                $condition['pharmacy_supplier.supplier_name like']='%'.trim($this->input->post('supplier_name')).'%';
            }    
             if($this->input->post('contact_no')){
                $condition['pharmacy_supplier.contact_no1 like']='%'.trim($this->input->post('contact_no')).'%';
            } 
            }
        

        $records=$this->db->select("
             SQL_CALC_FOUND_ROWS 
                        bf_pharmacy_purchase_order_mst.*,
                        bf_pharmacy_supplier.supplier_name,
                        bf_pharmacy_supplier.contact_no1
                        ",false)
                    ->join('bf_pharmacy_supplier','bf_pharmacy_purchase_order_mst.id=bf_pharmacy_supplier.id','left')
                    ->where('bf_pharmacy_purchase_order_mst.status <',2)
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->get('bf_pharmacy_purchase_order_mst')
                    ->result();

        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/purchase_order_receive/pharmacy/order_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('purchase_order_receive/purchase_order_list', compact('records','sl'), true);
            exit;
        } 
       // echo '<pre>'; print_r($records);die();

        Template::set($data);
        Template::set('toolbar_title','Purchase Order List');
        Template::set('search_box',$search_box);
        $list_view='purchase_order_receive/purchase_order_list';
        Template::set('list_view',$list_view);
        Template::set_view('report_template');
        Template::render();
    }


    public function receive_order($order_id=0){

        $this->auth->restrict('pharmacy.purchase.receive.Add');

        if(isset($_POST['save']) && isset($_POST['product_id'])){
            //echo '<pre>'; print_r($_POST);die();

            
           if($received_id=$this->receive_save($order_id)){
                Template::set_message('Order Receive Successfully ', 'success');
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $received_id . ' : ' . $this->input->ip_address(), 'bf_pharmacy_purchase_order_received_mst');

                    redirect(SITE_AREA . '/purchase_order_receive/pharmacy/order_list/'.$received_id);

           }else{
                Template::set_message(lang('bf_msg_create_failure') . $this->purchase_Order_received_Model->error, 'error');
                redirect(SITE_AREA . '/purchase_order_receive/pharmacy/order_list');
           }

        }

        $record=$this->db->select("
                        bf_pharmacy_purchase_order_mst.*,
                        bf_pharmacy_supplier.supplier_name,
                        bf_pharmacy_supplier.id,
                        bf_pharmacy_supplier.contact_no1
                        ")
                    ->join('bf_pharmacy_supplier','bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id','left')
                    ->where('bf_pharmacy_purchase_order_mst.id',$order_id)
                    ->where('bf_pharmacy_purchase_order_mst.status <',2)
                    ->get('bf_pharmacy_purchase_order_mst')
                    ->row();

            $data['record']=$record;

        $GetReturnApprovedListOBJ = new returnApproved($this);
        $return_pending_products=$GetReturnApprovedListOBJ->setSupplierID($record->id)->GetReturnApprovedList();
        $data['return_pending_products']=$return_pending_products;

       // echo '<pre>';print_r($return_pending_products);die();

        $data['products']=$this->db->select("
                                bf_pharmacy_purchase_order_dtls.*,
                                bf_pharmacy_products.product_name,
                                bf_pharmacy_category.category_name
                                ")
                        ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_order_dtls.product_id','left')
                        ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id')
                        ->where('bf_pharmacy_purchase_order_dtls.purchase_order_mst_id',$order_id)
                        ->where('bf_pharmacy_purchase_order_dtls.status <',2)
                        ->get('bf_pharmacy_purchase_order_dtls')
                        ->result();

                        //echo '<pre>';print_r($data['products']);die();

        

        Template::set($data);
        Template::set('toolbar_title','Recive Products');
        Template::set_view('purchase_order_receive/receive_order_product');
        Template::render();
    }

    public function receive_save($order_id){

        //echo '<pre>'; print_r($_POST);die();
        
        $this->db->trans_start(); //rollback start

        $data['order_id']=$order_id;
        $data['id']=$this->input->post('id');
        $data['supplier_id']=$this->input->post('supplier_id');
        $data['bill_no']=date('ymd').time();
        $data['received_date']=date('Y-m-d');
        $data['received_by']=$this->current_user->id;

        $master_id=$this->purchase_Order_received_Model->insert($data);


        /*
            @ bf_pharmacy_stock_mst insert for history 
        */
            $stock_data['stock_source_in_id']=$master_id;
            $stock_data['type']=1; //1=stock in
            $stock_data['created_by']=$this->current_user->id;

            $stock_mst_id=$this->stock_in_model->insert($stock_data);
        /*
            @ return
        */



        /*
            @ loop
            @ all porducto of an indivisual work order
        */
        $dtls['master_id']=$master_id;
        for($i=0;$i<count($_POST['product_id']);$i++){

            $dtls['product_id']=$_POST['product_id'][$i];
            $dtls['order_dtls_id']=$_POST['order_dtls_id'][$i];
            $dtls['received_qnty']=$_POST['receive_qnty'][$i];
            $dtls['free_qnty']=$_POST['receive_free_qnty'][$i];
            $dtls['unit_price']=$_POST['receive_unit_price'][$i];
            $dtls['total_price']=$_POST['receive_unit_price'][$i]*$_POST['receive_qnty'][$i];
            $dtls['total_qnty_received']=$_POST['receive_free_qnty'][$i]+$_POST['receive_qnty'][$i];

            $this->db->insert('bf_pharmacy_purchase_order_received_dtls',$dtls);

            if(!$this->main_stock_dtls($stock_mst_id,$_POST['product_id'][$i],$dtls['total_qnty_received'])){
                return false;
            }

            $status=$this->order_table_update($_POST['order_dtls_id'][$i],$_POST['receive_qnty'][$i],$_POST['receive_free_qnty'][$i]);
            if($status==1){
                $some_received=1;
            }else{
                $full_received=2;
            }


        }
        /*
            @return
        */
            if(isset($some_received)){
                $order_mst['status']=1;
                $this->db->where('id',$order_id)->update('bf_pharmacy_purchase_order_mst',$order_mst);
            }else{
                $order_mst['status']=2;
                $this->db->where('id',$order_id)->update('bf_pharmacy_purchase_order_mst',$order_mst);
            }

        /*
            @ if return pending product is exits
        */
            if(isset($_POST['return_replace_qnty'])){
                for($j=0;$j<count($_POST);$j++){
                    if(isset($_POST['return_replace_qnty'][$j]) && $_POST['return_replace_qnty'][$j]>0){
                        $return_replace['return_products_id']=$_POST['product_return_id'][$j];
                        $return_replace['replace_qntity']=$_POST['return_replace_qnty'][$j];

                        $ReturnApprovedOBJ = new returnApproved($this);
                        if(!$ReturnApprovedOBJ->replace_add($return_replace,$this->current_user->id)){
                            return false;
                        }

                    }
                }
            }

        /*
            @ return
        */

            $this->db->trans_complete(); //Rollback Complete here

            return $master_id;

    }


    public function receiving_report_print($order_received_id){
        $hospital=$this->db->Select('lib_hospital.*')->get('lib_hospital')->row();

        $records=$this->db->select("
                            bf_pharmacy_purchase_order_received_dtls.*,
                            bf_pharmacy_purchase_order_received_mst.bill_no,
                            bf_pharmacy_purchase_order_received_mst.received_date,
                            bf_pharmacy_purchase_order_received_mst.received_by,
                            bf_pharmacy_purchase_order_received_mst.order_id,
                            bf_pharmacy_products.product_name,
                            bf_pharmacy_purchase_order_mst.id,
                            bf_pharmacy_supplier.id,
                            bf_pharmacy_supplier.supplier_name,
                            bf_pharmacy_supplier.contact_no1,
                            bf_users.username
                            ")
                    ->join('bf_pharmacy_purchase_order_received_dtls','bf_pharmacy_purchase_order_received_dtls.master_id=bf_pharmacy_purchase_order_received_mst.id')
                    ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_purchase_order_received_dtls.product_id','left')
                    ->join('bf_pharmacy_purchase_order_mst','bf_pharmacy_purchase_order_mst.id=bf_pharmacy_purchase_order_received_mst.order_id','left')

                    ->join('bf_pharmacy_supplier','bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id','left')
                    ->join('bf_users','bf_users.id=bf_pharmacy_purchase_order_received_mst.received_by')

                    ->where('bf_pharmacy_purchase_order_received_mst.id',$order_received_id)
                    ->get('bf_pharmacy_purchase_order_received_mst')
                    ->result_array();

        //echo '<pre>'; print_r($records); die();

       // $crr_user=$this->current_user->display_name;
        return  $this->load->view('purchase_order_receive/receive_print', compact('records','hospital'), true);


    }

    public function main_stock_dtls($master_id=0,$product_id=0,$qnty=0){
        $dtls['stock_mst_id']=$master_id;
        $dtls['product_id']=$product_id;
        $dtls['quantity']=$qnty;
        $this->db->insert('bf_pharmacy_stock_dtls',$dtls);

        $stock_product=$this->db->where('product_id',$product_id)->get('bf_pharmacy_stock')->row();

        if($stock_product){
            $stock_update['quantity_level']=$qnty+$stock_product->quantity_level;
            $this->db->where('id',$stock_product->id)->update('bf_pharmacy_stock',$stock_update);
        }else{
            $stock_update['quantity_level']=$qnty;
            $stock_update['product_id']=$product_id;
            $this->db->insert('bf_pharmacy_stock',$stock_update);
        }

        return true;
    }


    public function order_table_update($dtls_id=0,$receive_qnty=0,$free_qnty=0){
        $row=$this->db->where('id',$dtls_id)->get('bf_pharmacy_purchase_order_dtls')->row();

        $data['receive_qnty']=$receive_qnty+$row->receive_qnty;
        $data['free_qnty']=$free_qnty+$row->free_qnty;
        if($row->order_qnty <= $data['receive_qnty']){
            $data['status']=2;
        }else{
            $data['status']=1;
        }

        $this->db->where('id',$dtls_id)->update('bf_pharmacy_purchase_order_dtls',$data);

        return $data['status'];
    }


    public function receive_list($print_order_id=0){

       // $this->auth->restrict('pharmacy.purchase.return.View');
         $this->auth->restrict('pharmacy.order.receive.View');
        if($print_order_id){
            
        }


        if(isset($_POST['save'])){
            //$this->auth->restrict('pharmacy.purchase.return.Create');
             $this->auth->restrict('pharmacy.order.receive.Add');
            if($insert_id=$this->save()){
                //request for return is done here
                Template::set_message('Request for return Successfully done', 'success');

            }else{
                Template::set_message('Request for return failed', 'error');
            }
        }

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Order No';
        $search_box['contact_no_flag']=1;

        $search_box['supplier_name_flag']=1;
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
                $condition['bf_pharmacy_supplier.supplier_name like']='%'.$this->input->post('supplier_name').'%';
            }
            if($this->input->post('contact_no')){
                $condition['bf_pharmacy_supplier.contact_no1 like']='%'.$this->input->post('contact_no').'%';
            }
            if($this->input->post('pharmacy_supplier_id')){
                $condition['pharmacy_supplier.id']=$this->input->post('pharmacy_supplier_id');
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
                    ->order_by('received_date','DESC')
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

}
