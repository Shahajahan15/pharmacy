<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Attendence_report extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
        $this->load->config('hrm/config_employee');
    }


    public function attendence_report($offset = 0)
    {
        $this->auth->restrict('Report.employee_attendence.early_summery');

   
        $search_box = $this->searchpanel->getSearchBox($this->current_user);

        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['employee_search_flag'] = 1;
        $search_box['designation_name_flag'] = 1;
        $search_box['branch_name_flag'] = 1;

   

       $conditions['ad.id >=']=0;
       $first_date=date('Y-01-01');
       $second_date=date('Y-12-31');
       $branch_id=$this->current_user->branch_id;

        
        if(count($_POST)>0) {
             if($this->input->post('branch_name')){           
                $branch_id=$this->input->post('branch_name');
            }

         if($this->input->post('from_date')){
                $first_date=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
            }
            else{
               $first_date=date("Y-m-d"); 
            }
            if($this->input->post('to_date')){
                $second_date=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }else{
                $second_date=date("Y-m-d");
            }
              if($this->input->post('employee_name_or_code')){
             $emp_name=trim($this->input->post('employee_name_or_code'));
                $this->db->where("(em.EMP_NAME LIKE '%$emp_name%' or em.EMP_CODE LIKE '%$emp_name%')");              
            }

           if($this->input->post('designation_name')){
                $conditions['em.EMP_DESIGNATION']=$this->input->post('designation_name');
            }

            

        }




          
         
        // $branch_id=$this->current_user->branch_id;
       
         $records = $this->db->select(
                 'SQL_CALC_FOUND_ROWS null as rows,
                  em.EMP_ID,
                  em.EMP_CODE,
                  em.EMP_NAME,
                  de.designation_name,
                  SUM(IF(ad.present_status=1,1,0)) AS presnet,
                  SUM(IF(ad.present_status=2,1,0)) AS absent,
                  SUM(IF(ad.present_status=3,1,0)) AS liave
                  ',false)
                  ->from('bf_hrm_attendance as ad')
                  ->from('bf_hrm_ls_employee as em')
                  ->from('bf_lib_designation_info as de')
                  ->where('ad.emp_id=em.EMP_ID')
                  ->where('em.EMP_DESIGNATION=de.DESIGNATION_ID')   
                  ->where($conditions)
                  ->where('ad.attendance_date >=', $first_date)
                  ->where('ad.attendance_date <=', $second_date)
                  //->like('ad.attendance_date', date('Y')) 
                  ->group_by('ad.emp_id')
                  //->limit($limit, $offset)
                  ->get()
                  ->result();


        
     


     
   
        // $data['title']="Purchase Order Report";
        //$data['project']=$this->db->where('id',1)->get('bf_lib_project_compay_setup')->row();
       
        $data['records']=$records;
        $data['first_date']=$first_date;
        $data['second_date']=$second_date;
   
        
        $list_view='attendence/attendence_report_list';


        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }


        Template::set($data);
        Template::set('toolbar_title', 'Attendence Report');
        Template::set('search_box', $search_box);

        Template::set('list_view', $list_view);
        Template::set_view('report_template');
        Template::render();
    }

}