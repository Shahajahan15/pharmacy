<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Money Receipt Discount controller
 */
class Money_receipt_discount extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------

	 public function __construct()
	{
		parent::__construct();
		$this->load->model('discount/money_receipt_discount_model');

		Assets::add_module_js('discount', 'money_receipt_discount.js');
		Template::set_block('sub_nav','money_receipt_discount/_sub_nav');


	}

	public function show_list() {
		$this->auth->restrict('Discount.MoneyReceipt.View');
		$data = [];
		$data['records'] = $this->money_receipt_discount_model->getMoneyReceiptDiscount(12);
		//echo '<pre>';print_r($data);exit;
		Template::set('toolbar_title', 'Money Receipt Discount List');
		Template::set($data);
		Template::set_view('money_receipt_discount/list');		
		Template::render();
	}

	public function pending_list() {
		$this->auth->restrict('Discount.MoneyReceipt.Approve');
		$data = [];
		$data['records'] = $this->money_receipt_discount_model->getMoneyReceiptDiscount(0);
		//echo '<pre>';print_r($data);exit;
		Template::set('toolbar_title', 'Money Receipt Discount List(Pending)');
		Template::set($data);
		Template::set_view('money_receipt_discount/pending_list');		
		Template::render();
	}

	public function collection_list() {
		$this->auth->restrict('Discount.MoneyReceipt.Collection');
		$data = [];
		$data['records'] = $this->money_receipt_discount_model->getMoneyReceiptDiscount(1);
		//echo '<pre>';print_r($data);exit;
		Template::set('toolbar_title', 'Money Receipt Discount Collection List');
		Template::set($data);
		Template::set_view('money_receipt_discount/collection_list');		
		Template::render();
	}

	public function add() {
		$this->auth->restrict('Discount.MoneyReceipt.Add');
		$data = [];
        $data['services'] = $this->db->where('id', 1)->where('refundable', 1)->order_by('service_name')->get('bf_lib_discount_service_setup')->result();
		Template::set('toolbar_title', 'Money Receipt Discount');
		Template::set($data);
		Template::set_view('money_receipt_discount/add');		
		Template::render();
	}

	public function show(){
		$this->auth->restrict('Discount.MoneyReceipt.Add');
		$data = [];
		$data['service_id'] = $this->input->post('service_id', true);
		$mr_no = $this->input->post('mr_no', true);
		 if (trim($data['service_id']) == '' || trim($mr_no) == '') {
		 	echo json_encode(array('success' => false,'message' => "Error"));
		 }
		$data['result'] = $this->money_receipt_discount_model->search($data['service_id'], $mr_no);
		//echo '<pre>';print_r($data['result']);exit;
		$page=$this->load->view('money_receipt_discount/show',$data,true);
		if ($data['result']) {
			echo json_encode(array('success' => true,'page' => $page,'message' => 'Successfully done'));
		} else {
			echo json_encode(array('success' => false,'page' => '','message' => "Error"));
		}
		exit;
	}

	public function save() {
		$this->auth->restrict('Discount.MoneyReceipt.Add');
		$mr_discount = $this->input->post('mr_discount', true);
		$mr_no = $this->input->post('mr_no', true);
		$patient_id = $this->input->post('patient_id', true);
		$service_id = $this->input->post('service_id', true);
		$net_bill = $this->input->post('net_bill', true);
		//print_r($service_id);exit;
		$this->db->trans_begin();
		$insert_id=$this->money_receipt_discount_model->save($mr_discount, $mr_no, $service_id,$net_bill, $this->current_user->id);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('success' => false,'message' => "Error"));
		} else {
			$this->db->trans_commit();

			$print = $this->sale_print($insert_id,$mr_no,$patient_id);
			echo json_encode(array('success' => true,'print' => $print,'message' => 'Successfully done'));
		}
		exit();
	}

	public function approved($id, $mr_approve_discount = 0){
    	$this->auth->restrict('Discount.MoneyReceipt.Approve');
    	if(!$this->mrDiscountCheck($id, 0)) {
    		echo json_encode(['status'=>false,'message'=>'Already Approved!']);
    	}
    	if($this->db
            ->where('id',$id)
            ->update('discount_money_receipt_discount',
                [
                'status'=>1,
                'mr_approve_discount' => $mr_approve_discount,
                'approved_by'=>$this->current_user->id,
                'approved_at'=>date('Y-m-d H:i:s')
                ]
                )){
    		echo json_encode(['status'=>true,'message'=>'Successfully Approved']);
    	}else{
    		echo json_encode(['status'=>false,'message'=>'Approved Failure']);
    	}
    }

    public function mrDiscountCheck($id, $status) {
    	$row = $this->db->where('id', $id)->get('discount_money_receipt_discount')->row();
    	if ($row) {
    		if ($row->status == $status) {
    			return true;
    		}
    	}
    	return false;
    }

    public function cancel($id) {
    	$this->auth->restrict('Discount.MoneyReceipt.Cancel');
    	if(!$this->mrDiscountCheck($id, 0)) {
    		echo json_encode(['status'=>false,'message'=>'Already Cancel!']);
    	}
    	if($this->db
            ->where('id',$id)
            ->update('discount_money_receipt_discount',
                [
                'status'=>2,
                'approved_by'=>$this->current_user->id,
                'approved_at'=>date('Y-m-d H:i:s')
                ]
                )){
    		echo json_encode(['status'=>true,'message'=>'Successfully Cancel']);
    	}else{
    		echo json_encode(['status'=>false,'message'=>'Approved Failure']);
    	}
    }


    public function collection_show()
    {
    	$this->auth->restrict('Discount.MoneyReceipt.Collection');
    	$id = $this->input->post('id', true);
    	//print_r($this->mrDiscountCheck($id, 1));exit;
    	if(!$this->mrDiscountCheck($id, 1)) {
    		//echo json_encode(['status'=>false,'message'=>'Already Collection!']);
    		//$this->collection_list
    		$data = false;
    		exit;
    	}
    	$data = $this->money_receipt_discount_model->mrDiscountInfo($id);
    	//echo '<pre>';print_r($data);
    	$this->load->view('money_receipt_discount/collection_add', $data);
    }

    public function collection()
    {
    	$this->auth->restrict('Discount.MoneyReceipt.Collection');
    	$id = $this->input->post('id', true);
    	$this->db->trans_begin();
    	$this->money_receipt_discount_model->collection_save($id);
    	if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('success' => false,'message' => "Error"));
		} else {
			$this->db->trans_commit();
			echo json_encode(array('success' => true, 'message' => 'Successfully done'));
		}
		exit;
    }

    public function sale_print($insert_id,$mr_no,$patient_id)
    {
    	$data['result']=$this->db->where('id',$insert_id)->get('bf_discount_money_receipt_discount')->row();

    	$hospital=$this->db->get('bf_lib_hospital')->result();
		$data['hospital']=$hospital[0];
		$data['patient_info']=$this->db->where('id',$patient_id)->get('bf_patient_master')->row();
		$data['mr_no']=$mr_no;
		$data['current_user']=$this->current_user->username;
		// return $this->load->view('money_receipt_discount/discount_print', compact('result','current_user','mr_no','patient_info','hospital'), true);

		return $this->load->view('money_receipt_discount/discount_print',$data,true);
    }

}