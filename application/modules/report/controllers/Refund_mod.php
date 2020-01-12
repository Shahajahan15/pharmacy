<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Refund_mod extends Admin_controller{
  
      public function _construct(){
      	parent::__construct();
        $this->lang->load('userwise');
     
       }

       public function index(){
            $this->auth->restrict('Report.Refund.View');
          	$data = array();
            $search_box = $this->searchpanel->getSearchBox($this->current_user);
            $search_box['patient_id_flag'] = 1;
            $search_box['patient_name_flag'] = 1;
            $search_box['mr_no_flag'] = 1;
            $search_box['from_date_flag'] = 1;
            $search_box['to_date_flag'] = 1;

            $this->load->library('pagination');
            $offset=$this->input->get('per_page');
            $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;

            $data['sl']=$offset;



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


            if($this->input->post('patient_id')){
                $condition['pm.patient_id']=trim($this->input->post('patient_id'));
                }

            if($this->input->post('patient_name')){
                $condition['pm.patient_name']=trim($this->input->post('patient_name'));
                }

            if($this->input->post('mr_num')){
                $condition['rcm.mr_no']=trim($this->input->post('mr_num'));
                }    

           }



       	$records = $this->db
       	           ->select('SQL_CALC_FOUND_ROWS
                        rcm.mr_no,

       	           	rcm.id,
       	           	pm.patient_id,
       	           	pm.patient_name,
       	           	rcm.refunded_at,
       	           	ss.service_name,
       	           	rcm.total_refund_amount,
       	           	rcm.payable_receivable_amount,
       	           	rcm.refund_type',false)
       	           ->from('refund_collection_master as rcm')
       	           ->join('lib_discount_service_setup as ss','rcm.service_id = ss.id')
       	           ->join('patient_master as pm','rcm.patient_id = pm.id')
                   ->where('rcm.refunded_at >=',$first_date)
                   ->where('rcm.refunded_at <=', $second_date)
       	           ->where($condition)

                   ->limit($limit,$offset)
       	           ->get()
       	           ->result();
       	// echo "<pre>";print_r($records);exit();
     $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
      // echo $total; exit();
     $this->pager['base_url'] = site_url() . '/admin/refund_mod/report/index' . '?';
     $this->pager['total_rows'] = $total;
     $this->pager['per_page'] = $limit;
     $this->pager['page_query_string'] = TRUE;
     $this->pagination->initialize($this->pager);
                   
      $list_view = 'refund/refund_view'; 
      $data['records'] = $records;
      $data['list_view'] = $list_view;
      $data['search_box'] = $search_box;
      $data['toolbar_title'] = 'Refund Report';
      $data['first_date']=$first_date;
        $data['second_date']=$second_date;
      if ($this->input->is_ajax_request()) {
         echo $this->load->view($list_view, $data, true);
         exit();
      }
       	
      Template::set($data);  
      Template::set_view('report_template');
      Template::render();         


       }

       public function refund_details($rcm_id){
            $this->auth->restrict('Report.Refund.Details');
       	
       	$data = array();
       	
       	$records = $this->db
       	           ->select('
                        rcm.total_refund_amount,
                        pm.patient_name,
                        rcm.service_id,
                        IF(rcm.service_id=1,ptname.test_name,0) AS test_name,
                        rcd.amount,
       	           	rcd.qnty,
       	           	rcd.discount,
       	           	rcd.mr_discount')
       	           ->from(' bf_refund_collection_details as rcd')
       	           ->join('refund_collection_master as rcm','rcm.id = rcd.refund_col_master_id')
                       ->join('patient_master as pm','rcm.patient_id = pm.id')
                       ->join('bf_pathology_diagnosis_details as pddeta','pddeta.id = rcd.details_id','left')
                       ->join('bf_pathology_test_name  as ptname','ptname.id=pddeta.test_id','left')
       	           ->where('rcm.id',$rcm_id)
       	           ->get()
       	           ->result();
/*
                        select rcm.total_refund_amount,
                        pm.patient_name,
                        rcm.service_id,
                        IF(rcm.service_id=1,ptname.test_name,0) AS test_name,
                        rcd.amount,
                        rcd.qnty,
                        rcd.discount,
                        rcd.mr_discount 
                        FROM bf_refund_collection_details as rcd
                        JOIN bf_refund_collection_master as rcm 
                        ON rcm.id = rcd.refund_col_master_id
                        JOIN bf_patient_master as pm 
                        ON rcm.patient_id = pm.id
                        LEFT JOIN bf_pathology_diagnosis_details as pddeta 
                        ON pddeta.id = rcd.details_id
                        LEFT JOIN bf_pathology_test_name  as ptname
                        ON ptname.id=pddeta.test_id
                        WHERE rcm.id = 1; 



*/
       	//echo "<pre>";print_r($records);exit();
            $patient_name = array();           
            foreach ($records as $key => $value) {
                          $patient_name[] = $value->patient_name;
                       } 


                             
       	$list_view = 'refund/refund_details'; 
       	$data['records'] = $records;
            $data['patient_name'] = $patient_name;
       	$data['list_view'] = $list_view;
       	$data['toolbar_title'] = 'Refund Details Report';
       	
       	Template::set($data);  
       	Template::set_view('report_template');
       	Template::render();  

       }	



}