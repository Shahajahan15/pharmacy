<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * purchase requistion approve controller
 */
class Purchase_requisition_approve extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct() 
	{
        parent::__construct();          
        $this->load->model('purchase_requisition_model', NULL, true);       
        $this->load->model('purchase_requisition_dtls_model', NULL, true);       
        $this->lang->load('common');
        $this->lang->load('purchase_requisition_approve');
        Assets::add_module_js('pharmacy','purchase_requisition_approve');
    }

    /* Write from here */

    public function show_list()
	{
        $this->auth->restrict('Pharmacy.PR.Approve.View');
        Template::set('toolbar_title', lang("pur_requisition_approve_title_view"));

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.PR.Approve.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->purchase_requisition_model->delete($pid);
            }

            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('shelf_delete_success'), 'success');
            } else {
                Template::set_message(lang('shelf_delete_failure') . $this->purchase_requisition_model->error, 'error');
            }
        }
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['requisition_no_flag'] = 1;
        $search_box['ticket_no_flag'] = 0;
        $search_box['sex_list_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['product_name_flag']=0;
        $search_box['pharmacy_product_list_flag']=1;
        $condition['prm.status >=']=0;

        if(count($_POST)>0){

           if($_POST['pharmacy_category_name']){
                $condition['p.category_id like']='%'.$this->input->post('pharmacy_category_name').'%';
            }
           
            if($this->input->post('pharmacy_product_id')){
                $condition['p.id like']='%'.trim($this->input->post('pharmacy_product_id')).'%';
            }
            if($this->input->post('from_date')){

                $condition['prm.requisition_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['prm.requisition_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }    
              if($this->input->post('requisition_no')){
                $condition['prm.requisition_no like']='%'.trim($this->input->post('requisition_no')).'%';
            }       

        }
        $records = $this->db->select("
                                SQL_CALC_FOUND_ROWS 
                                prm.requisition_no,
                                prm.requisition_date,
                                prm.id as mster_id,
                                prd.id,
                                prd.status,
                                prd.product_id,
                                prd.req_qnty,
                                p.purchase_price,
                                prd.approve_qnty,
                                prd.issue_qnty,
                                p.product_name,
                                p.id as product_code,
                                s.quantity_level,
                                c.category_name
                                ",false)
                    ->from('bf_pharmacy_purchase_requision_mst as prm')
                    ->join('bf_pharmacy_purchase_requision_dtls as prd','prd.pur_requision_id=prm.id and prd.status = 1')
                    ->join('bf_pharmacy_products as p','p.id=prd.product_id')
                    ->join('bf_pharmacy_category as c','c.id=p.category_id')
                    ->join('bf_pharmacy_stock s','s.product_id=prd.product_id','left')
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->get()
                    ->result();
                    
        $records=(array)$records;    
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/purchase_requisition_approve/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
               
            for($i=0;$i<count($records);$i++){
                /*
                    @Last apprve quantiy from purchse requisition dtls table
                */
                $last_approve_qnty=$this->db
                                        ->where('product_id',$records[$i]->product_id)
                                        ->where('status >',1)
                                        ->order_by('id','desc')
                                        ->get('bf_pharmacy_purchase_requision_dtls')
                                        ->row();
                if($last_approve_qnty){
                    $records[$i]->last_approved_qnty=$last_approve_qnty->approve_qnty;
                }else{
                    $records[$i]->last_approved_qnty=0;
                }

                /*
                    @Last purchase price add from purchse order dtls table
                */

                $last_purchase_price_unit=$this->db
                                        ->where('product_id',$records[$i]->product_id)
                                        ->order_by('id','desc')
                                        ->get('bf_pharmacy_purchase_order_dtls')
                                        ->row();
                if($last_purchase_price_unit){
                    $records[$i]->last_purchase_price_unit=$last_purchase_price_unit->order_unit_price;
                }else{
                    $records[$i]->last_purchase_price_unit=$records[$i]->purchase_price;
                }
            }
            $data['records']=(object)$records;

              if ($this->input->is_ajax_request()) {
            echo $this->load->view('purchase_requisition_approve/list', compact('records','sl'), true);
            exit;
        } 

        Template::set($data);
        $list_view='purchase_requisition_approve/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }
    
    public function purchaseRequisitionApprove(){
        $this->auth->restrict('Pharmacy.PR.Approve.Add');

    	$data = array();
    	$approve_id = $this->input->post('approve');
    	$count = count($approve_id);

    	for ($i = 0; $i < $count; $i++) {

    		$data['approve_qnty'] = $this->input->post('approve_qnty')[$i];
    		$data['requisition_approve_by'] = $this->current_user->id;
    		$data['requisition_approve_date'] = date('Y-m-d');
    		$data['status'] = 2;
    		$this->db->where('id',$approve_id[$i]);
            $this->db->where('status',1);
			$return =$this->db->update('pharmacy_purchase_requision_dtls',$data);
        }
        if (isset($return) && $return) {
		    echo json_encode(array('success' => true,'message' => 'Successfully done'));
        } else {
		    echo json_encode(array('success' => false,'message' =>'Approved Failed'));
		}

		exit;
	}
}
