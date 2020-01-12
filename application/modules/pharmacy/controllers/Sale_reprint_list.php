<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pharmacy controller
 */
class Sale_reprint_list extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('main_pharmacy_sale_list_model', NULL, TRUE);
        $this->load->model('report/pharmacy_client_wise_report_model', NULL, TRUE);
    }

    public function index()
    {
        $this->auth->restrict('Pharmacy.MoneyReceive.Reprint');
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Sale No';
        $search_box['ticket_no_flag'] = 0;
        $search_box['contact_no_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag'] = 0;
        $search_box['patient_id_flag'] = 0;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_name_flag'] = 0;
        $search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['pharmacy_customer_name_flag'] = 1;
        $condition['psm.created_by>='] = 0;


        if (count($_POST) > 0) {

            if ($this->input->post('common_text_search')) {
                $condition['psm.sale_no like'] = '%' . trim($this->input->post('common_text_search')) . '%';
            }

            if ($this->input->post('customer_name')) {
                $condition['IFNULL(cusm.customer_name, IFNULL(emp.EMP_NAME, IFNULL(pmast.patient_name, IFNULL(adpmst.patient_name,0)))) like'] = '%' . trim($this->input->post('customer_name')) . '%';
            }

            if ($this->input->post('pharmacy_customer_type')) {

                $condition["psm.customer_type"] = $this->input->post('pharmacy_customer_type');

            }

            if ($this->input->post('from_date')) {

                $condition["DATE_FORMAT(psm.created_date, '%Y-%m-%d') >= "] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('from_date'))));

            }
            if ($this->input->post('to_date')) {

                $condition["DATE_FORMAT(psm.created_date, '%Y-%m-%d') <= "] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('to_date'))));

            }
        } else {
            $condition["DATE_FORMAT(psm.created_date,'%Y-%m-%d')"] = date("Y-m-d");
        }

        $records = $this->main_pharmacy_sale_list_model->selectAllSaleInformation($offset, $limit, $condition);

        $data['records'] = $records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/sale_reprint_list/pharmacy/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('main_pharmacy_sale_list/main_pharmacy_sale_reprint_list', compact('records', 'sl'), true);
            exit;
        }

        $list_view = "main_pharmacy_sale_list/main_pharmacy_sale_reprint_list";

        Template::set($data);
        Template::set('search_box', $search_box);
        Template::set('list_view', $list_view);
        Template::set_view('report_template');
        Template::set('toolbar_title', 'Pharmacy Sale List');
        Template::render();
    }

    public function sale_print($id)
    {
        $row = $this->db->where('id', $id)->get('bf_pharmacy_sales_mst')->row();

        if ($row->customer_type == 1) {
            $c_info = $this->db
                ->select("bf_patient_master.patient_id as code,bf_patient_master.patient_name as name,bf_patient_master .age,bf_patient_master.contact_no as mobile,bf_pharmacy_sales_mst.admission_id")
                ->from("bf_pharmacy_sales_mst")
                ->join("bf_admission_patient", "bf_admission_patient.id=bf_pharmacy_sales_mst.admission_id")
                ->join('bf_patient_master', 'bf_patient_master.id=bf_admission_patient.patient_id')
                ->where('bf_pharmacy_sales_mst.id', $id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 2) {
            $c_info = $this->db
                ->select("bf_patient_master.patient_id as code,bf_patient_master.patient_name as name,bf_patient_master .age,bf_patient_master.contact_no as mobile")
                ->from("bf_pharmacy_sales_mst")
                ->join("bf_patient_master", "bf_patient_master.id=bf_pharmacy_sales_mst.patient_id")
                ->where('bf_pharmacy_sales_mst.id', $id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 3) {
            $c_info = $this->db
                ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as age,bf_pharmacy_customer.customer_mobile as mobile")
                ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                ->from("bf_pharmacy_sales_mst")
                ->join('bf_pharmacy_customer', 'bf_pharmacy_customer.id=bf_pharmacy_sales_mst.customer_id')
                ->where('bf_pharmacy_sales_mst.id', $id)
                ->get()
                ->row();
        } elseif ($row->customer_type == 4 || $row->customer_type == 5) {
            $c_info = $this->db
                ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as age,0 as mobile,bf_pharmacy_sales_mst.id,bf_hrm_ls_employee.EMP_ID")
                ->from("bf_pharmacy_sales_mst")
                ->join('bf_hrm_ls_employee', ' bf_hrm_ls_employee.EMP_ID=bf_pharmacy_sales_mst.employee_id')
                ->where('bf_pharmacy_sales_mst.id', $id)
                ->get()
                ->row();
        }
        //$data = array();
        $hospital = $this->db->select('lib_hospital.*')->get('lib_hospital')->row();

        $records = $this->db->Select('pharmacy_sales_mst.*,pharmacy_products.product_name,pharmacy_sales_dtls.unit_price,pharmacy_products.category_id,pharmacy_sales_dtls.normal_discount_percent,pharmacy_sales_dtls.normal_discount_taka,pharmacy_sales_dtls.service_discount_percent,pharmacy_sales_dtls.service_discount_taka
	    	,pharmacy_sales_dtls.qnty,pharmacy_category.category_name')
            ->join('pharmacy_sales_dtls', 'bf_pharmacy_sales_dtls.master_id=pharmacy_sales_mst.id')
            ->join('pharmacy_products', 'pharmacy_products.id=bf_pharmacy_sales_dtls.product_id')
            ->join('pharmacy_category', 'pharmacy_category.id=pharmacy_products.category_id')
            //->join('patient_master','patient_master.id=pharmacy_sales_mst.patient_id')

            //->join('lib_hospital','pharmacy_sales_dtls.id=lib_hospital.id')
            ->where('pharmacy_sales_mst.id', $id)
            ->get('pharmacy_sales_mst')
            ->result();
        $current_user = $this->current_user->username;

        $patient_info = $this->db->select('pharmacy_sales_mst.*,patient_master.patient_id,patient_master.patient_name,patient_master.age,patient_master.contact_no')
            ->join('patient_master', 'patient_master.id=pharmacy_sales_mst.patient_id')
            ->get('pharmacy_sales_mst')
            ->row();
        $sendData = array(
            'hospital' => $hospital,
            'records' => $records,
            'c_info' => $c_info,
            'current_user' => $current_user,
        );
        // return $this->load->view('main_pharmacy/sale_print', $sendData,true);
        $this->load->view('main_pharmacy/reprint_list', $sendData);

    }
}