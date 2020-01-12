<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dept_serial_report extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
         $this->auth->restrict('Report.TestService.View');
	}


    public function index(){

        $data = array();
        $filter = array();
        $where=array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
       
        $search_box['per_page_flag'] = 0;
        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['patient_name_flag']=1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;     
        $search_box['serial_id_flag'] = 1;     
        $search_box['department_name_list_flag'] = 1;

        $condition=[];
        $first_date=date('Y-m-d 00:00:00');
        $second_date=date('Y-m-d 23:59:59');
            if(count($_POST)>0){   
         
        if($this->input->post('from_date')){
                $first_date=date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$this->input->post('from_date'))));
                }
        else{
               $first_date=date("Y-m-d 00:00:00"); 
                }
        if($this->input->post('to_date')){
                $second_date=date('Y-m-d 23:59:59',strtotime(str_replace('/','-',$this->input->post('to_date'))));
                }
        else{
                $second_date=date("Y-m-d 23:59:59");
                }

        if (trim($this->input->post('serial_id'))){
            $condition['ad.id Like'] = '%'.trim($this->input->post('serial_id')).'%';

       }
        if (trim($this->input->post('patient_name'))) {
            $condition['ap.patient_name LIKE'] = '%'.trim($this->input->post('patient_name')).'%';
        }
      if ($this->input->post('department_name_list')) {
            $condition['ad.department_id'] = $this->input->post('department_name_list');
        }


    }
      
        $records=$this->db
        ->select('SQL_CALC_FOUND_ROWS 
            ap.patient_name,
            ap.contact_no,
            ad.schedule_date,
            ad.id,
            dd.department_name,
            ad.department_id


            ',false)
        ->from('bf_appointment_patients as ap')      
        ->join('bf_appointment_details as ad','ad.id=ap.appointment_id')
        ->join('bf_doctor_department as dd','dd.id=ad.department_id')
        ->where($condition)
        //->where('ad.schedule_date >=', $first_date)
      //  ->where('ad.schedule_date <=', $second_date)
        ->limit($limit, $offset)
        ->order_by('ad.id','asc')
        ->get()
        ->result();
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/dept_serial_report/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records'] = $records;

         if ($this->input->is_ajax_request()) {
            echo $this->load->view('dept_serial_report/dept_serial_report', compact('records','sl','first_date','second_date'),true);
            exit();        
        }
              
        $list_view='dept_serial_report/dept_serial_report';
        $data['first_date']=$first_date;
        $data['second_date']=$second_date;
        Template::set($data);
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set('records',$records);
        Template::set("toolbar_title", "Dept Serial Report");

        Template::set_block('sub_nav', 'dept_serial_report/_sub_report');

        Template::set_view('report_template');

        Template::render();

    }
    
   










}