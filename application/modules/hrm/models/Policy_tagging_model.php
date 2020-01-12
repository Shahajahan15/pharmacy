<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Policy_tagging_model extends BF_Model {

	protected $table_name	= "hrm_policy_tagging";	
	protected $key			= "POLICY_TRACKER_ID";
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
	/*
        protected $validation_rules 		= array(		
						array(
							"field"		=> "employee_master_emp_name",
							"label"		=> "Employee Name",
							"rules"		=> "max_length[100]"
							),
	
		
		
	);
         * 
         * 
         */
	protected $insert_validation_rules 	= array();
	protected $skip_validation 		= FALSE;

	//--------------------------------------------------------------------


	public function getPolicy() {
		$where =['LEAVE_POLICY_STATUS'=>1,'IS_DELETED'=>0]; 	   
		$leave_policy_details = $this->policy_leave_mst_model->find_all_by($where);	   
		
		$where =['STATUS'=>1,'IS_DELETED'=>0];
		$medical_policy_details 		= $this->medical_policy_master_model->find_all_by($where);
		
		$where =['ABSENT_POLICY_STATUS'=>1,'IS_DELETED'=>0];		
		$absent_policy_details 			= $this->absent_leave_mst_model->find_all_by($where);
		
		$where =['STATUS'=>1, 'IS_DELETED' => 0, 'SHIFT_TYPE' => 1];
		$shift_policy_details 			= $this->policy_shift_model->find_all_by($where);
		
		$where =['MATERNITY_STATUS'=>1,'IS_DELETED'=>0];		
		$maternity_policy_details 		= $this->maternity_leave_model->find_all_by($where);
		
		$where =['STATUS'=>1,'IS_DELETED'=>0];
		$bonus_policy_details 			= $this->policy_bonus_master_model->find_all_by($where); 
		
		$where =['STATUS'=>1, 'IS_DELETED' => 0];
		$roster_policy_details = $this->policy_roster_model->find_all_by($where);
		 
		$policy_name					= $this->config->item('policy_name');
        
		$data = array(
			'leave_policy_details' 			=> $leave_policy_details,
			'medical_policy_details' 		=> $medical_policy_details,
			'absent_policy_details' 		=> $absent_policy_details,
			'shift_policy_details' 			=> $shift_policy_details,
			'maternity_policy_details' 		=> $maternity_policy_details,
			'bonus_policy_details' 			=> $bonus_policy_details,
			'roster_policy_details'         => $roster_policy_details,
			'policy_name' 					=> $policy_name
		);
		return $data;
	}

	public function getEmployeePolicyTaggingList($limit, $offset) {
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
			if ($this->input->post('policy_type_with')) {
				$con['hpt.POLICY_TYPE']=$this->input->post('policy_type_with');
			}
			if ($this->input->post('policy_type_without')) {
				$con['hpt.POLICY_TYPE !=']=$this->input->post('policy_type_without');
			}
		}

		$records=$this->db->select('
				SQL_CALC_FOUND_ROWS
				emp.*,
				GROUP_CONCAT("##",hpt.POLICY_TYPE) as policy_types,
				ldi.DESIGNATION_NAME as designation_name,
				ld.department_name,
				lets.emp_type
			',false)
		->from('bf_hrm_ls_employee as emp')
		->join('lib_designation_info as ldi','ldi.DESIGNATION_ID=emp.EMP_DESIGNATION')
		->join('lib_department ld','ld.dept_id=emp.EMP_DEPARTMENT')
		->join('lib_employee_type_setup as lets','lets.id=emp.EMP_TYPE','left')
		->join('hrm_policy_tagging as hpt', 'emp.EMP_ID	= hpt.EMP_ID', 'left')
		->where($con)
		->order_by('emp.EMP_ID','DESC')
		->group_by('emp.EMP_ID')
		->limit($limit, $offset)
		->get()
		->result();
		//echo '<pre>';print_r($records);exit;
		return $records;
	}

}
