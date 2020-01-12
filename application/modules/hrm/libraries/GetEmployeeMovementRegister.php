<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetEmployeeMovementRegister
{
	
	private $context;
	private $result;	
	private $limit 	= 25;
	private $offset = 0;
	private $employeeId;
	private $employeeMobile;	
	private $resultType;
    private $employeeDepartment;
	private $employeeDesignation;	
	private $employeeDivision;
	private $employeeDistrict;
	
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
		if(trim($val) != '' || trim($val) != 'SelectOne'){
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
	public function setEmpDepartment($val){
		if(intval($val) > 0){
			$this->employeeDepartment = intval($val);
		}		
		return $this;
	}
        public function getEmpDepartment(){
		return $this->employeeDepartment;
	}
	 public function setEmpDesignation($val){
		if(intval($val) > 0){
			$this->employeeDesignation= intval($val);
		}		
		return $this;
	}
        public function getEmpDesignation(){
		return $this->employeeDesignation;
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
               
				
		$SQL = sprintf("SELECT SQL_CALC_FOUND_ROWS 
				emp.EMP_ID,
				emp.EMP_NAME,
				emp.STATUS,
				dep.department_name,
				des.DESIGNATION_NAME,
				dv.division_name
                
                FROM 
				".$dbtablePrefix."hrm_ls_employee AS emp
                INNER JOIN
				".$dbtablePrefix."lib_department AS dep ON  dep.dept_id   = emp.EMP_DEPARTMENT    
				
                LEFT JOIN
				".$dbtablePrefix."lib_designation_info AS des ON emp.EMP_DESIGNATION = des.DESIGNATION_ID 
				LEFT JOIN
				".$dbtablePrefix."hrm_ls_emp_contacts AS ec ON emp.EMP_ID = ec.EMP_ID
				LEFT JOIN
				".$dbtablePrefix."zone_trtarea AS tr ON ec.PERMANENT_POST_OFFICE_ID = tr.trt_id
				LEFT JOIN
				".$dbtablePrefix."zone_area AS ar ON tr.area_no = ar.area_id
				LEFT JOIN
				".$dbtablePrefix."zone_district AS dist ON ar.district_no = dist.district_id
				LEFT JOIN
				".$dbtablePrefix."zone_division AS dv ON dist.division_no = dv.division_id
				
				
				
				WHERE 
				emp.EMP_ID > 0
				
			"
                       
                        
                            

		);	
              
		
		if($this->employeeId)
		{
			$SQL.= " AND emp.EMP_ID = ". $this->employeeId;					
		}	
			
		 if($this->employeeName)
		{
			$SQL.= " AND emp.EMP_NAME  LIKE '%". $this->employeeName ."%'";					
		} 	
		
        if($this->employeeDepartment)
		{
			$SQL.= " AND dep.dept_id = ". $this->employeeDepartment;					
		}
		
		if($this->employeeDesignation)	
		{
			$SQL.= " AND des.DESIGNATION_ID  = ". $this->employeeDesignation;					
		}
		
		
		

		
		$SQL.=" ORDER BY `EMP_ID` ASC LIMIT ".$this->offset.", ".$this->limit;
                
		
                
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