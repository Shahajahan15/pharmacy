<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//--------------------------------------------------------------------
/**
 * Summary
 * @param string	 $resultType[optional]	The resultType value will be "Object" or "Array", DEFAULT value "Array"
 * @return By DEFAULT return Array Otherwise Object
 * */
//--------------------------------------------------------------------

class Commonservice{

    private $CI;
    private $c_user;
    public function __construct() {
    	$this->CI = & get_instance();
    	$this->CI->load->helper('url');
        $this->CI->load->library('session');
        $this->CI->config->item('base_url');
        $this->CI->load->database();
        $this->c_user =$this->CI->session->userdata();

    }

    public function serviceSubServiceName($service_id=0, $detail_id = 0,$sub_service_id=0) {
    	$service_name = "";
    	if ($service_id == 1) {
            $con = [];
            if($detail_id){
            	$con['pdd.id'] = $detail_id;
            }
            else{
            	$con['ptm.id'] = $sub_service_id;
            }
    		$service_name = $this->CI->db
    						->select('ptm.test_name')
    						->from('pathology_diagnosis_details as pdd')
    						->join('pathology_test_name as ptm', 'pdd.test_id = ptm.id')
    						->where($con)
    						->get()
    						->row();
    		if($service_name)
    		{
    		  $service_name = $service_name->test_name;	
    		}
    	} elseif($service_id == 4) {
    		$service_name = $this->CI->db
    						->select('otherservice_name')
    						->from('patient_others_service_details as posd')
    						->join('lib_otherservice as lo', 'posd.service_id = lo.id')
    						->where('posd.id', $detail_id)
    						->get()
    						->row()->otherservice_name;
    	}

    	return $service_name;
    }

    /*           doctor discount         */

    public function doctorPatientDiscount($test_id = 0, $patient_id = 0, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0) {

    	$result = ['result' => false];
    	
    	$row = $this->getDoctorWiseDiscount(1, $test_id, $patient_id, $agent_type, $agent_id, $service_id, $sub_service_id
    		);
    	//print_r($row);exit;
    	$test_price = $this->getTestPrice($test_id);

    	if ($row) {
    		if ($row->discount_type	== 1) {
    			$result = [
    				'result' => true,
    				'id' => $row->id,
    				'commission_id' => $row->commossion_id,
    				'dr_discount_percent' => $row->dr_discount,
    				'dr_discount_taka' => percent_convert_amount($row->dr_discount, $test_price),
    				'hospital_discount_percent' => $row->hospital_discount,
    				'hospital_discount_taka' => percent_convert_amount($row->hospital_discount, $test_price)
    				];
    		} elseif ($row->discount_type == 2) {
    			$row1 = $this->getDoctorWiseDiscount(2, $test_id, $patient_id, $agent_type, $agent_id, $service_id, $sub_service_id);
    			$result = [
    				'result' => true,
    				'id' => $row->id,
    				'commission_id' => $row->commossion_id,
    				'dr_discount_percent' => $row->dr_discount,
    				'dr_discount_taka' => percent_convert_amount($row->dr_discount, $test_price),
    				'hospital_discount_percent' => $row->hospital_discount,
    				'hospital_discount_taka' => percent_convert_amount($row->hospital_discount, $test_price)
    				];
    		}
    	}
    	return $result;
    }

    public function getTestPrice($test_id) {
    	$row = $this->CI->db->select("test_taka")->where("id",$test_id)->get("pathology_test_name")->row();
    	$test_price = 0;
    	if ($row) {
    		$test_price = $row->test_taka;
    	}

    	return $test_price;
    }

    public function getDoctorWiseDiscount($type = 0,$test_id = 0, $patient_id = 0, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0) {
    	$where = [
		    		"patient_id" => $patient_id, 
		    		"agent_type" => $agent_type,
		    		"agent_id" => $agent_id,
		    		"service_id" => $service_id,
		    		"status" => 1 
    			];
    	if ($type == 2) {
    		$where = [
		    		"patient_id" => $patient_id, 
		    		"agent_type" => $agent_type,
		    		"agent_id" => $agent_id,
		    		"service_id" => $service_id,
		    		"sub_service_id" => $sub_service_id,
		    		"status" => 1 
    			];
    	}

    	//print_r($where);exit;
    	
    	$row = $this->CI->db
    			->where($where)
    			->order_by("id","desc")
    			->where('DATE(approved_at)', date('Y-m-d'))
    			->get("lib_doctor_wise_discount")
    			->row();
    	//print_r($where);exit;
    	//print_r($row);exit;
    	return $row;
    }
 /*         edit  doctor discount         */

