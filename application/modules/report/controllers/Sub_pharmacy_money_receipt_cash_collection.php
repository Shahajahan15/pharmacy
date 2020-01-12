<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sub_pharmacy_money_receipt_cash_collection extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
    
	}

    
    public function index()
    {
        $data = array();
        $filter = array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['per_page_flag'] = 0;
        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['contact_no_flag'] = 1;
        $search_box['client_name_flag'] = 1;
        $search_box['client_id_flag'] = 0;
        $search_box['patient_id_flag'] = 0;
        $search_box['mr_no_flag'] = 1;
        $condition['ppt.collection_by>=']=0;
            if(count($_POST)>0){
                 if($_POST['mr_num']){
                $condition['psm.sale_no like']='%'.trim($this->input->post('mr_num')).'%';
            }
           
                
              
            
            }
         
        $c_info=$this->db
            ->select("
          IF(ppt.type = 1, SUM(ppt.amount), 0)as total_collect,
          IF(ppt.type = 2, SUM(ppt.amount), 0)as total_due,
          IF(ppt.type = 3, SUM(ppt.amount), 0)as total_return_amount,
          (CASE WHEN ppt.customer_type=1 THEN pm.patient_name ELSE
          (CASE WHEN ppt.customer_type=2 THEN ppm.patient_name ELSE
          (CASE WHEN ppt.customer_type=3 THEN pc.customer_name ELSE
          (CASE WHEN ppt.customer_type=4||ppt.customer_type=5 THEN em.EMP_NAME ELSE 'ghjh' END) END) END) END) as name,
          (CASE WHEN ppt.customer_type=1 THEN pm.contact_no ELSE
          (CASE WHEN ppt.customer_type=2 THEN ppm.contact_no ELSE
          (CASE WHEN ppt.customer_type=3 THEN pc.customer_mobile ELSE
          '' END) END) END) as mobile,
          (CASE WHEN ppt.customer_type=1 THEN ap.admission_code ELSE
          (CASE WHEN ppt.customer_type=2 THEN pm.patient_id ELSE
          (CASE WHEN ppt.customer_type=4||ppt.customer_type=5 THEN em.EMP_CODE ELSE
          '' END) END) END) as customer_id,
          psm.sale_no,
          psm.created_date,
          psm.tot_bill,
          ppt.customer_type,
          ppt.id,
          psm.tot_normal_discount,
          psm.tot_service_discount,
          psm.tot_less_discount

          ")
                    
            ->from('bf_pharmacy_sub_payment_transaction as ppt')         
            ->join('bf_admission_patient as ap','ap.id=ppt.admission_id','left')
            ->join('bf_patient_master as pm','pm.id=ppt.patient_id','left')
            ->join('bf_pharmacy_customer as pc','pc.id=ppt.customer_id','left')
            ->join('bf_hrm_ls_employee as em','em.EMP_ID=ppt.employee_id','left')
            ->join('bf_patient_master as ppm','ppm.id=ppt.patient_id','left')
            ->join('bf_pharmacy_indoor_sales_mst as psm','psm.id=ppt.id')            
            ->group_by('ppt.type')
            ->group_by('ppt.id')
            ->order_by('psm.created_date','ASC')
            ->get()
            ->result();

   $sum_collection = [];

        foreach ($c_info as $subArray) {
            if (isset($sum_collection[$subArray->id])) {

                 $sum_collection[$subArray->id]['total_collect'] += $subArray->total_collect;
                 $sum_collection[$subArray->id]['total_return_amount'] += $subArray->total_return_amount;
                 $sum_collection[$subArray->id]['total_due'] += $subArray->total_due;
            } else {
                $sum_collection[$subArray->id] = array(
                        'total_collect' => $subArray->total_collect,
                        'total_return_amount' => $subArray->total_return_amount,
                        'total_due' => $subArray->total_due,
                        'sale_no'=>$subArray->sale_no,
                        'created_date'=>$subArray->created_date,                      
                        'tot_bill'=>$subArray->tot_bill,
                        'customer_type'=>$subArray->customer_type,
                        'name'=>$subArray->name,
                        'customer_id'=>$subArray->customer_id,
                        'mobile'=>$subArray->mobile,
                        'tot_normal_discount'=>$subArray->tot_normal_discount,
                        'tot_service_discount'=>$subArray->tot_service_discount,
                        'tot_less_discount'=>$subArray->tot_less_discount,

                    );
            }

        }
        $data['sum_collection']=$sum_collection;
          $list_view='sub_pharmacy_money_receipt_cash_collection/index';
        if($this->input->is_ajax_request()){
          echo $this->load->view('sub_pharmacy_money_receipt_cash_collection/index', $data,true);
          exit();
        }
      
  
        Template::set("toolbar_title", "Sub Pharmacy Money Receipt Wise Collection List");
        Template::set('list_view',$list_view);
        Template::set($data);
        Template::set('search_box',$search_box);
        Template::set_view('report_template');
		    Template::render();
    }
    
   }