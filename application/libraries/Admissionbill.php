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

class Admissionbill{

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

    public function getAdmissionBillByPatientId($admission_id, $status = array(), $service_id = array())
	{
		if (!$status) {
    		$status = [2,3,4];
    	}
		$data = array();
		$apbill = $this->getAdmissionBill($status, $service_id);
		$net_bill = isset($apbill[$admission_id][0]) ? $apbill[$admission_id][0] : 0;
		$tot_paid = isset($apbill[$admission_id][1]) ? $apbill[$admission_id][1] : 0;
		$tot_day = isset($apbill[$admission_id][2]) ? $apbill[$admission_id][2] : 0;
		$tot_refund = isset($apbill[$admission_id][3]) ? $apbill[$admission_id][3] : 0;
		$tot_discount = isset($apbill[$admission_id][4]) ? $apbill[$admission_id][4] : 0;
		$tot_bill = ($net_bill + $tot_discount + $tot_refund);
		$data['tot_day'] = $tot_day;
		$data['tot_bill'] = $tot_bill;
		$data['tot_discount'] = $tot_discount;
		$data['tot_refund'] = $tot_refund;
		$data['tot_net_bill'] = $net_bill;
		$data['tot_paid'] = $tot_paid;
		$data['tot_due'] = ($net_bill - $tot_paid);
		return $data;
	}

	/*    total paid   */

