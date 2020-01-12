<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employee_model extends BF_Model {

	protected $table_name	= "hrm_ls_employee";	
	protected $key			= "EMP_ID";
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
	/*protected $validation_rules 		= array(		
						array(
							"field"		=> "employee_master_emp_name",
							"label"		=> "Employee Name",
							"rules"		=> "max_length[100]"
							),
	
		
		
	);*/
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= FALSE;

	//--------------------------------------------------------------------
	
	/*    get employee code      */
	
	public function get_doctor_code($type=0)
	{

		$max_code = $this->db->select("CODE")
				->where('EMP_TYPE',$type)
				->where('EMP_CODE is not NULL')
				->order_by('EMP_ID','desc')
				->get('bf_hrm_ls_employee')->row();


			$prefix="I-".$type;


			if($max_code->CODE){
				$max=(int)(str_replace($prefix,'0',$max_code->CODE));
				$doctor_code 		= $prefix.str_pad($max+1, 3, "0", STR_PAD_LEFT);
			}else{
				$doctor_code 		= $prefix.str_pad('1', 3, "0", STR_PAD_LEFT);
			}

			return $doctor_code;die();

	}
	
	/*    get employee code      */
	
	public function get_emp_code()
	{

		$row = $this->db->select("EMP_CODE")
				->order_by('EMP_ID','desc')
				->where('EMP_CODE is not NULL')
				->get('bf_hrm_ls_employee')->row();

			$incr_id = substr($row->EMP_CODE,3);
			$incr_id++;
			$incr_id = (string)$incr_id;
			$emp_code = "EM-".str_pad($incr_id, 3, '0', STR_PAD_LEFT);
		if(!$row){
			$emp_code = "EM-001";
		}

			return $emp_code;
	}

	public function get_by_user_id($user_id)
	{
		return $this->db->select('users.id as user_id')
						->select('hrm_ls_employee.*')
						->from('users')
						->join('hrm_ls_employee','users.employee_id = hrm_ls_employee.EMP_ID')
						->where('users.id', $user_id)
						->get()
						->row();
	}

}