    public function getDoctorPatientDiscount($test_id = 0, $patient_id = 0, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0, $dwd_id = 0) {

    	$result = ['result' => false];
    	
    	$row = $this->getDoctorDiscount(1, $test_id, $patient_id, $agent_type, $agent_id, $service_id, $sub_service_id, $dwd_id
    		);
    	//print_r($row);exit;
    	$test_price = $this->getTestPrice($test_id);

    	if ($row) {
    		if ($row->discount_type	== 1) {
    			$result = [
    				'result' => true,
    				'id' => $row->id,
    				'commission_id' => $row->commossion_id,
    				'dr_discount_percent' => $row->dr_discount,
    				'dr_discount_taka' => percent_convert_amount($row->dr_discount, $test_price),
    				'hospital_discount_percent' => $row->hospital_discount,
    				'hospital_discount_taka' => percent_convert_amount($row->hospital_discount, $test_price)
    				];
    		} elseif ($row->discount_type == 2) {
    			$row1 = $this->getDoctorDiscount(2, $test_id, $patient_id, $agent_type, $agent_id, $service_id, $sub_service_id, $dwd_id);
    			$result = [
    				'result' => true,
    				'id' => $row->id,
    				'commission_id' => $row->commossion_id,
    				'dr_discount_percent' => $row->dr_discount,
    				'dr_discount_taka' => percent_convert_amount($row->dr_discount, $test_price),
    				'hospital_discount_percent' => $row->hospital_discount,
    				'hospital_discount_taka' => percent_convert_amount($row->hospital_discount, $test_price)
    				];
    		}
    	}
    	return $result;
    }

    public function getDoctorDiscount($type = 0,$test_id = 0, $patient_id = 0, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0, $dwd_id = 0) {
    	$where = [
		    		"id" => $dwd_id, 
		    		"patient_id" => $patient_id, 
		    		"agent_type" => $agent_type,
		    		"agent_id" => $agent_id,
		    		"service_id" => $service_id,
		    		"status" => 2 
    			];
    	if ($type == 2) {
    		$where = [
		    		"id" => $dwd_id, 
		    		"agent_type" => $agent_type,
		    		"agent_id" => $agent_id,
		    		"service_id" => $service_id,
		    		"sub_service_id" => $sub_service_id,
		    		"status" => 2 
    			];
    	}

    	//print_r($where);exit;
    	
    	$row = $this->CI->db
    			->where($where)
    			->order_by("id","desc")
    			->where('DATE(approved_at)', date('Y-m-d'))
    			->get("lib_doctor_wise_discount")
    			->row();
    	//print_r($where);exit;
    	//print_r($row);exit;
    	return $row;
    }


