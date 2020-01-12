<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetEmployeeTransferListService
{
	
	private $context;
	private $result;	
	private $limit 	= 25;
	private $offset = 0;
	
	private $employeeId;
	private $transferLetterNo;
	private $transferStart;	
	private $transferEnd;	
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
	
	
	//===== Employee Id =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setEmpId($val){
		if(intval($val) > 0){
			$this->employeeId = intval($val);
		}		
		return $this;
	}
	
	public function getEmpId(){
		return $this->employeeId;
	}
	
	
	//===== Transfer Letter No =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setLetterNo($val){
		if(trim($val) != ''){
			$this->transferLetterNo = trim($val);
		}		
		return $this;
	}
	
	public function getLetterNo(){
		return $this->transferLetterNo;
	}
	
	
	//=====Transfer Start Date =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setStartDate($val){
		if(trim($val) != ''){
			$this->transferStart = trim($val);
		}		
		return $this;
	}
	
	public function getStartDate(){
		return $this->transferStart;
	}
	
	
	
	
	//=====Transfer End Date =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setEndtDate($val){
		if(trim($val) != ''){
			$this->transferEnd = trim($val);
		}		
		return $this;
	}
	
	public function getEndtDate(){
		return $this->transferEnd;
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
				
		$SQL="				
			SELECT SQL_CALC_FOUND_ROWS 
				et.EMP_TRANSFER_ID,
				et.JOINNING_DATE_FROM,
				et.JOINNING_DATE_TO,
				et.TRANSFER_REASON,
				et.TRANSFER_LETTER_NO,
				em.EMP_NAME,
				ed.DESIGNATION_NAME,
				br.BRANCH_NAME
								
			FROM 
				 ".$dbtablePrefix."hrm_ls_transfer AS et 
			LEFT JOIN
				".$dbtablePrefix."lib_designation_info AS ed ON et.TRANSFER_DESIGNATION_ID = ed.DESIGNATION_ID
			LEFT JOIN
				".$dbtablePrefix."hrm_ls_employee AS em ON em.EMP_ID = et.EMP_ID
			LEFT JOIN
				".$dbtablePrefix."lib_hrm_branch_info AS br ON et.TRANSFER_BRANCH_ID = br.BRANCH_ID
			WHERE 
				et.EMP_TRANSFER_ID >=1
			";	
		
		if($this->employeeId)
		{
			$SQL.= " AND em.EMP_ID = ". $this->employeeId;					
		}	
			
		if($this->transferLetterNo)
		{
			$SQL.= " AND et.TRANSFER_LETTER_NO =". $this->transferLetterNo;					
		}	
		
		if($this->transferStart)
		{
			$SQL.= " AND et.TRANSFER_DATE >= '". $this->transferStart."'";					
		}	
		
		
		if($this->transferEnd)
		{
			$SQL.= " AND et.TRANSFER_DATE <= '".$this->transferEnd."'";					
		}
		
		
		$SQL.=" ORDER BY `EMP_TRANSFER_ID` ASC LIMIT ".$this->offset.", ".$this->limit;
		//echo $SQL;
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