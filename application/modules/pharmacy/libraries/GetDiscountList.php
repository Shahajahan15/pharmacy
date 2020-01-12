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

class GetDiscountList {

   
    private $limit = 25;
    private $offset = 0;
    private $resultType = 'Object';
    private $date = 0;


  

    public function __construct($context) {
        $this->context = $context;
        $this->date=date('Y-m-d');
    }

    
    public function setLimit($var) {
        if (intval($var) > 0) {
            $this->limit = $var;
        }

        return $this;
    }

   
    public function setOffset($var) {        
        $this->offset = $var;

        return $this;
    }

    public function setDate($var) {      
        $this->date = $var; 
        return $this;
    }

    public function getCount() {
        return $this->context->doctrine->db->fetchColumn('SELECT FOUND_ROWS()');
    }


    
    //====== Query Execute =====

    public function execute() {   

        $conn = $this->context->doctrine->db;
        $dbtablePrefix = $this->context->db->dbprefix;


        $SQL = "SELECT
        			SQL_CALC_FOUND_ROWS bf_pharmacy_products.id,
					bf_pharmacy_products.id,
    				bf_pharmacy_products.product_name,
    				bf_pharmacy_discount.id as discount_id,
    				bf_pharmacy_discount.discount_parcent,
    				bf_pharmacy_discount.discount_from,
    				bf_pharmacy_discount.discount_to,
    				bf_pharmacy_discount.product_id,
    				bf_pharmacy_products.purchase_price,
    				bf_pharmacy_products.sale_price
				FROM `bf_pharmacy_products`
				JOIN `bf_pharmacy_discount`
				ON (bf_pharmacy_discount.product_id=bf_pharmacy_products.id OR bf_pharmacy_discount.product_id=0) AND bf_pharmacy_discount.id = (
    					SELECT MAX(bf_pharmacy_discount.id)
    					FROM bf_pharmacy_discount
    					WHERE (bf_pharmacy_discount.product_id = `bf_pharmacy_products`.id OR bf_pharmacy_discount.product_id = 0)";

    					if($this->date){
    						$SQL.="AND `bf_pharmacy_discount`.`discount_from` <= '".$this->date."' AND `bf_pharmacy_discount`.`discount_to` >= '".$this->date."')";
    					}

    					$SQL.="GROUP BY bf_pharmacy_products.id";
        

        $SQL.= " LIMIT " . $this->offset . "," . $this->limit;
        //$SQL = sprintf($SQL);
        //echo $SQL;
        $smt = $conn->prepare($SQL);
        $smt->execute();
        $this->result = $smt->fetchAll();


        if ($this->resultType == "Object") {
            //$this->result = $this->context->db->query($SQL)->result_object();
            $this->result = (object) $this->result;
        } else {
            //$this->result = $this->context->db->query($SQL)->result_array();
            $this->result = (array) $this->result;
        }

        return $this->result;
    }

}
