
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Sub_pharmacy_sale_list extends Admin_Controller
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

		$this->load->model('pharmacy_sales_dtls_model', NULL, TRUE);
		$this->load->model('pharmacy_indoor_sales_mst_model', NULL, TRUE);
		$this->load->model('product_model', NULL, TRUE);
		$this->load->model('customer_model', NULL, TRUE);
		$this->lang->load('main_pharmacy');
		$this->load->library('pharmacyCommonService');
        $this->load->model('report/pharmacy_client_wise_report_model', NULL, TRUE);
		Assets::add_module_js('pharmacy', 'sub_pharmacy_sale.js');

	}

    public function show_list(){
        $data = [];

        $this->auth->restrict('Pharmacy.Sub.Sale.View');
        $id = $this->session->userdata('master_id');
        
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Customer Name';
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['due_paid_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $condition='';

        $pharmacy_id = $this->current_user->pharmacy_id;

        $records = $this->pharmacy_client_wise_report_model->getPharmacyClientList($condition, $limit, $offset, $pharmacy_id);
        //echo '<pre>';print_r($records);die();
        if ($id) {
            $data['print'] = $this->sale_print($id);
            $this->session->unset_userdata('master_id');
        }
        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/sub_pharmacy_sale_list/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view='sub_pharmacy_list/list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }
        Template::set($data);
        Template::set('toolbar_title', "Client List");
        Template::set('search_box', $search_box);
        Template::set('list_view', $list_view);
        Template::set_view('report_template');
        
        Template::render();
    }

    public function sub_pharmacy_sale(){
       $records= $this->db
        ->select('pism.id,pism.sale_no,pism.tot_paid,pism.tot_bill,pism.tot_due,pism.created_date')
        ->from('pharmacy_indoor_sales_mst as pism')
        ->join('pharmacy_indoor_sales_dtls as pisd','pisd.master_id=pism.id')
        ->get()
        ->result();
     


        Template::set('records',$records);
        Template::set('toolbar_title','Sub Pharmacy Sale List');
        Template::set_view('sub_pharmacy/list');
        Template::render();
    }

	public function getFullPaid($condition)
	{
		
		if(isset($condition['paid_amount'])){
			unset($condition['paid_amount']);
		}

        $product_return=$this->db->select('IFNULL(SUM(tot_return),0) as total_return_pro')->where($condition)->get('bf_pharmacy_indoor_sales_mst')->row()->total_return_pro;

		if($condition['customer_type']==6){
			$result = $this->db
					->select('IF(ppt.type = 1 ,SUM(ppt.amount),0) as paid, IF(ppt.type = 2,SUM(ppt.amount),0) as due_paid,IF(ppt.type = 3,SUM(ppt.amount),0) as return_paid')
					->from('pharmacy_sub_payment_transaction as ppt')
					->where($condition)					
					->get()
					->row();

		}else{
			$result = $this->db
					->select('IF(ppt.type = 1 ,SUM(ppt.amount),0) as paid, IF(ppt.type = 2,SUM(ppt.amount),0) as due_paid,IF(ppt.type = 3,SUM(ppt.amount),0) as return_paid')
					->from('pharmacy_sub_payment_transaction as ppt')
					->where($condition)
					->group_by('ppt.admission_id')
                    ->group_by('ppt.customer_id')
                    ->group_by('ppt.patient_id')
                    ->group_by('ppt.employee_id')
					->get()
					->row();
		}

		$data['tot_paid'] =((float)$result->paid+(float)$result->due_paid);
        $data['tot_return'] =(float)$result->return_paid;
        $data['tot_return_product'] =(float)$product_return;
        return $data;
		
		
		return $data;
	}
   

	public function due_paid() {
        $data = [];
        $data['record'] = $this->getClientBillInfo();
		$this->load->view('sub_pharmacy_list/add', $data);
		
	}

    private function getClientBillInfo()
    {
        $ncondition['customer_type'] = $this->input->post('customer_type', true);
        $ncondition['client_id'] = 0;
        if ($ncondition['customer_type'] == 1) {
            $ncondition['client_id'] = $this->input->post('admission_id', true);
        } elseif ($ncondition['customer_type'] == 2) {
            $ncondition['client_id'] = $this->input->post('patient_id', true);
        } elseif ($ncondition['customer_type'] == 3) {
            $ncondition['client_id'] = $this->input->post('customer_id', true);
        } elseif ($ncondition['customer_type'] == 4 || $ncondition['customer_type'] == 5) {
            $ncondition['client_id'] = $this->input->post('employee_id', true);
        }
        $pharmacy_id = $this->current_user->pharmacy_id;
        $record = $this->pharmacy_client_wise_report_model->getPharmacyClientList($ncondition, 0, 10, $pharmacy_id, 1);
        return $record;
    }

     private function getPharmacyDueSaleNo() {
        $time = '';
        $PObj = new pharmacyCommonService();
        $pharmacy_id = $this->current_user->pharmacy_id;
        $sale_id = $this->input->post('sale_no', true);
        $sale_no_due = $this->input->post('sale_no_due', true);

        $customer_type = $this->input->post('customer_type', true);
        $due = $this->input->post('due_paid', true);
        $over_all_discount = $this->input->post('overall_discount', true);

        if ($customer_type == 1) {
            $client_id =  $this->input->post('admission_id', true);
        } elseif ($customer_type == 2) { 
            $client_id =  $this->input->post('patient_id', true);
        } elseif ($customer_type == 3) {
            $client_id =  $this->input->post('customer_id', true);
        } elseif ($customer_type == 4 || $customer_type == 5) {
            $client_id =  $this->input->post('employee_id', true);
        } elseif($customer_type == 6){
            $client_id = 0;
        }

        if (isset($sale_id)) {
            $records = $PObj->getPharmacyDueSaleNoBySaleId($customer_type, $client_id, $sale_no_due, $sale_id, $pharmacy_id, $over_all_discount, $time);
        } else {
            $records = $PObj->getPharmacyDueSaleNo($customer_type, $client_id, $due, $pharmacy_id, 0, $over_all_discount, $time);
        }
        return $records;
    }

    public function add() {
        //echo '<pre>';print_r($_POST);exit;
        $Obj = new Commonservice();
        $PObj = new pharmacyCommonService();
        $data = [];
        $this->db->trans_begin();
        $records = $this->getPharmacyDueSaleNo();
       // echo '<pre>';print_r($records);exit;
        foreach ($records as $data) {
            $this->db->insert('pharmacy_sub_payment_transaction', $data);
            $id = $this->db->insert_id();
           if ($_POST['patient_id']) {
                $Obj->patientHistoryAdd($_POST['patient_id'], $id, 55);
            }
            if ($_POST['admission_id']) {
                $Obj->patientHistoryAdd(0, $id, 54, $_POST['admission_id']);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
                echo json_encode(array('success' => false,'message' => $this->pharmacy_indoor_sales_mst_model->error));
                exit;
            } else {
                $this->db->trans_commit();
                $print = $this->due_Paid_print($id);
                $payment = $this->getClientBillInfo();
                echo json_encode(array('success' => true,'print' => $print,'payment' => $payment,'message' => 'Successfully done'));
            }
        exit;
    }

	/*public function add() {
		$Obj = new Commonservice();
		$PObj = new pharmacyCommonService();
        $data = [];
        $this->db->trans_begin();
        $id = $PObj->subPharmacyTranjactionPayment($_POST, $this->current_user,2,$_POST['customer_type']);
        if ($_POST['patient_id']) {
        	$Obj->patientHistoryAdd($_POST['patient_id'], $id, 55);
        } 
         if ($_POST['admission_id']) {
            $Obj->patientHistoryAdd(0, $id, 54, $_POST['admission_id']);
        }
        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
				echo json_encode(array('success' => false,'message' => $this->pharmacy_indoor_sales_mst_model->error));
                exit;
			} else {
				$this->db->trans_commit();
				$print = $this->due_Paid_print($id);
                $payment = $this->getClientBillInfo();
				echo json_encode(array('success' => true,'print' => $print,'payment' => $payment,'message' => 'Successfully done'));
			}
		exit;
	}*/

    public function d_test()
    {
       exit;
       // echo 'hi';exit;
        $obj = new pharmacyCommonService();
        $result = $this->db
                    ->select('customer_id,employee_id,patient_id,admission_id,customer_type,amount,create_time')
                    ->where('d_status', 0)
                    ->where('type', 2)
                    ->where('pharmacy_id', 1)
                    ->get('bf_pharmacy_sub_payment_transaction')
                    ->result();


        foreach ($result as $key => $val) {
            $customer_type = $val->customer_type;
            $client_id = 0;
                if ($customer_type  == 1) {
                    $client_id = $val->admission_id;
                } elseif ($customer_type  == 2) {
                    $client_id = $val->patient_id;
                } elseif ($customer_type  == 3) {
                    $client_id = $val->customer_id;
                } elseif ($customer_type  == 4 || $customer_type == 5) {
                    $client_id = $val->employee_id;
                }
           $records[] = $obj->getPharmacyDueSaleNo($customer_type, $client_id, $val->amount, 1, 0, 0,$val->create_time);
           
        }
            exit;
        //echo '<pre>';print_r($records);exit;
    }
   
	private function getPharmacyPatientId($master_id) {
		$Obj = new Commonservice();
		$patient_id = 0;
		$row = $this->db
			->where('id',$master_id)
			->get('pharmacy_indoor_sales_mst')
			->row();
		if ($row) {
			if ($row->customer_type == 1) {
				$patient_id = $Obj->getHistoryPatientId($row->admission_id);
			}
			if ($row->customer_type == 2) {
				$patient_id = $row->patient_id;
			}
		}

		return $patient_id;

	}

	public function getFullPaidInfo($condition)
	{
		$full_paid = $this->getFullPaid($condition);
		return $full_paid;
	}


	public function sale_print($id)
	{
		$row=$this->db->where('id',$id)->get('pharmacy_indoor_sales_mst')->row();
		//print_r($row);exit();
		if($row->customer_type==1){
			$c_info = $this->db
			->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master .age,patient_master.contact_no as mobile")
						->from("pharmacy_indoor_sales_mst")
						->join("admission_patient","admission_patient.id=pharmacy_indoor_sales_mst.admission_id")
						->join('patient_master','patient_master.id=admission_patient.patient_id')
						->where('pharmacy_indoor_sales_mst.id',$id)
						->get()
						->row();

		}
		elseif ($row->customer_type==2){
			$c_info = $this->db
			->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master .age,patient_master.contact_no as mobile")
						->from("pharmacy_indoor_sales_mst")
						->join("patient_master","patient_master.id=pharmacy_indoor_sales_mst.patient_id")
						->where('pharmacy_indoor_sales_mst.id',$id)
						->get()
						->row();

		}
		elseif ($row->customer_type==3){
			$c_info=$this->db
			->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as age,bf_pharmacy_customer.customer_mobile as mobile")
			            ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
			            ->from("pharmacy_indoor_sales_mst")
			            ->join('bf_pharmacy_customer','bf_pharmacy_customer.id=pharmacy_indoor_sales_mst.customer_id')
			            ->where('pharmacy_indoor_sales_mst.id',$id)
			            ->get()
			            ->row();
		}
		elseif($row->customer_type==4||$row->customer_type==5){
			$c_info=$this->db
			->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as age,0 as mobile,pharmacy_indoor_sales_mst.id,bf_hrm_ls_employee.EMP_ID")
			            ->from("pharmacy_indoor_sales_mst")
			            ->join('bf_hrm_ls_employee',' bf_hrm_ls_employee.EMP_ID=pharmacy_indoor_sales_mst.employee_id')
			            ->where('pharmacy_indoor_sales_mst.id',$id)
			            ->get()
			            ->row();
		}
		//$data = array();
		$hospital=$this->db->select('lib_hospital.*')->get('lib_hospital')->row();

	    $records=$this->db->Select('pharmacy_indoor_sales_mst.*,pharmacy_products.product_name,pharmacy_sales_dtls.unit_price,pharmacy_sales_dtls.normal_discount_percent,pharmacy_sales_dtls.normal_discount_taka,pharmacy_sales_dtls.service_discount_percent,pharmacy_sales_dtls.service_discount_taka
	    	,pharmacy_sales_dtls.qnty')
	    ->join('pharmacy_sales_dtls','bf_pharmacy_sales_dtls.master_id=pharmacy_indoor_sales_mst.id')
	    ->join('pharmacy_products','pharmacy_products.id=bf_pharmacy_sales_dtls.product_id')
	    //->join('patient_master','patient_master.id=pharmacy_indoor_sales_mst.patient_id')

	    //->join('lib_hospital','pharmacy_sales_dtls.id=lib_hospital.id')
	    ->where('pharmacy_indoor_sales_mst.id',$id)
	    ->get('pharmacy_indoor_sales_mst')
	    ->result();
	    $current_user=$this->current_user->username;
	    //$this->load->view('main_pharmacy/sale_print', $data, true);
// echo '<pre>';print_r($data['records']);exit();
        $patient_info=$this->db->select('pharmacy_indoor_sales_mst.*,patient_master.patient_id,patient_master.patient_name,patient_master.age,patient_master.contact_no')
        ->join('patient_master','patient_master.id=pharmacy_indoor_sales_mst.patient_id')
        ->get('pharmacy_indoor_sales_mst')
        ->row();
        $sendData=array(
        	'hospital'=>$hospital,
        	'records'=>$records,
        	'patient_info'=>$patient_info,
        	'c_info'=>$c_info,
        	'current_user'=>$current_user
        	);
		return $this->load->view('main_pharmacy/sale_print', $sendData,true);
		    
}
  public function due_paid_print($trans_id){

      $row=$this->db->where('id',$trans_id)->get('bf_pharmacy_sub_payment_transaction')->row();
        //print_r($row);exit();
        if($row->customer_type==1){
            $c_info = $this->db
            ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master .age,patient_master.contact_no as mobile")
                        ->from("bf_pharmacy_sub_payment_transaction")
                        ->join("admission_patient","admission_patient.id=bf_pharmacy_sub_payment_transaction.admission_id")
                        ->join('patient_master','patient_master.id=admission_patient.patient_id')
                        ->where('bf_pharmacy_sub_payment_transaction.id',$trans_id)
                        ->get()
                        ->row();

        }
        elseif ($row->customer_type==2){
            $c_info = $this->db
            ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master.age,patient_master.contact_no as mobile")
                        ->from("bf_pharmacy_sub_payment_transaction")
                        ->join("patient_master","patient_master.id=bf_pharmacy_sub_payment_transaction.patient_id")
                        ->where('bf_pharmacy_sub_payment_transaction.id',$trans_id)
                        ->get()
                        ->row();

        }
        elseif ($row->customer_type==3){
            $c_info=$this->db
            ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as age,bf_pharmacy_customer.customer_mobile as mobile")
                        ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                        ->from("bf_pharmacy_sub_payment_transaction")
                        ->join('bf_pharmacy_customer','bf_pharmacy_customer.id=bf_pharmacy_sub_payment_transaction.customer_id')
                        ->where('bf_pharmacy_sub_payment_transaction.id',$trans_id)
                        ->get()
                        ->row();
        }
        elseif($row->customer_type==4||$row->customer_type==5){
            $c_info=$this->db
            ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as age,0 as mobile,bf_pharmacy_sub_payment_transaction.id,bf_hrm_ls_employee.EMP_ID")
                        ->from("bf_pharmacy_sub_payment_transaction")
                        ->join('bf_hrm_ls_employee',' bf_hrm_ls_employee.EMP_ID=bf_pharmacy_sub_payment_transaction.employee_id')
                        ->where('bf_pharmacy_sub_payment_transaction.id',$trans_id)
                        ->get()
                        ->row();
        }


    $data=array();
    $hospital=$this->db->select('lib_hospital.*')->get('lib_hospital')->row();
    $current_user=$this->current_user->username;
    $records=$this->db
    ->select('ppt.customer_type,ppt.create_time,ppt.amount')
    ->from('bf_pharmacy_sub_payment_transaction as ppt')
    //->join('bf_pharmacy_sales_mst as psm','psm.id=ppt.cus')
    ->where('ppt.id',$trans_id)
    //->group_by('customer_type')
    ->get()
    ->result();
    $info=$this->db
    ->select('psm.id,psm.customer_type')
    ->from('pharmacy_sales_mst as psm')
   //->join('bf_pharmacy_payment_transaction as ppt','ppt.id=psm.id')
   //->where('ppt.id',$trans_id)

    ->get()
    ->result();
    //echo '<pre>';print_r($records);exit();
    $sendData=array(
            'hospital'=>$hospital,
            'records'=>$records,
            'c_info'=>$c_info,
            'current_user'=>$current_user
            );
       return $this->load->view('sub_pharmacy_list/subpharmacy_due_print',$sendData,true);
        
    }


    public function getMedicineDueSaleNoByKey($customer_type, $client_id){  
        $pharmacy_id = $this->current_user->pharmacy_id;
        if ($query = $this->input->get('q')) {

            $pObj = new pharmacyCommonService();
            $results = $pObj->getMedicineDueSaleNo($customer_type,$client_id,0, $pharmacy_id, $query);
            $items = array();
            $assoc = array();
            foreach($results as $result) {

                $text=$result->mr_no." >> ".$result->due;
                $items[] = array(
                    'id' => $result->id,
                    'text' =>$text,
                );
                
                $assoc[$result->id] = $result;
            }
            
            $status_code = 200;
            $json = array(
                'items' => $items,  
                'assoc' => $assoc,
            );
            
        } else {
            
            $status_code = 422;
            $json = array(
                'error' => 'Required Parameters not found.',    
            );
            
        }
        
        return $this->output->set_status_header($status_code)
                            ->set_content_type('application/json')
                            ->set_output(json_encode($json));
            
    }       

}






