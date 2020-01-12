<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctor_wise_collection extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        $this->load->config('outdoor/outdoor');
        Assets::add_module_js('report', 'jquery.rowspanizer.js');
        /*Assets::add_module_js('report', 'jquery.rowspanizer.js');*/
        $this->auth->restrict('Report.DoctorCollection.View');
	}

    
    public function index()
    {
    	$data = array();
    	$filter = array();
    	$search_box = $this->searchpanel->getSearchBox($this->current_user);
    	$search_box['common_text_search_flag'] = 0;
    	$search_box['per_page_flag'] = 0;
    	if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
    	
    	$data['doctor_ticket'] = $this->getDoctorWiseTicketList($filter);
    	$data['total_fee'] = $this->getDoctorTicketTotalCollection($filter);
    	$data['sex'] 			= $this->config->item('sex');
		$data['ticket_type'] 	= $this->config->item('ticket_type');
		$data['appointment_type'] 	= $this->config->item('appointment_type');
    	//echo '<pre>';print_r($data['ticket_type']);exit;
    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'doctor_wise_collection/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "Doctor Wise Collection List");
        Template::set($data);
        Template::set_block('sub_nav', 'doctor_wise_collection/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }
    
    private function getDoctorWiseTicketList($filter = array())
    {
		if (!isset($filter['from_date'])) {
			$filter['from_date'] = date('Y-m-d');
		}    	
		if (!isset($filter['to_date'])) {
			$filter['to_date'] = date('Y-m-d');
		}    	

    	if (isset($filter['from_date'])) {
    		$where['t.created_time >='] = $filter['from_date']." 00:00:00";
    	}
    	if (isset($filter['to_date'])) {
    		$where['t.created_time <='] = $filter['to_date']." 23:59:59";
    	}

    /*	$result = $this->db
						->select('
								t.doctor_id,
								pm.sex,
								t.ticket_type,
								t.appointment_type,
								SUM(t.ticket_fee) as amount,
								IF(pm.sex = 1,count(pm.sex),0) as male,
								IF(pm.sex = 2,count(pm.sex),0) as female,
								IF(pm.sex = 3,count(pm.sex),0) as common
							')
						->from('outdoor_patient_ticket as t')
						->join('patient_master as pm','pm.id = t.patient_id')
						->group_by('t.doctor_id')
						->group_by('t.ticket_type')
						->group_by('t.appointment_type')
						->group_by('pm.sex')
						->get()
						->result();
		*/
    	/*$query = "
    			SELECT 
    				t.doctor_id,
    				t.ticket_type,
    				t.appointment_type,
					IF(DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',SUM(t.ticket_fee),0) as amount,
					IF(pm.sex = 1 AND DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',count(pm.sex),0) as male,
					IF(pm.sex = 2 AND DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',count(pm.sex),0) as female,
					IF(pm.sex = 3 AND DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',count(pm.sex),0) as common,
					emp.EMP_NAME as doctor_name,
					t.created_time
				FROM 
					bf_outdoor_patient_ticket as t
				JOIN 
					bf_patient_master pm ON pm.id = t.patient_id
				JOIN 
					bf_hrm_ls_employee emp ON emp.EMP_ID = t.doctor_id
				GROUP BY 
					t.doctor_id,
					t.ticket_type,
					t.appointment_type,
					pm.sex
    	";*/

    	$query = "
    			SELECT 
    				u.employee_id as doctor_id,
    				t.ticket_type,
    				t.appointment_type,
					IF(DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',SUM(t.ticket_fee),0) as amount,
					IF(pm.sex = 1 AND DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',count(pm.sex),0) as male,
					IF(pm.sex = 2 AND DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',count(pm.sex),0) as female,
					IF(pm.sex = 3 AND DATE(t.created_time) BETWEEN '{$filter['from_date']}' AND '{$filter['to_date']}',count(pm.sex),0) as common,
					emp.EMP_NAME as doctor_name,
					t.created_time
				FROM 
					bf_users as u
				LEFT JOIN
					bf_outdoor_patient_ticket as t ON t.doctor_id = u.employee_id
				LEFT JOIN 
					bf_patient_master pm ON pm.id = t.patient_id
				JOIN 
					bf_hrm_ls_employee emp ON emp.EMP_ID = u.employee_id
				WHERE 
					u.role_id = 8
				GROUP BY 
					t.doctor_id,
					t.ticket_type,
					t.appointment_type,
					pm.sex,
					t.created_time
				ORDER BY
					u.employee_id asc
    	";

    	$result = $this->db->query($query)->result();
		 
		  $data_arr = array();
		  $d_arr = array();
		  foreach ($result as $key => $row) {
		  	$i = $row->doctor_id.".".$row->ticket_type.".".$row->appointment_type;
		  	$d = $row->doctor_id;
		  	if (isset($data_arr[$i])) {
				$data_arr[$i]['male'] += (int)$row->male;
				$data_arr[$i]['female'] += (int)$row->female;
				$data_arr[$i]['common'] += (int)$row->common;
				$data_arr[$i]['amount'] += (int)$row->amount;
			} else {
				$data_arr[$i]['doctor_id'] = (int)$row->doctor_id;
				$data_arr[$i]['ticket_type'] = (int)$row->ticket_type;
				$data_arr[$i]['appointment_type'] = (int)$row->appointment_type;
				$data_arr[$i]['male'] = (int)$row->male;
				$data_arr[$i]['female'] = (int)$row->female;
				$data_arr[$i]['common'] = (int)$row->common;
				$data_arr[$i]['amount'] = (int)$row->amount;
				$data_arr[$i]['doctor_name'] = $row->doctor_name;
			}
			
		  	
		  }
		  //echo '<pre>';print_r($data_arr);exit;
		return $data_arr;
	}
	
	public function getDoctorTicketTotalCollection()
	{
		if (!isset($filter['from_date'])) {
			$filter['from_date'] = date('Y-m-d');
		}    	
		if (!isset($filter['to_date'])) {
			$filter['to_date'] = date('Y-m-d');
		}    	

    	if (isset($filter['from_date'])) {
    		$where['outdoor_patient_ticket.created_time >='] = $filter['from_date']." 00:00:00";
    	}
    	if (isset($filter['to_date'])) {
    		$where['outdoor_patient_ticket.created_time <='] = $filter['to_date']." 23:59:59";
    	}

		$result = $this->db
					 ->select('doctor_id,SUM(ticket_fee) as amount,count(pmst.patient_id) as mfc_count')
					 ->join('patient_master as pmst', 'pmst.id = outdoor_patient_ticket.patient_id')
					 ->where($where)
					  ->group_by('doctor_id')
					 ->get('outdoor_patient_ticket')
					 ->result();
		//echo '<pre>';print_r($result);exit;
		$d_arr = array();
		if ($result) {
			foreach ($result as $row) {
				$d_arr[$row->doctor_id] = $row->amount;
			}
		}
		return $d_arr;
	}
    

}