<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Client_wise_report extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict('Report.PharClientInfo.View');

        $this->lang->load('common');
        $this->load->model('pharmacy_client_wise_report_model');
        $this->load->library('pharmacy/pharmacyCommonService');
    }

    public function client_wise_all_information($offset = 0)
    {

        $data = array();

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['pharmacy_list_flag'] = 1;
        $search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['pharmacy_customer_name_flag'] = 1;

        $pharmacy_name = 'Main Pharmacy';

        $condition["1"] = 1;
        $pharmacy_id = "";
        if (count($_POST) > 0) {
            if ($this->input->post('pharmacy_customer_type')) {
                $condition['psm.customer_type'] = $this->input->post('pharmacy_customer_type');
            }
            if ($this->input->post('customer_name')) {
                $condition['IFNULL(cusm.customer_name, IFNULL(emp.EMP_NAME, IFNULL(pmast.patient_name, IFNULL(adpmst.patient_name,0))))  LIKE'] = '%' . trim($this->input->post('customer_name')) . '%';
            }
            if ($this->input->post('pharmacy_name')) {
                $pharmacy_id = $this->input->post('pharmacy_name');
            }
        }
        $pharmacy_id = $this->input->post('pharmacy_name');
        if ($pharmacy_id == '' || $pharmacy_id != 200) {
            $pharmacy_name = $this->db->where('id', $pharmacy_id)
                ->get('bf_pharmacy_setup')
                ->row();
        }


        $data['pharmacy_name'] = $pharmacy_name;

        $records = $this->pharmacy_client_wise_report_model->select_all_pharmcy_client_information($condition, $limit, $offset, $pharmacy_id);

        $data['records'] = $records;

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/client_wise_report/report/client_wise_all_information' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'report/pharmacy_client_wise_report/client_wise_report';


        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, compact('pharmacy_name', 'pharmacy_id', 'records', 'sl'), true);
            exit;
        }

        $set = array(
            'records' => $records,
            'pharmacy_name' => $pharmacy_name,
            'toolbar_title' => 'Client Wise Report',
            'data' => $data,
            'list_view' => $list_view,
            'search_box' => $search_box
        );
        Template::set($set);
        Template::set_view('report_template');
        Template::render();
    }


    public function index()
    {

        $this->auth->restrict('Report.PharClientInfo.View');
        $data = array();

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $sl = $offset;
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 50;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['pharmacy_list_flag'] = 1;
        $search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Customer Name';
        $search_box['due_paid_flag'] = 1;
        $search_box['per_page'] = $limit;

        $pharmacy_name = 'Main Pharmacy';
        $role = $this->current_user->role_id;
       // $data['role'] = $role;
        //echo '<pre>'; print_r($role); exit();
        $pharmacy_id = ($role == 23) ? 1 : 200;
        $condition["1"] = 1;
        //$pharmacy_id = "";
        if (count($_POST) > 0) {
            if ($this->input->post('pharmacy_customer_type')) {
                $condition['psm.customer_type'] = $this->input->post('pharmacy_customer_type');
            }
            if ($this->input->post('customer_name')) {
                $condition['IFNULL(cusm.customer_name, IFNULL(emp.EMP_NAME, IFNULL(pmast.patient_name, IFNULL(adpmst.patient_name,0))))  LIKE'] = '%' . trim($this->input->post('customer_name')) . '%';
            }
            if ($this->input->post('pharmacy_name')) {
                $pharmacy_id = $this->input->post('pharmacy_name');
            }
        }

        //$pharmacy_id=$this->input->post('pharmacy_name');



        if ($pharmacy_id != '' || $pharmacy_id = 200) {
            $pharmacy_name = $this->db->where('id', $pharmacy_id)
                ->get('bf_pharmacy_setup')
                ->row();
        }


        $data['pharmacy_name'] = $pharmacy_name;
        $data['pharmacy_id'] = $pharmacy_id;
        //echo '<pre>'; print_r($data['pharmacy_id']); exit();
        $records = $this->pharmacy_client_wise_report_model->getPharmacyClientList($condition, $limit, $offset, $pharmacy_id);
        // echo '<pre>';print_r($records);exit;
        $data['records'] = $records;
        $pharmacy_id=$data['pharmacy_id'];
       //echo '<pre>'; print_r($pharmacy_id); exit();

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/client_wise_report/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'report/pharmacy/client_wise_report/index';


        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, compact('data','pharmacy_id','pharmacy_name', 'role','pharmacy_id', 'records', 'sl'), true);
            exit;
        }

        $set = array(
            'records' => $records,
            'pharmacy_name' => $pharmacy_name,
            'toolbar_title' => 'Client Wise Report',
            'data' => $data,
            'list_view' => $list_view,
            'search_box' => $search_box,
            'pharmacy_id' => $pharmacy_id,
            'role' => $role
        );
        Template::set($set);
        Template::set_view('report_template');
        Template::render();
    }

    public function mupdateTest()
    {
        $pObj = new pharmacyCommonService();
        $result = $pObj->getPharmacyDueSaleNo();
        // exit;
        /*   $data = [];
          $result = $this->db->select('id, mr_no, created_date')->get('bf_pharmacy_indoor_sale_return_mst')->result();
          if ($result) {
           foreach ($result as $key => $val) {
               $mr_no = "PR1".custom_date_format($val->created_date, "ymd");
               $this->db->where('id', $val->id)->update('bf_pharmacy_indoor_sale_return_mst', ['mr_no' => $mr_no]);
           }
          } */
        // exit;

        echo '<pre>';
        print_r($result);
        exit;
    }

    public function test_details($customer_type, $client_id, $pharmacy_id)

    {

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['pharmacy_customer_name_flag'] = 0;
        $search_box['amount_type_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $pharmacy_name = 'Main Pharmacy';

        $condition = [];
        if (count($_POST) > 0) {
            $mr_no = trim($this->input->post('mr_num', true));
            if ($mr_no) {
                $condition["psm.sale_no LIKE '%$mr_no%' OR ppt.due_mr_no LIKE '%$mr_no%' OR psrm.mr_no LIKE '%$mr_no%'"] = '';
            }
            $amount_type = $this->input->post('amount_type', true);
            if ($amount_type) {
                $condition['ppt.type'] = $amount_type;
            }
            if ($this->input->post('from_date')) {
                $from_formated_date = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $this->input->post('from_date'))));
                $condition['ppt.create_time >='] = $from_formated_date;
            }
            if ($this->input->post('to_date')) {
                $to_formated_date = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $this->input->post('to_date'))));
                $condition['ppt.create_time <='] = $to_formated_date;
            }
        }

        $admission_id = ($pharmacy_id == 200) ? "ppt.admission_patient_id" : "ppt.admission_id";
        if ($customer_type == 1) {
            $condition[$admission_id] = $client_id;
        } elseif ($customer_type == 2) {
            $condition['ppt.patient_id'] = $client_id;
        } elseif ($customer_type == 3) {
            $condition['ppt.customer_id'] = $client_id;
        } elseif ($customer_type == 4 || $customer_type == 5) {
            $condition['ppt.employee_id'] = $client_id;
        } elseif ($customer_type == 6) {
            $condition['ppt.customer_type'] = 6;
        }

        $condition['ppt.pharmacy_id'] = $pharmacy_id;

        $m_table = "pharmacy_payment_transaction as ppt";
        $sm_table = "pharmacy_sub_payment_transaction as ppt";
        $jm_table = "bf_pharmacy_sales_mst as psm";
        $jsm_table = "bf_pharmacy_indoor_sales_mst as psm";
        $r_table = "bf_pharmacy_sale_return_mst as psrm";
        $rs_table = "bf_pharmacy_indoor_sale_return_mst psrm";
        $table = ($pharmacy_id == "200" || $pharmacy_id == "") ? $m_table : $sm_table;
        $jtable = ($pharmacy_id == "200" || $pharmacy_id == "") ? $jm_table : $jsm_table;
        $rtable = ($pharmacy_id == "200" || $pharmacy_id == "") ? $r_table : $rs_table;

        $records = $this->db
            ->select('
                    SQL_CALC_FOUND_ROWS
                    (
                      CASE
                          WHEN ppt.customer_type = 1 THEN "Admission Patient"
                          WHEN ppt.customer_type = 2 THEN "Patient"
                          WHEN ppt.customer_type = 3 THEN "Customer"
                          WHEN ppt.customer_type = 4 THEN "Employee"
                          WHEN ppt.customer_type = 5 THEN "Doctor"
                          WHEN ppt.customer_type = 6 THEN "Hospital"
                      END
                    ) AS customer_type_name,
                    (
                      CASE
                          WHEN ppt.customer_type = 1 THEN pm.patient_name
                          WHEN ppt.customer_type = 2 THEN ppm.patient_name
                          WHEN ppt.customer_type = 3 THEN pc.customer_name
                          WHEN ppt.customer_type = 5 || ppt.customer_type = 4 THEN em.EMP_NAME
                          WHEN ppt.customer_type = 6 THEN "Hospital"
                      END
                    ) AS client_name,
                    (
                      CASE 
                        WHEN ppt.type = 1 THEN psm.sale_no
                        WHEN ppt.type = 2 THEN psrm.mr_no
                        WHEN ppt.type = 3 THEN ppt.due_mr_no
                      END
                    ) AS mr_no,
                    ppt.create_time,
                    ppt.type,
                    ppt.return_bill,
                    ppt.amount,
                    ppt.return_less_discount,
                    psm.tot_bill,psm.tot_less_discount,
                    emp.EMP_NAME as emp_name,
                    ppt.overall_discount,
                    ')
            ->from($table)
            ->join('' . $jtable . '', 'psm.id = ppt.source_id and ppt.type = 1', 'left')
            ->join('' . $rtable . '', 'psrm.id = ppt.source_id and ppt.type = 3', 'left')
            ->join('users as u', 'u.id = ppt.collection_by', 'left')
            ->join('hrm_ls_employee as emp', 'emp.EMP_ID = u.employee_id', 'left')
            ->join('admission_patient as ap', '' . $admission_id . ' = ap.id', 'left')
            ->join('patient_master as pm', 'pm.id = ap.patient_id', 'left')
            ->join('pharmacy_customer as pc', 'pc.id = ppt.customer_id', 'left')
            ->join('hrm_ls_employee as em', 'em.EMP_ID = ppt.employee_id', 'left')
            ->join('patient_master as ppm', 'ppm.id = ppt.patient_id', 'left')
            ->where($condition)
            ->order_by('ppt.id', 'asc')
            ->limit($limit, $offset)
            ->get()
            ->result();

        // echo '<pre>';print_r($records);exit;
        // print_r($this->db->last_query($records));

        $data['records'] = $records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/client_wise_report/report/test_details/' . $customer_type . '/' . $client_id . '/' . $pharmacy_id . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'report/pharmacy/client_wise_report/details';
        $data['pharmacy_name'] = ($pharmacy_id == 200) ? "Main Pharmacy" : $this->getPharmacyName($pharmacy_id);

        if ($this->input->is_ajax_request()) {

            echo $this->load->view($list_view, $data, true);
            exit;
        }

        $data['toolbar_title'] = "Client Wise Report Details";


        Template::set($data);
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

    private function getPharmacyName($pharmacy_id = 0)
    {
        $pharmacy_name = "Main Pharmacy";
        if ($pharmacy_id != 200) {
            $row = $this->db->where('id', $pharmacy_id)->get('bf_pharmacy_setup')->row();
            if ($row) {
                $pharmacy_name = $row->name;
            }
        }

        return $pharmacy_name;
    }

    public function client_wise_all_details_information($customer_type, $id, $pharmacy_id)

    {

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['pharmacy_customer_name_flag'] = 0;
        $pharmacy_name = 'Main Pharmacy';

        $condition["1"] = 1;
        if (count($_POST) > 0) {

            if ($this->input->post('from_date')) {
                $from_formated_date = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $this->input->post('from_date'))));
                $condition['ppt.create_time >='] = $from_formated_date;
            }
            if ($this->input->post('to_date')) {
                $to_formated_date = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $this->input->post('to_date'))));
                $condition['ppt.create_time <='] = $to_formated_date;
            }
        }


        if ($customer_type == 1) {
            $condition['admission_patient_id'] = $id;
        } elseif ($customer_type == 2) {
            $condition['patient_id'] = $id;
        } elseif ($customer_type == 3) {
            $condition['customer_id'] = $id;
        } elseif ($customer_type == 4) {
            $condition['employee_id'] = $id;
        } elseif ($customer_type == 5) {
            $condition['employee_id'] = $id;
        } elseif ($customer_type == 6) {
            $condition['customer_type'] = 6;
        }

        if ($pharmacy_id == "200" || $pharmacy_id == "") {
            $records = $this->db->where($condition)
                ->order_by('ppt.id', 'desc')
                ->get('bf_pharmacy_payment_transaction as ppt')
                ->result();
        } else {

            $records = $this->db->where($condition)
                ->order_by('ppt.id', 'desc')
                ->get('bf_pharmacy_sub_payment_transaction as ppt')
                ->result();
        }
        // echo "<pre>";
        // print_r($records);
        //   exit();
        $data['records'] = $records;


        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/client_wise_report/report/client_wise_all_information' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'report/pharmacy_client_wise_report/client_wise_pharmacy_report_all_info';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, compact('records', 'sl'), true);
            exit;
        }


        Template::set($data);
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }


    /* public function test()
     {
       $result = $this->db->select('id,admission_id,created_date')->where('customer_type', 1)->get('pharmacy_sales_mst')->result();

       foreach ($result as $val) {
         $this->db
           ->where('create_time', trim($val->created_date))
           ->where('admission_patient_id', $val->admission_id)
           ->where('type', 1)
           ->where('customer_type', 1)
           ->update('pharmacy_payment_transaction', ['source_id' => $val->id]);
       }
       echo '<pre>';print_r($result);exit;
     } */

}