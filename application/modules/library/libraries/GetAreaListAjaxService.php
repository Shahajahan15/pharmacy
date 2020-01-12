<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class GetAreaListAjaxService
{
	
	private $context;	
	
	private $projectNo;
	private $divisionNo;
	private $districtNo;
	
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
	
	public function setDistrictNo($val){
		if(intval($val) > 0){
			$this->districtNo = $val;
		}		
		return $this;
	}
	
	public function getDistrictNo(){
		return $this->districtNo;
	}
	
	public function execute(){
		
		$this->context->db->select("a.*");
		
		$this->context->db->from('zone_area AS a');
		
		if($this->projectNo){
			$this->context->db->where("a.project_no",$this->projectNo);		
		}
		if($this->divisionNo){
			$this->context->db->where("a.division_no",$this->divisionNo);		
		}
		if($this->districtNo){
			$this->context->db->where("a.district_no",$this->districtNo);		
		}		
		$this->context->db->distinct("a.area_id");
		
		$records = $this->context->db->get()->result_array();
		//print $this->context->db->last_query(); die;
		return $records;		
	}
			
}