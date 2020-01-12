<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Patient_admission extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
        $this->load->model('patient/admission_form_model', NULL, TRUE);
         $this->auth->restrict('Report.BillAssesmentSheet.View');
	}

    
    public function index()
    {
        $data = array();
        $obj = new Admissionbill();
        $admission_id = $this->input->get('admission_id');
        $data['admission_fee'] = $this->input->get('admission_fee', true);
        $data['bed_charge'] = $this->input->get('bed_charge', true);
        $data['operation_charge'] = $this->input->get('operation_charge', true);
        $data['ot'] = $this->input->get('ot', true);
        $data['post_op'] = $this->input->get('post_op', true);
        $data['surgeon'] = $this->input->get('surgeon', true);
        $data['surgeon_team'] = $this->input->get('surgeon_team', true);
        $data['anesthesia'] = $this->input->get('anesthesia', true);
        $data['guest_doctor'] = $this->input->get('guest_doctor', true);
        $data['o_blood'] = $this->input->get('o_blood', true);
        $data['consultant'] = $this->input->get('consultant', true);
        $data['out_consultant'] = $this->input->get('out_consultant', true);
        $data['tot_bill'] = $this->input->get('tot_bill', true);
        $data['discount'] = $this->input->get('discount', true);
        $data['refund'] = $this->input->get('refund', true);
        $data['over_all_discount'] = $this->input->get('over_all_discount', true);
        $data['mr_discount'] = $this->input->get('mr_discount', true);
        $data['diagnosis'] = $this->input->get('diagnosis', true);
        $data['paid_amount'] = $this->input->get('paid_amount', true);
        $data['return_amount'] = $this->input->get('return_amount', true);

        $data['os_list'] = $this->db->get("lib_otherservice")->result();
        $data['other_service_bill'] = $obj->OtherServieBillBySubServiceId([2,3,4]);

        $data['admission'] = $this->admission_form_model->find($admission_id);
        $data['record'] = $this->getPersonalInformation($admission_id);
        $data['emp_name'] = $this->getEmpName($admission_id);

        $data['list_view'] = 'patient_admission/index'; 

        Template::set("toolbar_title", "Patient Admission Bill");
        Template::set($data);

       	Template::set_block('sub_nav', 'patient_admission/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }

    public function test() {
        $id = $this->input->get('admission_id', true);
        print_r($id);
    }

    private function getPersonalInformation($id)
    {
        $this->admission_form_model->select("a.id,a.attendant_name,a.attendant_relation,a.attendant_contact_no,a.attendant_address,p.patient_id as patient_no,p.patient_name,p.father_name,p.mother_name,p.blood_group,p.sex,p.birthday,p.contact_no,p.age,a.patient_id,a.bed_id,a.status,a.admission_date,a.id");
        $this->admission_form_model->from('admission_patient AS a');
        $this->db->join('patient_master  as p', 'p.id = a.patient_id');
        $records = $this->admission_form_model->find_by("a.id",$id);
    //print_r($records);exit;
        return $records;
    }

    private function getEmpName($id = 0) {
        $result = $this->db
                    ->select('EMP_ID as id,EMP_NAME as doctor_name,QUALIFICATION as qualification')
                    ->where(['EMP_TYPE'=>1,'STATUS'=>1])
                    ->get('hrm_ls_employee')
                    ->result();
        $data = array();
        if ($result) {
            foreach ($result as $key => $val) {
                $data[$val->id] = $val->doctor_name."".(($val->qualification)? ", ".$val->qualification : "");
            }
        }
        return $data;
    }
    
   
}