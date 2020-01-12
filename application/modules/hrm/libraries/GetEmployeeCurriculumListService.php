<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetEmployeeCurriculumListService
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
			SELECT 
				e.EMP_EDUCATION_ID,
				e.EMP_ID,
				e.PASS_YEAR,					
				e.SCORE,
				e.EARNED_SCORE,
				e.CLASS_DIVISION,
				ex.exam_name,				
				b.exam_board AS board
				
			FROM 
			
				 ".$dbtablePrefix."hrm_ls_emp_education  AS e
			LEFT JOIN				
				  ".$dbtablePrefix."exam_exam_board  AS b ON e.BOARD_UNIV = b.id 
			LEFT JOIN				
				  ".$dbtablePrefix."exam_exam AS ex ON e.EXAMCODE_ID = ex.id 
			WHERE 
				 	EMP_EDUCATION_ID > 0 
					
			";
				
		if($this->employeeId){
			$SQL.= " AND EMP_ID = ". $this->employeeId;					
		}
		//echo $this->employeeId; die;
		
		$SQL.=" GROUP BY `EMP_EDUCATION_ID`  LIMIT ".$this->offset.", ".$this->limit;
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