<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Patient_booth_serial extends Admin_Controller
{
 
    public function __construct()
    {
        parent::__construct();
        
        $this->lang->load('common');
		$this->lang->load('patient/booth_serial');
		$this->load->model('doctor/doctor_model');
    }
    
    
    public function index($offset = 0)
    {
        $this->auth->restrict('Report.PatientBoothSerial.List');
		
		$this->load->model('patient/appointment_details_model');
		$this->load->config('patient/config_patient');
		$this->load->config('patient/config_admission_bed_approve');
		// $this->load->config('patient/config_admission_form');
		
        $data = array();
		$filter = array();
		$offset = (int) $this->input->get('per_page');
		$limit = (int) $this->input->post('per_page') ?: 50;
		
		$search_box = $this->searchpanel->getSearchBox($this->current_user);
		$search_box['serial_no_flag'] = 1;
		$search_box['token_id_flag'] = 1;
		$search_box['patient_id_flag'] = 1;

		$search_box['from_date_flag'] = 1;
		$search_box['to_date_flag'] = 1;
		$search_box['patient_name_flag'] = 1;
		$search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Doctor Name';
        $search_box['contact_no_flag'] = 1;

        if (trim($this->input->post('serial_no'))) {
            $filter['app.serial_no LIKE'] = '%'.trim($this->input->post('serial_no')).'%';
        }
        if (trim($this->input->post('patient_name'))) {
            $filter['apa.patient_name LIKE'] = '%'.trim($this->input->post('patient_name')).'%';
        }
        if (trim($this->input->post('patient_id'))) {
            $filter['pa.patient_id LIKE'] = '%'.trim($this->input->post('patient_id')).'%';
        }
        if (trim($this->input->post('contact_no'))) {
            $filter['apa.contact_no LIKE'] = '%'.trim($this->input->post('contact_no')).'%';
        }
        if (trim($this->input->post('common_text_search'))) {
            $filter['d.doctor_full_name LIKE']= '%'.trim($this->input->post('common_text_search')).'%';
        }  
        if (trim($this->input->post('token_id'))) {
            $filter['app.token_no LIKE'] = '%'.trim($this->input->post('token_id')).'%';
        }
        if(trim($this->input->post('from_date'))){

            $filter['app.schedule_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
             if(trim($this->input->post('to_date'))){

            $filter['app.schedule_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
               
            }
        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
             $filter['app.schedule_date >='] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
       if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
          $filter['app.schedule_date <=']= $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
             
              
		$data['records'] = $this->appointment_details_model->get_serialed_patients_list($filter, $limit, $offset);
		  if (empty($data['from_date'])) {
            $row = $this->db->select('DATE(MIN(schedule_date)) as min_date')->get('appointment_details')->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }
         if (empty($data['to_date'])) {
            $row = $this->db->select('DATE(MAX(schedule_date)) as max_date')->get('appointment_details')->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }
		$data['sl'] = $offset;
		$this->load->library('pagination');
		$this->pager['base_url'] = site_url('admin/patient_booth_serial/report/index');
		$this->pager['total_rows'] = $this->appointment_details_model->get_serialed_patients_list($filter, 0);
		$this->pager['per_page'] = $limit;
		$this->pagination->initialize($this->pager);
		
		$data['sexs'] = $this->config->item('sex');
		$data['serial_sources'] = $this->config->item('serial_source');
		$data['blood_groups'] = $this->config->item('blood_group');
		$data['search_box'] = $search_box;
		$data['list_view'] = 'patient/booth/serials_report';
		
		if ($this->input->is_ajax_request()) {
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}
        
        Template::set($data);
		Template::set('toolbar_title', 'Patient Serial Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'patient/booth/_sub_report_btn');
        Template::render();
    }
    
}