	public function patient_discount($patient_id=0,$service_id=0,$sub_service_id=0){
		
		$patient=$this->CI->db->where('id',$patient_id)->get('bf_patient_master')->row();
		if(!$patient){
			$discount['request_status']=0;
			$discount['message']='Patient Not Found';
			return 0;
		}

		//print_r($patient);exit();
		
		$patient_type_id=$patient->patient_type_id;
		$patient_sub_type_id=$patient->patient_sub_type_id;

		//patient type id ==0 hole normal patient id hobe		
		if($patient_type_id){
			//echo '11';
			//jodi Normal patient na hoi
			$today=date('Y-m-d 00:00:00');
			$condition['patient_type_id']=$patient_type_id;
			$condition['patient_sub_type_id']=$patient_sub_type_id;
			$condition['service_id']=$service_id;


			$condition['date_start <=']=$today;
			$condition['date_end >=']=$today;

			$discount=$this->CI->db
					->where($condition)
					->where("(sub_service_id=$sub_service_id OR sub_service_id=0)")
					->where("status",1)
					->order_by('id','DESC')
					->get('bf_discount')
					->row_array();

			$discount['discount_nature']=2; //type discount=(corporate,quata etc)
			$discount['request_status']=1;
			$discount['sub_service_id']=$sub_service_id;

			$discount['message']='Discount Found';
			$discount['discount_type']=1;
			$discount['has_overall_discount'] = 1;
			
		}else{
			//echo '22';
			//jodi normal patient hoi
			//tar patient id diye mast er ebong sub thakle details e check korte hobe..
			
			
			$today=date('Y-m-d');

			$condition['bf_lib_patient_discounts_mst.patient_id']=$patient_id;
			$condition['bf_lib_patient_discounts_mst.discount_start_date <=']=$today;
			$condition['bf_lib_patient_discounts_mst.discount_end_date >=']=$today;

			$discount=$this->CI->db->select("bf_lib_patient_discounts_dtls.*, bf_lib_patient_discounts_mst.has_overall_discount")
					->join('bf_lib_patient_discounts_dtls','bf_lib_patient_discounts_dtls.discount_mst_id=bf_lib_patient_discounts_mst.id')
					->where($condition)
					->where('bf_lib_patient_discounts_dtls.service_id',$service_id)
					->where('bf_lib_patient_discounts_dtls.sub_service_id',$sub_service_id)
					->where('bf_lib_patient_discounts_mst.approve_status', 1)
					->get('bf_lib_patient_discounts_mst')->row_array();
					//print_r($discount);exit;

			
			//print_r($discount);exit;
			if(!$discount){
				$discount=$this->CI->db->select("bf_lib_patient_discounts_mst.*")
					->where($condition)
					->where('bf_lib_patient_discounts_mst.service_id',$service_id)
					->where('bf_lib_patient_discounts_mst.approve_status', 1)
					->get('bf_lib_patient_discounts_mst')->row_array();

			}
			$discount['sub_service_id']=$sub_service_id;
			$discount['discount_nature']=1; //Normal Patient discount=(patient type=0)
			$discount['request_status']=1;
			$discount['message']='Discount Found';
			
		}

		if(isset($discount['discount'])){
			$discount['patient_type_id']=$patient_type_id;
			$discount['patient_sub_type_id']=$patient_sub_type_id;
			
			return (object)$discount;
		}else{
		//	echo '33';
			$discount['discount_mst_id']=0;
			$discount['request_status']=1;
			$discount['message']='Discount Not Found';
			$discount['id']=0;
			$discount['patient_type_id']=$patient_type_id;
			$discount['patient_sub_type_id']=$patient_sub_type_id;
			$discount['discount']=0;
			$discount['service_id']=$service_id;
			$discount['sub_service_id']=$sub_service_id;
			$discount['discount_type']=0;

			return (object)$discount;
		}
	}
// Discount For Campaign
public function campaignDiscount(){
			$today=date('Y-m-d 00:00:00');			
			$condition['date_start <=']=$today;
			$condition['date_end >=']=$today;			
			$result = ['result' => false];
			$row =$this->CI->db
					->where($condition)
					->where("status",1)
					->where("is_campaign",1)
					->order_by('id','DESC')
					->get('bf_discount')
					->row();
		if($row){
    		if ($row->discount_type	== 1) {	// 1= overall
			$result['result']=true;
			$result['discount_nature']=2; //type discount=(corporate,quata, campaign etc)
			$result['request_status']=1;
			$result['message']='Discount Found';
			$result['has_overall_discount'] = 1;
			$result['id'] =$row->id;
			$result['discount_type']= $row->discount_type; 
			$result['patient_type_id'] =$row->patient_type_id;
			$result['patient_sub_type_id'] =$row->patient_sub_type_id;
			$result['service_id'] =$row->service_id;
			$result['sub_service_id'] =$row->sub_service_id;
			$result['discount'] =$row->discount;
			return $result;
			}
		}/*else{		
			$result['result']=false;
			$result['discount_nature']=0; //type discount=(corporate,quata, campaign etc)
			$result['request_status']=0;
			$result['message']='Discount Found';
			$result['has_overall_discount'] = 0;
			$result['id'] =0;
			$result['discount_type']= 0; 
			$result['patient_type_id'] =0;
			$result['patient_sub_type_id'] =0;
			$result['service_id'] =0;
			$result['sub_service_id'] =0;
			$result['discount'] =0;
			return $result;
		}*/
	}


	private function getDiscountCheck($test_id = 0) 
	{
		$discount_check = true;
		$test_info = $this->CI->db
					->select('free_status')
					->where('id', $test_id)
					->get('bf_pathology_test_name')
					->row();
				if ($test_info) {
					$discount_check = ($test_info->free_status == 0) ? true : false;
				}
		return $discount_check;
	}
	
