<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test_service extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
         $this->auth->restrict('Report.TestService.View');
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
        $search_box['test_name_flag']=1;
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

        if($_POST['test_name']){
                 $condition['bf_pathology_test_name.test_name like']='%'.trim($this->input->post('test_name')).'%';

                     }




    }
        

            //print_r($where);exit();

         
    	$records=$this->db
        ->select('SQL_CALC_FOUND_ROWS 
            bf_pathology_diagnosis_master.*,
            bf_pathology_diagnosis_details.test_date,
         
            SUM(bf_pathology_diagnosis_details.discount*bf_pathology_diagnosis_details.qnty) as discount,
            SUM(bf_pathology_diagnosis_details.d_discount_amount*bf_pathology_diagnosis_details.qnty) as d_discount_amount,              
            bf_pathology_test_name.test_name,
            SUM(bf_pathology_diagnosis_details.qnty) as total,
           
            bf_pathology_test_name.test_taka'
            ,false)
        ->join('bf_pathology_diagnosis_details','bf_pathology_diagnosis_details.diagnosis_id=bf_pathology_diagnosis_master.id')
        ->join('bf_pathology_test_name','bf_pathology_test_name.id=bf_pathology_diagnosis_details.test_id')
        //->where($where)
         ->where('bf_pathology_diagnosis_details.test_date >=', $first_date)
         ->where('bf_pathology_diagnosis_details.test_date <=', $second_date)
         //->where('bf_pathology_test_name.free_status',0)
        ->where($condition)
        
        ->group_by('test_id')
        ->order_by('bf_pathology_diagnosis_details.test_date','desc')
        ->limit($limit, $offset)
        ->get('bf_pathology_diagnosis_master')
        ->result();
//echo '<pre>';print_r($records);exit();
    	$total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/test_service/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records'] = $records;

         if ($this->input->is_ajax_request()) {
            echo $this->load->view('test_service/index', compact('records','sl','first_date','second_date'),true);
            exit();        
        }
              
        $list_view='test_service/index';
        $data['first_date']=$first_date;
        $data['second_date']=$second_date;
        Template::set("toolbar_title", "Test Service List");
        Template::set($data);
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
       	Template::set_block('sub_nav', 'test_service/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }
    
   











}