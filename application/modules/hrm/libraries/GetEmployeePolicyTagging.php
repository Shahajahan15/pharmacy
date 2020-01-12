<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetEmployeePolicyTagging
{
	
	private $context;
	private $result;	
	private $limit 	= 25;
	private $offset = 0;
	
	private $employeeId;
	private $employeeName;
	private $employeeMobile;	
	private $resultType;
        private $employeeDepartment;
	private $employeeWithPolicy;	
	private $employeeWithoutPolicy;
	
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
	public function setEmpDepartment($val){
		if(intval($val) > 0){
			$this->employeeDepartment = intval($val);
		}		
		return $this;
	}
        public function getEmpDepartment(){
		return $this->employeeDepartment;
	}
	public function setWithPolicyType($val){		
		$this->employeeWithPolicy = intval($val);
			
		return $this;
	}
        public function getWithPolicyType(){
		return $this->employeeWithPolicy;
	}
	public function setWithoutPolicyType($val){
		$this->employeeWithoutPolicy = intval($val);
			
		return $this;
	}
        public function getWithoutPolicyType(){
		return $this->employeeWithoutPolicy;
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
				pt.POLICY_ID,
                GROUP_CONCAT(pt.POLICY_TYPE) AS POLICY_TYPE,
				GROUP_CONCAT(lp.LEAVE_POLICY_NAME) AS LEAVE_POLICY_NAME,
				GROUP_CONCAT(mp.NAME) AS MEDICAL_POLICY_NAME,
				GROUP_CONCAT(ap.ABSENT_POLICY_NAME) AS ABSENT_POLICY_NAME,
				GROUP_CONCAT(sp.SHIFT_NAME) AS SHIFT_NAME,
				GROUP_CONCAT(matp.MATERNITY_POLICY_NAME) AS MATERNITY_POLICY_NAME,
				GROUP_CONCAT(bp.NAME) AS NAME
				FROM 
					".$dbtablePrefix."hrm_ls_employee AS emp
				INNER JOIN ".$dbtablePrefix."lib_department AS dep 
					ON dep.dept_id  = emp.EMP_DEPARTMENT
				LEFT JOIN `".$dbtablePrefix."hrm_policy_tagging` pt 
					ON emp.EMP_ID = pt.EMP_ID
				LEFT JOIN `".$dbtablePrefix."hrm_leave_policy_mst` lp 
					ON pt.POLICY_ID = lp.LEAVE_POLICY_MST_ID AND pt.POLICY_TYPE = 1
				LEFT JOIN `".$dbtablePrefix."hrm_ls_medical_policy_master` mp 
					ON pt.POLICY_ID = mp.MEDICAL_POLICY_MASTER_ID AND pt.POLICY_TYPE = 2
				LEFT JOIN `".$dbtablePrefix."hrm_absent_leave_mst` ap 
					ON pt.POLICY_ID = ap.ABSENT_POLICY_MST_ID AND pt.POLICY_TYPE = 3
				LEFT JOIN `".$dbtablePrefix."hrm_ls_shift_policy` sp 
					ON pt.POLICY_ID = sp.SHIFT_POLICY_ID AND pt.POLICY_TYPE = 4
				LEFT JOIN `".$dbtablePrefix."hrm_maternity_leave` matp 
					ON pt.POLICY_ID = matp.MATERNITY_LEAVE_ID AND pt.POLICY_TYPE = 5
				LEFT JOIN `".$dbtablePrefix."hrm_is_bonus_policy_master` bp 
					ON pt.POLICY_ID = bp.BONUS_POLICY_MST_ID AND pt.POLICY_TYPE = 6
			WHERE	
				emp.EMP_ID > 0	
				%s	
				%s	
			GROUP BY 
				emp.EMP_ID
			",
			$this->employeeWithPolicy ? " AND emp.EMP_ID IN (SELECT EMP_ID FROM `".$dbtablePrefix."hrm_policy_tagging` hpt WHERE hpt.POLICY_TYPE = $this->employeeWithPolicy)" : "",
			$this->employeeWithoutPolicy && !$this->employeeWithPolicy ? " AND emp.EMP_ID NOT IN (SELECT EMP_ID FROM `".$dbtablePrefix."hrm_policy_tagging` hpt WHERE hpt.POLICY_TYPE = $this->employeeWithoutPolicy)" : ""			

		);	
                
		if($this->employeeId)
		{
			$SQL.= " AND emp.EMP_ID = ". $this->employeeId;					
		}	
			
		if($this->employeeName)
		{
			$SQL.= " AND emp.EMP_NAME  LIKE '%". $this->employeeName ."%'";					
		}	
		
//		if($this->employeeMobile)
//		{
//			$SQL.= " AND cont.MOBILE = ". $this->employeeMobile;					
//		}
                if($this->employeeDepartment)
		{
			$SQL.= " AND dep.dept_id = ". $this->employeeDepartment;					
		}	

		
		$SQL.=" ORDER BY `EMP_ID` ASC LIMIT ".$this->offset.", ".$this->limit;
                
		//echo $SQL;
               // die;
                
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