	/*     all discount insert     */
	/**
	* 
	* @param undefined $data_info
	* @param undefined $user
	* get array(patient_id,service_id,sub_service_id, amount/price,source_id,source,admission_id,prescription_id)
	* @return
	*/
	
	public function allDiscountAdd ($data_info, $service_id, $amount, $qnty, $d_type)
	{
		/*echo "<pre>";
	
		print_r($d_type);
		exit();*/
		if (!$data_info['patient_id']){

			return FALSE;
		}


		$count = count($service_id);
		for ($j = 0; $j < $count; $j++){
			$discount_check = true;
			if ($data_info['service_id'] == 1) {
				$discount_check = $this->getDiscountCheck($service_id[$j]);
			}


			
			$discount_info = $this->patient_discount($data_info['patient_id'], $data_info['service_id'], $service_id[$j]);
			//echo '<pre>';print_r($discount_info);exit;
			if($discount_info && $discount_info->discount && isset($d_type[$j]) && $discount_check){
				//echo "hello";exit();
				$discount_type = ($discount_info->discount_type == 1) ? 2 : 1;
				if ($discount_type == 2) {
					$discount_persent = $discount_info->discount;
					$discount_amount = percent_convert_amount($discount_info->discount, $amount[$j]);
					$discount_amount = ($discount_amount * $qnty[$j]);
				} else {
					$discount_persent = 0;
					$discount_amount = ($discount_info->discount * $qnty[$j]);
				}
				$data = array(
						//'discount_id' => $discount_info->id,
						'discount_id' => 0,
						'source_id' => $data_info['id'],
						'source' => $data_info['source'],
						'patient_id' => $data_info['patient_id'],
						'admission_id' => isset($data_info['admission_id']) ? $data_info['admission_id'] : 0,
						'prescription_id' => isset($data_info['prescription_id']) ? $data_info['prescription_id'] : 0,
						'discount_type' => $discount_type,
						'discount_persent' => $discount_persent,
						'discount_amount' => $discount_amount,
						'created_by' => $this->c_user['user_id'],
						'service_id' => isset($discount_info->service_id) ? $discount_info->service_id : 0,
						'sub_service_id' => isset($discount_info->sub_service_id) ? $discount_info->sub_service_id : 0,
						'patient_type_id' => $discount_info->patient_type_id,
						'patient_sub_type_id' => $discount_info->patient_sub_type_id,
						'specific_discount' => isset($discount_info->has_overall_discount) ? $discount_info->has_overall_discount : 0,
						'qnty' => $qnty[$j]
					);
					//echo"<pre>";print_r($data);exit();
							
				$this->CI->db->insert('bf_all_discount',$data);
			}

			


		}
	}

	/*        doctor or reference discount      */

	public function doctorReferenceDiscount($data = array()){


		$count = count($data['dr_discount_id']);

		for ($i = 0; $i < $count; $i++){
			$drw_info = $this->CI->db->where('id',$data['dr_discount_id'][$i])->get('lib_doctor_wise_discount')->row();
			$discount_check = $this->getDiscountCheck($data['sub_service_id'][$i]);
			if ($data['dr_discount_id'][$i] > 0 && $discount_check) {
				$arr = [
				'discount_id' => $data['dr_discount_id'][$i],
				'source_id' => $data['id'],
				'source' => $data['source'],
				'patient_id' => $data['patient_id'],
				'admission_id' => isset($data['admission_id']) ? $data['admission_id'] : 0,
				'discount_type' => 2,	
				'qnty' => $data['qnty'][$i],	
				'doctor_discount_percent' => $data['total_percent'][$i],
				'discount_amount' =>  ($data['hr_amount'][$i] * $data['qnty'][$i]),
				'doctor_discount_amount' => ($data['dr_amount'][$i] * $data['qnty'][$i]),
				'created_by' => $this->c_user['user_id'],
				'service_id' => $data['service_id'],
				'sub_service_id' => $data['sub_service_id'][$i],
				'specific_discount' => $drw_info->discount_type,
				'type' => 1
			];
			$this->CI->db->insert('all_discount', $arr);
			}
		}
	}
	
	/*     get patient code      */
	
