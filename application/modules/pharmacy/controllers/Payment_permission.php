<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * purchase requistion approve controller
 */
class Payment_permission extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct() 
	{
        parent::__construct();               
        $this->load->model('purchase_order_received_model', NULL, true);       
        $this->lang->load('common');
        $this->lang->load('payment_permission');
        Assets::add_module_js('pharmacy','payment_permission');
    }

    /* Write from here */

    public function show_list()
	{
		$data = array();
        $this->auth->restrict('Pharmacy.Payment.Per.View');
        Template::set('toolbar_title', lang("pur_requisition_approve_title_view"));
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Bill No';
        $search_box['bill_no_flag']=1;
        $search_box['supplier_name_flag']=1;
       // $search_box['pharmacy_supplier_list_flag']=1;
        $search_box['from_date_flag'] =0;
        $search_box['to_date_flag'] = 0;
        $condition['rm.status>=']=0;
        if(count($_POST)>0){
         
              if($this->input->post('bill_no')){
                $condition['rm.bill_no like']='%'.trim($this->input->post('bill_no')).'%';
            } 
          
            if($this->input->post('pharmacy_supplier_id')){
                $condition['s.id']=$this->input->post('pharmacy_supplier_id');
            } 
            if($this->input->post('supplier_name')){
                $condition['s.supplier_name']=$this->input->post('supplier_name');
            }     
            }
        $records = $this->db->select("
              SQL_CALC_FOUND_ROWS
                                    rm.*,
                                    s.supplier_name as supplier_name,
                                    SUM(rd.received_qnty) as qnty,
                                    SUM(rd.total_price) as price
                                ",false)
                    ->from('bf_pharmacy_purchase_order_received_mst as rm')
                    ->join('bf_pharmacy_supplier as s','rm.supplier_id = s.id','left')
                    ->join('bf_pharmacy_purchase_order_received_dtls as rd','rm.id=rd.master_id')
                    ->where('rm.status',0)
                    ->where($condition)
                    ->group_by('rd.master_id')
                    ->limit($limit, $offset)
                    ->get()
                    ->result();
                       $data['records']=$records;  
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/payment_permission/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('payment_permission/list', compact('records','sl'), true);
            exit;
        } 
		//echo '<pre>';print_r($data['records']);exit;
        Template::set($data);
        //Template::set_view('payment_permission/list');
        $list_view='payment_permission/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }
    
    public function approve(){

    	$this->auth->restrict('Pharmacy.Payment.Per.Add');
    	$data = array();
    	$approve_id = $this->input->post('approve');
    	$count = count($approve_id);
    	
    	for ($i = 0; $i < $count; $i++) {
    		$data['approve_by'] = $this->current_user->id;
    		$data['approve_date'] = date('Y-m-d');
    		$data['status'] = 1;
    		$this->db->where('id',$approve_id[$i]);
			$return = $this->db->update('pharmacy_purchase_order_received_mst',$data);

        }

        if ($return) {
		    echo json_encode(array('success' => true,'message' => 'Successfully done'));
        } else {
		    echo json_encode(array('success' => false,'message' => $this->purchase_order_received_model->error));
		}

		exit;
	}
}
