<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bill_assesment_sheet extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        $this->load->model('Bill_assesment_sheet_model','basm');
        $this->load->config('patient/config_admission_discharge');
        $this->auth->restrict('Report.BillAssesmentSheet.View');
	}

    
    public function index()
    {
        $obj = new Admissionbill();

        $data = [];
        $con = [];
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Admission Id';
        $search_box['patient_name_flag'] = 1;
        if ($this->input->is_ajax_request()) {
            $patient_name = trim($this->input->post('patient_name', true));
            if ($patient_name) {
                $con["pm.patient_name LIKE '%$patient_name%'"] = null;
            }
            $admission_id = trim($this->input->post('common_text_search', true));
            if ($admission_id) {
                $con["ap.admission_code LIKE '%$admission_id%'"] = null;
            }
            $from_date = trim($this->input->post('from_date', true));
            $to_date = trim($this->input->post('to_date', true));
            if ($from_date) {
                $from_date = custom_date_format($from_date)." 00:00:00";
                $con["ap.admission_date >= '$from_date'"] = null;
            }

            if ($to_date) {
                $to_date = custom_date_format($to_date)." 23:59:59";
                $con["ap.admission_date <= '$to_date'"] = null;
            }
        }
        //print_r($search_box);
        $data['records']=$this->db
        ->select('SQL_CALC_FOUND_ROWS ap.id,ap.admission_code, ap.admission_date, ROUND(ap.admission_fee) as admission_fee, ap.release_date, pm.patient_name, CONCAT(emp.EMP_NAME," ",emp.QUALIFICATION) as doctor_name', false)
        ->from('admission_patient as ap')
        ->join('patient_master as pm','pm.id=ap.patient_id','left')
        ->join('hrm_ls_employee emp','ap.reference_doctor = emp.EMP_ID','left')
        ->where_in('ap.status',[2,3,4])
        //->where_in('ap.status',[2,3])
        ->where($con)
        ->limit($limit, $offset)
        ->get()
        ->result();

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/bill_assesment_sheet/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $data['os_list'] = $this->db->get("lib_otherservice")->result();
        $data['virtual_bill'] = $obj->getAdmissionPatientVirtualBill([2,3,4]);
       // echo '<pre>';print_r($data['virtual_bill']);exit;
        $data['other_service_bill'] = $obj->OtherServieBillBySubServiceId([2,3,4]);
        $data['diagnosis_service_bill'] = $obj->getAdmissionPatientOtherBill(0,[2,3,4], [4]);
        $data['operation_service_bill'] = $obj->getOperationServiceBill();
        $data['consultant_bill'] = $obj->getConsultantBill();
        $data['discount'] = $obj->getAdmissionPatientOtherBill(0,[2,3,4], [2,4]);
        $data['refund'] = $obj->getAdmissionBill([2,3,4], [2,3,4,7]);
        $data['total_paid'] = $obj->getPaid();
      // echo '<pre>';print_r($data['refund']);exit;
        $data['search_box'] = $search_box;
        $data['list_view'] = 'bill_assesment_sheet/index';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "Bill Assesment Sheet");
        Template::set($data);

       	Template::set_block('sub_nav', 'bill_assesment_sheet/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }

    public function test1()
    {
        $data = [];
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Bed Name, Patient Contact No';
        $search_box['admission_id_flag'] = 1;
        $search_box['patient_name_flag'] = 1;
        $search_box['admission_status_list_flag'] = 1;
        $search_box['admission_discharge_reason_list_flag'] = 1;
        $search_box['room_type_list_flag'] = 1;
        $search_box['bed_type_list_flag'] = 1;
        $search_box['due_paid_flag'] = 1;
        $search_box['referred_doctor_list_flag'] = 1;
        $search_box['doctor_type_list_flag'] = 1;
        $search_box['doctor_list_flag'] = 1;
        $search_box['sex_list_flag'] = 1;

        $data['os_list'] = $this->db->get("lib_otherservice")->result();
        $data['reason'] = $this->config->item('reason');

        $data['records'] = $this->basm->getBillAssesmentSheet($limit, $offset);

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/bill_assesment_sheet/report/test1' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $data['search_box'] = $search_box;
        $data['list_view'] = 'bill_assesment_sheet/index1';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        Template::set("toolbar_title", "Bill Assesment Sheet");
        Template::set($data);

        Template::set_block('sub_nav', 'bill_assesment_sheet/_sub_report');
        Template::set_view('report_template');
        Template::render();

    }


    public function test()
    {
        /*      admission tranjaction view        */
      /*
        $sql = "
           SELECT
                ap.id AS admission_id,

                IFNULL(SUM(apt.amount), 0) AS bill_amount,
                IFNULL(SUM(apt.bill_refund_amount), 0) AS bill_refund_amount,
                IFNULL(SUM(apt.discount), 0) AS discount,
                IFNULL(SUM(apt.mr_discount), 0) AS mr_discount,
                IFNULL(SUM(apt.less_discount), 0) AS less_discount,
                SUM(IF(apt.paid_type = 1 , apt.paid, 0)) AS bill_receivable,
                SUM(IF(apt.paid_type = 2 , apt.paid, 0)) AS due_receivable,
                SUM(IF(apt.paid_type = 3 , apt.paid, 0)) AS return_payable
            FROM
                bf_admission_patient AS ap
            LEFT JOIN 
                bf_admission_patient_transaction AS apt
                ON ap.id = apt.admission_id
            WHERE
                apt.source_type != 5
            GROUP BY 
                ap.id
            ORDER BY 
                apt.admission_id
        ";
        */

        /*      admission tranjaction dtls view        */

        $sql = "
            (
                SELECT
                    osm.admission_id,
                    4 AS service_id,
                    osd.service_id AS other_service_id,
                    SUM(osd.total_price) AS other_service_amount,
                    0 AS out_consultant_amount,
                    0 AS consultant_amount,
                    0 AS package_operation_cost,
                    0 AS operation_theater_cost,
                    0 AS post_operative_bed_cost,
                    0 AS surgeon_cost,
                    0 AS surgeon_team_cost,
                    0 AS anesthesia_cost,
                    0 AS guest_doctor_cost,
                    0 AS blood_cost,
                    0 AS medicine_cost,
                    0 AS investigation_amount
                FROM
                    bf_patient_others_service_master as osm
                LEFT JOIN
                    bf_patient_others_service_details AS osd
                        ON osm.id = osd.service_master_id
                GROUP BY 
                    osm.admission_id,
                    osd.service_id
            )
            UNION 
            (
                SELECT
                    acr.admission_id,
                    20 AS service_id,
                    0 AS other_service_id,
                    0 AS other_service_amount,
                    SUM(IF(emp.IS_EXTERNAL = 0, acr.per_patient_price, 0)) AS out_consultant_amount,
                    SUM(IF(emp.IS_EXTERNAL = 1, acr.per_patient_price, 0)) AS consultant_amount,
                    0 AS package_operation_cost,
                    0 AS operation_theater_cost,
                    0 AS post_operative_bed_cost,
                    0 AS surgeon_cost,
                    0 AS surgeon_team_cost,
                    0 AS anesthesia_cost,
                    0 AS guest_doctor_cost,
                    0 AS blood_cost,
                    0 AS medicine_cost,
                    0 AS investigation_amount
                FROM
                    bf_admission_consultant_round AS acr
                LEFT JOIN
                    bf_hrm_ls_employee AS emp 
                        ON acr.consultant_id = emp.EMP_ID 
                GROUP BY 
                    acr.admission_id
            )
            UNION
            (
                SELECT
                    oao.admission_id,
                    10 AS service_id,
                    0 AS other_service_id,
                    0 AS other_service_amount,
                    0 AS out_consultant_amount,
                    0 AS consultant_amount,
                    SUM(oao.doctor_settle_amount) AS package_operation_cost,
                    SUM(os.operation_theater_cost) AS operation_theater_cost,
                    SUM(os.post_operative_bed_cost) AS post_operative_bed_cost,
                    SUM(os.surgeon_cost) AS surgeon_cost,
                    SUM(os.surgeon_team_cost) AS surgeon_team_cost,
                    SUM(os.anesthesia_cost) AS anesthesia_cost,
                    SUM(os.guest_doctor_cost) AS guest_doctor_cost,
                    SUM(os.blood_cost) AS blood_cost,
                    SUM(IF(os.medicine_cost_paid_by = 1, os.medicine_cost, 0)) AS medicine_cost,
                    0 AS investigation_amount
                FROM
                    bf_operation_admission_operation AS oao
                LEFT JOIN
                    bf_operation_schedule AS os
                        ON oao.id = os.admission_operation_id
                WHERE
                    oao.is_refund != 1
                GROUP BY
                    oao.admission_id
            )
            UNION
            (
                SELECT
                    apt.admission_id,
                    1 AS service_id,
                    0 AS other_service_id,
                    0 AS other_service_amount,
                    0 AS out_consultant_amount,
                    0 AS consultant_amount,
                    0 AS package_operation_cost,
                    0 AS operation_theater_cost,
                    0 AS post_operative_bed_cost,
                    0 AS surgeon_cost,
                    0 AS surgeon_team_cost,
                    0 AS anesthesia_cost,
                    0 AS guest_doctor_cost,
                    0 AS blood_cost,
                    0 AS medicine_cost,
                    (SUM(apt.amount) - SUM(apt.less_discount)) AS investigation_amount
                FROM
                    bf_admission_patient_transaction as apt
                WHERE
                    apt.source_type = 4
                GROUP BY
                    apt.admission_id
            )
        ";

        $records = $this->db->query($sql)->result();
        echo "<pre>";print_r($records);exit;
    }

    
   
}