<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_loan extends Admin_Controller {
	
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
       
		Template::set_block('sub_nav', 'employee_loan/_sub_nav');		
	} // end construct 


	public function show_list(){
		$this->auth->restrict('Hrm.Employee.Loan.View');

		// search with pagination

 	    $this->load->library('pagination');
		$offset=(int)$this->input->get('per_page');
		$limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
		$sl=$offset;
		$data['sl']=$sl;

		$search_box=$this->searchpanel->getSearchBox($this->current_user);
		$search_box['employee_search_flag']=1;
		$search_box['designation_name_flag']=1;
		$search_box['em_department_name_flag']=1;
		$search_box['from_amount_flag']=1;
		$search_box['to_amount_flag']=1;

		$condition['bf_hrm_employee_loan.created_by >=']=0;

        if(count($_POST)>0){

            if($this->input->post('employee_name_or_code')){
                $emp_name=trim($this->input->post('employee_name_or_code'));
                $this->db->where("bf_hrm_ls_employee.EMP_NAME LIKE '%$emp_name%' or bf_hrm_ls_employee.EMP_CODE LIKE '%$emp_name%'");
            }
            if($this->input->post('designation_name')){
                $condition['bf_hrm_ls_employee.EMP_DESIGNATION']=$this->input->post('designation_name');
            }if($this->input->post('em_department_name')){
                $condition['bf_hrm_ls_employee.EMP_DEPARTMENT']=$this->input->post('em_department_name');
            }
            if($this->input->post('from_date')){

                $condition['bf_hrm_employee_loan.date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));

            }
            if($this->input->post('to_date')){

                $condition['bf_hrm_employee_loan.date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }
            if($this->input->post('from_amount')){

                $condition['bf_hrm_employee_loan.loan_amount >=']=$this->input->post('from_amount');
            }
            if($this->input->post('to_amount')){

                $condition['bf_hrm_employee_loan.loan_amount <=']=$this->input->post('to_amount');
            }
         print_r($condition);exit();
        }



		$records=$this->db->select("SQL_CALC_FOUND_ROWS null as rows,
							bf_hrm_employee_loan.*,
							bf_hrm_ls_employee.EMP_CODE as emp_code,
							bf_hrm_ls_employee.EMP_Name as employee_name,
							bf_hrm_ls_employee.EMP_PHOTO as photo,
							bf_lib_designation_info.designation_name,
							bf_lib_department.department_name
						",false)
					->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_hrm_employee_loan.emp_id','left')
					->join('bf_lib_designation_info','bf_lib_designation_info.DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION','left')
					->join('bf_lib_department','bf_lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT','left')
					->where($condition)
					->limit($limit,$offset)
					->order_by('id','desc')
					->get('bf_hrm_employee_loan')
					->result();

		

		$data['records']=$records;
		$total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/employee_loan/hrm/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view="employee_loan/list";

		if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }
		Template::set($data);
		Template::set('toolbar_title','Employee Loan List');
		Template::set('search_box',$search_box);
        Template::set('list_view', $list_view);
        Template::set_view('report_template');
		Template::render();
	}


	public function create(){

		$this->auth->restrict('Hrm.Employee.Loan.Add');
		if(isset($_POST['save'])){
			if($insert_id=$this->save()){

				// Log the activity
				log_activity($this->current_user->id,'Employee loan Created' .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_hrm_employee_loan');

				Template::set_message('Employee Loan Create Successfully', 'success');
				redirect(SITE_AREA .'/employee_loan/hrm/show_list');
			}else{
				Template::set_message('Employee Loan Create Failure', 'error');
			}
		}

		Template::set_view('employee_loan/create');
		Template::set('toolbar_title','Employee Loan Create');
		Template::render();

	}

	public function save(){
		//echo '<pre>';print_r($_POST);die();

		$this->db->trans_start();

			$data['date']						=date('Y-m-d',strtotime(str_replace('/','-',$_POST['date'])));

			$data['emp_id']						= $_POST['emp_id'];
			$data['loan_amount']				= $_POST['loan_amount'];
			$data['per_month_repaid_amount']	= $_POST['per_month_repaid_amount'];
			$data['total_month']				= $_POST['payment_month'];
			$data['created_by']					= $this->current_user->id;

			$this->db->insert('bf_hrm_employee_loan',$data);
			$return=$this->db->insert_id();

			//loan paid breck down
			$month=$this->input->post('payment_month');
			$paid_date=date('Y-m-1',strtotime(str_replace('/','-',$_POST['lp_starting_date'])));

            for ($x = 1; $x <= $month; $x++) {

            	$data1['paid_date']			= $paid_date;
				$data1['emp_id']			= $_POST['emp_id'];
				$data1['paid_amount']		= $_POST['per_month_repaid_amount'];

				$paid_date=date('Y-m-1', strtotime("+1 month", strtotime($paid_date)));
				$this->db->insert('bf_hrm_employee_loan_status',$data1);
			} 

			$this->db->trans_complete();

			return $return;

	}
	
        
}// end controller


                     
                     
                     

