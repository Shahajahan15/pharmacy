<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_return_replace extends Admin_Controller 
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
        $this->load->model('return_products_replace_model', NULL, TRUE);

		$this->load->library('returnApproved');

        $this->lang->load('common');
		
        Assets::add_module_js('pharmacy', 'product_return_replace.js');
      
		
    }

   
  

    /*
    	@ Return approved quantity list..
    	@ This list quantity will be re pharmacy from supplier..
    */

    public function return_approved_list($success=0){
         $this->auth->restrict('pharmacy.PReturn.confirm.View');
    	if($success){
    		Template::set_message('Successfully Replaced product', 'success');
    	}
    	
        $GetreturnApprovedListOBJ = new returnApproved($this);
        $records=$GetreturnApprovedListOBJ->GetreturnApprovedList();

    	$data['records']=$records;

    	Template::set($data);
        Template::set('toolbar_title','Return Confirmed Product list');
        Template::set_view('return_product_replace/approved_list');
        Template::render();
    }


    public function replace_form($id){
    	//echo $id;
    	$record=$this->db->select('
    							bf_pharmacy_return_products.*,
    							bf_pharmacy_products.product_name,
    							bf_pharmacy_supplier.supplier_name,
                            	bf_pharmacy_supplier.contact_no1,
                            	bf_pharmacy_supplier.supplier_code,
                            	bf_pharmacy_purchase_order_received_mst.received_date,
                            	bf_pharmacy_purchase_order_received_mst.bill_no,
                            	bf_pharmacy_purchase_order_received_dtls.received_qnty
    							')
    				->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_return_products.product_id','left')
    				->join('bf_pharmacy_supplier','bf_pharmacy_return_products.supplier_id=bf_pharmacy_supplier.id','left')
    				->join('bf_pharmacy_purchase_order_received_mst','bf_pharmacy_return_products.received_mst_id=bf_pharmacy_purchase_order_received_mst.id','left')
    				->join('bf_pharmacy_purchase_order_received_dtls','bf_pharmacy_return_products.receive_dtls_id=bf_pharmacy_purchase_order_received_dtls.id','left')
    				->where('bf_pharmacy_return_products.id',$id)
    				->get('bf_pharmacy_return_products')
    				->row();

    	//echo '<pre>';print_r($record);die();
    	$data['record']=$record;
    	json_encode($this->load->view('return_product_replace/replace_form',$data));
    }


    public function replace_add(){
        $this->auth->restrict('pharmacy.PReturn.replace.add');
        if(isset($_POST['save'])){

            $returnApprovedOBJ = new returnApproved($this);
            $returnApprovedOBJ->replace_add($_POST,$this->current_user->id);   
        }
        
    	redirect(SITE_AREA . '/product_return_replace/pharmacy/return_approved_list/'.$replace_id);
    }
    



	
}
