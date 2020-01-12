<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Doctor_wise_discount extends Admin_Controller 
{

    public function __construct() 
	{
        parent::__construct();

        $this->load->model('discount_service_model', NULL, true);      
        Assets::add_module_js('library','doctor_wise_discount.js');
        Template::set_block('sub_nav', 'doctor_wise_discount/_sub_nav');
        $this->load->library('pagination');
        $this->lang->load('common');
        $this->load->config('outdoor/outdoor');
    }
    public function show_list() 
	{
        $this->auth->restrict('Lib.DoctorDiscount.View');
              
        $records=$this->db->select("
                                SQL_CALC_FOUND_ROWS
                                dwd.*,
                                dss.service_name,
                                pm.patient_name,
                                pm.patient_id AS patient_code,
                                (CASE WHEN (dwd.agent_type = 1 || dwd.agent_type = 3) THEN emp.EMP_NAME ELSE r.ref_name END) as doctor_name
                            ",false)
                    ->from('lib_doctor_wise_discount as dwd')
                    ->join('lib_discount_service_setup as dss','dss.id = dwd.service_id')
                    ->join('patient_master as pm','pm.id = dwd.patient_id')
                    ->join('lib_reference as r','r.id = dwd.agent_id', 'left')
                    ->join('hrm_ls_employee as emp','dwd.agent_id = emp.EMP_ID','left')
                    ->order_by('dwd.id','DESC')
                    ->get()
                    ->result_array();

        foreach($records as $key=>$record){
        	if($record['discount_type']==2){
				$table=$this->getTableNameByServiceId($record['service_id']);
				$field=$this->getTableFieldNameByServiceId($record['service_id']);
				if($table!=false || $field!=false){
					$records[$key]['sub_service_name']=$this->db->where('id',$record['sub_service_id'])->get("$table")->row()->$field;
				}else{
					$records[$key]['sub_service_name']='No service Availabe';
				}
			}else{
				$records[$key]['sub_service_name']='Overall';
			}
					
					
		}

		//echo '<pre>';print_r($records);die();

       
        $data['records']=$records;

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('doctor_wise_discount/show_list', compact('records','sl'), true);
            exit;
        }
       
        Template::set('records', $records);
        Template::set('toolbar_title', 'Doctor Discount');
         $list_view='doctor_wise_discount/show_list';
        Template::set('list_view', $list_view); 
        Template::set_view('report_template');
        Template::render();
    }
    public function approved_pending_list() 
	{
        $this->auth->restrict('Lib.DoctorDiscount.Approve');

        $condition['dwd.status']=0;        
        $records=$this->db->select("
                                SQL_CALC_FOUND_ROWS
                                dwd.*,
                                dss.service_name,
                                pm.patient_name,
                                pm.patient_id AS patient_code,
                                (CASE WHEN (dwd.agent_type = 1 || dwd.agent_type = 3) THEN emp.EMP_NAME ELSE r.ref_name END) as doctor_name

                            ",false)
                    ->from('lib_doctor_wise_discount as dwd')
                    ->join('lib_discount_service_setup as dss','dss.id = dwd.service_id')
                    ->join('patient_master as pm','pm.id = dwd.patient_id')
                    ->join('lib_reference as r','r.id = dwd.agent_id', 'left')
                    ->join('hrm_ls_employee as emp','dwd.agent_id = emp.EMP_ID','left')
                    ->where($condition)
                    ->order_by('dwd.id','DESC')
                    ->get()
                    ->result_array();

        foreach($records as $key=>$record){
        	if($record['discount_type']==2){
				$table=$this->getTableNameByServiceId($record['service_id']);
				$field=$this->getTableFieldNameByServiceId($record['service_id']);
				if($table!=false || $field!=false){
					$records[$key]['sub_service_name']=$this->db->where('id',$record['sub_service_id'])->get("$table")->row()->$field;
				}else{
					$records[$key]['sub_service_name']='No service Availabe';
				}
			}else{
				$records[$key]['sub_service_name']='Overall';
			}
					
					
		}

		//echo '<pre>';print_r($records);die();

       
        $data['records']=$records;

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('doctor_wise_discount/list', compact('records','sl'), true);
            exit;
        }
       
        Template::set('records', $records);
        Template::set('toolbar_title', 'Doctor Discount Pending List');
         $list_view='doctor_wise_discount/list';
        Template::set('list_view', $list_view); 
        Template::set_view('report_template');
        Template::render();
    }

    
    public function create() 
	{
        $this->auth->restrict('Lib.DoctorDiscount.Add');
        //TODO you code here	
        $data=array();
        if (isset($_POST['save']) && count($_POST)>2) 
		{
			//echo '<pre>'; print_r($_POST);die();
            if ($insert_id = $this->save()) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'Discount_model');
  					if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $insert_id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/doctor_wise_discount/library/show_list');
            } else 
			{
                  if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $insert_id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
                Template::set_message(lang('bf_msg_create_failure') . $this->company_model->error, 'error');
            }
        }

        $conditon['is_deleted']=0;
		$conditon['has_discount']=1;
		$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
        $data['sex'] = $this->config->item('sex');
      //  print_r($data['sex']);

		$form_view='doctor_wise_discount/create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view,$data,true);
            exit;
        }
        Template::set($data);
        Template::set('toolbar_title','New Discount');
        Template::set_view($form_view);
        Template::render();
    }

    public function showDiscount() {
       // echo '<pre>';print_r($_POST);exit;
        $data = array();
        $ref_id = $this->input->post("ref_id", true);
        $agent_type = $this->input->post("agent_type", true);
        $discount_type = $this->input->post("discount_type", true);
        $suggest_discount = $this->input->post("suggest_discount", true);
        $service_id = $this->input->post("service_id", true);
        $sub_service_id = $this->input->post("sub_service_id", true);
        $sub_service_name = $this->input->post("sub_service_name", true);
        $data["service_name"] = $this->getServiceName($service_id);
        if ($discount_type == 1) {
            $overall_discount = $this->getCommissionDiscount(1, $agent_type, $ref_id, $service_id, $sub_service_id, $suggest_discount);
            $data['commossion'] = $overall_discount['commission'];
            $data['commission_id'] = $overall_discount['id'];
            $data['h_discount'] = $overall_discount['h_discount'];
        } else {
            $overall_discount = $this->getCommissionDiscount(2, $agent_type, $ref_id, $service_id, $sub_service_id, $suggest_discount);
            $data['sub_service_id'] = $sub_service_id;
            $data['sub_service_name'] = $sub_service_name;
            $data['commossion'] = $overall_discount['commission'];
            $data['commission_id'] = $overall_discount['id'];
            $data['h_discount'] = $overall_discount['h_discount'];
        }
        $data['type'] = $discount_type;
        $page=$this->load->view('doctor_wise_discount/show_discount',$data,true);
        $error = false;
        if ($overall_discount) {
            $error = true;
        }
        echo json_encode(array("page" => $page, "commission" => $data['commossion'], "error" => $error));
        exit;
    }

    public function getServiceName($service_id = 0) {
        $service_name = "";
        $row = $this->db->where("id", $service_id)->get("lib_discount_service_setup")->row();
        if ($row) {
            $service_name = $row->service_name;
        }
        return $service_name;
    }

    public function getCommissionDiscount($type, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0, $suggest_discount = 0)
    {

        $row = $this->getCommissionInfo($type, $agent_type, $agent_id, $service_id, $sub_service_id, $suggest_discount);
        if ($type == 1 && $row->commission_type == 2) {
            return false;
        }
        //print_r($row);exit;
        $re_discount = 0;
        $id = 0;
        if ($row) {
            $id = $row->id;
            $re_discount = ($row->commission) ? $row->commission : 0;

            if ($type == 2 && $row->commission_type == 2) {
                $sub_service_price = $this->getSubservicePrice($service_id, $sub_service_id);
                //print_r($re_discount);print_r($sub_service_price);exit;
                $re_discount = amount_convert_percent($re_discount, $sub_service_price );

            }
        }
       // print_r($re_discount);exit;
        if ($suggest_discount > $re_discount) {
             $re_discount = $re_discount;
        } else {
             $re_discount = $suggest_discount;
        }

        /*      hospital discount   */

        $h_discount = ($suggest_discount - $re_discount);
            if ($h_discount < 0) {
                $h_discount = 0;
            }

        $re_arr = [
            'id' => $id,
            'commission' => $re_discount,
            'h_discount' => $h_discount
        ];

        return $re_arr;
    }

    public function getSubservicePrice($service_id = 0, $sub_service_id = 0) {

        if (!$service_id) {
            return false;
        }
         $table_name = getTableNameByServiceId($service_id);

         $row = $this->db->where("id", $sub_service_id)->get($table_name)->row();
         $sub_service_price = 0;
         if ($row) {
            if ($service_id == 1) {
                $sub_service_price = $row->test_taka;
            }
         }
         return $sub_service_price;

    }

    public function getCommissionInfo($type, $agent_type = 0, $agent_id = 0, $service_id = 0, $sub_service_id = 0, $suggest_discount = 0) {
         $condition = "WHERE bf_doctor_commission.agent_id = $agent_id and agent_type = $agent_type and service_id = $service_id";
        if ($sub_service_id) {
             $condition = "WHERE bf_doctor_commission.agent_id = $agent_id and agent_type = $agent_type and service_id = $service_id and sub_service_id = $sub_service_id";
        }
       
        $sql = "
            SELECT 
                dc.id,
                dc.commission,
                dc.commission_type
            FROM
                bf_doctor_commission as dc
            JOIN(
                SELECT
                    MAX(id) as inner_id
                FROM
                    bf_doctor_commission
                   $condition
                GROUP BY
                    agent_id
            ) as q1
            ON q1.inner_id = dc.id
        ";
        $row = $this->db->query($sql)->row();
        return $row;
    }

    

    private function save() 
	{

		//echo '<pre>';print_r($_POST);die();
        $data = [];
		$this->db->trans_start();
		$patient_id			= $this->input->post('patient_id');
        $data['patient_name']       = $this->input->post('patient_name');
        $data['sex']                = $this->input->post('sex');
        $data['birthday']             = $this->input->post('dob');
        $data['contact_no']       = $this->input->post('contact_no');

        $obj = new Commonservice();
        if ($patient_id) {
            $patient_id = $obj->patientAdd($data, $patient_id);
        } else {
            $patient_id = $obj->patientAdd($data);
        }

		$agent_type			= $this->input->post('agent_type');	
        $agent              = $this->input->post('agent'); 				
		$service_id			= $this->input->post('service_id');
        $sub_service_id         = $this->input->post('sub_service_id'); 	
        $suggest_discount	= $this->input->post('suggest_discount');	
		$discount_type		= $this->input->post('discount_type');
        $dr_discount        = $this->input->post('dr_discount');
        $commossion_id      = $this->input->post('commossion_id');   	
        $hospital_discount      = $this->input->post('hospital_discount');	
        $count = count($commossion_id);

        for ($t = 0; $t < $count; $t++){
        $tr_dtls = array(
            'patient_id' => $patient_id,
            'agent_type' => $agent_type,
            'agent_id' => $agent,
            'service_id' => $service_id,
            'suggest_discount' => $suggest_discount,
            'discount_type' => $discount_type,
            'sub_service_id' => $sub_service_id[$t],
            'dr_discount' => $dr_discount[$t],
            'hospital_discount' => $hospital_discount[$t],
            'commossion_id' => $commossion_id[$t],
            'created_by' => $this->current_user->id,
            'status'=>1,
            'approved_by'=>$this->current_user->id,
            'approved_at'=>date('Y-m-d H:i:s')

        );
        $this->db->insert('lib_doctor_wise_discount', $tr_dtls);
        }
        $return=$this->db->insert_id();
        $this->db->trans_complete();
        return $return;
    }

    public function approved($id, $h_discount = 0){
    	$this->auth->restrict('Lib.DoctorDiscount.Approve');
    	$check = $this->approveCheck($id);
        if (!$check) {
            echo json_encode(['status'=>false,'check'=> false,'message'=>'Already Approved']);
            exit;
        }
    	if($this->db
            ->where('id',$id)
            ->update('bf_lib_doctor_wise_discount',
                [
                'status'=>1,
                'hospital_discount' => $h_discount,
                'approved_by'=>$this->current_user->id,
                'approved_at'=>date('Y-m-d H:i:s')
                ]
                )){
    		echo json_encode(['status'=>true, 'check'=> true,'message'=>'Successfully Approved']);
    	}else{
    		echo json_encode(['status'=>false, 'check'=> true,'message'=>'Approved Failure']);
    	}
        exit;
    }

    public function approveCheck($id = 0) {
        $return = false;
        $row = $this->db->where("id", $id)->get('lib_doctor_wise_discount')->row();
        if ($row) {
            if($row->status == 0) {
                $return = true;
            }
        }

        return $return;
    }

    


    public function getPatient(){
		/*$id= (int)$this->input->post('id'); 
		$row = $this->db
			->where('id',$id)
			->get('bf_patient_master')
			->row();
		if (!$row) {
            return;
        }
        echo "$('#patient_code').val('" . $row->patient_id. "');";
        echo "$('#patient_id').val('" . $row->id . "');";
        echo "$('#patient_name').val('" . $row->patient_name . "');";
        exit;*/

        $id= (int)$this->input->post('id'); 
        $row = $this->db->where('id',$id) ->get('bf_patient_master')->row();
        $from = new DateTime($row->birthday);
        $to   = new DateTime('today');
        $y = $from->diff($to)->y;
        $m = $from->diff($to)->m;
        $d = $from->diff($to)->d;  
        if (!$row) {
            return;
        } 
        echo "$('#patient_id').val('" . $row->id . "');";       
        echo "$('#patient_name').val('" . $row->patient_name . "');";
        echo "$('#sex').val('" . $row->sex . "').trigger('change');";
        echo "$('#cyear').val('" . $y . "');";
        echo "$('#cmonth').val('" . $m . "');";
        echo "$('#cday').val('" . $d . "');";
        echo "$('#dob').val('" .date('d/m/Y',strtotime($row->birthday)). "');";
        echo "$('#contact_no').val('" . $row->contact_no . "');";
        exit;
	}

	private function getTableNameByServiceId($id){
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

	private function getTableFieldNameByServiceId($id){
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
