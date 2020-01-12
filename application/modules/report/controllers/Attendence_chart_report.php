<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Attendence_chart_report extends Admin_Controller
{
	   public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
        $this->load->config('hrm/config_employee');
        Assets::add_module_js('report','attendence_chart');
    }

     public function attendence_chart()
    {
        $list_view='attendence/attendence_chart_list';
        
        Template::set('toolbar_title', 'Attendence Chart Report');
        Template::set('list_view', $list_view);
        Template::set_view('report_template');
        Template::render();
   
        
     
    }

    public function proccess(){

         	if(isset($_POST['month'])){
			$month=$_POST['month'];
		}else{
			return false;
		}


        $condition["DATE_FORMAT(ad.attendance_date,'%Y-%m')"]=$month;
        
	
        $records = $this->db->select(
            'SQL_CALC_FOUND_ROWS null as rows,
            em.EMP_ID,
            em.EMP_CODE,
            em.EMP_NAME,
            de.designation_name,
            ad.attendance_date,
            ad.present_status
            ',false)
        ->from('bf_hrm_attendance as ad')
        ->join("bf_hrm_ls_employee as em","em.EMP_ID=ad.emp_id")
        ->join('bf_lib_designation_info as de','de.DESIGNATION_ID=em.EMP_DESIGNATION')  
        ->where($condition)
        ->order_by('ad.attendance_date','ASC')
        ->get()
        ->result();
        
    
        $emp_present_status=[];
        foreach ($records as $key => $status) {
            $emp_present_status[$status->EMP_ID][(int)date('d',strtotime($status->attendance_date))]=$status->present_status;
        }
      //echo "<pre>"; print_r($emp_present_status);exit();
        $data['emp_present_status']=$emp_present_status;
    
        $this_month=$month."-01";
        $data['this_month']=$this_month;

        $employees=$this->db->select('EMP_ID as id, EMP_CODE as code, EMP_NAME as name')->get('bf_hrm_ls_employee')->result();
        $data['employees']=$employees;
        $data['attendance_status']=$this->config->item('attendance_status');


     
   

         //$data['project']=$this->db->where('id',1)->get('bf_lib_project_compay_setup')->row();
         $data['records']=$records;
        
      
        	if ($this->input->is_ajax_request()) {
            echo $this->load->view('attendence/attendence_chart_view',$data, true);
            exit;
        } 

		Template::set($data);
		Template::set_view('attendence/attendence_chart_view');
		Template::set('toolbar_title','Employee Attendance Chart');
		Template::render();



    }

    

}