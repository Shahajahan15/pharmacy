<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Refund extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
        $this->load->config('patient/config_patient');
         $this->auth->restrict('Report.Discount.View');
	}

    
    public function index($offset = 0)
    {
    	$data = array();
        $filter = array();
    	$c_filter = array();
        $offset = (int) $this->input->get('per_page');
        $limit = (int) $this->input->post('per_page') ?: 5;

    	$search_box = $this->searchpanel->getSearchBox($this->current_user);
        //echo '<pre>';print_r($search_box);exit;
    	$search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Patient Id/Name';
        $search_box['discount_service_list_flag'] = 1;

        
        $search_box['discount_service_list']= array_intersect_key($search_box['discount_service_list'], array('1' => '', '3' => '', '4' => ''));

    	if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
         if ($this->input->is_ajax_request()) {
            if ($this->input->post('discount_service_id')) {
                $c_filter['d.service_id'] = $this->input->post('discount_service_id');
            }
         }
        if($this->input->post('common_text_search')){
            $filter['common']=$this->input->post('common_text_search');
        }
    	//print_r($search_box);
    	$data['discount_info'] = $this->getDiscountInfo($filter, $c_filter, $limit, $offset);
        $data['refund_info'] = $this->getRefundInfo($filter);
        $data['other_service_name'] = $this->getOtherServiceName();
        $data['test_name'] = $this->getDiagnosisServiceName();
        $data['patient_type'] = $this->config->item('discount_patient');
    	//echo '<pre>';print_r($data['patient_type']);exit;
        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/refund/report/index');
        $this->pager['total_rows'] = count($this->getDiscountInfo($filter, $c_filter, 0));;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

    	$data['search_box'] = $search_box;
    	$data['list_view'] = 'refund/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
       // echo '<pre>';print_r($data['discount_info']);exit;

        Template::set("toolbar_title", "Refund List");
        Template::set($data);
       	Template::set_block('sub_nav', 'refund/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }

    /*         refund information       */

    private function getRefundInfo($filter = array()) {
        if (isset($filter['from_date'])) {
            $where['rcm.refunded_at >='] = $filter['from_date']." 00:00:00";
        }
        if (isset($filter['to_date'])) {
            $where['rcm.refunded_at <='] = $filter['to_date']." 23:59:59";
        }

        if (!isset($filter['from_date']) && !isset($filter['to_date'])) {
            $where['rcm.refunded_at >='] = date('Y-m-d 00:00:00');
            $where['rcm.refunded_at <='] = date('Y-m-d 23:59:59');
        }
        $result = $this->db
                    ->select('rcm.mr_no as rmr_no, rcm.service_id,ss.service_name,pm.patient_id as patient_code,pm.patient_name,(rcm.total_refund_amount - rcm.less_discount) as total_refund,date(rcm.refunded_at) as refund_date,aemp.EMP_NAME as approve_by, rcd.details_id, (rcd.amount - rcd.discount) as sub_amount, remp.EMP_NAME as refund_by')
                    ->from('refund_collection_details as rcd')
                    ->join('refund_collection_master as rcm', 'rcd.refund_col_master_id = rcm.id and1')
                    ->join('lib_discount_service_setup as ss','rcm.service_id = ss.id')
                    ->join('patient_master as pm','rcm.patient_id = pm.id')
                    ->join('users as au', 'au.id = rcm.created_by','left')
                    ->join('hrm_ls_employee as aemp', 'au.employee_id = aemp.EMP_ID','left')
                    ->join('users as ru', 'ru.id = rcm.refunded_by','left')
                    ->join('hrm_ls_employee as remp', 'ru.employee_id = remp.EMP_ID','left')
                    ->where($where)
                    ->get()
                    ->result();
                //echo '<pre>';print_r($result); exit;
        return $result;
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