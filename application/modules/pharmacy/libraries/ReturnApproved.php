<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//--------------------------------------------------------------------
/**
 * Summary
 * @param string	 $resultType[optional]	The resultType value will be "Object" or "Array", DEFAULT value "Array"
 * @return By DEFAULT return Array Otherwise Object
 * */
//--------------------------------------------------------------------

class ReturnApproved{

    private $CI;
    private $c_user;
    private $supplier_id=0;

    public function __construct() {
    	$this->CI = & get_instance();
    	$this->CI->load->helper('url');
        $this->CI->load->library('session');
        $this->CI->config->item('base_url');
        $this->CI->load->model('return_products_replace_model', NULL, TRUE);
        $this->CI->load->model('stock_in_model', NULL, TRUE);
        $this->CI->load->database();
    }


    public function setSupplierID($supplier_id=0){
        $this->supplier_id=$supplier_id;
        return $this;
    }

    public function GetReturnApprovedList(){

        $condition['bf_pharmacy_return_products.is_confirm']=1;
        $condition['bf_pharmacy_return_products.is_received']=0;

        if($this->supplier_id){
            $condition['bf_pharmacy_return_products.supplier_id']=$this->supplier_id;
        }


    	$approved_list=$this->CI->db->select('
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
    				->where($condition)
    				->get('bf_pharmacy_return_products')
    				->result();

    				return $approved_list;

    }

    /*
    	@ purpose: method used for add return replace product 
     	@ parameter: post_data is a array contain=['return_products_id'=>value,'replace_qntity'=>value];
            #return_products_id is = id of bf_pharmacy_return_products table

    */

    public function replace_add($post_data,$current_user_id){

    	$this->CI->db->trans_begin();
    	//insert a row for replace action once
    	$data['return_products_id']=$post_data['return_products_id'];
    	$data['replace_qnty']=$post_data['replace_qntity'];
    	$data['replace_date']=date('Y-m-d');
    	$data['replace_received_by']=$current_user_id;
    	$replace_id=$this->CI->return_products_replace_model->insert($data);
    	//$this->db->insert('bf_pharmacy_return_products_replace',$data);


    	//return approved table update by increase replace qnty

    	$approved_table=$this->CI->db->where('id',$data['return_products_id'])->get('bf_pharmacy_return_products')->row();
    	$approved_table_update['replace_qnty']=$approved_table->replace_qnty+$data['replace_qnty'];
    	if($approved_table_update['replace_qnty']>=$approved_table->return_approved_qnty){
    		$approved_table_update['is_received']=2;
    	}
    	$this->CI->db->where('id',$data['return_products_id'])->update('bf_pharmacy_return_products',$approved_table_update);

    	//stock in update and insert stock mst dtls history

    	$stock_mst['stock_source_in_id']=$replace_id;
    	$stock_mst['stock_source_out_id']=0;
    	$stock_mst['pharmacy_id']=0;
    	$stock_mst['type']=1;
    	$stock_mst['source']=7;
    	$stock_mst['created_by']=$current_user_id;
    	$stock_mst_id=$this->CI->stock_in_model->insert($stock_mst);

    	$stock_dtls['stock_mst_id']=$stock_mst_id;
    	$stock_dtls['product_id']=$approved_table->product_id;
    	$stock_dtls['quantity']=$post_data['replace_qntity'];
    	$this->CI->db->insert('bf_pharmacy_stock_dtls',$stock_dtls);



    	$stock=$this->CI->db->where('product_id',$approved_table->product_id)->get('bf_pharmacy_stock')->row();
    	$stock_update['quantity_level']=$stock->quantity_level+$post_data['replace_qntity'];
    	$this->CI->db->where('id',$stock->id)->update('bf_pharmacy_stock',$stock_update);



        if ($this->CI->db->trans_status() === FALSE){
            $this->CI->db->trans_rollback();
            return false;
        }
        else{
            $this->CI->db->trans_commit();
            return true;
        }










    }
	
	

}
