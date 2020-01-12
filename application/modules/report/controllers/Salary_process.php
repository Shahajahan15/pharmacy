<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Salary_process extends Admin_Controller {
	
	/**
	 * Constructor
	 *
	 * @return void
	*/
    public function __construct() {		
        
		parent::__construct();
	
        $this->lang->load('common');
        $this->load->config('config_employee');
        Assets::add_module_js('hrm','salary_process');
	} // end construct 


	public function show_list(){		
		
        $this->auth->restrict('Hrm.Salary.proccess.View');
		//Template::set($data);
		Template::set_view('salary_process/index');
		Template::set('toolbar_title','Employee Salary Report');
		Template::render();
	}


	public function proccess(){
		$this->auth->restrict('Hrm.Salary.proccess.Process');
		if(isset($_POST['month'])){
			$month=$_POST['month'];
		}else{
			return false;
		}


		$data=$this->getProcess($month);	
		
		

		if ($this->input->is_ajax_request()) {
            echo $this->load->view('salary_process/salary',$data, true);
            exit;
        } 

		Template::set($data);
		Template::set_view('salary_process/salary');
		Template::set('toolbar_title','Employee Salary Sheet');
		Template::render();

	}

	public function getProcess($month){
		$condition["DATE_FORMAT(bf_hrm_attendance.attendance_date,'%Y-%m')"]=$month;
		$records=$this->db->select("
							SUM(CASE WHEN bf_hrm_attendance.present_status=1 THEN 1 ELSE 0 END) as tot_present,
							SUM(CASE WHEN bf_hrm_attendance.present_status=2 THEN 1 ELSE 0 END) as tot_absenses,
							SUM(CASE WHEN bf_hrm_attendance.present_status=3 THEN 1 ELSE 0 END) as tot_leave,
							bf_hrm_ls_employee.EMP_CODE as emp_code,
							bf_hrm_ls_employee.EMP_ID as emp_id,
							bf_hrm_ls_employee.EMP_Name as employee_name,
							bf_hrm_ls_employee.EMP_PHOTO as photo,
							bf_lib_designation_info.designation_name,
							bf_lib_department.department_name,
							(SELECT total_gross_amount from bf_hrm_employee_salary 
							WHERE 
							bf_hrm_employee_salary.emp_id=bf_hrm_ls_employee.EMP_ID 
							AND (DATE_FORMAT(bf_hrm_employee_salary.salary_start_month,'%Y-%m') = '$month'
							 OR DATE_FORMAT(bf_hrm_employee_salary.salary_start_month,'%Y-%m') < '$month')
							ORDER BY bf_hrm_employee_salary.salary_start_month DESC
							LIMIT 1) as total_gross_amount
						")
				->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_hrm_attendance.emp_id','left')
				->join('bf_lib_designation_info','bf_lib_designation_info.DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION','left')
				->join('bf_lib_department','bf_lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT','left')
				->group_by('bf_hrm_attendance.emp_id')
				->where($condition)
				->order_by('bf_hrm_attendance.created_at','DESC')
				->get('bf_hrm_attendance')
				->result();
			//echo $this->db->last_query();exit;
			//echo '<pre>';print_r($records);die();
		 

		 foreach ($records as $key => $record) {
		 	$deductions=$this->getDeductionByEmployee($record->emp_id,$month);
		 	$records[$key]->loan_deduction=$deductions->loan;
		 	$records[$key]->advance_deduction=$deductions->advance;
		 	$records[$key]->absense_deduction=(int)(($record->total_gross_amount/30)*$record->tot_absenses);
		 	$records[$key]->total_deduction=$records[$key]->loan_deduction+$records[$key]->advance_deduction+$records[$key]->absense_deduction;
		 	$records[$key]->payable_salary=$records[$key]->total_gross_amount-$records[$key]->total_deduction;
		 	$records[$key]->month=$month;
		 }
		 



		$total_day_row=$this->db->select('IFNULL(COUNT(id),0) as total_day')
							->where('DATE_FORMAT(attendance_date,"%Y-%m")',$month)
							->group_by('emp_id')
							->get('bf_hrm_attendance')
							->row();
		if($total_day_row){
			$total_day=$total_day_row->total_day;
		}else{
			$total_day=0;
		}
		//echo '<pre>'; print_r($total_day_row);die();

		$data['total_day']=$total_day;
		$data['month']=date('F,Y',strtotime($month));
		$data['records']=$records;

		return $data;
	}


	public function print_salary_sheet(){
		if(isset($_POST['month'])){
			$month=$_POST['month'];
		}else{
			return false;
		}


		$data=$this->getProcess($month);
		//$data['project']=$this->db->where('id',1)->get('bf_lib_project_compay_setup')->row();
		
		

		if ($this->input->is_ajax_request()) {
            echo $this->load->view('salary_process/print_sheet',$data, true);
            exit;
        }
	}

	public function getPayslip(){ 
		if(isset($_GET['month'])){
			$month=$_GET['month'];
		}else{
			return false;
		}


		$condition["DATE_FORMAT(bf_hrm_attendance.attendance_date,'%Y-%m')"]=$month;
		$condition["bf_hrm_ls_employee.EMP_ID"]=$_GET['emp_id'];
		$record=$this->db->select("
							SUM(CASE WHEN bf_hrm_attendance.present_status=1 THEN 1 ELSE 0 END) as tot_present,
							SUM(CASE WHEN bf_hrm_attendance.present_status=2 THEN 1 ELSE 0 END) as tot_absenses,
							SUM(CASE WHEN bf_hrm_attendance.present_status=3 THEN 1 ELSE 0 END) as tot_leave,
							bf_hrm_ls_employee.EMP_CODE as emp_code,
							bf_hrm_ls_employee.EMP_ID as emp_id,
							bf_hrm_ls_employee.EMP_Name as employee_name,
							bf_hrm_ls_employee.EMP_FATHER_NAME as employee_father_name,
							bf_hrm_ls_employee.EMP_PHOTO as photo,
							bf_hrm_ls_emp_contacts.MOBILE as mobile,
							bf_lib_designation_info.designation_name,
							bf_lib_department.department_name,
							(SELECT total_gross_amount from bf_hrm_employee_salary 
							WHERE 
							bf_hrm_employee_salary.emp_id=bf_hrm_ls_employee.EMP_ID 
							AND (DATE_FORMAT(bf_hrm_employee_salary.salary_start_month,'%Y-%m') = '$month'
							 OR DATE_FORMAT(bf_hrm_employee_salary.salary_start_month,'%Y-%m') < '$month')
							ORDER BY bf_hrm_employee_salary.salary_start_month DESC
							LIMIT 1) as total_gross_amount
						")
				->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_hrm_attendance.emp_id','left')
				->join('bf_lib_designation_info','bf_lib_designation_info.DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION','left')
				->join('bf_lib_department','bf_lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT','left')
				->join('bf_hrm_ls_emp_contacts','bf_hrm_ls_emp_contacts.EMP_ID=bf_hrm_ls_employee.EMP_ID','left')
				->group_by('bf_hrm_attendance.emp_id')
				->where($condition)
				->order_by('bf_hrm_attendance.created_at','DESC')
				->get('bf_hrm_attendance')
				->row();
			//echo $this->db->last_query();exit;
			//echo '<pre>';print_r($record);die();
		 
		
		 	$deductions=$this->getDeductionByEmployee($record->emp_id,$month);
		 	$record->loan_deduction=$deductions->loan;
		 	$record->advance_deduction=$deductions->advance;
		 	$record->absense_deduction=(int)(($record->total_gross_amount/30)*$record->tot_absenses);
		 	$record->total_deduction=$record->loan_deduction+$record->advance_deduction+$record->absense_deduction;
		 	$record->payable_salary=$record->total_gross_amount-$record->total_deduction;
		 	$record->month=$month;
		 
		 



		$total_day_row=$this->db->select('IFNULL(COUNT(id),0) as total_day')
							->where('DATE_FORMAT(attendance_date,"%Y-%m")',$month)
							->group_by('emp_id')
							->get('bf_hrm_attendance')
							->row();
		if($total_day_row){
			$total_day=$total_day_row->total_day;
		}else{
			$total_day=0;
		}
		//echo '<pre>'; print_r($record);die();

		$data['total_day']=$total_day;
		$data['month']=date('F,Y',strtotime($month));
		$data['record']=$record;
		//$data['project']=$this->db->where('id',1)->get('bf_lib_project_compay_setup')->row();
		
		

		if ($this->input->is_ajax_request()) {
            echo $this->load->view('salary_process/payslip_print',$data, true);
            exit;
        }
	}

	public function getDeductionByEmployee($emp_id,$month){
		$deductions=new stdClass();

		//loan amount
		$loan=$this->db->select("IFNULL(SUM(paid_amount),0) as payable_loan")->where('emp_id',$emp_id)->where("DATE_FORMAT(paid_date,'%Y-%m')",$month)->get('bf_hrm_employee_loan_status')->row();
		if($loan){
			$deductions->loan=$loan->payable_loan;
		}else{
			$deductions->loan=0;
		}

		//Advance Salary
		$advance=$this->db->select("IFNULL(SUM(advance_amount),0) as payable_advance")->where('emp_id',$emp_id)->where("DATE_FORMAT(created_at,'%Y-%m')='$month'")->get('bf_hrm_advance_salary')->row();
		if($advance){
			$deductions->advance=$advance->payable_advance;
		}else{
			$deductions->advance=0;
		}

		return $deductions;
	}
	public function getAbsenseDeduction($emp_id,$salary){
		$salary_per_day=($salary/30)*$absense;

	}
}// end controller


                     
                     
                     

