<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctor_wise_test_collection extends Admin_Controller {

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
      $this->load->library('pagination');
      $offset = (int)$this->input->get('per_page');
        $search_box['per_page_flag'] = 0;
      $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
      $sl=$offset;
      $data['sl']=$sl;
     // $search_box['per_page'] = $limit;

      $search_box = $this->searchpanel->getSearchBox($this->current_user);
    	$search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Doctor Name';

    
    	if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
    	
    	$data['records'] = $this->test($limit,$offset);
    	$data['sex'] 			= $this->config->item('sex');
    	//echo '<pre>';print_r($data['ticket_type']);exit;
    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'doctor_wise_collection/test/test_collection';
      $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
      $this->pager['base_url'] = site_url() . '/admin/doctor_wise_test_collection/report/index' . '?';
      $this->pager['total_rows'] = $total;
      $this->pager['per_page'] = $limit;
      $this->pager['page_query_string'] = TRUE;
      $this->pagination->initialize($this->pager); 
      

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "Doctor Wise Collection(Test) List");
        Template::set($data);
        Template::set_block('sub_nav', 'doctor_wise_collection/test/_sub_report');
        Template::set_view('report_template');
		    Template::render();
	
    }
    
   

    public function test($limit=null, $offset=null)
    {
      //echo 'hi';


        $con='';
        $cons='';

        if ($this->input->is_ajax_request()) {


        $doctor_name = trim($this->input->post('common_text_search',true));
        if($doctor_name){
          $con .= " AND lr.ref_name LIKE '%$doctor_name%'";
        }
        $from_date = custom_date_format(trim($this->input->post('from_date')));
        $to_date = custom_date_format(trim($this->input->post('to_date')));

        if (isset($from_date) && $from_date) {
          $con .=" AND pdm.created_date >= '$from_date 00:00:00'";
          $cons .=" AND jpdm.created_date >= '$from_date 00:00:00'";

        } else {
           $con .=" AND pdm.created_date >= '".date('Y-m-d 00:00:00')."'";
           $cons .=" AND jpdm.created_date >= '".date('Y-m-d 00:00:00')."'";
        }
        if (isset($to_date) && $to_date) {
          $con .=" AND pdm.created_date <= '$to_date 23:59:59'";
          $cons .=" AND jpdm.created_date <= '$to_date 23:59:59'";
        } else {
          $con .=" AND pdm.created_date <= '".date('Y-m-d 23:59:59')."'";
          $cons .=" AND jpdm.created_date <= '".date('Y-m-d 23:59:59')."'";
        }
       
      } 
      else {
        $con .=" AND pdm.created_date >= '".date('Y-m-d 00:00:00')."'";
        $con .=" AND pdm.created_date <= '".date('Y-m-d 23:59:59')."'";

        $cons .=" AND jpdm.created_date >= '".date('Y-m-d 00:00:00')."'";
        $cons .=" AND jpdm.created_date <= '".date('Y-m-d 23:59:59')."'";
      }





        $sql = "
            SELECT
            SQL_CALC_FOUND_ROWS
                lr.ref_name,
                pdm.reference_id,
                pdm.created_date,
                'Reference' AS doctor_type,
                pm.sex,
                COUNT(IF(pm.sex = 1, pm.sex, NULL)) AS male,
                COUNT(IF(pm.sex = 2, pm.sex, NULL)) AS female,
                COUNT(IF(pm.sex = 3, pm.sex, NULL)) AS common,
                (
                    COUNT(IF(pm.sex = 1, pm.sex, NULL))
                    +
                    COUNT(IF(pm.sex = 2, pm.sex, NULL))
                    +
                    COUNT(IF(pm.sex = 3, pm.sex, NULL))
                ) AS total_patient,
                pddtable.test_amount
            FROM 
                bf_pathology_diagnosis_master AS pdm
            JOIN 
                bf_lib_reference AS lr
                ON lr.id = pdm.reference_id
            JOIN 
                bf_patient_master AS pm 
                ON pm.id = pdm.patient_id
            JOIN
            (
                SELECT 
                    jpdm.created_date,
                    jpdm.reference_id,
                    ROUND(SUM(pdd.test_price)) AS test_amount
                FROM 
                    bf_pathology_diagnosis_master AS jpdm
                JOIN 
                    bf_pathology_diagnosis_details AS pdd
                     ON pdd.diagnosis_id = jpdm.id
                     WHERE 
               jpdm.id > 0
              {$cons} 
                GROUP BY 
                    jpdm.reference_id

            ) AS pddtable 
                ON pddtable.reference_id = pdm.reference_id

            WHERE 
                pdm.patient_type = 3
             {$con}    
            
            GROUP BY 
                pdm.reference_id
            ORDER BY 
                total_patient DESC
            LIMIT {$offset},{$limit}    
                ";
        $record = $this->db->query($sql)->result();
        //echo '<pre>';print_r($record);exit();
        //echo 'hi';
        return $record;
        
    }
    

    

}