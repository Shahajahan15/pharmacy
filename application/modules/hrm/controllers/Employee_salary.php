<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_salary extends Admin_Controller {
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() {		
        
		parent::__construct();
	
        $this->lang->load('common');
        //$this->lang->load('attendance_processing');
        $this->load->config('config_employee');
        Assets::add_module_js('hrm','advance_salary');
       
		Template::set_block('sub_nav', 'employee_salary/_sub_nav');		
	} // end construct 


	public function show_list(){
		$this->auth->restrict('Hrm.Salary.View');

		$records=$this->db->select("
							bf_hrm_employee_salary.*,
							bf_hrm_ls_employee.EMP_CODE as emp_code,
							bf_hrm_ls_employee.EMP_Name as employee_name,
							bf_hrm_ls_employee.EMP_PHOTO as photo,
							bf_lib_designation_info.designation_name,
							bf_lib_department.department_name
						")
					->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_hrm_employee_salary.emp_id','left')
					->join('bf_lib_designation_info','bf_lib_designation_info.	DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION','left')
					->join('bf_lib_department','bf_lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT','left')
					->order_by('id','desc')
					->get('bf_hrm_employee_salary')
					->result();

		

		$data['records']=$records;
		Template::set($data);
		Template::set_view('employee_salary/list');
		Template::set('toolbar_title','Employee Salary List');
		Template::render();
	}


	public function create(){

		$this->auth->restrict('Hrm.Salary.Add');
		if(isset($_POST['save'])){
			if($insert_id=$this->save()){

				// Log the activity
				log_activity($this->current_user->id,'Advance Salary Created' .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_hrm_employee_salary');

				Template::set_message('Employee Salary Create Successfully', 'success');
				redirect(SITE_AREA .'/employee_salary/hrm/show_list');
			}else{
				Template::set_message('Employee Salary Create Failure', 'error');
			}
		}

		Template::set_view('employee_salary/create');
		Template::set('toolbar_title','Employee Salary Create');
		Template::render();

	}

	public function save(){
		//echo '<pre>';print_r($_POST);die();

		$this->db->trans_start();

			$data['salary_start_month']						= date('Y-m-1',strtotime(str_replace('/','-',$_POST['salary_start_month'])));

			$data['emp_id']						= $_POST['emp_id'];
			$data['total_gross_amount']			= $_POST['total_gross_amount'];
			$data['created_by']					= $this->current_user->id;
			
			$this->db->insert('bf_hrm_employee_salary',$data);
			$return=$this->db->insert_id();			

			$this->db->trans_complete();

			return $return;

	}
	
        
}// end controller


                     
                     
                     

