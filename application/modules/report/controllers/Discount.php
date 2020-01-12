<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Discount extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
        $this->load->config('patient/config_patient');
         //$this->auth->restrict('Report.Discount.View');
	}

    

    public function index()
    {  
        $data = array() ;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);


        $search_box['patient_id_flag'] = 1;
        $search_box['patient_name_flag'] = 1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['discount_service_list_flag'] = 1;
        $search_box['mr_no_flag'] = 1;

        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $data['sl']=$offset;
        

        $condition['alldisc.id >']=0;   
        $first_date=date('Y-m-d 00:00:00');
        $second_date=date('Y-m-d 23:59:59');

        if($this->input->is_ajax_request()){ 

            if($this->input->post('patient_id')){           
                $condition['patmast.patient_id']=trim($this->input->post('patient_id'));
            }
            if($this->input->post('mr_num')){           
                $condition['pdmast.mr_no']=trim($this->input->post('mr_num'));
            }
            if($this->input->post('patient_name')){
                $condition['patmast.patient_name']=trim($this->input->post('patient_name'));
            }

              if ($this->input->post('discount_service_id')) {
           // print_r($_POST);exit;
            $condition['alldisc.service_id'] = $this->input->post('discount_service_id');


            
            

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
      
          
           }

        $records= $this->db->select("SQL_CALC_FOUND_ROWS 
            patmast.patient_name,
            alldisc.discount_type,
            alldisc.service_id,
            patmast.patient_id,
            alldisc.created_time,
            alldisc.discount_type,
            alldisc.type,
            alldisc.source,
            disser.service_name,
            pdmast.mr_no,
            pdmast.id,
            alldisc.doctor_discount_percent,
            alldisc.discount_persent,
            IF(alldisc.type = 0,SUM(alldisc.discount_amount),0) as service_dicount,
            IF(alldisc.type = 0 and alldisc.source = 10,SUM(alldisc.discount_amount),0) as refund_dicount,
            IF(alldisc.type = 1,SUM(alldisc.discount_amount),0) as hospital_dicount,
            IF(alldisc.type = 1,SUM(alldisc.doctor_discount_amount),0) as doctor_dicount,
            IF(alldisc.type = 3,SUM(alldisc.discount_amount),0) as sd_dicount,
            IF(alldisc.type = 2,SUM(alldisc.mr_discount),0) as mr_discount",false)
             ->from('bf_all_discount as alldisc')
             ->join('bf_patient_master as patmast','patmast.id=alldisc.patient_id','left')
             ->join('bf_lib_discount_service_setup as disser','disser.id=alldisc.service_id','left')
             ->join('bf_pathology_diagnosis_master as pdmast','pdmast.id=alldisc.source_id and alldisc.service_id=1','left')
             ->join('bf_pathology_test_name as ptname','ptname.id=alldisc.sub_service_id and alldisc.service_id=1','left')
             ->where('ptname.free_status',0)
             ->where('alldisc.created_time >=', $first_date)
             ->where('alldisc.created_time <=', $second_date)
             ->where($condition)
             ->limit($limit,$offset)
             ->group_by('pdmast.mr_no')
             ->get()
             ->result(); 
        //echo "<pre>";print_r($records);exit(); 

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
      // echo $total; exit();
        $this->pager['base_url'] = site_url() . '/admin/discount/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view='discount/index';
        $data['title']="Discount Report";
        $data['discount_info']=$records;
        $data['first_date']=$first_date;
        $data['second_date']=$second_date;
        $data['list_view']=$list_view;
        $data['search_box']=$search_box;
        $data['toolbar_title']='Discount  Report';

        if ($this->input->is_ajax_request()) {

             if(isset($_POST['print'])){
                echo $this->load->view('discount/print_report', $data, true);
                exit;
            }
        echo $this->load->view($list_view, $data, true);
                                   exit;
                            }

        Template::set($data);
        Template::set_block('sub_nav', 'discount/_sub_report');

        Template::set_view('report_template');
        Template::render();
    }

    public function details($id=0)
    {   
        $data = array() ;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);


        $search_box['patient_id_flag'] = 1;
        $search_box['patient_name_flag'] = 1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['discount_service_list_flag'] = 1;
        $search_box['mr_no_flag'] = 1;

        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $data['sl']=$offset;
        

        $condition['alldisc.id >']=0;


            if($this->input->is_ajax_request()){ 


              if ($this->input->post('discount_service_id')) {
           // print_r($_POST);exit;
              $condition['alldisc.service_id'] = $this->input->post('discount_service_id');
          } 
        }

        $records= $this->db->select("SQL_CALC_FOUND_ROWS 
            patmast.patient_name,
            alldisc.discount_type,
            alldisc.service_id,
            alldisc.sub_service_id,
            patmast.patient_id,
            alldisc.source,
            alldisc.created_time,
            alldisc.discount_type,
            alldisc.type,
            disser.service_name,
            pdmast.mr_no,
            alldisc.doctor_discount_percent,
            alldisc.discount_persent,
            IF(alldisc.type = 0 and alldisc.source != 10,alldisc.discount_amount,0) as service_dicount,
            IF(alldisc.type = 0 and alldisc.source = 10,SUM(alldisc.discount_amount),0) as refund_dicount,
            IF(alldisc.type = 1,alldisc.discount_amount,0) as hospital_dicount,
            IF(alldisc.type = 1,alldisc.doctor_discount_amount,0) as doctor_dicount,
            IF(alldisc.type = 3,alldisc.discount_amount,0) as sd_dicount,
            IF(alldisc.type = 2,alldisc.mr_discount,0) as mr_discount",false)
             ->from('bf_all_discount as alldisc')
             ->join('bf_patient_master as patmast','patmast.id=alldisc.patient_id','left')
             ->join('bf_lib_discount_service_setup as disser','disser.id=alldisc.service_id','left')
             ->join('bf_pathology_diagnosis_master as pdmast','pdmast.id=alldisc.source_id and alldisc.service_id=1','left')
             ->join('bf_pathology_test_name as ptname','ptname.id=alldisc.sub_service_id and alldisc.service_id=1','left')
             ->where('ptname.free_status',0)
             ->where('pdmast.id',$id)
             ->where($condition)
             ->limit($limit,$offset)
             ->get()
             ->result();    
        //echo '<pre>';print_r($records); die();
        

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
      // echo $total; exit();
        $this->pager['base_url'] = site_url() . '/admin/discount/report/details' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        
        $list_view='discount/dis_view';
        $data['title']="Discount Report Details";
        $data['discount_info']=$records;
        $data['list_view']=$list_view;
        $data['search_box']=$search_box;
        $data['toolbar_title']='Discount  Report Details';


    if ($this->input->is_ajax_request()) {
        echo $this->load->view($list_view, $data, true);
                                   exit;
                            }


        Template::set($data);
        Template::set_view('report_template');
        Template::render();

    }

    private function getDiscountInfo($filter = array(), $c_filter= array(), $limit = null, $offset = null) {
        $like_where = array();
        if (isset($filter['from_date'])) {
            $where['d.created_time >='] = $filter['from_date']." 00:00:00";
        }
        if (isset($filter['to_date'])) {
            $where['d.created_time <='] = $filter['to_date']." 23:59:59";
        }

        if (!isset($filter['from_date']) && !isset($filter['to_date'])) {
            $where['d.created_time >='] = date('Y-m-d 00:00:00');
            $where['d.created_time <='] = date('Y-m-d 23:59:59');
        }
            $this->db
                    ->select('d.*,pm.patient_name,pm.patient_id as patient_code,ldss.service_name,pst.sub_type_name')
                    ->from('all_discount as d')
                    ->join('patient_master as pm','d.patient_id = pm.id')
                    ->join('lib_discount_service_setup as ldss','d.service_id = ldss.id')
                    ->join('lib_patient_sub_type_setup as pst', 'd.patient_sub_type_id  = pst.id','left')
                    ->where($c_filter)
                    ->where($where)
                    ->limit($limit, $offset);
        if(isset($filter['common'])){
            $common=trim($filter['common']);
            $this->db->where("(pm.patient_name like'%$common%' or pm.patient_id like'%$common%')");
                    
        }
        $result = $this->db->get()->result();
        //echo $this->db->last_query();exti;

        return $result;
    }

    private function getDiagnosisServiceName() {
        $result = $this->db
                ->select('id, test_name')
                ->get('pathology_test_name')
                ->result();
        $data =array();
        if ($result) {
            foreach ($result as $value) {
               $data[$value->id] = $value->test_name;
            }
        }
        return $data;
    }

    private function getOtherServiceName() {
        $result = $this->db
                ->where('service_id', 4)
                ->get('lib_otherservice')
                ->result();
        $data =array();
        if ($result) {
            foreach ($result as $value) {
               $data[$value->id] = $value->otherservice_name;
            }
        }
        return $data;
    }
    
    private function getCashCollection($filter = array())
    {
       
    	if (isset($filter['from_date'])) {
    		$where['tp.collection_date >='] = $filter['from_date']." 00:00:00";
    	}
    	if (isset($filter['to_date'])) {
    		$where['tp.collection_date <='] = $filter['to_date']." 23:59:59";
    	}

    	if (!isset($filter['from_date']) && !isset($filter['to_date'])) {
    		$where['tp.collection_date >='] = date('Y-m-d 00:00:00');
    		$where['tp.collection_date <='] = date('Y-m-d 23:59:59');
    	}

		$result = $this->db
				 ->select('s.id as service_id,s.service_name, IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount, IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount, tp.collection_date
				 ')
				 ->from('lib_discount_service_setup as s')
				 ->join('transaction_mst as tmst','s.id = tmst.service_id','left')
				 ->join('transaction_payment as tp','tmst.id = tp.mst_id','left')
				 ->where($where)
				 ->or_where([
				 	'tp.collection_date' => null
				 ])
				 ->group_by('s.id')
				 ->group_by('tp.transaction_type')
                 ->group_by('date(tp.collection_date)')
				 ->order_by('service_id','asc')
				 ->get()
				 ->result();
			//echo '<pre>';print_r($result);exit;
			$data_array = array();
			foreach ($result as $key => $val) {
				if (isset($data_array[$val->service_id])) {
					$data_array[$val->service_id]['paid_amount'] += $val->paid_amount;
					$data_array[$val->service_id]['due_paid_amount'] += $val->due_paid_amount;
					$data_array[$val->service_id]['refund_amount'] += $val->refund_amount;
				} else {
					$data_array[$val->service_id] = array(
						'service_id' => $val->service_id,
						'service_name' => $val->service_name,
						'paid_amount' => $val->paid_amount,
						'due_paid_amount' => $val->due_paid_amount,
						'refund_amount' => $val->refund_amount
					);
				}
			}
		return $data_array;
	}
    

}