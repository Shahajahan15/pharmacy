<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 *
 *
 * @author    Moktar Ali
 * @copyright Worldtechbd.org
 * @link      http://worldtechbd.org
 * @since     Version 1.0
 * @filesource
 */
if (!function_exists('limit_string')) {

    /**
     * Long string showing as a limit with tooltip
     *
     * @param $str
     * @param int $len
     * @return mixed|string
     */
    function limit_string($str, $len = 100) {
        if (strlen($str) < $len) {
            return $str;
        }

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
        if (strlen($str) <= $len) {
            return $str;
        }

        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';
            if (strlen($out) >= $len) {
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out . " <a class='tooltip-icon' href='javascript:void(0)' title='" . $str . "'><i class='glyphicon glyphicon-info-sign'></i></a>";
            }
        }
    }

}

if (!function_exists('custom_date_format')) {
    /**
     * Convert any valid date format to any valid date format
     */
    function custom_date_format($date, $format = 'Y-m-d') {
        if ((int) $date <= 0) {
            $date = "";
        } else {
            $date = date_create(str_replace('/', '-', $date));
            $date = date_format($date, $format);
        }

        return $date;
    }
}

if (!function_exists('custom_date_time_format')) {
    /**
     * Convert any valid date format to any valid date format
     */
    function custom_date_time_format($date, $format = 'Y-m-d h:i:s') {
        if ((int) $date <= 0) {
            $date = "";
        } else {
            $date = date_create(str_replace('/', '-', $date));
            $date = date_format($date, $format);
        }

        return $date;
        //return $date->format('d/m/Y');
    }
}

// date time format conver only date format
if (!function_exists('custom_date_format')) {
    
    function custom_date_format($date, $format = 'Y-m-d') {
        if ((int) $date <= 0) {
            $date = "";
        } else {
            $date = date_create(str_replace('/', '-', $date));
            $date = date_format($date, $format);
        }

        return $date;
        
    }
}


if (!function_exists('convert_to_mysql_date_format')) {
    function convert_to_mysql_date_format($date) {
        $date = date_create_from_format('d/m/Y', $date);
        if (!$date)
            return false;
        return $date->format('Y-m-d');
    }
}

if (!function_exists('convert_to_displayable_date_format')) {
    function convert_to_displayable_date_format($date) {
        $date = date_create_from_format('Y-m-d', $date);
        if (!$date)
            return false;
        return $date->format('d/m/Y');
    }
}

if (!function_exists('ticket_receipt_no')) {

    function ticket_receipt_no($ticket_id) {
    	$CI = & get_instance();
       $CI->load->database();
       	$CI->db->where('id',$ticket_id);
       $row = $CI->db->get('outdoor_patient_ticket')->row();
       $c_date = custom_date_format($row->created_time,'ymd');
      $recip_no = $row->receipt_no;
       	$ticket_no = '';
        if($row->ticket_type == 1){
			$ticket_no = "ODP-".$c_date.$recip_no;
		}
		if($row->ticket_type == 2){
			$ticket_no = "EMP-".$c_date.$recip_no;
		}
        return $ticket_no;
    }

}

if (!function_exists('mrDiscountInfo')) {
    function mrDiscountInfo($service_id, $source_id) {
        if ($service_id == 1) {
            $CI = & get_instance();
            $CI->load->database();
            $row = $CI->db
                    ->select('pm.patient_id as patient_code, pm.patient_name, pdm.mr_no,"Diagnosis" as service_name')
                    ->from('pathology_diagnosis_master as pdm')
                    ->join('patient_master as pm','pdm.patient_id = pm.id')
                    ->where('pdm.id', $source_id)
                    ->get()
                    ->row();
        }

        return $row;
    }
}

/*        percent to amount        */
if (!function_exists('percent_convert_amount')) {
	function percent_convert_amount($percent,$price){
		$price = (float) $price;
		$percent = (float) $percent;
		if(!$percent){
			$percent = 0;
		}
		if(!$price) {
			$price = 0;
		}
		//var_dump($price);
		$result = ($percent * $price)/100;
		return $result;
	}
}

	/*        amount to percent        */
if (!function_exists('amount_convert_percent')) {
	function amount_convert_percent($percent,$price){
		$price = (float) $price;
		$percent = (float) $percent;
		if(!$percent){
			$percent = 0;
		}
		if(!$price) {
			$price = 0;
		}
		
		$result = ($percent * 100)/$price;
        //var_dump($price);exit;
		return $result;
	}
}


function dateDiff($start, $end) {

$start_ts = strtotime($start);

$end_ts = strtotime($end);

$diff = $end_ts-$start_ts;

return round($diff / 86400);

}




