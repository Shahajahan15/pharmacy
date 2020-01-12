<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetEmployeeListService
{
	
	private $context;
	private $result;	
	private $limit 	= 25;
	private $offset = 0;
	
	private $employeeId;
	private $employeeName;
	private $employeeMobile;	
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
	
	
	//===== Employee Name =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setEmpName($val){
		if(trim($val) != ''){
			$this->employeeName = trim($val);
		}		
		return $this;
	}
	
	public function getEmpName(){
		return $this->employeeName;
	}
	
	
	//===== Employee Mobile =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setEmpMobile($val){
		if(trim($val) != ''){
			$this->employeeMobile = trim($val);
		}		
		return $this;
	}
	
	public function getEmpMobile(){
		return $this->employeeMobile;
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
				emp.EMP_ID,
				emp.EMP_NAME,
				emp.GENDER,
				emp.EMP_TYPE,	
				emp.MOBILE_NUM as mobile,		
				emp.STATUS,
				DATE_FORMAT(emp.JOINNING_DATE,'%d-%b-%Y') as joined_date,				
				cont.MOBILE,
				desig.DESIGNATION_NAME,
				dep.department_name
								
			FROM 
				 ".$dbtablePrefix."hrm_ls_employee AS emp 
			LEFT JOIN
				".$dbtablePrefix."lib_designation_info AS desig ON desig.DESIGNATION_ID  = emp.EMP_DESIGNATION 
			LEFT JOIN
				".$dbtablePrefix."hrm_ls_emp_contacts AS cont ON cont.EMP_ID  = emp.EMP_ID 
			LEFT JOIN
				".$dbtablePrefix."lib_department AS dep ON dep.dept_id  = emp.EMP_DEPARTMENT 
			WHERE 
				emp.EMP_ID >= 1
			";	
		
		if($this->employeeId)
		{
			$SQL.= " AND emp.EMP_ID = ". $this->employeeId;					
		}	
			
		if($this->employeeName)
		{
			$SQL.= " AND emp.EMP_NAME  LIKE '%". $this->employeeName ."%'";					
		}	
		
		if($this->employeeMobile)
		{
			$SQL.= " AND cont.MOBILE = ". $this->employeeMobile;					
		}	
		
		//$SQL.= " ORDER BY emp.EMP_ID ASC LIMIT ".$this->offset.", ".$this->limit;
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