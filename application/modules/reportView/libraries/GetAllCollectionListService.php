<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetAllCollectionListService
{
	private $context;
	private $result;
	private $limit = 25;
	private $offset = 0;	
	

	private $collectionId;
	private $collectionDate;
	private $collectionDateTo;
	private $counterId;
	
	private $patientId;
	private $patientNo;
	private $patientName;
	private $contactNo;
	
	private $transactionType;
	private $sourceId;
	private $collectionSource;
	private $collectionBy;
	
	
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
	
	//===== Collection Id =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setCollectionId($val){
		if(intval($val) > 0){
			$this->collectionId = intval($val);
		}		
		return $this;
	}
	
	public function getCollectionId(){
		return $this->collectionId;
	}
		
	//====== Set Get collectionDate =====
	/**
     * @param date $var
     * @return $this
     */
	public function setCollectionDate($val){
		if(trim($val) != ""){
			$this->collectionDate = trim($val);
		}		
		return $this;
	}
	
	public function getCollectionDate(){
		return $this->collectionDate;
	}	
	
	//====== Set Get collectionDate To =====
	/**
     * @param date $var
     * @return $this
     */
	public function setCollectionDateTo($val){
		if(trim($val) != ""){
			$this->collectionDateTo = trim($val);
		}		
		return $this;
	}
	
	public function getCollectionDateTo(){
		return $this->collectionDateTo;
	}
		
	//===== Counter Id =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setCounterId($val){
		if(intval($val) > 0){
			$this->CounterId = intval($val);
		}		
		return $this;
	}
	
	public function getCounterId(){
		return $this->CounterId;
	}
	
	
	//===== Patient Id =======
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setPatientId($val){
		if(intval($val) > 0){
			$this->patientId = intval($val);
		}		
		return $this;
	}
	
	public function getPatientId(){
		return $this->patientId;
	}
	
	
	//====== Set Get PatientNo =====
	public function setPatientNo($val){
		if(trim($val) != ""){
			$this->patientNo = trim($val);
		}		
		return $this;
	}
	
	public function getPatientNo(){
		return $this->patientNo;
	}
	
	//====== Set Get PatientName =====
	public function setPatientName($val){
		if(trim($val) != ""){
			$this->patientName = trim($val);
		}		
		return $this;
	}
	
	public function getPatientName(){
		return $this->patientName;
	}
	
	//====== Set Get ContactNo =====
	public function setContactNo($val){
		if(trim($val) != ""){
			$this->contactNo = trim($val);
		}		
		return $this;
	}
	
	public function getContactNo(){
		return $this->contactNo;
	}
	
	//===== Source Id =======	
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setSourceId($val){
		if(intval($val) > 0){
			$this->sourceId = intval($val);
		}		
		return $this;
	}
	
	public function getSourceId(){
		return $this->sourceId;
	}
	
	//===== transactionType =======	
	/**
     * @param Int $var
     * @return $this
     */
	 
	public function setTransactionType($val){
		if(intval($val) > 0){
			$this->transactionType = intval($val);
		}		
		return $this;
	}
	
	public function getTransactionType(){
		return $this->transactionType;
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
	
	
	//===== collectionBy =======	
	 /**
     * @param Int $var
     * @return $this
     */
	 
	/*public function setCollectionBy(array $val){
		if(is_array($val)){
			$this->collectionBy = $val;
		}		
		return $this;
	}*/
	
	public function setCollectionBy( $val){
		if(intval($val) > 0){
			$this->collectionBy = intval($val);
		}		
		return $this;
	}
	
	public function getCollectionBy(){
		return $this->collectionBy;
	}
	
	
	
	/**     
     * @return object
     */
	 
	public function execute(){
		
		$conn = $this->context->doctrine->db;
		$dbtablePrefix = $this->context->db->dbprefix;
				
		$SQL="				
			SELECT 
				c.id, 
				c.counter_id,
				c.patient_id,
				c.source_id,
				c.source_name,
				c.amount,
				c.paid_by,
				c.collection_by,
				DATE_FORMAT(c.collection_date,'%d %b %Y') as collection_date, 
				pm.patient_id as patient_no,
				pm.patient_name,
				pm.father_name,
				pm.contact_no,					
				CASE c.`source_name`
					WHEN 1 THEN 'Ambulance'
					WHEN 2 THEN 'Ticket'
					WHEN 3 THEN 'Diagnosis'
					WHEN 4 THEN 'Admission'
					WHEN 5 THEN 'Discharge'
				END AS `collection_source`,
				c.remarks
				
			FROM 
				".$dbtablePrefix."transaction_details AS c 
			LEFT JOIN
                ".$dbtablePrefix."patient_master as pm ON pm.id=c.`patient_id`			
			WHERE 
				c.is_delete = 0 
			";
				
		if($this->patientId){
			$SQL.= " AND c.patient_id = ". $this->patientId;					
		}
		
		if($this->patientNo){
			$SQL.= " AND pm.patient_id = '". $this->patientNo ."'";					
		}
		
		if($this->patientName){
			$SQL.= " AND pm.patient_name LIKE '%". $this->patientName ."%'";			
		}
		
		if($this->contactNo){
			$SQL.= " AND pm.contact_no = '". $this->contactNo ."'";					
		}
		
		if($this->collectionId){
			$SQL.= " AND c.id = ". $this->collectionId;					
		}
		
		if($this->collectionDate != NULL && $this->collectionDateTo == NULL){
			$SQL.= " AND DATE(c.collection_date) = '". $this->collectionDate."'";					
		}
		elseif($this->collectionDate != NULL && $this->collectionDateTo != NULL)
		{
			$SQL.= " AND DATE(c.collection_date) BETWEEN '". $this->collectionDate ."' AND '". $this->collectionDateTo ."'";
		}
		
		if($this->counterId){
			$SQL.= " AND c.counter_id = ". $this->counterId;					
		}		
		
		
		if($this->transactionType){
			$SQL.= " AND c.transaction_type = ". $this->transactionType;					
		}
		
		if($this->sourceId){
			$SQL.= " AND c.source_id = ". $this->sourceId;					
		}
		
		if($this->collectionSource){ 
			$SQL.= " AND c.source_name = ". $this->collectionSource;					
		}
		
		if($this->collectionBy){ 
			$SQL.= " AND c.collection_by = ". $this->collectionBy;					
		}	
		
		//if($this->collectionBy){ 
			//$SQL.= " AND c.collection_by IN( ". implode(",", $this->collectionBy).")";					
		//}		
				
		$SQL.=" GROUP BY c.`id` ORDER BY c.`id` DESC LIMIT ".$this->offset.", ".$this->limit; 
		
		//$SQL = sprintf($SQL);  //echo $SQL; die; 
		
		$smt = $conn->prepare($SQL);
        $smt->execute();
		$this->result = $smt->fetchAll(); 
		
		return $this->result;						
			
	}
			
}