/*if (!function_exists('dob_convert_age')) {

    function dob_convert_age($dob) {
    	$c_date = date_create(date('Y-m-d'));
    	$dob = date_create(custom_date_format($dob));
    	$diff = date_diff($c_date,$dob);
        $year = $diff->format("%Y");
        $month = $diff->format("%M");
    	$day = $diff->format("%D");
        if($month==00)
        {
            $month="0";
        }
        if($day==00)
        {
            $day="0";
        }
        if($year==00 || $year==000)
        {
           $year="0";
        }
        $age=$year."Y ".$month."M ".$day."D";
    	return $age;

    }
}*/

if (!function_exists('dob_convert_age')) {

    function dob_convert_age($dob) {
        $dob = custom_date_format($dob,"d-m-Y");
        $current_time = time();
        //return date('Y',strtotime($dob));

        $age_years = date('Y',$current_time) - date('Y',strtotime($dob));
        $age_months = date('m',$current_time) - date('m',strtotime($dob));
        $age_days = date('d',$current_time) - date('d',strtotime($dob));

        if ($age_days<0) {
            $days_in_month = date('t',$current_time);
            $age_months--;
            $age_days= $days_in_month+$age_days;
        }

        if ($age_months<0) {
            $age_years--;
            $age_months = 12+$age_months;
        }

         $age=$age_years."Y ".$age_months."M ".$age_days."D";
        return $age;

    }
}

if (!function_exists('age_convert_dob')) {

    function age_convert_dob($age) {

    	$age = "$age year";
    	$date = date('Y-m-d');
    	$date = date_create($date);
		date_sub($date, date_interval_create_from_date_string($age));
		$dob = date_format($date, 'Y-m-d');
    	return $dob;

    }
}

        /*         service discount       */
if (!function_exists('patient_discount')) {

    function patient_discount($patient_id=0,$service_id=0,$sub_service_id=0) {
        $obj = new Commonservice();
        $result = $obj->patient_discount($patient_id, $service_id, $sub_service_id);
        return $result;

    }
}
        // normal discount
if (!function_exists('normal_discount')) {

    function normal_discount() {
		$obj = new pharmacyCommonService();
		$result = $obj->normal_discount();
    	return $result;

    }
}



          /*   Current Admission Patient Bed Info      */
 if (!function_exists('currentAdmissionPatientBedInfo')) {
    function currentAdmissionPatientBedInfo($admission_id = 0) {
        $obj = new Commonservice();
        $bResult = $obj->currentAdmissionPatientBedInfo($admission_id);
        return $bResult;
    }
 }

        /*         Medicine discount       */
if (!function_exists('medicine_discount')) {

    function medicine_discount($medicine_id = 0,$date = 0, $onlyPercent = 1) {
		$pObj = new pharmacyCommonService();
		$result = $pObj->discount_product($medicine_id, $date, $onlyPercent);
    	return $result;

    }
}

/* medicine count by roule and duration */

if (!function_exists('getMedicineCount')) {
    function getMedicineCount($roule_id = 0, $duratin = 0)
    {
        $CI = & get_instance();
        $CI->load->database();
        $duratin = (int)$duratin;
        if (!$duratin) {
            $duratin = 1;
        }
        $where = array(
                'id' => $roule_id,
                'status' => 1,
                'is_deleted' => 0
            );
        $roule = $CI->db
                    ->where($where)
                    ->get('bf_doctor_rule')
                    ->row()->rule_name;
    /*
        if ($roule) {
            $roule = strlen(str_replace('0', '', str_replace('+','',$roule)));
        } else {
            $roule = 1;
        }
        $total_medicine = ($roule*$duratin);
        return $total_medicine; */

        //$roule = "1+0+1/2";
//$duration = 4;
        $roule = explode("+", $roule);
        $sum = 0;
        //print_r($roule);exit;
        foreach ($roule as $key => $value) {
          if (strpos($value, '/') == false){
            $sum += (int)$value;
          }else {
            $f_roule = explode("/", $value);
            $f_roule = ((int)$f_roule[0] / (int)$f_roule[1]);
            $sum += $f_roule;
          }
          
        }
        //exit;
        return $sum * $duratin;
    }
}

if (!function_exists('admin_readonly')) {

    function admin_readonly($role_id) {
       	$readonly = 'readonly=""';
        if($role_id == 1){
			$readonly = '0';
		}
        return $readonly;
    }

}

if (!function_exists('current_user')) {
    function current_user() {
        return CI::$APP->auth->user();
    }
}

if (!function_exists('current_session')) {
    function current_session() {
        $CI =& get_instance();
        return $CI->session;
    }
}

if (!function_exists('uri')) {
    function uri() {
        $CI =& get_instance();
        return $CI->uri;
    }
}

