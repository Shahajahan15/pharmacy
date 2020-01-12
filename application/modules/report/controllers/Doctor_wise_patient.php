//<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctor_wise_patient extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
         $this->auth->restrict('Report.BillAssesmentSheet.View');
	}




   public function index()
   {
     $data = array();
        $filter = array();
        $where=array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['per_page_flag'] = 0;
        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag']=1;
        $search_box['common_text_search_label']='Doctor Name';
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;     
       
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

        if($_POST['common_text_search']){
                 $condition['lib_reference.ref_name like']='%'.trim($this->input->post('common_text_search')).'%';

                     }




}





        $records=$this->db
        ->select('SQL_CALC_FOUND_ROWS
            COUNT(bf_patient_master.patient_name)as total,
            bf_admission_patient.admission_date,
             lib_reference.ref_name',false)


        ->join('lib_reference', 'lib_reference.id = bf_admission_patient.reference_doctor','LEFT')
       
        ->join('patient_master','patient_master.id = bf_admission_patient.patient_id')
        ->group_by('reference_doctor')
        ->where('reference_doctor!=',NULL)
        ->where('reference_doctor!=',0)
        ->where('status',2)
        ->where('bf_admission_patient.admission_date >=', $first_date)
        ->where('bf_admission_patient.admission_date <=', $second_date)
        ->where($condition)
        ->limit($limit,$offset)
        
        ->get('bf_admission_patient')
        ->result();
       // echo $this->db->last_query();


        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/doctor_wise_patient/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records'] = $records;

         if ($this->input->is_ajax_request()) {
            echo $this->load->view('doctor_wise_patient/list', compact('records','sl','first_date','second_date'),true);
            exit();        
        }
              
        $list_view='doctor_wise_patient/list';
        $data['first_date']=$first_date;
        $data['second_date']=$second_date;
        Template::set("toolbar_title", "Doctor wise patient List");
        Template::set($data);
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_block('sub_nav', 'doctor_wise_patient/_sub_report');
        Template::set_view('report_template');
        Template::render();








        
   } 

    
   
}