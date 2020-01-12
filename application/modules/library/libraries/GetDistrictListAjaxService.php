<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetDistrictListAjaxService
{
	
	private $context;	
	
	private $projectNo;
	private $divisionNo;
	
	public function __construct($context) { 
        $this->context = $context;
    }
	
	public function setProjectNo($val){
		if(intval($val) > 0){
			$this->projectNo = $val;
		}else{
		$this->projectNo = 1;	
		}
		return $this;
	}
	
	public function getProjectNo(){
		return $this->projectNo;
	}
	
	public function setDivisionNo($val){
		if(intval($val) > 0){
			$this->divisionNo = $val;
		}		
		return $this;
	}
	
	public function getDivisionNo(){
		return $this->divisionNo;
	}	
	
	
	public function execute(){
		
		$this->context->db->select("d.*");
		
		$this->context->db->from('zone_district AS d');
		
		if($this->projectNo){
			$this->context->db->where("d.project_no",$this->projectNo);		
		}
		if($this->divisionNo){
			$this->context->db->where("d.division_no",$this->divisionNo);		
		}		
		$this->context->db->distinct("d.district_id");
		
		$records = $this->context->db->get()->result_array();
		//print $this->context->db->last_query(); die;
		return $records;		
	}
			
}