	public function getPatientCode()
	{
		$row = $this->CI->db
		    ->select('patient_id','id')
			//->select_max('id')
			->where('Date(created_date)',date('Y-m-d'))
			->order_by('id','desc')
			->get('patient_master')
			->row();
		
		if(!$row){
			$patient_code = "P".date('ymd')."1";
		}else{
			$incr_id = substr($row->patient_id,7);
			$incr_id++;
			$patient_code = "P".date('ymd').$incr_id;
		}
		return $patient_code;
	}
	
	/*    patient history */
	
	public function patientHistoryAdd($patient_id = 0,$source_id = 0, $source_type = 0, $admission_id = 0)
	{
		if ($patient_id && !$admission_id) {
			$admission_id = $this->getHistoryAdmissionId($patient_id);
		}
		if ($admission_id && !$patient_id) {
			$patient_id = $this->getHistoryPatientId($admission_id);
		}
        
		$data = array(
			'patient_id' => isset($patient_id) ? $patient_id : 0,
			'admission_id' => isset($admission_id) ? $admission_id : 0,
			'source_id' => $source_id,
			'source_type' => $source_type,
			'created_by' => $this->c_user['user_id'],
			'remark' => '',
		);

		//echo '<pre>';print_r($data);exit;
		$this->CI->db->insert('patient_history', $data);
	}

	public function getHistoryAdmissionId($patient_id = 0) {
		$row = $this->CI->db
							->where('patient_id', $patient_id)
							->order_by('id','desc')
							->get('admission_patient')
							->row();
	  $admission_id = 0;
	  if ($row) {
	  	$admission_id = $row->id;
	  }
	  return $admission_id;
	}

	public function getHistoryPatientId($admission_id = 0) {
		$row = $this->CI->db
							->where('id', $admission_id)
							->get('admission_patient')
							->row();
	  $patient_id = 0;
	  if ($row) {
	  	$patient_id = $row->patient_id;
	  }
	  return $patient_id;
	}

	public function allDiscountByProduct($data){
		
	}


	/*
		@ Admission Patient Total Bill calculation
	*/
	public function operation_total_bill($admission_operation_id=0){
        
        $record=$this->CI->db->where('id',$admission_operation_id)->get('bf_operation_admission_operation')->row();

        $total_bill=0;
        if($record){
            $total_bill+=$record->doctor_settle_amount;
        }

        if($record->operation_type==1){
        	$other_cost=$this->CI->db->where('admission_operation_id',$admission_operation_id)->get('bf_operation_schedule')->row();
        	if($other_cost){
        		$medicine_cost = ($other_cost->medicine_cost_paid_by == 1) ? $other_cost->medicine_cost : 0;
            	$total_bill += (
            			$other_cost->post_operative_bed_cost
            			+
            			$other_cost->guest_doctor_cost
            			+
            			$other_cost->blood_cost
            			+
            			$other_cost->operation_theater_cost
            			+
            			$other_cost->anesthesia_cost
            			+
            			$other_cost->surgeon_cost
            			+
            			$other_cost->surgeon_team_cost
            			+
            			$medicine_cost
            		);
        	}
        }
        return $total_bill;
        
    }

    /*         admission patient tranjaction */

    public function admissionPatientTransaction($data = array(), $user_info) {
    	$admission_data = array(
					'admission_id' => $data['admission_id'],
					'counter_id' => $user_info->id,
					'source_id' => isset($data['source_id']) ? $data['source_id'] : 0,
					'source_type' => $data['source_type'],
					'amount' => isset($data['amount']) ? $data['amount'] : 0,
					'discount' => isset($data['discount']) ? $data['discount'] : 0,
					'less_discount' => isset($data['less_discount']) ? $data['less_discount'] : 0,
					'paid' => isset($data['paid']) ? $data['paid'] : 0,
					'paid_type' => $data['paid_type'],
					'created_by' => $user_info->id
				);
			$this->CI->db->insert('admission_patient_transaction', $admission_data);
    }

