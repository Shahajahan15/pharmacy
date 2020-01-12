<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Roster_policy_processing_model extends BF_Model {

	protected $table_name	= "hrm_ls_roster_policy_processing";	
	protected $key			= "ID";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";

	protected $log_user 	= FALSE;

	protected $set_created	= false;
	protected $set_modified = false;

	/*
		Customize the operations of the model without recreating the insert, update,
		etc methods by adding the method names to act as callbacks here.
	 */
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 		= array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	/*
		For performance reasons, you may require your model to NOT return the
		id of the last inserted row as it is a bit of a slow method. This is
		primarily helpful when running big loops over data.
	 */
	protected $return_insert_id 	= TRUE;

	// The default type of element data is returned as.
	protected $return_type 			= "object";

	// Items that are always removed from data arrays prior to
	// any inserts or updates.
	protected $protected_attributes = array();

	/*
		You may need to move certain rules (like required) into the
		$insert_validation_rules array and out of the standard validation array.
		That way it is only required during inserts, not updates which may only
		be updating a portion of the data.
	 */
	protected $validation_rules 		= array(		
						array(
							"field"		=> "employee_master_emp_name",
							"label"		=> "Employee Name",
							"rules"		=> "max_length[100]"
							),
	
		
		
	);
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= FALSE;

	//--------------------------------------------------------------------


	public function getRosterPolicyProcessingList($limit, $offset)
	{
		$con = [];
		if ($this->input->is_ajax_request()) {
			if ($this->input->post('employee_name')) {
				$con['emp.EMP_NAME like']='%'.trim($this->input->post('employee_name')).'%';
			}

			if ($this->input->post('emp_code')) {
				$con['emp.EMP_CODE like']='%'.trim($this->input->post('emp_code')).'%';
			}

			if ($this->input->post('test_department_list')) {
				$con['emp.EMP_DEPARTMENT']=$this->input->post('test_department_list');
			}

			if ($this->input->post('designation_list')) {
				$con['emp.EMP_DESIGNATION']=$this->input->post('designation_list');
			}
			
			if ($this->input->post('empType_list')) {
				$con['emp.EMP_TYPE']=$this->input->post('empType_list');
			}
			if($this->input->post('from_date')){
                $from_date = custom_date_format(trim($this->input->post('from_date')));
                $con['TRIM(rpp.DATE) >='] = $from_date;
            }
 
            if($this->input->post('to_date')){
                $to_date = custom_date_format(trim($this->input->post('to_date')));
                $con['TRIM(rpp.DATE) <='] = $to_date;
            }
            if ($this->input->post('shift_id')) {
				$con['rpp.SHIFT_ID']=$this->input->post('shift_id');
			}
			if ($this->input->post('roster_id')) {
				$con['rpp.ROSTER_ID']=$this->input->post('roster_id');
			}
		}

		$records=$this->db->select('
				SQL_CALC_FOUND_ROWS
				rpp.ID,
				rpp.DATE,
				rp.ROSTER_POLICY_NAME as roster_name,
				sp.SHIFT_NAME,
				rpp.COUNT_DAY,
				rpp.CHANGE_DAY,
				emp.EMP_NAME as emp_name,
				emp.EMP_CODE as emp_code,
				ldi.DESIGNATION_NAME as designation_name,
				ld.department_name
			',false)
		->from('hrm_ls_roster_policy_processing as rpp')
		->join('hrm_ls_employee as emp','rpp.EMP_ID = emp.EMP_ID')
		->join('lib_designation_info as ldi','ldi.DESIGNATION_ID = emp.EMP_DESIGNATION','left')
		->join('lib_department ld','ld.dept_id = emp.EMP_DEPARTMENT','left')
		->join('hrm_ls_roster_policy rp','rpp.ROSTER_ID = rp.ROSTER_POLICY_ID','left')
		->join('hrm_ls_shift_policy as sp','rpp.SHIFT_ID = sp.SHIFT_POLICY_ID','left')
		->where($con)
		->order_by('rpp.ID','ASC')
		//->group_by('emp.EMP_ID')
		->limit($limit, $offset)
		->get()
		->result();
		//echo '<pre>';print_r($records);exit;
		//print_r($this->db->last_query($records));
		return $records;
	}

	public function getRosterPolicyProcessInfo($processing_id = 0) 
	{
		$record = $this->db->select('
				rpp.ID,
				rpp.EMP_ID,
				rpp.DATE,
				rp.ROSTER_POLICY_NAME as roster_name,
				rpp.CHANGE_DAY,
				emp.EMP_NAME as emp_name,
				emp.EMP_CODE as emp_code,
				ldi.DESIGNATION_NAME as designation_name,
				ld.department_name
			')
		->from('hrm_ls_roster_policy_processing as rpp')
		->join('hrm_ls_employee as emp','rpp.EMP_ID = emp.EMP_ID')
		->join('lib_designation_info as ldi','ldi.DESIGNATION_ID = emp.EMP_DESIGNATION','left')
		->join('lib_department ld','ld.dept_id = emp.EMP_DEPARTMENT','left')
		->join('hrm_ls_roster_policy rp','rpp.ROSTER_ID = rp.ROSTER_POLICY_ID','left')
		->where('rpp.ID', $processing_id)
		->get()
		->row();
		return $record;
	}

	/*            roster policy */

	private function getRosterPolicy($emp_id)
	{
		$con = [];
		if ($emp_id) {
			$con['hpt.EMP_ID']=$emp_id;
		}
		$result = $this->db
					->select('hpt.POLICY_TRACKER_ID as id, hpt.EMP_ID as emp_id, hrp.SHIFT_POLICY_NAME as shift_policy_name, hrp.ROSTER_POLICY_ID as roster_id, hrp.AFTER_CHANGE_DAY as after_change_day')
					->from('hrm_policy_tagging as hpt')
					->join('hrm_ls_roster_policy as hrp', 'hpt.POLICY_ID = hrp.ROSTER_POLICY_ID and hpt.POLICY_TYPE = 7')
					->where($con)
					->get()
					->result_array();
		return $result;
	}

	public function getRosterPolicyProcessing($start_date = '', $end_date = '', $emp_id = 0) 
	{
		//print_r($start_date);print_r($end_date);exit;
		$result = $this->getRosterPolicy($emp_id);
		
	 	$datetime1 = date_create($start_date);
	    $datetime2 = date_create($end_date);
	    $interval = date_diff($datetime1, $datetime2);
	    $total_day = $interval->format('%a')+1;

  		$length = count($result);

  		for ($i = 0; $i < $length; $i++) {
		  	$x = 0;
		  	$count = 0;
		  	$data = [];
		  	$sf = 0;
		  	while ( $x < $total_day) {
		  		$shift_policy_name = explode(",",$result[$i]["shift_policy_name"]);
		  		$count++;
		  		if (isset($data[$result[$i]["emp_id"]])) {
		  			$data[$result[$i]["emp_id"]]["start_date"] = date('Y-m-d', strtotime('+1 day', strtotime($data[$result[$i]["emp_id"]]["start_date"])));
		  			$data[$result[$i]["emp_id"]]["count"] = $count;
		  			$data[$result[$i]["emp_id"]]["shift_id"] = $shift_policy_name[$sf];
		  			$data[$result[$i]["emp_id"]]["length"] = count($shift_policy_name);

		  		} else {
		  			$data[$result[$i]["emp_id"]]["start_date"] = $start_date;
		  			$data[$result[$i]["emp_id"]]["count"] = $count;
		  			$data[$result[$i]["emp_id"]]["shift_id"] = $shift_policy_name[$sf];
		  			$data[$result[$i]["emp_id"]]["length"] = count($shift_policy_name);
		  		}
				$arr[] = [
					"policy_tag_id" => $result[$i]["id"],
					"date" => $data[$result[$i]["emp_id"]]["start_date"],
					"emp_id" => $result[$i]["emp_id"],
					"roster_id" => $result[$i]["roster_id"],
		  			"shift_id" => $data[$result[$i]["emp_id"]]["shift_id"],
		  			"count_day" => $data[$result[$i]["emp_id"]]["count"],
		  			"after_change_day" => $result[$i]["after_change_day"]
		  		];

		  		if ($data[$result[$i]["emp_id"]]["count"] == $result[$i]["after_change_day"]) {
		  			$count = 0;
		  			$sf++;
		  			if ($data[$result[$i]["emp_id"]]["length"] == $sf) {
		  				$sf = 0;
		  			}
		  		}

		  		$x++;
		  	}
		  }
		  return $arr;
	}

}
