<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class List_model extends BF_Model {
	
	function GetProjectList(){
	$this->db->select('*');
	$this->db->from("bf_project");
	$this->db->order_by('project_name','ASC');
	$query = $this->db->get();
	return $query;
	}
	
	function GetAccGroupList($projectId=0){ 
	$this->db->select('*');
	$this->db->from("bf_ac_group");
	if(intval($projectId) > 0){
		$this->db->where("project_name", $projectId);
	}	
	$this->db->distinct('gid');
	$this->db->order_by('group_name','ASC');
	$query = $this->db->get(); 
	//$this->db->last_query();
	if(intval($projectId) > 0){
		return $query->result_array();
	} else {
		return $query;
	}
	}
	function GetAccCategoryList(){
	$this->db->select('*');
	$this->db->from("bf_ac_category");
	$this->db->order_by('category_name','ASC');
	$query = $this->db->get();
	return $query;
	}
	function GetAccSubcategoryList(){
	$this->db->select('*');
	$this->db->from("bf_ac_subcategory");
	$this->db->order_by('sub_category_name','ASC');
	$query = $this->db->get();
	return $query;
	}
    function GetAccchildList(){
	$this->db->select('*');
	$this->db->from("bf_ac_subchild");
	$this->db->order_by('sub_child_name','ASC');
	$query = $this->db->get();
	return $query;
	}
	
	
	//====== Common Function =====
	
	function GetRes4Dropdown($table,$fieldName=NULL,$fieldVal=0,$distinctField=NULL,$groupByField=NULL,$groupBy=NULL){ 
		$this->db->select('*');
		$this->db->from("$table");
		if($fieldName!="" && intval($fieldVal) >0){
			$this->db->where("$fieldName", $fieldVal);
		}	
		if($distinctField!=""){
			$this->db->distinct("$distinctField");
		}
		if($groupByField!=""){
			if($groupBy==""){$groupBy = "ASC";}
			$this->db->order_by("$groupByField","$groupBy");
		}
		$query = $this->db->get();	//$this->db->last_query(); exit;
		return $query->result_array();
	}
	function getDropdownOption($options,$innerHtmlName){
		$groupId = 0; $groupDropDown="";
		if(is_array($options)){				
		$groupDropDown = form_dropdown("$innerHtmlName", $options, set_value("$innerHtmlName", (intval($groupId) > 0) ? $groupId : ''), " ", "class='form-control'", '', "class='control-label col-sm-4'", 1);				
		}
		return $groupDropDown;
	}
	
}


