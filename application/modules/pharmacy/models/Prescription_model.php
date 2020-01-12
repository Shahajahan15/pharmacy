<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Prescription_model extends BF_Model {

	protected $table_name	= "prescription_master";
	protected $key			= "id";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";

	protected $log_user 	= FALSE;

	protected $set_created	= false;
	protected $set_modified = false;

	/*
		Customize the operations of the model without recreating the insert, update,
		etc methods by adding the method names to act as callbacks here.
	 */
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 		= array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	/*
		For performance reasons, you may require your model to NOT return the
		id of the last inserted row as it is a bit of a slow method. This is
		primarily helpful when running big loops over data.
	 */
	protected $return_insert_id 	= TRUE;

	// The default type of element data is returned as.
	protected $return_type 			= "object";

	// Items that are always removed from data arrays prior to
	// any inserts or updates.
	protected $protected_attributes = array();

	/*
		You may need to move certain rules (like required) into the
		$insert_validation_rules array and out of the standard validation array.
		That way it is only required during inserts, not updates which may only
		be updating a portion of the data.
	 */
	protected $validation_rules = array(
		array(
			"field"		=> "doctor_department_name",
			"label"		=> "Department Name",
			"rules"		=> "required|max_length[255]"
		),
                array(
			"field"		=> "doctor_department_status",
			"label"		=> "Status",
			"rules"		=> "max_length[1]"
		),


	);
	protected $insert_validation_rules 	= array();
	protected $skip_validation 		= FALSE;

	//--------------------------------------------------------------------

	/* get waiting patient list  */

	public function get_waiting_patient_list($user_id = 0,$array)
	{
		//print_r($user_id);
		//print_r($array);
		//print_r($array);exit;
		$this->db->select('pm.patient_id,pm.patient_name,pm.sex,pm.birthday as dob,pt.ticket_type,pt.patient_type, pt.id');
		$this->db->from('outdoor_patient_ticket as pt');
		$this->db->join('patient_master as pm','pm.id = pt.patient_id');
		if($user_id != 0){
			$this->db->where('pt.doctor_id',$user_id);
		}
		$this->db->where('pt.ticket_type !=',3);
		//$this->db->where('pt.is_prescription',$status);
		if($array){
		$this->db->where($array);

	}

		$result = $this->db->get()->result();
		return $result;
	}

	public function get_patient_info($ticket_id)
	{
		$this->db->select('pm.id as p_id,pm.patient_id,pm.patient_name,pm.sex,pm.birthday as dob,pt.ticket_type,pt.patient_type,pt.id as ticket_id,pt.doctor_id');
		$this->db->from('outdoor_patient_ticket as pt');
		$this->db->join('patient_master as pm','pm.id = pt.patient_id');
		//$this->db->where('patient_event.status',1);
		$this->db->where('pt.id',$ticket_id);
		$row = $this->db->get()->row();
		return $row;
	}

	/* get prescription info     */

	public function get_prescription_info($ticket_id = 0)
	{
		$this->db->select('prescription_master.*');
		$this->db->where('prescription_master.ticket_id',$ticket_id);
		$row = $this->db->get('prescription_master')->row();
		return $row;
	}

	/*   insert   prescription  */
	public function add_prescription ($user_id, $id = 0)
	{
		if($this->input->post('is_admitted')) {
			$admitted = 1;
		} else {
			$admitted = 0;
		}
		$d_discount = 0;
		if ($this->input->post('d_discount')) {
			$d_discount = $this->input->post('d_discount');
		}
		$next_date = custom_date_format($this->input->post('next_visit'));
		$add_pres = array(
            'ticket_id' => $this->input->post('ticket_id'),
            'patient_id' => $this->input->post('p_id'),
            'doctor_id' => $this->input->post('doctor_id'),
            'height' => $this->input->post('height'),
            'weight' => $this->input->post('weight'),
            'temp' => $this->input->post('temp'),
            'pulse' => $this->input->post('pulse'),
            'bp' => $this->input->post('bp'),
            'cvs' => $this->input->post('cvs'),
            'resp_sys' => $this->input->post('resp_sys'),
            'nervous_sys' => $this->input->post('nervous_sys'),
            'abdomen' => $this->input->post('abdomen'),
            'b_group' => $this->input->post('b_group'),
            'cc' => $this->input->post('cc'),
            'ph' => $this->input->post('ph'),
            'oe' => $this->input->post('oe'),
            'dh' => '',
            'd_discount' => $d_discount,
            'next_visit' => $next_date?:NULL,
            'advice' => $this->input->post('advice'),
            'symptom_id' => $this->input->post('symptom_id'),
            'is_admitted' => $admitted,
            'create_by' => $user_id
        );

        if(!$id){
			$this->db->insert('prescription_master', $add_pres);
			$id = $this->db->insert_id();
		} else {
			$this->db->where('id', $id);
			$this->db->update('prescription_master', $add_pres);
		}


		// echo '<pre>'; print_r($id);exit;
		return $id;
	}

	/*   insert   prescription medicine  */
	public function add_prescription_medicine ($pre_id)
	{
		if(!isset($_POST['medicine_id'])){
			return true;
		}
		$count = count($this->input->post('medicine_id'));
		for ($i = 0; $i < $count; $i++) {
		$add_pres[] = array(
            'prescription_id' => $pre_id,
            'medicine_id' => $this->input->post('medicine_id')[$i],
            'roule_id' => $this->input->post('roule_id')[$i],
            'duration' => $this->input->post('duration')[$i],
            'advice_medicine' => $this->input->post('advice_medicine')[$i]
        );
         //$this->db->insert('prescription_master', $add_pres);
        }

      // echo '<pre>'; print_r($add_pres);exit;
         if (count($add_pres) > 0 && $add_pres) {
        $this->db->insert_batch('prescription_medicine', $add_pres);
		$id = $this->db->insert_id();
		} else {
			$id = false;
		}
		return $id;
	}

	/*   insert   prescription diagnosis test  */
	public function add_prescription_diagnosis ($pre_id,$patient_id, $user_id)
	{
		//echo '<pre>';print_r($_POST);exit;
		if(!isset($_POST['test_id'])){
			if(!isset($_POST['package_id'])){
				return true;
			}else{
				return $this->add_prescription_pack_diagnosis($pre_id,$patient_id, $user_id);
			}
		}
		//add test
		$count = count($this->input->post('test_id'));
		$test_id = $this->input->post('test_id');
		for ($i = 0; $i < $count; $i++) {
			if(strlen($test_id[$i]) != null) {

				$add_diag[] = array(
	            'patient_id' => $patient_id,
	            'prescription_id' => $pre_id,
	            'test_id' => $test_id[$i],
	            'patient_type' => 2,
	            'diagnosis_type' => 1, //Normal test
	            'quntity' => $this->input->post('test_quantity')[$i],
	            'advice_test' => $this->input->post('advice_test')[$i],
	            'created_by' => $user_id,
	        );
			}
        }

        if (count($add_diag) > 0 && $add_diag) {
        $this->db->insert_batch('bf_prescription_admission_diagnosis', $add_diag);
		$id = $this->db->insert_id();
		} else {
			$id = false;
		}
		if($this->add_prescription_pack_diagnosis ($pre_id,$patient_id, $user_id)){
			return $id;
		}else{
			return false;
		}

	}
	//package test
	public function add_prescription_pack_diagnosis ($pre_id,$patient_id, $user_id)
	{
		//add test
		$count = count($this->input->post('package_id'));
		$package_id = $this->input->post('package_id');
		for ($i = 0; $i < $count; $i++) {
			if(strlen($package_id[$i]) != null) {
				$add_diag[] = array(
	            'patient_id' => $patient_id,
	            'prescription_id' => $pre_id,
	            'package_id' => $package_id[$i],
	            'test_id' => 0,
	            'patient_type' => 2,
	            'diagnosis_type' => 2, //Package Test
	            'quntity' => 1,
	            'advice_test' => $this->input->post('advice_test')[$i],
	            'created_by' => $user_id,
	        );
			}
        }

        if (count($add_diag) > 0 && $add_diag) {
        $this->db->insert_batch('bf_prescription_admission_diagnosis', $add_diag);
		$id = $this->db->insert_id();
		} else {
			$id = false;
		}
		return $id;
	}

	/*   get pation admission list    */
	public function get_bed_requisition_list ($where)
	{
		$this->db->select('prmst.*,pmst.patient_name,pmst.patient_id,t.doctor_id,t.receipt_no');
		$this->db->from('prescription_master as prmst');
		$this->db->join('outdoor_patient_ticket as t','prmst.ticket_id=t.id');
		$this->db->join('patient_master as pmst','prmst.patient_id=pmst.id');
		$this->db->where('prmst.is_admitted',1);
		$this->db->where('prmst.is_bed_approve',0);
		$this->db->where($where);
		$result = $this->db->get()->result();
		//echo $this->db->last_query();
		return $result;
	}

	/*    serial       */

	public function getTicketId($id)
	{
		$row = $this->db
			->where('appointment_type',5)
			->where('ticket_type',3)
			->where('source_id',$id)
			->get('outdoor_patient_ticket')
			->row()->id;
			return $row;
	}

	public function getPatientInfo($id, $user_id, $type) {
		$this->db->trans_begin();
		$Obj = new Commonservice();
		$serial_info = $this->getSerialInfo($id);
		  $patient_type = 2;
		 if (!$serial_info->patient_id) {
		 	/*     patient master add     */
		 	$patient_id = $this->PatientMasterAdd($serial_info, $user_id);
		 	$patient_type = 1;
		 }

		 $patient_id = ($patient_type == 2) ? $serial_info->patient_id : $patient_id;
		  $this->updateAppoinment($serial_info,$patient_id, $type);
		 $data = array(
		 	'receipt_no' => $serial_info->serial_no,
		 	'patient_id' => $patient_id,
		 	'patient_type' => $patient_type,
		 	'source_id' => $id,
		 	'ticket_type' => 3,
		 	'appointment_type' => 5,
		 	'appointment_date' => date('Y-m-d'),
		 	'doctor_id' => $serial_info->doctor_id,
		 	'created_by' => $user_id,
		 );
		 $this->db->insert('outdoor_patient_ticket', $data);
		 $ticket_id = $this->db->insert_id();
		 $his_id = ($type == 1) ? 16 : 15;
		 $Obj->patientHistoryAdd($patient_id, $ticket_id, $his_id);

		$result = $this->get_patient_info($ticket_id);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			if ($type == 1) {
				redirect(SITE_AREA .'/doctor/doctor/serial_show_list');
			} else {
				redirect(SITE_AREA .'/doctor_assistant/doctor/serial_show_list');
			}
			} else {
				$this->db->trans_commit();
			 return $result;
			}
	}

	public function updateAppoinment($serial_info,$patient_id, $type) {
		/*       update    appointment patient    */
		 	$a_data['patient_id'] = $patient_id;
		 	$a_data['t_status'] = 1;
		 	//$a_data['is_prescription'] = ($type == 1) ? 2 : 1;
		 	$this->db->where('appointment_patients.id',$serial_info->id);
		 	$this->db->update('appointment_patients', $a_data);
	}

	public function getSerialInfo($id) {
		$serial_info = $this->db
						->select('dtls.*,ddi.doctor_id,mst.serial_no')
						->from('appointment_details as mst')
						->join('appointment_patients as dtls','mst.id = dtls.appointment_id')
						->join('doctor_doctor_info as ddi', 'mst.doctor_id = ddi.id')
						->where('mst.id',$id)
						->get()
						->row();
		return $serial_info;
	}

	public function PatientMasterAdd($serial_info, $user_id)
	{
		$Obj = new Commonservice();
		$patient_code = $Obj->getPatientCode();
		 	$p_data = array(
		 		'patient_id' => $patient_code,
		 		'patient_name' => $serial_info->patient_name,
		 		'age' => $serial_info->age,
		 		'sex' => $serial_info->sex,
		 		//'birthday' => age_convert_dob($serial_info->age),
		 		'birthday' => $serial_info->birthday,
		 		'blood_group' => $serial_info->blood_group,
		 		'email' => $serial_info->email,
		 		'address' => $serial_info->address,
		 		'contact_no' => $serial_info->contact_no,
		 		'source' => 7,
		 		'created_by' => $user_id,
		 	);
		 	$this->db->insert('patient_master', $p_data);
		 	$patient_id = $this->db->insert_id();
		 	return $patient_id;
	}

}
