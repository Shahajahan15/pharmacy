<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Commission_report extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->lang->load('userwise');
        $this->auth->restrict('Report.Commission.View');
    }

    
    public function index()
    {   //ini_set('max_execution_time', 0);
        $this->auth->restrict('Report.Commission.View');
        $data = array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['commission_agent_type_flag'] = 1;
        

        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        //echo "<pre>";print_r($offset);exit();
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $data['sl']=$offset;

        $condition['c.id>=']=0;
        $first_date=date('Y-m-d 00:00:00');
        $second_date=date('Y-m-d 23:59:59');
        if(count($_POST)>0){

            if($this->input->post('commission_agent_id')){
                $condition['dc.agent_id']=trim($this->input->post('commission_agent_id'));
            }


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

          //  if($this->input->post('from_date')){
          //       $condition['c.created_date >=']=date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$this->input->post('from_date'))));
          //       }

          // if($this->input->post('to_date')){
          //       $condition['c.created_date <=']=date('Y-m-d 23:59:59',strtotime(str_replace('/','-',$this->input->post('to_date'))));
          //       }

        }

        $records=$this->db
        ->select('
            SQL_CALC_FOUND_ROWS 
            dc.agent_type,
            dc.agent_id,
            c.created_date,
            SUM(c.discount_amount) AS discount_amount,
            SUM(c.commission_amount) AS commission_amount,
            SUM(pddeta.qnty) AS total_test,
            IF(dc.agent_type=1,em.EMP_NAME,0) AS ex_emp,
            IF(dc.agent_type=2,ref.ref_name,0) AS ref_nam,
            IF(dc.agent_type=3,em.EMP_NAME,0) AS in_emp',false)
        ->from('bf_commission as c')
        ->join('bf_doctor_commission as dc','dc.id=c.commission_id')
        ->join('bf_hrm_ls_employee as em','em.EMP_ID=dc.agent_id','left')
        ->join('bf_lib_reference as ref','ref.id=dc.agent_id','left')
        ->join('bf_pathology_diagnosis_master as pdmast','pdmast.id=c.source_id')
        ->join('bf_pathology_diagnosis_details as pddeta','pddeta.test_id=c.sub_service_id and pddeta.diagnosis_id =pdmast.id')
        ->join('bf_pathology_test_name as tname','tname.id=pddeta.test_id')
        ->group_by('dc.agent_type')
        ->group_by('dc.agent_id')
        ->where($condition)
        ->where('c.created_date >=', $first_date)
        ->where('c.created_date <=', $second_date)
        ->where('tname.free_status',0)
        ->limit($limit,$offset)
        ->get()
        ->result();


      /*  $sql = "
            SELECT 
                `dc`.`agent_type`,
                `dc`.`agent_id`,
                SUM(c.discount_amount) AS discount_amount,
                SUM(c.commission_amount) AS commission_amount,
                SUM(pddeta.qnty) AS total_test, 
                `dc`.`agent_id`,
                `dc`.`agent_type`,
                IF(dc.agent_type=1, `em`.`EMP_NAME`, 0) AS ex_emp,
                IF(dc.agent_type=2, `ref`.`ref_name`, 0) AS ref_nam,
                IF(dc.agent_type=3, `em`.`EMP_NAME`, 0) AS in_emp 
            FROM 
                `bf_commission` as `c` 
                JOIN `bf_doctor_commission` as `dc` ON `dc`.`id`=`c`.`commission_id` 
                JOIN `bf_pathology_diagnosis_master` as `pdmast` ON `pdmast`.`id`=`c`.`source_id` 
                JOIN `bf_pathology_diagnosis_details` as `pddeta` ON `pddeta`.`test_id`=`c`.`sub_service_id` 
                    and `pddeta`.`diagnosis_id` =`pdmast`.`id` 
                LEFT JOIN `bf_hrm_ls_employee` as `em` ON `em`.`EMP_ID`=`dc`.`agent_id` 
                LEFT JOIN `bf_lib_reference` as `ref` ON `ref`.`id`=`dc`.`agent_id`                 
                WHERE `c`.`id` >=0 
                GROUP BY `dc`.`agent_type`, `dc`.`agent_id` 
                LIMIT 25";
       */
        //->get();
        //->result();
        // $dd = $this->db->last_query($records);

        //echo '<pre>';print_r($records);exit();
       $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/commission_report/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'commission_report/com_view';
        $data['search_box'] = $search_box;
        $data['records'] = $records;
        $data['toolbar_title'] = 'Commission Report';
        $data['list_view'] = $list_view ;

    if ($this->input->is_ajax_request()) {
        echo $this->load->view('commission_report/com_view',compact('records','sl','first_date','second_date'),true);
                                   exit;
                            }
       
        Template::set_block('sub_nav', 'commission_report/_com_report');
        Template::set($data);
        Template::set_view('report_template');
        Template::render();
    }

    public function ref_com_details($agent_id,$agent_type){
        $this->auth->restrict('Report.Commission.Details');

        $data = array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $search_box['diagnosis_test_list_flag'] = 1;
        

        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $sl = $offset;
        $data['sl'] = $sl;

        $condition['c.id>=']=0;

        if ($this->input->is_ajax_request()) {

            if($this->input->post('mr_num')){  
                $condition['pdmast.mr_no']=trim($this->input->post('mr_num'));
            }

            if($this->input->post('diagnosis_test_list')){            
                $condition['ptname.id'] = $this->input->post('diagnosis_test_list');
            }

            if($this->input->post('from_date')){
                $condition['c.created_date >=']=date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$this->input->post('from_date'))));
            }

            if($this->input->post('to_date')){
                $condition['c.created_date <=']=date('Y-m-d 23:59:59',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }
        }

            



            $records=$this->db
            ->select('
            SQL_CALC_FOUND_ROWS    
            pdmast.mr_no,
            pdmast.diagnosis_date,
            ptname.test_name,
            pddeta.id as pid,
            pddeta.qnty,
            pddeta.test_price,
            c.commission_amount,
            c.discount_amount,
            IF(dc.agent_type=1,em.EMP_NAME,0) AS ex_emp,
            IF(dc.agent_type=2,ref.ref_name,0) AS ref_nam,
            IF(dc.agent_type=3,em.EMP_NAME,0) AS in_emp
            ',false)
        ->from('bf_commission  as c')
        ->join('bf_pathology_diagnosis_master as pdmast','c.source_id=pdmast.id')
        ->join('bf_pathology_diagnosis_details as pddeta','pddeta.test_id=c.sub_service_id and pddeta.diagnosis_id =pdmast.id')
        ->join('bf_pathology_test_name  as ptname','ptname.id=pddeta.test_id')
        ->join('bf_doctor_commission as dc','dc.id=c.commission_id')
        ->join('bf_hrm_ls_employee as em','em.EMP_ID=dc.agent_id','left')
        ->join('bf_lib_reference as ref','ref.id=dc.agent_id','left')
        ->join('bf_pathology_test_name as tname','tname.id=pddeta.test_id')
        ->where('dc.agent_id',$agent_id)
        ->where('dc.agent_type',$agent_type)
        ->where('tname.free_status',0)
        ->where($condition)
        ->limit($limit,$offset)
        ->get()
        ->result();

      //echo '<pre>';print_r($records);exit();
        $emp_name = array();
        foreach ($records as $key => $value) {
              if($agent_type == 1){
                    $emp_name[] = $value->ex_emp;
                }
                elseif ($agent_type == 2) {
                    $emp_name[] =  $value->ref_nam;
                }
                elseif ($agent_type == 3) {
                    $emp_name[] =  $value->in_emp;
                }
        }

             if($agent_type == 1){
                    $agent_t = 'External Doctor';
                }
                elseif ($agent_type == 2) {
                    $agent_t = 'Reference';
                }
                elseif ($agent_type == 3) {
                    $agent_t = 'Internal Doctor';
                }

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        //echo $total; exit();
        $this->pager['base_url'] = site_url() . "/admin/commission_report/report/ref_com_details/$agent_id/$agent_type" . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'commission_report/details_view';
        $data['search_box'] = $search_box;
        $data['records'] = $records;
        $data['emp_name'] = $emp_name;
        $data['agent_t'] = $agent_t;
        $data['toolbar_title'] = 'Commission Report Details';
        $data['list_view'] = $list_view ;

    if ($this->input->is_ajax_request()) {
        echo $this->load->view($list_view, $data, true);
                                   exit;
                            }


        Template::set($data);
        Template::set_block('sub_nav', 'commission_report/_com_report');
        Template::set_view('report_template');
        Template::render();
           


    }
    
   }
