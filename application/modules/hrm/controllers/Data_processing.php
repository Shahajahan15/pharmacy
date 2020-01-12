<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* 

	Data Processing[roster policy, data backup from machine, attendance]

*/

class Data_processing extends Admin_Controller 
{
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() 
	{		
        parent::__construct();    
        Template::set_block('sub_nav', 'data_processing/_sub_nav');		
        $this->load->model('roster_policy_processing_model', NULL, true);
		//$this->lang->load('common');
       // $this->load->library('hrm/GetEmployeePolicyTagging',NULL,true);  
     //   $this->load->config('config_policy_tagging');
        Assets::add_module_js('hrm', 'data_processing.js');
	} // end construct 

	/*         roster processing list     */

	public function roster_data_processing() {
		$this->auth->restrict('HRM.RosterDP.Add');
		$data = [];
		$this->load->library('pagination');

        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user); 
		//echo '<pre>';print_r($search_box);exit;
		
        $search_box['employee_name_flag'] = 1;
        $search_box['designation_list_flag'] = 1;
        $search_box['department_test_list_flag'] = 1;
        $search_box['empType_list_flag'] = 1;
        $search_box['empType_list_flag'] = 1;
        $search_box['employee_code_flag'] = 1;
        $search_box['shift_list_flag'] = 1;
        $search_box['roster_list_flag'] = 1;

		$data['records'] = $this->roster_policy_processing_model->getRosterPolicyProcessingList($limit, $offset);

		$total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
       
        $this->pager['base_url'] = site_url() . '/admin/data_processing/hrm/roster_data_processing' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

		$list_view = 'data_processing/roster/list';
		$data['toolbar_title'] = 'Roster Policy Data Processing List';
		$data['search_box'] = $search_box;

		if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }

		Template::set($data);
        Template::set('list_view', $list_view);       
		Template::set_view('report_template');
        Template::render();
	}

	public function roster_data_processing_save() {
		$this->auth->restrict('HRM.RosterDP.Add');
		$start_date = custom_date_format($this->input->post('start_date', true));
		$end_date = custom_date_format($this->input->post('end_date', true));

		$emp_id = $this->input->post('emp_id', true);

		$r_process = $this->roster_policy_processing_model->getRosterPolicyProcessing($start_date, $end_date, $emp_id);
		if (!$r_process) {
			echo json_encode(array('success' => false,'message' => "No Roster Policy Select Of Employee"));
			exit;
		}
		$this->db->trans_begin();

		/*      delete   */

		$this->db->where('DATE >=', $start_date);
		$this->db->where('DATE <=', $end_date);
		if ($emp_id) {
			$this->db->where('EMP_ID', $emp_id);
		}
		$this->db->delete('hrm_ls_roster_policy_processing');

		foreach ($r_process as $key => $val) {
			$data = [
				'DATE' => $val['date'],
				'EMP_ID' => $val['emp_id'],
				'POLICY_TAG_ID' => $val['policy_tag_id'],
				'ROSTER_ID' => $val['roster_id'],
				'SHIFT_ID' => $val['shift_id'],
				'COUNT_DAY' => $val['count_day'],
				'CHANGE_DAY' => $val['after_change_day'],
				'CREATED_BY' => $this->current_user->id,
			];
			$this->db->insert('hrm_ls_roster_policy_processing', $data);
		} 

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
				echo json_encode(array('success' => false,'message' => "Error"));
			} else {
				$this->db->trans_commit();
				echo json_encode(array('success' => true,'message' => 'Successfully done'));
			}
		exit;

	}

	/*           roster re-process show         */

	public function roster_data_re_process()
	{
		$add = [];
		$process_id = $this->input->post('process_id', true);
		$data['record'] = $this->roster_policy_processing_model->getRosterPolicyProcessInfo($process_id);
		$this->load->view('data_processing/roster/re_add',$data);
	}

	public function machine_data_processing() {
		$this->auth->restrict('HRM.MachineDP.Add');
	}

	public function attendance_data_processing() {
		$this->auth->restrict('HRM.AttendanceDP.Add');
		$data = [];
		$this->load->library('pagination');

        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user); 
		//echo '<pre>';print_r($search_box);exit;
		
        $search_box['employee_name_flag'] = 1;
        $search_box['designation_list_flag'] = 1;
        $search_box['department_test_list_flag'] = 1;
        $search_box['empType_list_flag'] = 1;
        $search_box['empType_list_flag'] = 1;
        $search_box['employee_code_flag'] = 1;
        $search_box['shift_list_flag'] = 1;
        $search_box['roster_list_flag'] = 1;

		$data['records'] = $this->roster_policy_processing_model->getRosterPolicyProcessingList($limit, $offset);

		$total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
       
        $this->pager['base_url'] = site_url() . '/admin/data_processing/hrm/attendance_data_processing' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

		$list_view = 'data_processing/attendance/list';
		$data['toolbar_title'] = 'Attendance Data Processing List';
		$data['search_box'] = $search_box;

		if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }

		Template::set($data);
        Template::set('list_view', $list_view);       
		Template::set_view('report_template');
        Template::render();
	}

}// end controller


                     
                     
                     

