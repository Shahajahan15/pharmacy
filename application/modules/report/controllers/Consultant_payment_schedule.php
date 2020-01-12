<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Consultant_payment_schedule extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
         $this->auth->restrict('Report.BillAssesmentSheet.View');
	}

    
    public function index($consultant_id)
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
        //print_r($search_box);
        $data['records']=$this->db
        ->select('COUNT(acr.id)as total_visit,SUM(acr.per_patient_price)as total_price,acr.period_from,acr.period_to,emp.ref_name,emp.ref_quali,patient_master.patient_name,patient_master.patient_id')
        ->from('admission_consultant_round as acr')
        ->join('admission_patient','acr.admission_id = admission_patient.id')
        ->join('patient_master','patient_master.id = admission_patient.patient_id')
       // ->where('patient_master.admission_status',1)

        ->join('bf_lib_reference as emp', 'acr.consultant_id = emp.id')
        ->where('acr.consultant_id',$consultant_id)
        ->group_by('acr.admission_id')
        ->get()
        ->result();
        //echo '<pre>';print_r($data['records']);exit();
        $data['search_box'] = $search_box;
        $data['list_view'] = 'consultant_payment/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('consultant_payment/index', $data, true);
            exit;
        }
        Template::set("toolbar_title", "Consultant Payment Schedule List");
        Template::set($data);

       	Template::set_block('sub_nav', 'consultant_payment/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }
   


   public function report_list()
   {
        $records=$this->db
        ->select('admission_consultant_round.*,emp.ref_name,emp.ref_quali,patient_master.patient_name,COUNT(patient_name)as total,SUM(per_patient_price)as total_price')
        ->join('bf_lib_reference as emp', 'admission_consultant_round.consultant_id = emp.id','LEFT')
     
        ->join('patient_master','patient_master.id = admission_consultant_round.admission_id')
        ->group_by('consultant_id')
        ->order_by('total','asc')

        ->get('admission_consultant_round')
        ->result();
        //echo "<pre>";print_r($records);exit();
        $data['list_view'] = 'consultant_payment/list';
        Template::set($data);
        Template::set('records',$records);
        Template::set("toolbar_title", "Consultant Round List");
        Template::set_view('report_template');
        Template::render();
   } 

    public function details($id)
    {
       $this->index($id);
    }
   
}