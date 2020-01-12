<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class List_model extends BF_Model {
		
	//====== Common Function =====
	
	function GetRecords($table,$fieldName=NULL,$fieldVal=0,$distinctField=NULL,$groupByField=NULL,$groupBy=NULL){ 
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
		$query = $this->db->get();	//$this->db->last_query(); 
		return $query->result_array();
	}
	function getDropdownOption($options,$innerHtmlName){
		$groupId = 0; $groupDropDown="";
		if(is_array($options)){				
		$groupDropDown = form_dropdown("$innerHtmlName", $options, set_value("$innerHtmlName", (intval($groupId) > 0) ? $groupId : ''), " ", "class='form-control'", '', "class='control-label col-sm-4'", 1);				
		}
		return $groupDropDown;
	}
	function getDrawTableRow($result,$tableHeader,$filedName,$delURL,$isAjax=0){
		$TR="<table width='100%'  border='0' class='table table-bordered'>".$tableHeader;
		if($result){
			 foreach($result as $row){ 
				$TR.="<tr>";
				$TR.=$this->getDrawTableColumn($row,$filedName,$delURL,$isAjax);
				$TR.="</tr>"; 
			 }
		 }
		 $TR.="</table><br>"; 
		 return $TR;
	}
	function getDrawTableColumn($row,$filedName,$delURL=NULL,$isAjax=0){
		$i=0; $str=""; $filed_name=""; $noOfColumn = count($filedName);
		if(intval($noOfColumn)>0){  
			while($i<$noOfColumn){
				$filed_name = $filedName[$i]; 
				if($i<($noOfColumn - 1)){
				$str.="<td>".$row["$filed_name"]."</td>";
				}elseif($i==($noOfColumn -1 )){
					if($delURL!=""){
					$id = $row["$filed_name"];
					if($isAjax==1){
						$str.="<td align='center'><a title='Delete' href='#' onclick=$delURL('$id')>
						<span class='glyphicon glyphicon-remove-circle'> </span>
						</a></td>";	
					}else{
						$str.="<td align='center'><a title='Delete' href='$delURL/$id'>
						<span class='glyphicon glyphicon-remove-circle'> </span>
						</a></td>";
					}
					
					}
				}
				$i++;			
			}
		} 
		return $str;
	}	
}