    public function patientInfoByAdmissionId($admission_id=0)
	{
		
		if($admission_id)		
		{
			$patientData=$this->CI->db->select("
								p.id as patient_id,
								p.contact_no,
								ad.admission_code,
								p.patient_id as patient_code,	
								p.patient_name,	
								bd.bed_name,
								r.room_name,								
								adp.admission_id ,
								adp.bed_fee,
								adp.bed_id,								
								adp.bed_reservation_start_time,
								adp.id as migrationTableId
							 ")
					->from('admission_bed_migrate as adp')
					->join('patient_master as p', 'adp.patient_id = p.id', 'left')
					->join('admission_bed as bd', 'adp.bed_id = bd.id', 'left')
					->join('admission_room as r', 'bd.bed_room_id = r.id', 'left')
					->join('admission_patient as ad', 'adp.admission_id = ad.id', 'left')
					->where("adp.admission_id",$admission_id)
					->order_by("adp.id", "desc")
					->limit(1)
					->distinct("adp.id")
					->get("admission_bed_migrate")->row();

			return $patientData;
		}
	}

	/*   automatic Booking Cancel   */

	public function autBookingCancel() {
		@$this->CI->db->query("SET time_zone = '+6:00'");
		$result = $this->CI->db
				->select('id,patient_id,bed_id,prescription_id')
				->where('DATE_ADD(booked_time, INTERVAL 6 HOUR) < NOW()')
				->where('status', 0)
				->get('admission_patient')
				->result();	
		$k = 7;
		if ($result && $k == 7) {
			$this->CI->db->trans_begin();
			foreach ($result as $key => $value) {
				/*    prescriptoin update  */
				$this->CI->db->where('id',$value->prescription_id)->update('prescription_master',array('is_bed_approve' => 0));
				/*    admission status  */
				$this->CI->db->where('id',$value->id)->update('admission_patient',array('status' => 5));
				/*      admission bed status       */
			$this->CI->db->where('id',$value->bed_id)->update('admission_bed',array('bed_status' => 1));
				/*      patient history   */
			$this->patientHistoryAdd($value->patient_id, $value->id, 47);
			if ($this->CI->db->trans_status() === FALSE) {
				$this->CI->db->trans_rollback();
				return FALSE;
			} else {
				$this->CI->db->trans_commit();
				return TRUE;
			}

			} 
		}
		return TRUE;
	}


	/*
	  @ Parameters
		  a_type=agent type[1=external doctor,2=reference,3=Internal Doctor]
		  a_id=agent_id
		  service_id=like 1 is diagnosis
	  @ return
	  	  primary key(id) of commission table

	*/
	public function getCommission($a_type=0,$a_id=0,$service_id=0,$sub_service_id=0){

		$record=$this->CI->db->select("*")
							->where('agent_type',$a_type)
							->where('agent_id',$a_id)
							->where('service_id',$service_id)
							->where("(sub_service_id=0 OR sub_service_id=$sub_service_id)")
							->order_by('id','DESC')
							->get('bf_doctor_commission')
							->row();
		if($record){
			return $record;
		}else{
			return 0;
		}
		

	}
	public function getAdmissionPatientList($condition=0){
		if($condition==0){
			$con['admission_patient.status>=']=0;
		}else{
			$con=$condition;
		}
		$records= $this->CI->db
            ->select('SQL_CALC_FOUND_ROWS
                    bf_patient_master.*,
                    admission_patient.id as admission_id,
                    admission_patient.admission_code,
                    admission_patient.admission_date
                ',false)
            ->join('bf_patient_master','bf_patient_master.id=admission_patient.patient_id','left')
            ->where('admission_patient.release_date is NULL')
            ->where_in('admission_patient.status',[2,3,4])
            ->where($con)
            ->get('admission_patient')
            ->result();
        return $records;
	}

	/*     Current Admission Patient Bed Information   */

	public function currentAdmissionPatientBedInfo($admission_id = 0)
	{
		if ($admission_id){
			$condition = "AND ap.id = $admission_id";
		} else {
			$condition = "";
		}
		$sql = "
			SELECT
				ap.id,
				ap.admission_code,
				pm.patient_name,
				pm.patient_id,
				pm.contact_no,
				bed.id as bed_id,
				bed.bed_name,
				bed.bed_short_name,
				(
					CASE 
						WHEN bed.bed_room_type = 1 THEN 'Cabin' 
						ELSE 'Ward' 
					END
				) as bed_room_type,
				(
					CASE
						WHEN ar.room_condition = 1 THEN 'AC'
						ELSE 'Non AC'
					END
				) as room_condition,
				ar.room_name,
				(CASE
					WHEN ar.room_position = 1 THEN 'East'
					WHEN ar.room_position = 2 THEN 'West'
					WHEN ar.room_position = 3 THEN 'North'
					WHEN ar.room_position = 4 THEN 'South'
					ELSE ''
				END
				) as room_position,
				(CASE 
					WHEN ar.cabin_type = 1 THEN 'Single'
					WHEN ar.cabin_type = 2 THEN 'Multiple'
					ELSE ''
				END
				) as cabin_type,
				ar.room_floor,
				lb.building_name
			FROM
				bf_admission_patient AS ap
			JOIN
				(
					SELECT
					 abm.id,
					 abm.admission_id,
					 abm.bed_id
					FROM
					 bf_admission_bed_migrate AS abm
					JOIN
						(
							SELECT
								MAX(id) as inner_id
							FROM
								bf_admission_bed_migrate
							WHERE
								status != 2
							GROUP BY
								admission_id
						) as q1
							ON q1.inner_id = abm.id
				) as bed_migrate
					ON bed_migrate.admission_id = ap.id
			LEFT JOIN 
				bf_patient_master as pm ON ap.patient_id = pm.id
			LEFT JOIN
				bf_admission_bed as bed ON bed_migrate.bed_id = bed.id
			LEFT JOIN
				bf_admission_room as ar ON bed.bed_room_id = ar.id
			LEFT JOIN
				bf_lib_building as lb ON ar.room_building = lb.building_id
			WHERE
				(ap.status != 4 OR ap.status != 0)
			$condition
		";
		if ($admission_id) {
			$row = $this->CI->db->query($sql)->row();
			return $row;
		} else {
			$result = $this->CI->db->query($sql)->result();
			$data_array = array();
			if ($result) {
				foreach ($result as $val) {
					$data_array[$val->id]['admission_code'] = $val->admission_code;
					$data_array[$val->id]['patient_name'] = $val->patient_name;
					$data_array[$val->id]['patient_code'] = $val->patient_id;
					$data_array[$val->id]['bed_name'] = $val->bed_name;
					$data_array[$val->id]['bed_short_name'] = $val->bed_short_name;
					$data_array[$val->id]['bed_room_type'] = $val->bed_room_type;
					$data_array[$val->id]['room_condition'] = $val->room_condition;
					$data_array[$val->id]['room_name'] = $val->room_name;
					$data_array[$val->id]['room_position'] = $val->room_position;
					$data_array[$val->id]['cabin_type'] = $val->cabin_type;
					$data_array[$val->id]['room_floor'] = $val->room_floor;
					$data_array[$val->id]['building_name'] = $val->building_name;
					$data_array[$val->id]['bed_id'] = $val->bed_id;
				}
			}

			return $data_array;
		}
	
	}

	public function barCode($arr = array(), $folder = 0) {
		$patient_id = $arr['patient_id'];
		$patient_code = $this->CI->db->where("id", $patient_id)->get("patient_master")->row();
		$patient_code = $patient_code->patient_id;
		$master_id = $arr['id'];

		$this->CI->load->library('zend');
		$this->CI->zend->load('Zend/Barcode');
		//$dirfolder = $this->makeDirFolder($folder);
		//print_r($folder);exit;\

		 $file = Zend_Barcode::draw('code128', 'image', array('text' => $patient_code), array());
		 $img_file = $master_id."-".$patient_code;
		 $store_image = imagepng($file,"barcode/$folder/{$img_file}.png");
		 return $img_file.'.png';

	}

/*	private function makeDirFolder($folder){
		$directoryPath = "barcode";
		mkdir($directoryPath, 0644);
	} */

	public function patientAdd($arr = [], $id = 0)
	{
		//echo '<pre>';print_r($this->c_user['user_id']);exit;
		$data = [
			'patient_name' => $arr['patient_name'],
			'sex' => isset($arr['sex']) ? $arr['sex'] : '',
			'birthday' =>  isset($arr['birthday']) ? custom_date_format($arr['birthday']) : '',
			'contact_no' =>  isset($arr['contact_no']) ? $arr['contact_no'] : '',
			'blood_group' =>  isset($arr['blood_group']) ? $arr['blood_group'] : 0,
		];
		if (!$id) {
			$data['patient_id'] = $this->getPatientCode();
			$data['created_by'] = $this->c_user['user_id'];
			$this->CI->db->insert('bf_patient_master', $data);
			$patient_id = $this->CI->db->insert_id();
		} else {
			$data['updated_by'] = $this->c_user['user_id'];
			$data['updated_time'] = date('Y-m-d H:i:s');
			$this->CI->db
				->where('id', $id)
				->update('bf_patient_master', $data);
			$patient_id = $id;
		}

		return $patient_id;
		
	}
}
