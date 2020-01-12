<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Diagnosis_cash_collection extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
	}

    

    public function index()
    {
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['patient_name_flag'] = 1;
        $search_box['patient_id_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $search_box['to_date_flag'] = 1;     
        $search_box['by_date_flag'] = 0;


        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $data['sl']=$offset; 

        $where['tmst.service_id'] = 1;
        $first_date=date('Y-m-d 00:00:00');
        $second_date=date('Y-m-d 23:59:59');
         
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
       
        //echo '<pre>';print_r($where);die();

        if(trim($this->input->post('mr_num'))){
                $mr_no=trim($this->input->post('mr_num'));
                $conditionLike="tmst.mr_no Like '%".$mr_no."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('patient_id'))){
                $patient_id=trim($this->input->post('patient_id'));
                $conditionLike="pmt.patient_id Like '%".$patient_id."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('patient_name'))){
                $patient_name=trim($this->input->post('patient_name'));
                $conditionLike="pmt.patient_name Like '%".$patient_name."%'";
                $this->db->where($conditionLike);

        }
        if(trim($this->input->post('contact_no'))){
                $contact_no=trim($this->input->post('contact_no'));
                $conditionLike="pmt.contact_no Like '%".$contact_no."%'";
                $this->db->where($conditionLike);

        }

        $records = $this->db
                    ->select('
                        SQL_CALC_FOUND_ROWS
                        tmst.patient_id,
                        tmst.id,
                        tmst.source_id as source_id,
                        service_id,
                        tot_bill,
                        transaction_type,
                        tp.collection_by,
                        tp.collection_date,
                        IF(tp.transaction_type = 1,SUM(tp.amount),0) as paid_amount,
                         IF(tp.transaction_type = 2,SUM(tp.amount),0) as due_paid_amount,
                         IF(tp.transaction_type = 3,SUM(tp.amount),0) as refund_amount,
                         pmt.patient_id as patient_code,
                         pmt.patient_name,
                         dmast.less_discount_amount,
                         dmast.discount_amount,
                         tmst.mr_no,
                         tmst.refund_bill_amount'
                        ,false)
                    ->from('transaction_mst as tmst')
                    ->join('transaction_payment as tp','tp.mst_id = tmst.id')
                    ->join('patient_master as pmt','tmst.patient_id = pmt.id')
                    ->join('bf_pathology_diagnosis_master as dmast','dmast.id = tmst.source_id AND tmst.service_id=1')
                    ->where('tp.collection_date >=', $first_date)
                    ->where('tp.collection_date <=', $second_date)
                    ->where($where)
                    //->group_by('tp.transaction_type')
                    ->group_by('tp.mst_id')
                    //->order_by('tp.collection_date','ASC')
                    ->limit($limit, $offset)
                    ->get()
                    ->result();
        

        //echo "<pre>";print_r($records);exit();
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

        $this->pager['base_url'] = site_url() . '/admin/diagnosis_cash_collection/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);


        $list_view='money_receipt_wise_cash_collection/mr_collection';
        $data['title']="Diagnosis Cash Collection List";
        $data['mr_wise_collection']=$records;
        $data['first_date']=$first_date;
        $data['second_date']=$second_date;
        $data['list_view']=$list_view;
        $data['search_box']=$search_box;
        $data['toolbar_title']='Diagnosis  Report';

        


    

        if ($this->input->is_ajax_request()) {

            echo $this->load->view($list_view, $data, true);
            exit;
        }
        
  
        Template::set($data);
        Template::set_view('report_template');
        Template::render();
    }


    
 
    

}