	public function getPaid() {
        $result = $this->CI->db
                    ->select('
                        apt.admission_id, 
                        ROUND(IFNULL(SUM(apt.paid), 0)) as spaid,
                        ROUND(SUM(IF(apt.paid_type = 1, apt.paid, 0))) as paid_amount,
                        ROUND(SUM(IF(apt.paid_type = 2, apt.paid, 0))) as due_paid_amount,
                        ROUND(SUM(IF(apt.paid_type = 3, apt.paid, 0))) as return_amount,
                    ')
                    ->from('admission_patient_transaction as apt')
                    ->join('admission_patient as ap','ap.id = apt.admission_id')
                    ->where('apt.source_type !=', 5)
                    ->where_in('ap.status',[2,3,4])
                    ->group_by('apt.admission_id')
                    ->get()
                    ->result();
        if (!$result) {
            return false;
        }

        $data = [];

        foreach ($result as $key => $val) {
            $data[$val->admission_id]['payable'] = ($val->paid_amount + $val->due_paid_amount);
            $data[$val->admission_id]['receiveable'] = ($val->return_amount);
        }
       return $data;
    }

    public function getAdmissionBill($status = array(),$service_id = array())
    {
    	if (!$status) {
    		$status = [2,3,4];
    	}
    	$virtual_bill = $this->getAdmissionPatientVirtualBill($status);
		$patient_bill = array();
		$tr_bill = $this->getAdmissionPatientOtherBill(0, $status, $service_id);
		$consultant_bill = $this->getConsultantBill();
		//echo '<pre>';print_r($tr_bill);exit;
		if ($virtual_bill){
			foreach ($virtual_bill as $key => $row) {
				$consultant = isset($consultant_bill[$row['admisssion_id']]['consultant_price']) ? $consultant_bill[$row['admisssion_id']]['consultant_price'] : 0;
				$out_consultant = isset($consultant_bill[$row['admisssion_id']]['out_consultant_price']) ? $consultant_bill[$row['admisssion_id']]['out_consultant_price'] : 0;
				$total_consultant_bill = ($consultant + $out_consultant);
				$tot_bill = isset($tr_bill[$row['admisssion_id']]['bill_amount']) ? $tr_bill[$row['admisssion_id']]['bill_amount'] : 0;
				$tot_bill_paid = isset($tr_bill[$row['admisssion_id']]['bill_paid']) ? $tr_bill[$row['admisssion_id']]['bill_paid'] : 0;
				$tot_due_paid = isset($tr_bill[$row['admisssion_id']]['due_paid']) ? $tr_bill[$row['admisssion_id']]['due_paid'] : 0;
				$tot_refund_paid = isset($tr_bill[$row['admisssion_id']]['refund_paid']) ? $tr_bill[$row['admisssion_id']]['refund_paid'] : 0;
				$tot_refund_bill = isset($tr_bill[$row['admisssion_id']]['refund_bill']) ? $tr_bill[$row['admisssion_id']]['refund_bill'] : 0;
				$tot_discount = isset($tr_bill[$row['admisssion_id']]['tot_discount']) ? $tr_bill[$row['admisssion_id']]['tot_discount'] : 0;
				$patient_bill[$row['admisssion_id']][0] = $row['tot_bill'] + $tot_bill + $total_consultant_bill;
				$patient_bill[$row['admisssion_id']][1] = $tot_bill_paid + $tot_due_paid;
				$patient_bill[$row['admisssion_id']][2] = $row['total_day'];
				$patient_bill[$row['admisssion_id']][3] = $tot_refund_paid;
				$patient_bill[$row['admisssion_id']][4] = $tot_discount + $row['total_discount'] + $row['over_all_discount'];
				$patient_bill[$row['admisssion_id']][5] = $row['total_discount'];
				$patient_bill[$row['admisssion_id']][6] = $row['over_all_discount'];
				$patient_bill[$row['admisssion_id']][7] = $total_consultant_bill;
				$patient_bill[$row['admisssion_id']][8] = $tot_refund_bill;
			}
		}
		return $patient_bill;
    }

    /*
		@ AdAdmission Patient Virtual(Bed) Bill    
		@parm status(array)
		@default status = arry
		@return array
	*/

	public function getAdmissionPatientVirtualBill($status = array())
	{
		if (!$status) {
			$status = [2,3,4];
		}

		/*$result = $this->CI->db
				  ->select('abm.admission_id,GROUP_CONCAT("##",abm.bed_fee) as concat, abm.bed_fee,MIN(abm.bed_reservation_start_time) as start_time,abm.bed_reservation_end_time as end_time, abm.status, IF(ap.discount_amount > 0, ROUND(ap.discount_amount / ap.discount_days, 2), 0) as per_discount_amount,ap.patient_id,ROUND(ap.over_all_discount) as over_all_discount')
				  ->from('admission_patient as ap')
				  ->join('admission_bed_migrate as abm','ap.id = abm.admission_id and abm.status=0')
				  ->or_where_in('ap.status',[2,3,4])
				  ->order_by('abm.id','asc')
				  ->group_by('abm.admission_id')
				  ->group_by('DATE(abm.bed_reservation_start_time)')
				  ->get()
				  ->result();*/
		$sql = "
			SELECT 
				MAX(abm.id) AS id,
				abm.admission_id,
			  	GROUP_CONCAT('##',abm.bed_fee) AS concat,
			  	MIN(abm.bed_reservation_start_time) AS start_time,
			  	abm.bed_reservation_end_time AS end_time,
			  	ap.patient_id,
			  	abm.status,
			  	ROUND(ap.over_all_discount) as over_all_discount,
			  	0 as per_discount_amount

			FROM 
				bf_admission_patient AS ap
			JOIN 
				bf_admission_bed_migrate AS abm
				ON ap.id = abm.admission_id AND abm.status = 0
			GROUP BY 
				abm.admission_id,
				DATE(abm.bed_reservation_start_time)
			ORDER BY 
				abm.id ASC 
			
		";

		$result = $this->CI->db->query($sql)->result();
		//print_r($this->CI->db->last_query($result));
		//echo '<pre>';print_r($result);
		$data_array = array();

		if ($result) {
			foreach ($result as $key => $row) {

				$ad_info = $this->getLastAdmissionInfo($row->id);
				$row->end_time = $ad_info->end_time;
				$bed_fee = $ad_info->bed_fee;

				$total_day = $this->getTotalDay($row);
				$start_date_time = $row->start_time;
				$end_date_time = $row->end_time;

				$virtual_discount = $this->getVirtualDiscount($row, $total_day);

	 			$exp = explode("##",$row->concat);
	 			//$bed_fee = $row->bed_fee;
	 			//$bed_fee = (float)end($exp);
	 			$total_bed_fee = ($total_day * $bed_fee);

				$total_discount = $virtual_discount;

				if (isset($data_array[$row->admission_id])) {
					$data_array[$row->admission_id]['tot_bill'] += $total_bed_fee;
					$data_array[$row->admission_id]['total_day'] += $total_day;
					$data_array[$row->admission_id]['total_discount'] += $total_discount;
					$data_array[$row->admission_id]['end_date_time'] = $end_date_time;
					$data_array[$row->admission_id]['l_bed_fee'] = $bed_fee;
				} else {
					$data_array[$row->admission_id] = array(
						'admisssion_id' => $row->admission_id,
						'tot_bill' => $total_bed_fee,
						'total_day' => $total_day,
						'total_discount' => $total_discount,
						'over_all_discount' => $row->over_all_discount,
						'start_date_time' => $start_date_time,
						'end_date_time' => $end_date_time,
						'f_bed_fee' => $bed_fee,
						'l_bed_fee' => $bed_fee
					);
				}
			}
		}

		$virtual_result = $this->getVirtualBill($data_array);
		//echo '<pre>';print_r($data_array);exit;
		return $virtual_result;
	} 

	private function getLastAdmissionInfo($id)
	{
		$row = $this->CI->db
				->select('bed_fee, bed_reservation_end_time AS end_time')
				->where('status', 0)
				->where('id', $id)
				->get('bf_admission_bed_migrate')
				->row();
		return $row;
	}

	private function getVirtualBill($result)
	{
		foreach ($result as $key => $val) {
			$tot_day = $val['total_day'];
			$tot_bill = $val['tot_bill'];

			if (!$val['end_date_time'])
			{
				$val['end_date_time'] = date('Y-m-d H:i:s');
			}

			$startReservationTime = strtotime($val['start_date_time']);
	 		$endReservationTime = strtotime($val['end_date_time']);

	 		$afterFive = strtotime(date('Y-m-d', $startReservationTime) . "05:00:00");
	 		$afterTwo = strtotime(date('Y-m-d', $endReservationTime) . "14:00:00");

	 		if ($startReservationTime < $afterFive) {
	 			$tot_day += 1;
	 			$tot_bill +=$val['f_bed_fee'];
	 		}

	 		if ($endReservationTime > $afterTwo) {
	 			$tot_day += 1;
	 			$tot_bill +=$val['l_bed_fee'];
	 		}

	 		if ($tot_day == 0)
	 		{
	 			$tot_bill = $val['l_bed_fee'];
	 			$tot_day = 1;
	 		}

			$data[$val['admisssion_id']] = [
				'admisssion_id' => $val['admisssion_id'],
				'tot_bill' => $tot_bill,
				'total_day' => $val['total_day'],
				'tot_dayd' => $tot_day,
				'total_discount' => $val['total_discount'],
				'over_all_discount' => $val['over_all_discount'],
				'start_date_time' => $val['start_date_time'],
				'end_date_time' => $val['end_date_time']
			];
		}
		return $data;
	}


	private function getTotalDay($row = array()) {
		$start_time = custom_date_format($row->start_time)." 00:00:00";
		$end_time = ($row->end_time) ? $row->end_time : date('Y-m-d H:i:s');

	 		$day = 0;
	 		$d = 0;
	 		/*$startReservationTime = strtotime($start_time);
	 		$endReservationTime = strtotime($end_time);

	 		$afterFive = strtotime(date('Y-m-d', $startReservationTime) . "05:00:00");
	 		$afterTwo = strtotime(date('Y-m-d', $endReservationTime) . "14:00:00");
	 		if ($startReservationTime < $afterFive) {
	 			$day += 1;
	 		}
	 		if ($endReservationTime > $afterTwo) {
	 			$day += 1;
	 		} */

	 		$a = strtotime($start_time);
	 		$n = strtotime($end_time);
	 		$datediff = $n - $a;	 		
	 		$d = floor($datediff / (60 * 60 * 24));
	 		
	 		return $d;
	 		return ($d + 1);

	 		$datetime1 = new DateTime($start_time);
			$datetime2 = new DateTime($end_time);
			$interval = $datetime1->diff($datetime2);
			$day += $interval->format('%a');
			if ($day <= 0) {
				$day = 1;
			} 
			return $day;
	}


	/*
		Virtual Discount 
		@parm array[patient_id, bed_fee], total day
		@retrun discount
	*/

	public function getVirtualDiscount($row = array(), $total_day)
	{
		$Obj = new Commonservice();
		$virtual_discount = 0;
		$patient_discount = $Obj->patient_discount($row->patient_id, 3, 1);
		if ($patient_discount) {
			if ($patient_discount->id && ($patient_discount->discount_type == 1)) {
				$discount_amount = percent_convert_amount($patient_discount->discount, $row->bed_fee);
				$virtual_discount = ($discount_amount * $total_day);
			} elseif ($patient_discount->id && ($patient_discount->discount_type == 0)) {
				$virtual_discount = ($patient_discount->discount * $total_day);
			}
		}
		return $virtual_discount;
	}

  /*  public function getAdmissionPatientVirtualBill($status = array())
	{
		if (!$status) {
			$status = [2,3,4];
		}

		$result = $this->CI->db
				  ->select('abm.admission_id,abm.bed_fee,(abm.bed_reservation_start_time) as start_time,(abm.bed_reservation_end_time) as end_time, abm.status, IF(ap.discount_amount > 0, ROUND(ap.discount_amount / ap.discount_days, 2), 0) as per_discount_amount,ap.patient_id,ROUND(ap.over_all_discount) as over_all_discount,abm.first_migrate')
				  ->from('admission_patient as ap')
				  ->join('admission_bed_migrate as abm','ap.id = abm.admission_id and (abm.status = 1 or abm.status=0)')
				  ->or_where_in('ap.status',$status)
				  ->order_by('abm.id','asc')
				  ->get()
				  ->result();
		$data_array = array();

		if ($result) {
			foreach ($result as $key => $row) {

				$total_day = $this->getTotalDay($row, $key);

				$virtual_discount = $this->getVirtualDiscount($row, $total_day);

				$total_bill = ($total_day * $row->bed_fee);
				//$total_discount = ($total_day * $row->per_discount_amount);
				$total_discount = $virtual_discount;

				if (isset($data_array[$row->admission_id])) {
					$data_array[$row->admission_id]['tot_bill'] += $total_bill;
					$data_array[$row->admission_id]['total_day'] += $total_day;
					$data_array[$row->admission_id]['total_discount'] += $total_discount;
				} else {
					$data_array[$row->admission_id] = array(
						'admisssion_id' => $row->admission_id,
						'tot_bill' => $total_bill,
						'total_day' => $total_day,
						'total_discount' => $total_discount,
						'over_all_discount' => $row->over_all_discount
					);
				}
			}
		}
		return $data_array;
	} */

	/*
		@ Admission Patient Other Service Bill
		@parm type, status(array)
		@default type = 0, status = arry
		@return array
	*/
	public function getAdmissionPatientOtherBill($type = 0, $status = array(), $service_id = array())
    {

    	if (!$status) {
			$status = [2,3,4];
		}

    	$where = [];
    	if ($type) {
    		$where = ['apt.source_type' => 2];
    	}
    	if ($service_id) {
			$this->CI->db->where_in('apt.source_type',$service_id);
		}
		$row_data = $this->CI->db
				  ->select('
				  		ap.id,SUM(apt.amount) as bill_amount,
				  		SUM(apt.mr_discount) as mr_discount,
				  		SUM(apt.bill_refund_amount) as bill_refund_amount,
				  		SUM(apt.discount + apt.less_discount + apt.mr_discount) as tot_discount,
				  		SUM(apt.less_discount) as less_discount,
				  		IF(apt.paid_type = 1,SUM(apt.paid),0) as bill_paid,
				  		IF(apt.paid_type = 2,SUM(apt.paid),0) as due_paid,
				  		IF(apt.paid_type = 3,SUM(apt.paid),0) as refund_paid,
				  		IF(apt.paid_type = 3,SUM(apt.bill_refund_amount),0) as refund_bill
				  		')
				  ->from('admission_patient as ap')
				  ->join('admission_patient_transaction as apt','ap.id = apt.admission_id')
				  ->where_in('ap.status',$status)
				  ->where($where)
				  ->group_by('apt.admission_id')
				  ->group_by('apt.paid_type')
				  ->get()
				  ->result();
		//echo '<pre>';print_r($row_data);exit;
		$data_array = array();
			foreach ($row_data as $key => $val) {
				if (isset($data_array[$val->id])) {
					$data_array[$val->id]['bill_paid'] += $val->bill_paid;
					$data_array[$val->id]['due_paid'] += $val->due_paid;
					$data_array[$val->id]['refund_paid'] += $val->refund_paid;
					$data_array[$val->id]['refund_bill'] += $val->refund_bill;
				} else {
					$data_array[$val->id] = array(
						'id' => $val->id,
						'bill_amount' => $val->bill_amount,
						'bill_refund_amount' => $val->bill_refund_amount,
						'mr_discount' => $val->mr_discount,
						'less_discount' => $val->less_discount,
						'tot_discount' => $val->tot_discount,
						'bill_paid' => $val->bill_paid,
						'due_paid' => $val->due_paid,
						'refund_paid' => $val->refund_paid,
						'refund_bill' => $val->refund_bill,
					);
				}
			}

		return $data_array;
	}
	

	/*          
	   @ Admission Total Day    
		@parm array   [start time, end time, status]

	*/

	/*	public function getTotalDay($row = array()) {
		$total_day = 0;
		$start_date = custom_date_format($row->start_time);
				date_default_timezone_set("Asia/Dhaka");
				$end_date = isset($row->end_time) ? custom_date_format($row->end_time) : date('Y-m-d H:i:s') ;
				$interval = date_diff(date_create($end_date), date_create($start_date));
				$dif_time = 0;
				$dif_time = $this->getDifTime($row);
				if ($row->end_time) {
					$end_time = (int)(date('H', strtotime( $row->end_time)));
					if (($end_time < 14) || ($row->status == 1 && $dif_time < 12)){
						$total_day = ((int)$interval->format('%a'));
					} else {
						$total_day = ((int)($interval->format('%a'))+ 1);
					}

				} else {
					$total_day = ((int)$interval->format('%a')+1);
				}
			return $total_day;
	} */

	/*public function getTotalDay($row = array(), $key) {
		$total_day = 0;
		//$start_date = custom_date_format($row->start_time);
		$start_date = $row->start_time;
				date_default_timezone_set("Asia/Dhaka");
				//$end_date = isset($row->end_time) ? custom_date_format($row->end_time) : date('Y-m-d H:i:s') ;
				$end_date = isset($row->end_time) ? $row->end_time : date('Y-m-d H:i:s');
				$interval = date_diff(date_create($end_date), date_create($start_date));
				$dif_time = 0;
				$dif_time = $this->getDifTime($row);
				//$first_time_check = $this->firstTimeCheck($row);
				//print_r($first_time_check);
				$start_time_hour = (int)(date('H', strtotime( $start_date )));
				if ($row->end_time) {
					$end_time_hour = (int)(date('H', strtotime( $row->end_time)));
					
					if (($end_time_hour < 14) || ($row->status == 1 && $dif_time < 12)){
						$total_day = ((int)$interval->format('%a') + 1); 
					} elseif ($row->first_migrate == 1 && $start_time_hour < 5) {

						$total_day = ((int)($interval->format('%a'))+ 2);
					} else {
						$total_day = ((int)($interval->format('%a'))+ 1);
					}

				} else {
					if ($row->first_migrate == 1 && $start_time_hour < 5) {
						$total_day = ((int)$interval->format('%a')+2);
					} else {
						$total_day = ((int)$interval->format('%a')+1);
					}
					
				}
				//print_r($total_day);
			return $total_day;
	} */


	private function getDifTime($row)
	{
		//$start_time = new DateTime(date('Y-m-d H:i:sa',strtotime($row->start_time)));
		$start_time = new DateTime(date('Y-m-d H:i:s',strtotime($row->start_time)));
		//$end_time = new DateTime(date('Y-m-d H:i:sa',strtotime($row->end_time)));
		$end_time = new DateTime(date('Y-m-d H:i:s',strtotime($row->end_time)));
		$intervalt= $start_time->diff($end_time);
		$dif_time = (($intervalt->days * 24) + $intervalt->h);
		return $dif_time;
	}

	/*      Others Service Bill      */

	public function OtherServieBillBySubServiceId($status = array(), $admission_id = 0)
	{
		$where = "";
		if ($admission_id) {
			//$where[''] = $admission_id;
		}
		$result = $this->CI->db
					->select('posm.admission_id, posd.service_id,ROUND(SUM((posd.total_price) - ((posd.total_price / posd.service_qty) * (posd.refund_qty)))) as tot_price')
					->from('patient_others_service_master as posm')
					->join('patient_others_service_details as posd','posm.id = posd.service_master_id')
					->group_by('posm.admission_id')
					->group_by('posd.service_id')
					//->where('posm.admission_id', $where)
					->get()
					->result();
		 $result_array = array();
		 foreach ($result as $val) {
		 	$result_array[$val->admission_id][$val->service_id] = $val->tot_price;
		 }

		// echo '<pre>';print_r($result_array);exit;

		 return $result_array;
	}

	/*               operation service bill                */

	public function getOperationServiceBill($status = array())
	{
		$result = $this->CI->db
					->select('
						oao.admission_id,
						oao.operation_type,
						SUM(ROUND(oao.doctor_settle_amount)) as doctor_settle_amount,
						SUM(os.post_operative_bed_cost) as post_operative_bed_cost,
						SUM(os.operation_theater_cost) as operation_theater_cost,
						SUM(os.anesthesia_cost) as anesthesia_cost,
						SUM(os.surgeon_cost) as surgeon_cost,
						SUM(os.surgeon_team_cost) as surgeon_team_cost,
						SUM(os.guest_doctor_cost) as guest_doctor_cost,
						SUM(os.blood_cost) as blood_cost
					')
					->from('operation_admission_operation as oao')
					->join('operation_schedule as os','oao.id = os.admission_operation_id')
					->where('oao.is_refund !=', 1)
					->group_by('operation_type')
					->group_by('admission_id')
					->get()
					->result();
			$data_array = array();
			foreach ($result as $val) {
				$post_op = ($val->operation_type == 1)? $val->post_operative_bed_cost : 0;
				$operatin_theater = ($val->operation_type == 1)? $val->operation_theater_cost : 0;
				$anesthesia = ($val->operation_type == 1)? $val->anesthesia_cost : 0;
				$surgeon_team = ($val->operation_type == 1)? $val->surgeon_team_cost : 0;
				$surgeon = ($val->operation_type == 1)? $val->surgeon_cost : 0;
				$guest_doctor = ($val->operation_type == 1)? $val->guest_doctor_cost : 0;
				$blood = ($val->operation_type == 1)? $val->blood_cost : 0;
			   if (isset($data_array[$val->admission_id])) {
				   	$data_array[$val->admission_id]['operatin_package_amount'] += $val->doctor_settle_amount;
					$data_array[$val->admission_id]['post_operative_bed_cost'] += $post_op;
					$data_array[$val->admission_id]['operation_theater_cost'] += $operatin_theater;
					$data_array[$val->admission_id]['anesthesia_cost'] += $anesthesia;
					$data_array[$val->admission_id]['surgeon_team_cost'] += $surgeon_team;
					$data_array[$val->admission_id]['surgeon_cost'] += $surgeon;
					$data_array[$val->admission_id]['guest_doctor_cost'] += $guest_doctor;
					$data_array[$val->admission_id]['blood_cost'] += $blood;
			   } else {
			   	$data_array[$val->admission_id] = array(
					'operatin_package_amount' => $val->doctor_settle_amount,
					'post_operative_bed_cost' => $post_op,
					'operation_theater_cost' => $operatin_theater,
					'anesthesia_cost' => $anesthesia,
					'surgeon_team_cost' => $surgeon_team,
					'surgeon_cost' => $surgeon,
					'guest_doctor_cost' => $guest_doctor,
					'blood_cost' => $blood
			   	);
			   }
			} 
			return $data_array;
	}

	/*     Consultant Bill           */

	public function getConsultantBill() {

		$result = $this->CI->db
					->select('
						acr.admission_id,
						IF(emp.IS_EXTERNAL = 1, (IFNULL(SUM(ROUND(acr.per_patient_price)), 0)), 0) as out_consultant_price,
						IF(emp.IS_EXTERNAL = 0, (IFNULL(SUM(ROUND(acr.per_patient_price)), 0)), 0) as consultant_price
						')
					->from('admission_consultant_round as acr')
					->join('hrm_ls_employee as emp','acr.consultant_id = emp.EMP_ID')
					->group_by('acr.admission_id')
					->group_by('emp.IS_EXTERNAL')
					->get()
					->result();
		$data_array = array();
		foreach ($result as $val) {
			if(isset($data_array[$val->admission_id])) {
				$data_array[$val->admission_id]['consultant_price'] += $val->consultant_price;
				$data_array[$val->admission_id]['out_consultant_price'] += $val->out_consultant_price;
			} else {
				$data_array[$val->admission_id] = array(
						'consultant_price' => $val->consultant_price,
						'out_consultant_price' => $val->out_consultant_price
					);
			}
		}
		return $data_array;
	}

	/*             admission patient bill paid              */

	public function getAdmissionPatientBillPaidByAdmissionId($admission_id = 0) {
		$status = [2,3];

		                 /*             other service bill               */

		$other_service_bill = $this->getAdmissionPatientOtherBill(0, $status, [2]);
		$other_bill = isset($other_service_bill[$admission_id]['bill_amount']) ? $other_service_bill[$admission_id]['bill_amount'] : 0;
		$other_paid = isset($other_service_bill[$admission_id]['bill_paid']) ? $other_service_bill[$admission_id]['bill_paid'] : 0;
		$other_refund = isset($other_service_bill[$admission_id]['refund_paid']) ? $other_service_bill[$admission_id]['refund_paid'] : 0;
		$other_service_due = ($other_bill - ($other_paid + $other_refund));

							 /*             consultant bill             */

		$consultant = $this->getConsultantBill();		 
		$consultant_bill = isset($consultant[$admission_id]['consultant_price']) ? $consultant[$admission_id]['consultant_price'] : 0;
		$out_consultant_bill = isset($consultant[$admission_id]['out_consultant_price']) ? $consultant[$admission_id]['out_consultant_price'] : 0;

		                   /*       bed bill       */

		$virtual_bill = $this->getAdmissionPatientVirtualBill($status);
		$bed_bill = isset($virtual_bill[$admission_id]['tot_bill']) ? $virtual_bill[$admission_id]['tot_bill'] : 0;
		$bed_discount = isset($virtual_bill[$admission_id]['total_discount']) ? $virtual_bill[$admission_id]['total_discount'] : 0;
		$over_all_discount = isset($virtual_bill[$admission_id]['over_all_discount']) ? $virtual_bill[$admission_id]['over_all_discount'] : 0;

		/*              admission bill paid          */
		$bill_paid = $this->getAdmissionPatientOtherBill(0, $status, [3]);

		$data = array(
				'bed_bill' => ($bed_bill - $bed_discount),
				'bill_paid' => (isset($bill_paid[$admission_id]['bill_paid']) ? $bill_paid[$admission_id]['bill_paid'] : 0),
				'other_service_due_bill' => $other_service_due,
				'consultant_bill' => ($consultant_bill + $out_consultant_bill),
				'over_all_discount' => $over_all_discount
			);

		return $data;
	}
}
