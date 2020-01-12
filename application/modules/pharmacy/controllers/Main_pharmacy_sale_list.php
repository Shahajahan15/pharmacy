<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Main_pharmacy_sale_list extends Admin_Controller
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
        $this->load->model('report/pharmacy_client_wise_report_model', NULL, TRUE);
        $this->load->model('pharmacy_sales_dtls_model', NULL, TRUE);
        $this->load->model('pharmacy_sales_mst_model', NULL, TRUE);
        $this->load->model('product_model', NULL, TRUE);
        $this->load->model('customer_model', NULL, TRUE);
        $this->load->model('report/pharmacy_client_wise_report_model', NULL, TRUE);
        $this->lang->load('main_pharmacy');
        $this->lang->load('common');
        $this->load->library('pharmacyCommonService');
        Assets::add_module_js('pharmacy', 'main_pharmacy_sale.js');

    }

    public function show_list()
    {
        $data = array();
        $this->auth->restrict('Pharmacy.Pres.Sale.View');
        $id = $this->session->userdata('master_id');
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 20000;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Customer Name';
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $search_box['due_paid_flag'] = 1;
        $condition = '';

        $records = $this->pharmacy_client_wise_report_model->getPharmacyClientList($condition, $limit, $offset); //echo '<pre>';print_r($records);exit();
      //  echo '<pre>';print_r($records);die();
        if ($id) {
            $data['print'] = $this->sale_print($id);
            $this->session->unset_userdata('master_id');
        }
        $data['records'] = $records;
        //echo '<pre>'; print_r($records); exit();
        
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/main_pharmacy_sale_list/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'main_pharmacy_list/list';

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
        return json_encode($records);
    }

    public function show_list_new()
    {
        $list_view = 'main_pharmacy_list/list_new';
    }

    public function due_details_print($customer_type, $client_id)
    {
        if ($customer_type == 1) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master.birthday as dob,patient_master.contact_no as mobile,admission_patient.id as admission_id")
                ->from("bf_pharmacy_payment_transaction")
                ->join("admission_patient", "admission_patient.id=bf_pharmacy_payment_transaction.admission_patient_id")
                ->join('patient_master', 'patient_master.id=admission_patient.patient_id')
                ->where('bf_pharmacy_payment_transaction.admission_patient_id', $client_id)
                ->get()
                ->row();

        } elseif ($customer_type == 3) {
            $c_info = $this->db
                ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as dob,bf_pharmacy_customer.customer_mobile as mobile")
                ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                ->from("bf_pharmacy_payment_transaction")
                ->join('bf_pharmacy_customer', 'bf_pharmacy_customer.id=bf_pharmacy_payment_transaction.customer_id')
                ->where('bf_pharmacy_payment_transaction.customer_id', $client_id)
                ->get()
                ->row();
        } elseif ($customer_type == 4 || $customer_type == 5) {
            $c_info = $this->db
                ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as dob,0 as mobile,bf_pharmacy_payment_transaction.id,bf_hrm_ls_employee.EMP_ID")
                ->from("bf_pharmacy_payment_transaction")
                ->join('bf_hrm_ls_employee', ' bf_hrm_ls_employee.EMP_ID=bf_pharmacy_payment_transaction.employee_id')
                ->where('bf_pharmacy_payment_transaction.employee_id', $client_id)
                ->get()
                ->row();
        }

        $condition = [];

        if ($customer_type == 3) {
            $condition['ppt.customer_id'] = $client_id;
        } elseif ($customer_type == 4 || $customer_type == 5) {
            $condition['ppt.employee_id'] = $client_id;
        } elseif ($customer_type == 1) {
            $condition['ppt.admission_patient_id'] = $client_id;
        }

        //$condition['ct.amount >'] = 0;

        $records = $this->db->select('
                psm.*,
                ppt.create_time,
                ppt.amount,
                ppt.type,
                (
                    CASE
                        WHEN ppt.type = 1 THEN "Sales"
                        WHEN ppt.type = 2 THEN "Due Paid"
                        WHEN ppt.type = 3 THEN "Return"
                    END
                ) as type_name,
                ppt.customer_type,              
                ppt.due_mr_no,
                ppt.return_bill as refund,
                 (
                    CASE
                        WHEN ppt.type = 3 THEN psrm.mr_no
                    END
                ) as return_mr_no,
                ppt.overall_discount
             ')
            ->from('bf_pharmacy_payment_transaction as ppt')
            ->join('bf_pharmacy_sales_mst as psm', 'ppt.source_id = psm.id')
            ->join('bf_pharmacy_sale_return_mst as psrm', 'ppt.source_id = psrm.id', 'left')
            ->where($condition)
            //->where('ppt.customer_type',$customer_type)
            ->order_by('ppt.create_time', 'asc')
            ->get()
            ->result();
        //echo '<pre>'; print_r($records);exit();
       /* $condition['customer_type'] = $records['0']->customer_type;
        if ($condition['customer_type'] == 1) {
            $condition['client_id'] = $records['0']->admission_id;
        } elseif ($condition['customer_type'] == 3) {
            $condition['client_id'] = $records['0']->customer_id;
        } elseif ($condition['customer_type'] == 4 || $condition['customer_type'] == 5) {
            $condition['client_id'] = $records['0']->employee_id;
        }*/
       // $return_bill = $this->pharmacy_client_wise_report_model->getPharmacyClientList($condition, 0, 0, 200, 1);
        $data = array();
        $hospital = $this->db->select('lib_hospital.*')->get('lib_hospital')->row();
        $current_user = $this->current_user->username;
        $total_due = 0;


        $sendData = array(
            'total_due' => $total_due,
            'c_info' => $c_info,
            'hospital' => $hospital,
            'current_user' => $current_user,
            'records' => $records,
           // 'return_bill' => $return_bill
        );
       // echo '<pre>';print_r($sendData);exit();
        echo $this->load->view('main_pharmacy_list/due_details_print', $sendData, true);
    }

    public function getFullPaid($condition)
    {
        if (isset($condition['due_paid'])) {
            unset($condition['due_paid']);
        }

        $product_return = $this->db->select('IFNULL(SUM(tot_return),0) as total_return_pro')->where($condition)->get('bf_pharmacy_sales_mst')->row()->total_return_pro;

        if (isset($condition['admission_id'])) {
            $condition['admission_patient_id'] = $condition['admission_id'];
            unset($condition['admission_id']);
        }


        if ($condition['customer_type'] == 6) {
            $result = $this->db
                ->select('IF(ppt.type = 1 ,SUM(ppt.amount),0) as paid, IF(ppt.type = 2,SUM(ppt.amount),0) as due_paid,IF(ppt.type = 3,SUM(ppt.amount),0) as return_paid, IF(ppt.type = 3 ,SUM(ppt.amount),0) as tot_return')
                ->from('pharmacy_payment_transaction as ppt')
                ->where($condition)
                ->get()
                ->row();

        } else {
            $result = $this->db
                ->select('IF(ppt.type = 1 ,SUM(ppt.amount),0) as paid, IF(ppt.type = 2,SUM(ppt.amount),0) as due_paid,IF(ppt.type = 3,SUM(ppt.amount),0) as return_paid, IF(ppt.type = 3,SUM(ppt.amount),0) as tot_return')
                ->from('pharmacy_payment_transaction as ppt')
                ->where($condition)
                ->group_by('ppt.admission_patient_id')
                ->group_by('ppt.customer_id')
                ->group_by('ppt.patient_id')
                ->group_by('ppt.employee_id')
                ->get()
                ->row();
        }


        $data['tot_paid'] = ((float)$result->paid + (float)$result->due_paid);
        $data['tot_return'] = (float)$result->tot_return;
        $data['tot_return_product'] = (float)$product_return;
        return $data;
    }

    public function due_paid()
    {
        $data = [];
        $data['record'] = $this->getClientBillInfo();
        $this->load->view('main_pharmacy_list/add', $data);
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
        $record = $this->pharmacy_client_wise_report_model->getPharmacyClientList($ncondition, 0, 10, 200, 1);
        return $record;
    }

    private function getPharmacyDueSaleNo()
    {
        $time = '';
        $PObj = new pharmacyCommonService();
        $sale_id = $this->input->post('sale_no', true);
        $sale_no_due = $this->input->post('sale_no_due', true);

        $customer_type = $this->input->post('customer_type', true);
        $due_paid = $this->input->post('due_paid', true);
        //$due_paid = isset($due_paid_wone)?$due_paid_wone:1;
        //echo "<pre>"; print_r($due_paid);exit();

        $over_all_discount = $this->input->post('overall_discount', true);

        if ($customer_type == 1) {
            $client_id = $this->input->post('admission_id', true);
        } elseif ($customer_type == 2) {
            $client_id = $this->input->post('patient_id', true);
        } elseif ($customer_type == 3) {
            $client_id = $this->input->post('customer_id', true);
        } elseif ($customer_type == 4 || $customer_type == 5) {
            $client_id = $this->input->post('employee_id', true);
        } elseif ($customer_type == 6) {
            $client_id = 0;
        }

        if (isset($sale_id)) {
            $records = $PObj->getPharmacyDueSaleNoBySaleId($customer_type, $client_id, $sale_no_due, $sale_id, 200, $over_all_discount);
        } else {
            $records = $PObj->getPharmacyDueSaleNo($customer_type, $client_id, $due_paid, 200, 0, $over_all_discount, $time);
        }
        return $records;
    }

    public function add()
    {

        // echo '<pre>';print_r($_POST);die();

        $Obj = new Commonservice();
        $PObj = new pharmacyCommonService();
        $data = [];

        $this->db->trans_begin();

        $records = $this->getPharmacyDueSaleNo();

        foreach ($records as $data) {
            $this->db->insert('pharmacy_payment_transaction', $data);
            $id = $this->db->insert_id();
            if ($_POST['patient_id']) {
                $Obj->patientHistoryAdd($_POST['patient_id'], $id, 54);
            }
            if ($_POST['admission_id']) {
                $Obj->patientHistoryAdd(0, $id, 54, $_POST['admission_id']);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('success' => false, 'message' => $this->pharmacy_sales_mst->error));
        } else {
            $this->db->trans_commit();
            $print = $this->due_paid_print($id);
            //$print = 1;
            $payment = $this->getClientBillInfo();
            //echo '<pre>'; print_r($payment);exit;
            echo json_encode(array('success' => true, 'print' => $print, 'payment' => $payment, 'message' => 'Successfully done'));
        }
        exit;
    }


    public function d_test()
    {
        exit;
        $obj = new pharmacyCommonService();
        $result = $this->db
            ->select('customer_id,employee_id,patient_id,admission_patient_id as admission_id,customer_type,amount,create_time')
            ->where('d_status', 0)
            ->where('type', 2)
            ->get('bf_pharmacy_payment_transaction')
            ->result();


        foreach ($result as $key => $val) {
            $customer_type = $val->customer_type;
            $client_id = 0;
            if ($customer_type == 1) {
                $client_id = $val->admission_id;
            } elseif ($customer_type == 2) {
                $client_id = $val->patient_id;
            } elseif ($customer_type == 3) {
                $client_id = $val->customer_id;
            } elseif ($customer_type == 4 || $customer_type == 5) {
                $client_id = $val->employee_id;
            }
            $records[] = $obj->getPharmacyDueSaleNo($customer_type, $client_id, $val->amount, 200, 0, 0, $val->create_time);

        }
        // echo '<pre>';print_r($records);exit;


    }

    public function getFullPaidInfo($condition)
    {
        $full_paid = $this->getFullPaid($condition);
        return $full_paid;
    }

    public function due_paid_print($trans_id)
    {

        $row = $this->db->where('id', $trans_id)->get('bf_pharmacy_payment_transaction')->row();
        if (!$row) {
            return false;
        }
        $due_mr_no = trim($row->due_mr_no);
        //print_r($row);exit();
        if ($row->customer_type == 1) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master.birthday as dob,patient_master.contact_no as mobile,admission_patient.id as admission_id")
                ->from("bf_pharmacy_payment_transaction")
                ->join("admission_patient", "admission_patient.id=bf_pharmacy_payment_transaction.admission_patient_id")
                ->join('patient_master', 'patient_master.id=admission_patient.patient_id')
                ->where('bf_pharmacy_payment_transaction.id', $trans_id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 2) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master.birthday as dob,patient_master.contact_no as mobile")
                ->from("bf_pharmacy_payment_transaction")
                ->join("patient_master", "patient_master.id=bf_pharmacy_payment_transaction.patient_id")
                ->where('bf_pharmacy_payment_transaction.id', $trans_id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 3) {
            $c_info = $this->db
                ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as dob,bf_pharmacy_customer.customer_mobile as mobile")
                ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                ->from("bf_pharmacy_payment_transaction")
                ->join('bf_pharmacy_customer', 'bf_pharmacy_customer.id=bf_pharmacy_payment_transaction.customer_id')
                ->where('bf_pharmacy_payment_transaction.id', $trans_id)
                ->get()
                ->row();
        } elseif ($row->customer_type == 4 || $row->customer_type == 5) {
            $c_info = $this->db
                ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as dob,0 as mobile,bf_pharmacy_payment_transaction.id,bf_hrm_ls_employee.EMP_ID")
                ->from("bf_pharmacy_payment_transaction")
                ->join('bf_hrm_ls_employee', ' bf_hrm_ls_employee.EMP_ID=bf_pharmacy_payment_transaction.employee_id')
                ->where('bf_pharmacy_payment_transaction.id', $trans_id)
                ->get()
                ->row();
        }

        $hospital = $this->db->select('lib_hospital.*')->get('lib_hospital')->row();
        $current_user = $this->current_user->username;
        $records = $this->db
            ->select('ppt.customer_type,ppt.create_time,ppt.amount, ppt.overall_discount, psm.sale_no, ppt.due_mr_no')
            ->from('bf_pharmacy_payment_transaction as ppt')
            ->join('bf_pharmacy_sales_mst as psm', 'ppt.source_id = psm.id')
            ->where('ppt.due_mr_no', $due_mr_no)
            ->get()
            ->result();
		// after paid then show previous due. but further paid due change.
        $condition['customer_type'] = $row->customer_type;
        if ($condition['customer_type'] == 1) {
            $condition['client_id'] = $row->admission_patient_id;
        } elseif ($condition['customer_type'] == 2) {
            $condition['client_id'] = $row->patient_id;
        } elseif ($condition['customer_type'] == 3) {
            $condition['client_id'] = $row->customer_id;
        } elseif ($condition['customer_type'] == 4 || $condition['customer_type'] == 5) {
            $condition['client_id'] = $row->employee_id;
        }
        $return_bill = $this->pharmacy_client_wise_report_model->getPharmacyClientList($condition, 0, 0, 200, 1);
        $sendData = array(
            'hospital' => $hospital,
            'records' => $records,
            'c_info' => $c_info,
            'current_user' => $current_user,
            'return_bill' => $return_bill,
        );
       //echo"<pre>";print_r($sendData);die();
        //print_r($this->load->view('main_pharmacy_list/pharmacy_due_print',$sendData));
        return $this->load->view('main_pharmacy_list/pharmacy_due_print', $sendData, true);

    }


    public function due_print($customer_type, $client_id, $due_bill)
    {
        if ($customer_type == 1) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master.birthday as dob,patient_master.contact_no as mobile,admission_patient.id as admission_id")
                ->from("bf_pharmacy_payment_transaction")
                ->join("admission_patient", "admission_patient.id=bf_pharmacy_payment_transaction.admission_patient_id")
                ->join('patient_master', 'patient_master.id=admission_patient.patient_id')
                ->where('bf_pharmacy_payment_transaction.admission_patient_id', $client_id)
                ->get()
                ->row();

        } elseif ($customer_type == 2) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master.birthday as dob,patient_master.contact_no as mobile")
                ->from("bf_pharmacy_payment_transaction")
                ->join("patient_master", "patient_master.id=bf_pharmacy_payment_transaction.patient_id")
                ->where('bf_pharmacy_payment_transaction.patient_id', $client_id)
                ->get()
                ->row();

        } elseif ($customer_type == 3) {
            $c_info = $this->db
                ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as dob,bf_pharmacy_customer.customer_mobile as mobile")
                ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                ->from("bf_pharmacy_payment_transaction")
                ->join('bf_pharmacy_customer', 'bf_pharmacy_customer.id=bf_pharmacy_payment_transaction.customer_id')
                ->where('bf_pharmacy_payment_transaction.customer_id', $client_id)
                ->get()
                ->row();
        } elseif ($customer_type == 4 || $customer_type == 5) {
            $c_info = $this->db
                ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as dob,0 as mobile,bf_pharmacy_payment_transaction.id,bf_hrm_ls_employee.EMP_ID")
                ->from("bf_pharmacy_payment_transaction")
                ->join('bf_hrm_ls_employee', ' bf_hrm_ls_employee.EMP_ID=bf_pharmacy_payment_transaction.employee_id')
                ->where('bf_pharmacy_payment_transaction.employee_id', $client_id)
                ->get()
                ->row();
        }

        $condition = [];
        if ($customer_type == 1) {
            $condition['ppt.admission_patient_id'] = $client_id;
        } elseif ($customer_type == 2) {
            $condition['ppt.patient_id'] = $client_id;
        } elseif ($customer_type == 3) {
            $condition['ppt.customer_id'] = $client_id;
        } elseif ($customer_type == 4 || $customer_type == 5) {
            $condition['ppt.employee_id'] = $client_id;
        } elseif ($customer_type == 6) {
            $condition['ppt.customer_type'] = 6;
        }

        $records = $this->db
            ->select('ppt.create_time,ppt.amount,ppt.type,ppt.customer_type,psm.sale_no')
            ->from('bf_pharmacy_payment_transaction as ppt')
            ->join('bf_pharmacy_sales_mst as psm', 'ppt.source_id = psm.id')
            ->where($condition)
            ->order_by('ppt.id', 'desc')
            ->get()
            ->result();
