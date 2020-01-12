<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetEmployeeTraining
{
	
	private $context;
	private $result;	
	private $limit 	= 25;
	private $offset = 0;
	
	private $empTrainingId;
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
	
	
	
	//===== setempTrainingId =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setEmpTrainingId($val){
		if(intval($val) > 0){
			$this->empTrainingId = intval($val);
		}		
		return $this;
	}
	
	
	
	public function getEmpTrainingId(){
		return $this->empTrainingId;
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
				
		/*$SQL="				
			SELECT  SQL_CALC_FOUND_ROWS
				tr.EMP_TRAINING_ID,
				tr.EMP_ID,
				tr.TRAINING_NAME,
				tr.CERTIFICATE_FLAG,
				tr.CONDUCTED_BY,
				tr.CONDUCTED_BY_BENGALI,
				tr.COMPLETION_DATE
				
				
			FROM 			
				 ".$dbtablePrefix."hrm_ls_emp_training  as tr 		
			 	
				 
			WHERE 
				 	EMP_TRAINING_ID > 0 					
			";
			*/
		$SQL="				
			SELECT  SQL_CALC_FOUND_ROWS
				tr.EMP_TRAINING_ID,
				tr.EMP_ID,
				tr.TRAINING_NAME,
				tr.CERTIFICATE_FLAG,
				tr.CONDUCTED_BY,
				tr.CONDUCTED_BY_BENGALI,
				tr.COMPLETION_DATE,
				training_type.TRAINING_TYPE_NAME
				
			FROM 			
				 ".$dbtablePrefix."hrm_ls_emp_training  as tr 		
			 	
			LEFT JOIN 
				".$dbtablePrefix."lib_training_type AS training_type ON training_type.TRAINING_TYPE_ID  = tr.TRAINING_NAME 
			
			WHERE 
				 	EMP_TRAINING_ID > 0 					
			";
				
		if($this->employeeId){
			$SQL.= " AND EMP_ID = ". $this->employeeId;					
		}
		
		if($this->empTrainingId){
			$SQL.= " AND EMP_TRAINING_ID = ". $this->empTrainingId;					
		}
		//echo $this->employeeId; die;
		
		$SQL.=" GROUP BY `EMP_TRAINING_ID`  LIMIT ".$this->offset.", ".$this->limit;
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