<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * purchase requistion approve controller
 */
class Payment extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct()
	{
        parent::__construct();
        $this->load->model('purchase_order_received_model', NULL, true);
        $this->load->model('purchase_order_received_dtls_model', NULL, true);
        $this->load->model('payment_mst_model', NULL, true);
        $this->load->model('payment_dtls_model', NULL, true);
        $this->lang->load('common');
        $this->lang->load('payment');
        Assets::add_module_js('pharmacy','payment');
    }

    /* Write from here */

    public function show_list()
	{
		$data = array();
        $this->auth->restrict('Pharmacy.Payment.View');
        Template::set('toolbar_title', lang("pharmacy_payment_pending_list"));
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

         $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Bill No';

        $search_box['pharmacy_product_list_flag']=0;
        $search_box['pharmacy_supplier_list_flag']=1;
        $condition['rm.status>=']=0;
            if(count($_POST)>0){

                 if($this->input->post('from_date')){

                $condition['rm.received_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));

            }

            if($this->input->post('to_date')){

                $condition['rm.received_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }
              if($this->input->post('common_text_search')){
                $condition['rm.bill_no like']='%'.trim($this->input->post('common_text_search')).'%';
            }
            if($this->input->post('pharmacy_supplier_name')){
                $condition['s.id']=$this->input->post('pharmacy_supplier_name');
            }
            }
        $records= $this->db->select("
                                SQL_CALC_FOUND_ROWS
                                rm.*,
                                (SELECT SUM(pd.paid) from bf_pharmacy_payment_dtls as pd where pd.master_id=pm.id) as paid_price,
                                s.supplier_name,
                                SUM(rd.total_price) as total_price
                                ",false)
                    ->from('bf_pharmacy_purchase_order_received_mst as rm')
                    ->join('bf_pharmacy_supplier as s','rm.supplier_id = s.id','left')
                    ->join('bf_pharmacy_purchase_order_received_dtls as rd','rm.id=rd.master_id')
                    ->join('bf_pharmacy_payment_mst as pm','pm.receive_order_id=rm.id','left')
                    //->join('bf_pharmacy_payment_dtls as pd','pd.master_id=pm.id','left')
                    ->where('rm.status !=',2)
                    ->where($condition)
                    ->group_by('rd.master_id')
                	->order_by('rm.status','desc')
                    ->limit($limit, $offset)
                    ->get()
                    ->result();

                    //echo "<pre>";print_r($records);exit();
                     
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/payment/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('payment/list', compact('records','sl'), true);
            exit;
        }
       // echo '<pre>';print_r($records);exit;
        $data['records']=$records;
        Template::set($data);
        $list_view='payment/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

    public function payment(){
        $this->auth->restrict('Pharmacy.Payment.Add');
    	$data = array();
		$id = $this->input->post('id',true);
		$data['pr_mst'] = $this->purchase_order_received_model->find($id);
        //echo "<pre>";print_r($data);exit();

		$data['total_bill'] = $this->db
								->select('SUM(dtls.total_price) as total_bill')
								->from('pharmacy_purchase_order_received_mst as mst')
								->join('pharmacy_purchase_order_received_dtls as dtls','mst.id = dtls.master_id')
								->where('mst.id',$id)
								->group_by('dtls.master_id')
								->get()
								->row();
        $data['sup_name'] = $this->db
                                ->select('s.supplier_name,s.id')
                                ->from('pharmacy_purchase_order_received_mst as mst')
                                ->join('bf_pharmacy_supplier as s','mst.supplier_id = s.id','left')
                                ->where('mst.id',$id)
                                ->get()
                                ->row();                        

		$data['pr_dtls'] = $this->purchase_order_received_dtls_model->find_all_by(array('master_id' => $id));
		$data['p_paid'] = $this->db
								->select('SUM(dtls.paid) as p_price')
								->from('pharmacy_payment_mst as mst')
								->join('pharmacy_payment_dtls as dtls','mst.id = dtls.master_id')
								->where('mst.receive_order_id',$id)
								->group_by('dtls.master_id')
								->get()
								->row();
		$data['p_paid'] = (!$data['p_paid']) ? 0 : $data['p_paid']->p_price;
		$data['branch_list'] = $this->db
							->select('lib_branch_info.*,lib_bank_info.bank_name')
							->join('lib_bank_info','lib_bank_info.id = lib_branch_info.bank_id')
							->get('lib_branch_info')
							->result();
		$this->load->view('payment/add',$data);
	}

    public function save(){
    	$this->auth->restrict('Pharmacy.Payment.Add');
    	$data = array();
    	$ddata = array();
        $udata = array();

    	$data['receive_order_id'] = $this->input->post('recieve_order_id');
        $data['supplier_id'] = $this->input->post('supplier_id');
    	$data['total_bill'] = $this->input->post('total_bill');
    	$due = $this->input->post('due');
    	        /*     check receive order id */
    	$check = $this->payment_mst_model->find_by(array('receive_order_id' => $data['receive_order_id']));
    	$this->db->trans_begin();
    	if (!$check) {
			$ddata['master_id'] = $this->payment_mst_model->insert($data);
		} else {
			$ddata['master_id'] = $check->id;
		}
        /*     purchase received master update */
        $current_paid = $this->input->post('payment');
        $previous_paid = $this->input->post('recieve_mst_paid');
        $udata['paid'] = $current_paid + $previous_paid;
        $this->db->where('id',$data['receive_order_id']);
        $this->db->update('pharmacy_purchase_order_received_mst',$udata);


		/*     payment detls insert */
    	$ddata['payment_type'] = $this->input->post('payment_type');
    	$ddata['paid'] = $this->input->post('payment');
    	$ddata['branch_id'] = ($ddata['payment_type'] != 1) ? $this->input->post('branch_id') : 0;
    	$ddata['check_date'] = ($ddata['payment_type'] != 1) ? custom_date_format($this->input->post('check_date')) : NULL;
    	$ddata['created_by'] = $this->current_user->id;
    	$this->payment_dtls_model->insert($ddata);

    	/*      status change   */
    	if (!$due) {
			$update['status'] = 2;
			$this->db->where('id',$data['receive_order_id']);
			$this->db->update('pharmacy_purchase_order_received_mst',$update);
		}
		$js_status = (!$due) ? true : false;
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
				echo json_encode(array('success' => false,'message' => $this->payment_mst_model->error));
			} else {
				$this->db->trans_commit();
				echo json_encode(array('success' => true,'status' => $js_status,'message' => 'Successfully done'));
			}
		exit;
	}

}