// echo $this->db->last_query();exit();
//echo '<pre>';print_r($records);exit();
        $data = array();
        $hospital = $this->db->select('lib_hospital.*')->get('lib_hospital')->row();
        $current_user = $this->current_user->username;
        $total_due = $due_bill;

        $sendData = array(
            'total_due' => $total_due,
            'c_info' => $c_info,
            'hospital' => $hospital,
            'current_user' => $current_user,
            'records' => $records
        );
        echo $this->load->view('main_pharmacy_list/due_reprint', $sendData, true);


    }


    public function sale_print($id)
    {
        $row = $this->db->where('id', $id)->get('pharmacy_sales_mst')->row();
        //print_r($row);exit();
        if ($row->customer_type == 1) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master .age,patient_master.contact_no as mobile")
                ->from("pharmacy_sales_mst")
                ->join("admission_patient", "admission_patient.id=pharmacy_sales_mst.admission_id")
                ->join('patient_master', 'patient_master.id=admission_patient.patient_id')
                ->where('pharmacy_sales_mst.id', $id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 2) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master .age,patient_master.contact_no as mobile")
                ->from("pharmacy_sales_mst")
                ->join("patient_master", "patient_master.id=pharmacy_sales_mst.patient_id")
                ->where('pharmacy_sales_mst.id', $id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 3) {
            $c_info = $this->db
                ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as age,bf_pharmacy_customer.customer_mobile as mobile")
                ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                ->from("pharmacy_sales_mst")
                ->join('bf_pharmacy_customer', 'bf_pharmacy_customer.id=pharmacy_sales_mst.customer_id')
                ->where('pharmacy_sales_mst.id', $id)
                ->get()
                ->row();
        } elseif ($row->customer_type == 4 || $row->customer_type == 5) {
            $c_info = $this->db
                ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as age,0 as mobile,pharmacy_sales_mst.id,bf_hrm_ls_employee.EMP_ID")
                ->from("pharmacy_sales_mst")
                ->join('bf_hrm_ls_employee', ' bf_hrm_ls_employee.EMP_ID=pharmacy_sales_mst.employee_id')
                ->where('pharmacy_sales_mst.id', $id)
                ->get()
                ->row();
        }
        //$data = array();
        $hospital = $this->db->select('lib_hospital.*')->get('lib_hospital')->row();

        $records = $this->db->Select('pharmacy_sales_mst.*,pharmacy_products.product_name,pharmacy_sales_dtls.unit_price,pharmacy_sales_dtls.normal_discount_percent,pharmacy_sales_dtls.normal_discount_taka,pharmacy_sales_dtls.service_discount_percent,pharmacy_sales_dtls.service_discount_taka
	    	,pharmacy_sales_dtls.qnty')
            ->join('pharmacy_sales_dtls', 'bf_pharmacy_sales_dtls.master_id=pharmacy_sales_mst.id')
            ->join('pharmacy_products', 'pharmacy_products.id=bf_pharmacy_sales_dtls.product_id')
            //->join('patient_master','patient_master.id=pharmacy_sales_mst.patient_id')

            //->join('lib_hospital','pharmacy_sales_dtls.id=lib_hospital.id')
            ->where('pharmacy_sales_mst.id', $id)
            ->get('pharmacy_sales_mst')
            ->result();
        $current_user = $this->current_user->username;
        //$this->load->view('main_pharmacy/sale_print', $data, true);
// echo '<pre>';print_r($data['records']);exit();
        $patient_info = $this->db->select('pharmacy_sales_mst.*,patient_master.patient_id,patient_master.patient_name,patient_master.age,patient_master.contact_no')
            ->join('patient_master', 'patient_master.id=pharmacy_sales_mst.patient_id')
            ->get('pharmacy_sales_mst')
            ->row();
        $sendData = array(
            'hospital' => $hospital,
            'records' => $records,
            'patient_info' => $patient_info,
            'c_info' => $c_info,
            'current_user' => $current_user
        );
        return $this->load->view('main_pharmacy/sale_print', $sendData, true);

    }

    /* medicine due sale no   */

    public function getMedicineDueSaleNoByKey($customer_type, $client_id)
    {
        if ($query = $this->input->get('q')) {

            $pObj = new pharmacyCommonService();
            $results = $pObj->getMedicineDueSaleNo($customer_type, $client_id, 0, 200, $query);
            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->mr_no . " >> " . $result->due;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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
    public function addData(){

    }

}






