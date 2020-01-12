<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetProductListService
{

	private $context;
	private $result;	
	private $limit 	= 25;
	private $offset = 0;
	
	private $productName;
	private $productCode;
	private $productCategory;
	private $resultType;
	
	public function __construct($context) { 
        $this->context = $context;
    }
	
	
	/**
     * @param $var
     * @return $this
     */
    public function setLimit($var)
    {
        if(intval($var) > 0){
            $this->limit = $var;
        }

        return $this;
    }

    /**
     * @param $var
     * @return $this
     */
    public function setOffset($var)
    {
        if(intval($var) > 0){
            $this->offset = $var;
        }

        return $this;
    }

    public function getCount()
    {
        return $this->context->doctrine->db->fetchColumn('SELECT FOUND_ROWS()');
    }

	//===== Product Name =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setProductName($val){
		if(trim($val) > 0){
			$this->productName = trim($val);
		}		
		return $this;
	}
	
	public function getProductName(){
		return $this->productName;
	}
	
		//====== Set Get Result Type =====
	public function setResultType($val){
		if(trim($val) != ""){
			$this->resultType = trim($val);
		}else{
			$this->resultType = "Array";	
		}
		return $this;
	}
	
	public function getResultType(){
		return $this->resultType;
	}

	
	/**     
     * @return object
     */
	 
	public function execute()
	{
		
		$conn = $this->context->doctrine->db;
		$dbtablePrefix = $this->context->db->dbprefix;
				
		$SQL="	SELECT SQL_CALC_FOUND_ROWS 
				p.STORE_PRODUCT_ID,
				p.STORE_PRODUCT_NAME,
                                p.STORE_PRODUCT_CODE,
                                c.STORE_PRODUCT_CATEGORY_NAME,
                                p.STATUS
			FROM 
				 ".$dbtablePrefix."store_product AS p 
			LEFT JOIN
				".$dbtablePrefix."store_product_category AS c ON p.STORE_PRODUCT_CATEGORY_ID  = c.STORE_PRODUCT_CATEGORY_ID 
			WHERE 
				p.STORE_PRODUCT_ID >= 1
			";	
		
		if($this->productName)
		{
                    $SQL.= " AND  p.STORE_PRODUCT_NAME= ". $this->productName;					
		}	 

		$SQL.=" ORDER BY `STORE_PRODUCT_ID` ASC LIMIT ".$this->offset.", ".$this->limit;
               
		$smt = $conn->prepare($SQL);
                $smt->execute();
     
		$this->result = $smt->fetchAll();
		
		if($this->resultType=="Object")
		{		
			$this->result = (object) $this->result;			
		}else
		{			
			$this->result = (array) $this->result;			
		}						
		return $this->result;					
			
	}	
			
}