if(!function_exists('dropdown_helper')) {
    function dropdown_helper($name, $list = [], $default_value = null, $attributes = "", $first_option_val = "", $is_empty = 1) {

        if(!is_string($first_option_val)) {
            $first_option_val = intval($first_option_val);
        }

        if($is_empty) {
            $options = "<option value='$first_option_val' selected>--Select--</option>";
        } else {
            $options = "";
        }

        if(!$is_empty && !is_array($list) && count($list)) {
            return "<p>You have either set the is_empty var to 1 or provide the list array</p>";
        }

        if(!is_string($default_value)) {
            $default_value = intval($default_value);
        }

        if(is_array($list)) {
            foreach ($list as $key => $value) {
                $selected = ($key == $default_value)? " ":"";
                $options .= "<option value='$key' $selected>$value</option>";
            }
        }

        $select_box = "<select name = '$name' $attributes >".$options."</select>";

        return $select_box;
    }
}


if(!function_exists('convert_to_mysql_time')) {
    function convert_to_mysql_time($time)
    {
        return date('H:i:s',strtotime('2000-01-01 '.$time));
    }
}



if (!function_exists('dd')) {
    /**
     * Used for Debuging in pretty print colored style
     */
    function dd($data) {
        highlight_string("<?php\n\$data = " . var_export($data, true) . ";\n?>");
    }

}

/*
    @ for getting sub service item by using service_id
*/
if(!function_exists('getTableNameByServiceId')) {
    function getTableNameByServiceId($id){
        if($id==1){
            return 'bf_pathology_test_name';
        }elseif($id==2){
            return 'bf_pharmacy_products';
        }elseif($id==3){
            return 'bf_lib_otherservice';
        }elseif ($id==4) {
            return 'bf_lib_otherservice';
        }else{
            return false;
        }
    }
}
/*
    @return
*/
if(!function_exists('getTableFieldNameByServiceId')) {
    function getTableFieldNameByServiceId($id){
        if($id==1){
            return 'test_name';
        }elseif($id==2){
            return 'product_name';
        }elseif ($id==3 || $id==4) {
            return 'otherservice_name';
        }else{
            return false;
        }
    }
}


if (!function_exists('report_header')) {

    function report_header() {
        return CI::$APP->load->view('report_header', null, true);
    }

}


if (!function_exists('migration_make')) {
    function migration_make($sql) {
        if (ENVIRONMENT != 'development') {
            return false;
        }


    }
}

if (!function_exists('service_sub_service_name')) {
    function service_sub_service_name($service_id, $detail_id,$sub_service_id=0) {
        $obj = new Commonservice();
       echo $service_name = $obj->serviceSubServiceName($service_id, $detail_id,$sub_service_id);
    }
}

if(!function_exists('inWords')) {
    function inWords($amount) {
        $number = $amount;
        $no = floor($number);
        $point = abs(round($number - $no, 2) * 100);
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' =>'nineteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'forty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety');

        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore','billion','trillion');

        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] ." " . $digits[$counter] . $plural ." " . $hundred:
                    $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? "." . $words[$point / 10] . " " .$words[$point = $point % 10] : '';

        
        if($points){
            return ucwords($result) . "Taka  " . $points . " Poisa";
        }else{
            return ucwords($result) . "Taka  ";
        }
    }
}

/*if(!function_exists('referenceDoctorDiscount')) {
    function referenceDoctorDiscount($arr = array()) {
        $obj = new Commonservice();
        $row = $obj->getCommission($arr['doctor_type'], $arr['ref_id'], 1, $arr['test_id']);
        if ($row) {
            $commission_type = $row->commission_type;
            $commission= $row->commission;
            if ($commission_type == 1) {
                $d_discount_percent = doctorDiscountCheck($commission, $arr['per_discount']);
                $d_discount_amount = percent_convert_amount($d_discount_percent, $arr['test_price']);
            } else {
                $commission= amount_convert_percent($commission, $test_prices);
                $d_discount_percent = doctorDiscountCheck($commission, $arr['per_discount']);
                $d_discount_amount = percent_convert_amount($d_discount_percent, $arr['test_price']);
            }
        } else {
            $d_discount_percent = 0;
            $d_discount_amount = 0;
        }

        $r_arr = array(
            'd_discount_percent' => $d_discount_percent,
            'd_discount_amount' => $d_discount_amount
            );
        return $r_arr;
    }
    } */

    if(!function_exists('referenceDoctorDiscount')) {
    function referenceDoctorDiscount($test_id = 0, $patient_id = 0, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0) {
        $obj = new Commonservice();
        $result = $obj->doctorPatientDiscount($test_id, $patient_id, $agent_type, $agent_id, $service_id, $test_id);
        return $result;
    }
    } 

    // edit doctor discount
    if(!function_exists('getReferenceDoctorDiscount')) {
    function getReferenceDoctorDiscount($test_id = 0, $patient_id = 0, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0, $dwd_id = 0) {
        $obj = new Commonservice();
        $result = $obj->getDoctorPatientDiscount($test_id, $patient_id, $agent_type, $agent_id, $service_id, $test_id, $dwd_id);
        return $result;
    }
    } 

