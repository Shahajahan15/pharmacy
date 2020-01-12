<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Patient_overall_discount extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		
		$this->load->model('patient/admission_form_model', NULL, true);
		$this->lang->load('common');
		Assets::add_module_js('library', 'patient_overall_discount');		
	}

    
	 
    public function create()
    {
		$admission_patient_bill=$this->admission_form_model->getAdmissionPatientDayBill();
		$admission_patients=$this->db->select("
									bf_patient_master.patient_name,
									bf_admission_patient.discount_type,
									bf_admission_patient.over_all_discount,
									bf_admission_patient.id,
								")
								->join('bf_patient_master','bf_patient_master.id=bf_admission_patient.patient_id')
								->where('status',3)
								->get('bf_admission_patient')
								->result();

		//echo '<pre>';print_r($admission_patient_bill);die();
		$data['admission_patients']=$admission_patients;
		$data['admission_patient_bill']=$admission_patient_bill;
		
		
		Template::set($data);
		Template::set('toolbar_title', 'Overall Discount Create');
        Template::set_view('ovarall_discount/create');
        Template::render();
    }
	

	
	
	public function save()
	{
		$data['over_all_discount']=$this->input->post('over_all_discount');
		$id=$this->input->post('admission_id');
		if($this->db->where('id',$id)->update('bf_admission_patient',$data)){
			echo json_encode(['status'=>1,'message'=>'Overall Discount Successfully Done']);
		}
		
	
	}


	
}

?>
