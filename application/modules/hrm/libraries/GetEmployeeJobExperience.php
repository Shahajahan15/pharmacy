<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetEmployeeJobExperience
{
	
	private $context;
	private $result;	
	private $limit 	= 25;
	private $offset = 0;
	
	private $employeeId;	
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
	
	
	//===== setempId =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setempId($val){
		if(intval($val) > 0){
			$this->employeeId = intval($val);
		}		
		return $this;
	}
	
	
	
	public function getEmpId(){
		return $this->employeeId;
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
	 
	public function execute(){
		
		$conn = $this->context->doctrine->db;
		$dbtablePrefix = $this->context->db->dbprefix;
				
		$SQL="				
			SELECT SQL_CALC_FOUND_ROWS 
				ex.EMP_JOB_EXP_ID,
				ex.EMP_ID,
				ex.ORGANIZATION,
				ex.ORGANIZATION_ADDRESS,
				ex.YEAR_START,
				ex.YEAR_END,
				ex.REASON_FOR_LEAVING,
				ex.CONTACT_PERSON,
				ex.CONTACT_NUMBER,
				desig.DESIGNATION_NAME
				
			FROM 			
				 ".$dbtablePrefix."hrm_ls_emp_job_experience AS ex   
			LEFT JOIN 
				".$dbtablePrefix."lib_designation_info AS desig ON desig.DESIGNATION_ID = ex.POSITION	 
			WHERE 
				 	ex.EMP_JOB_EXP_ID > 0 					
			";
				
		if($this->employeeId){
			$SQL.= " AND ex.EMP_ID = ". $this->employeeId;					
		}
		//echo $this->employeeId; die;
		
		$SQL.=" ORDER BY `EMP_JOB_EXP_ID` ASC LIMIT ".$this->offset.", ".$this->limit;
		//echo $SQL; die;
		
		$smt = $conn->prepare($SQL);
        $smt->execute();
		$this->result = $smt->fetchAll();
		
		if($this->resultType=="Object"){		
			$this->result = (object) $this->result;			
		}else{			
			$this->result = (array) $this->result;			
		}					
		
		return $this->result;					
			
	}
			
}