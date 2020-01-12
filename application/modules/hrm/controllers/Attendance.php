<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends Admin_Controller {
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() {		
        
		parent::__construct();
	
        $this->lang->load('common');
        $this->load->config('config_employee');
        Assets::add_module_js('hrm','attendance');
       
		Template::set_block('sub_nav', 'attendance/_sub_nav');		
	} // end construct 


	public function show_list(){
		$this->auth->restrict('Hrm.Attendance.View');

		// search with pagination

 	    $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page'):25;
        
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['employee_search_flag'] = 1;
        $search_box['designation_name_flag']=1;
		$search_box['em_department_name_flag']=1;
		$search_box['attendance_type_flag']=1;
        
        $condition['bf_hrm_attendance.create_by >=']=0;
        $condition['bf_hrm_attendance.branch_id']=$this->current_user->branch_id;

        if(count($_POST)>0) {

           
            if($this->input->post('employee_name_or_code')){
             $emp_name=trim($this->input->post('employee_name_or_code'));
                $this->db->where("(bf_hrm_ls_employee.EMP_NAME LIKE '%$emp_name%' or bf_hrm_ls_employee.EMP_CODE LIKE '%$emp_name%')");              
            }

            if($this->input->post('designation_name')){
                $condition['bf_hrm_ls_employee.EMP_DESIGNATION']=$this->input->post('designation_name');
            }
            if($this->input->post('em_department_name')){
                $condition['bf_hrm_ls_employee.EMP_DEPARTMENT']=$this->input->post('em_department_name');
            }
           
            if($this->input->post('from_date')){

                $condition['bf_hrm_attendance.attendance_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));

            }
            if($this->input->post('to_date')){

                $condition['bf_hrm_attendance.attendance_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }

        }




		$records=$this->db->select("SQL_CALC_FOUND_ROWS null as rows,
							bf_hrm_attendance.attendance_date,
							bf_hrm_attendance.present_status,
							bf_hrm_ls_employee.EMP_CODE,
							bf_hrm_ls_employee.EMP_NAME,
							bf_hrm_ls_employee.EMP_PHOTO as photo,
							bf_lib_designation_info.designation_name,
							bf_lib_department.department_name
						",false)
				->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_hrm_attendance.emp_id','left')
				->join('bf_lib_designation_info','bf_lib_designation_info.DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION','left')
				->join('bf_lib_department','bf_lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT','left')
				->where($condition)
				->limit($limit,$offset)
				->order_by('bf_hrm_attendance.created_at','DESC')
				->get('bf_hrm_attendance')
				->result();
				//echo $this->db->last_query();
				// echo "<pre>";
				// print_r($records);
				// exit();
				$attendance_status=$this->config->item('attendance_status');
				$data['attendance_status']=$attendance_status;

		//echo '<pre>';print_r($records);die();

		$total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/attendance/hrm/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records']=$records;
        $list_view='attendance/list';


        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }


		Template::set($data);
		Template::set('toolbar_title','Attendance List');
		Template::set('search_box', $search_box);
        Template::set('list_view', $list_view);
        Template::set_view('report_template');
		Template::render();
	}


	public function take_attendance(){

		$this->auth->restrict('Hrm.Attendance.Add');

		if(isset($_POST['save'])){
			if($insert_id=$this->attendance_save()){
				// Log the activity
				log_activity($this->current_user->id,'Attendance Created' .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_hrm_attendance');

				Template::set_message('Attendance Create Successfully', 'success');
				redirect(SITE_AREA .'/attendance/hrm/show_list');
			}else{
				Template::set_message('Attendance Create Failure', 'error');
			}
		}

		$attendance_status=$this->config->item('attendance_status');		
		$data['attendance_status']=$attendance_status;

		if(isset($_POST['date'])){
			$curr_date=date('Y-m-d',strtotime(str_replace('/','-',$_POST['date'])));
		}else{
			$curr_date=date('Y-m-d');
		}
		$data['curr_date']=$curr_date;
		
		$employees=$this->db->select("

							bf_hrm_ls_employee.EMP_ID as id,
							bf_hrm_ls_employee.EMP_CODE as emp_code,
							bf_hrm_ls_employee.EMP_Name as employee_name,
							bf_hrm_ls_employee.EMP_PHOTO as photo,
							bf_lib_designation_info.designation_name,
							bf_lib_department.department_name,
							
						")
					->join('bf_lib_designation_info','bf_lib_designation_info.	DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION','left')
					->join('bf_lib_department','bf_lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT','left')
					->get('bf_hrm_ls_employee')					
					->result();
		
		if($this->input->is_ajax_request()){
			echo $this->load->view('attendance/take_attendance', compact('employees','attendance_status','curr_date'), true);
            exit;
		}


		$data['employees']=$employees;

		Template::set($data);
		Template::set_view('attendance/take_attendance');
		Template::set('toolbar_title','Attendance Proccess');
		Template::render();

	}

	public function attendance_save(){
		//echo '<pre>';print_r($_POST);die();
		$data['attendance_date']=date('Y-m-d',strtotime(str_replace('/','-',$_POST['attendance_date'])));

		//attendance check for that day
		$exist=$this->db->where('attendance_date',$data['attendance_date'])->where('branch_id',$this->current_user->branch_id)->get('bf_hrm_attendance')->num_rows();
		if($exist>0){
			Template::set_message('Attendance proccess Completed for that day', 'error');
			redirect(SITE_AREA .'/attendance/hrm/show_list');
		}
		//check complete


		$this->db->trans_start();
			$data['create_by']=$this->current_user->id;
			$data['branch_id'] = $this->current_user->branch_id;
		for($i=0;$i<count($_POST['emp_id']);$i++){
			$data['emp_id']				= $_POST['emp_id'][$i];
			$data['present_status']		= $_POST['present_status'][$i];

			$this->db->insert('bf_hrm_attendance',$data);


			$return=$this->db->insert_id();
			if(isset($_POST['leave_type'][$data['emp_id']])){
				$emp_leave['leave_type_id']= $_POST['leave_type'][$data['emp_id']];
				$emp_leave['emp_id'] = $data['emp_id'];
				$emp_leave['leave_date'] = $data['attendance_date'];				
				$emp_leave['created_by'] = $this->current_user->id;			

				$this->db->insert('bf_hrm_employe_leave',$emp_leave);
			}
		}
		$this->db->trans_complete();
		return $return;

	}
	public function checkAttendanceByDate(){
		if($this->input->is_ajax_request()){
			$date = date('Y-m-d',strtotime(str_replace('/','-',trim($this->input->post('date')))));
			echo $this->db->where('attendance_date',$date)
							->where('branch_id',$this->current_user->branch_id)
							->get('bf_hrm_attendance')->num_rows();
		}

	}
	public function getLeaveTypeOptions($employee_id){
		$this->load->model('leave_type_model', NULL, TRUE);
		//$leave_types = $this->leave_type_model->find_all_by(['is_deleted'=>0]);

		$leave_types =$this->db->select("
						bf_hrm_leave_type_setup.*,
					")
					->where('bf_hrm_leave_type_setup.is_deleted',0)
					->get('bf_hrm_leave_type_setup')
					->result();

		foreach($leave_types as $key=>$types){
			$leave_taken=$this->db->select("
							count(bf_hrm_employe_leave.id) as total_leave_taken,
						")					
					->where('emp_id',$employee_id)
					->where('leave_type_id',$types->id)
					->where('DATE_FORMAT(leave_date,"%Y")',date('Y'))
					->get('bf_hrm_employe_leave')
					->row()->total_leave_taken;

			$leave_types[$key]->leave_taken=$leave_taken;

			$leave_types[$key]->remaining_leave=$types->total_leave_days-$leave_taken;
		}

		//echo '<pre>';print_r($leave_types);exit;

		if ($this->input->is_ajax_request()) {
            echo $this->load->view('attendance/leave_type_options', compact('leave_types','employee_id'), true);
            exit;
        } 
	}
	public function getEmployeeReamingLeave(){
		$record=$this->db->select("
							leave_type,
							total_leave_days
						")
						->where('id',$this->input->post('leave_type_id'))
						->get('bf_hrm_leave_type_setup')->row();

		$record->total_leave_taken=$this->db->select("
							count(bf_hrm_employe_leave.id) as total_leave_taken,
						")					
					->where('emp_id',$this->input->post('employee_id'))
					->where('leave_type_id',$this->input->post('leave_type_id'))
					->get('bf_hrm_employe_leave')
					->row()->total_leave_taken;
		if($record->total_leave_days>$record->total_leave_taken){
			$record->available=true;
			$record->available_days=$record->total_leave_days-$record->total_leave_taken;
		}else{
			$record->available=false;
			$record->available_days=0;
		}

		echo json_encode($record);exit();
	}

	
        
}// end controller


                     
                     
                     

