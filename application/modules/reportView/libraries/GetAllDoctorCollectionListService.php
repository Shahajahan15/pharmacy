<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetAllDoctorCollectionListService
{
	private $context;
	private $result;
	private $limit = 25;
	private $offset = 0;	
	

	private $doctorIdArray;
	private $collectionDateStart;	
	private $collectionDateEnd;
	private $collectionSource;
	
	
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
	
	//===== Doctor Id =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setdoctorIdArray($val){	
			$this->doctorIdArray = $val;				
			return $this;
	}
	
	public function getdoctorIdArray(){
		return $this->doctorIdArray;
	}
		
	//====== Set Get collection Start Date =====
	/**
     * @param date $var
     * @return $this
     */
	public function setCollectionDateStart($val){
		if(trim($val) != ""){
			$this->collectionDateStart = trim($val);
		}		
		return $this;
	}
	
	public function getCollectionDateStart(){
		return $this->collectionDateStart;
	}	
	
	//====== Set Get collection End Date =====
	/**
     * @param date $var
     * @return $this
     */
	public function setCollectionDateEnd($val){
		if(trim($val) != ""){
			$this->collectionDateEnd = trim($val);
		}		
		return $this;
	}
	
	public function getCollectionDateEnd(){
		return $this->collectionDateEnd;
	}	


	//===== Collection Source =======
	/**
     * @param Int $var 
     * @return $this
     */
	 
	public function setCollectionSource($val){
		if(intval($val) > 0){
			$this->collectionSource = intval($val);
		}		
		return $this;
	}
	
	public function getCollectionSource(){
		return $this->collectionSource;
	}
	
	
	/**     
     * @return object
     */
	 
	public function execute(){
		
		$conn = $this->context->doctrine->db;
		$dbtablePrefix = $this->context->db->dbprefix;
				
		$SQL="				
			SELECT 
			
				odp.appointment_type, 
				
				SUM(if(odp.appointment_type=1, odp.ticket_fee, null)) as ticketFee,
				
				SUM(if(odp.appointment_type=2, odp.ticket_fee, null)) as consultantFee,
				COUNT(if(odp.appointment_type=1 and pm.sex=1, pm.sex, null)) as maleTicket,
				COUNT(if(odp.appointment_type=2 and pm.sex=1, pm.sex)) as maleConsult
				
				
			FROM 
				".$dbtablePrefix."outdoor_patient_ticket AS odp
			LEFT JOIN
                ".$dbtablePrefix."patient_master as pm ON pm.id=odp.patient_id 
			LEFT JOIN
                ".$dbtablePrefix."doctor_doctor_info as ddi ON odp.doctor_id=ddi.id	
			LEFT JOIN
                ".$dbtablePrefix."hrm_employees as emp ON ddi.doctor_id=emp.id	
			WHERE 
				odp.id > 0 
			";
				
		
		if($this->collectionDateStart != NULL && $this->collectionDateEnd == NULL){
			$SQL.= " AND DATE(odp.created_time) = '". $this->collectionDateStart."'";					
		}
		elseif($this->collectionDateStart != NULL && $this->collectionDateEnd != NULL)
		{
			$SQL.= " AND DATE(odp.created_time) BETWEEN '". $this->collectionDateStart ."' AND '". $this->collectionDateEnd ."'";
		}
		
		/*
		if($this->collectionSource){ 
			$SQL.= " AND c.source_name = ". $this->collectionSource;					
		}*/
		
		if($this->doctorIdArray){ 
			$SQL.= " AND odp.doctor_id = ". $this->doctorIdArray;					
		}
	
				
		$SQL.=" GROUP BY odp.doctor_id ORDER BY odp.doctor_id ASC LIMIT ".$this->offset.", ".$this->limit; 
		
		//$SQL = sprintf($SQL);  //echo $SQL; die; 
		
		$smt = $conn->prepare($SQL);
        $smt->execute();
		$this->result = $smt->fetchAll(); 
		
		return $this->result;						
			
	}
			
}