if (!function_exists('doctorDiscountCheck')) {
    function doctorDiscountCheck($commission = 0, $per_discount = 0) {
        if ($per_discount >= $commission) {
          return $commission;
        }
        return $per_discount;
    }
}

if (!function_exists('pathologyTotalTest')) {
    function pathologyTotalTest($master_id = 0, $type = 0) {
        $CI = & get_instance();
        $CI->load->database();
        $row = $CI->db
               ->select('count(pdd.diagnosis_id) as test_count')
               ->from('pathology_diagnosis_master as pdm')
               ->join('pathology_diagnosis_details as pdd','pdd.diagnosis_id = pdm.id and ((pdd.refund_status = 1 and (pdd.qnty - pdd.refund_qty) > 0) || pdd.refund_status = 0)')
               ->get()->row();
        $test_count = 0;
        if ($row) {
        $test_count = $row->test_count;
        }
        return $test_count;
    }
}

if (!function_exists('rosterCombination')) {
    function rosterCombination($str = []) {
        $results = [];
        $arr = [];
        combination($str, $results);
        for ($i = 0; $i < count($results); $i++) {
            $ro = trim($results[$i]);
            if (str_word_count($ro) != 1) {
                $arr[keyVal($ro)] = $ro;
            }
        }
        return $arr;
    }
}

if (!function_exists('keyVal')) {
        function keyVal($ro) {
            $r_arr = getRosterByShift(0);
            $ro_arr = explode("->",$ro);
            foreach ($ro_arr as $key => $val) {
               $re[] = isset($r_arr[$val]) ? $r_arr[$val] : 0;
            }
            $k = implode(",",$re);
          return $k;
        }
    }

if (!function_exists('getRosterShiftNameByShiftIds')) {
    function getRosterShiftNameByShiftIds($ids) {
        $ids_arr = explode(",",$ids);
        $r_arr = getRosterByShift(2);
        foreach ($ids_arr as $key => $val) {

            $ids_name[] = isset($r_arr[$val]) ? $r_arr[$val] : 0;
        }
        $result = implode("->",$ids_name);
        return $result;
    }
}

if (!function_exists('getRosterByShift')) {
    function getRosterByShift($s = 0) {
        $CI = & get_instance();
        $CI->load->database();
       $result = $CI->db
                ->select('SHIFT_POLICY_ID as id, SHIFT_NAME as shift_name, SHIFT_STARTS as shift_start, SHIFT_ENDS as shift_end')
                ->where('IS_DELETED', 0)
                ->where('SHIFT_TYPE', 2)
                ->where('STATUS', 1)
                ->get('hrm_ls_shift_policy')
                ->result();
        $arr = [];
        if ($s == 1) :
        foreach ($result as $key => $val) {
            //$arr[$key] = $val->shift_name."-".$val->id."(".$val->shift_start." to ".$val->shift_end.")";
            $arr[$key] = trim($val->shift_name);
        }
        elseif ($s == 2) :
            foreach ($result as $key => $val) {
            $arr[$val->id] = trim($val->shift_name);
        }
        else :
            foreach ($result as $key => $val) {
            $arr[trim($val->shift_name)] = $val->id;
        }
        endif;
        return $arr;
    }
}

if (!function_exists('combination')) {
    function combination(&$set, &$results) {
        for ($i = 0; $i < count($set); $i++)
        {
            $results[] = $set[$i];
            $tempset = $set;
            array_splice($tempset, $i, 1);
            $tempresults = array();
            combination($tempset, $tempresults);
            foreach ($tempresults as $key => $res)
            {
                $re = $set[$i] ."->". $res;
                $results[] = $re;
            }
        }
    }
}

/*             policy check from group concate id         */

if (!function_exists('policyCheck')) {
    function policyCheck($policy_id = 0, $policyIds) {
        $re = '';
        $exp = array_filter(explode("##",$policyIds));
        if (in_array($policy_id, $exp))
          {
            $re = '<span class="label label-success">Yes</span>';
          }
        else
          {
            $re = '<span class="label label-danger">No</span>';
          }
          return $re;
    }
}

/*             policy check from group concate id         */

if (!function_exists('pharmacyDue')) {
    function pharmacyDue($admission_id = 0) {
        $CI = get_instance();
        $CI->load->model('report/pharmacy_client_wise_report_model');
        $con['client_id'] = $admission_id;
        $con['customer_type'] = 1;
        $pharmacy_due = $CI->pharmacy_client_wise_report_model->getPharmacyClientList($con,0,0,200,1);
        return $pharmacy_due;
}
}

