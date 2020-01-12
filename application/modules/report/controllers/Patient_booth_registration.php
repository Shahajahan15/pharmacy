<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Patient_booth_registration extends Admin_Controller
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
       $this->auth->restrict('Patient.Registration.View');
		
		$this->load->config('patient/config_admission_bed_approve');
		$this->load->model('patient/patient_model');
		
		$data = array();
		$filter = array();
		$offset = (int) $this->input->get('per_page');
		$limit = (int) $this->input->post('per_page') ?: 50;
                
       if ($this->input->post('patient_id')) {
            $filter[$this->db->dbprefix.'patient_master.patient_id LIKE']= '%'.trim($this->input->post('patient_id')).'%';
        }
        if ($this->input->post('patient_name')) {
            $filter[$this->db->dbprefix.'patient_master.patient_name LIKE']= '%'.trim($this->input->post('patient_name')).'%';
        }
        if (trim($this->input->post('contact_no'))) {
            $filter[$this->db->dbprefix.'patient_master.contact_no LIKE'] = '%'.trim($this->input->post('contact_no')).'%';
        }
        if(trim($this->input->post('from_date'))){

            $filter['patient_master.registered_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
        if(trim($this->input->post('to_date'))){

            $filter['patient_master.registered_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
               
            }
        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
             $filter['patient_master.registered_date >='] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
          $filter['patient_master.registered_date <=']= $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
		$data['records'] = $this->patient_model->get_registered_patients_list($filter, $limit, $offset);
		 if (empty($data['from_date'])) {
            $row = $this->db->select('DATE(MIN(registered_date)) as min_date')->get('patient_master')->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }
         if (empty($data['to_date'])) {
            $row = $this->db->select('DATE(MAX(registered_date)) as max_date')->get('patient_master')->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }
		$data['sl'] = $offset;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
		$search_box['patient_id_flag']   = 1;
		$search_box['contact_no_flag'] = 1;
        $search_box['from_date_flag']    = 1;
        $search_box['to_date_flag']      = 1;
        $search_box['patient_name_flag'] = 1;
        $search_box['common_text_search_flag'] = 0;
		

		$this->load->library('pagination');
		$this->pager['base_url']   = $this->uri->uri_string();
		$this->pager['total_rows'] = $this->patient_model->get_registered_patients_list($filter, 0);
		$this->pager['per_page']   = $limit;
		$this->pager['page_query_string'] = true;
		$this->pagination->initialize($this->pager);
		
		$data['sexs'] = $this->config->item('sex');
		$data['list_view'] = 'patient/booth/registration_report';
		$data['search_box'] = $search_box;
		
		if ($this->input->is_ajax_request()) {
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}
		
		$title = 'Registration List';
		
		
		//Assets::add_module_js('patient','registration_booth_list.js');
		
        Template::set($data);
		Template::set('toolbar_title', 'Patient registration Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'patient/booth/_sub_registration_report_btn');
        Template::render();
    }
    
}