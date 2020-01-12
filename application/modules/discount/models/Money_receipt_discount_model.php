<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Money_receipt_discount_model extends BF_Model {

	protected $services = [
        '1'  => [
            'label' => 'Diagnosis',
            'model' => "pathology/diagnosis_master_model",
        ],
        '3'  => [
            'label' => 'Admission',
            'model' => "patient/admission_model",
        ],
        '4'  => [
            'label' => 'Others',
            'model' => "patient/others_service_master_bill_model",
        ],
        '5'  => [
            'label' => 'Emergency',
            'model' => "emergency/emergency_ticket_model",
        ],
        '6'  => [
            'label' => 'Outdoor',
            'model' => "outdoor/outdoor_ticket_model",
        ],
        '7'  => [
            'label' => 'Registration',
            'model' => "patient/registration_model",
        ],
        '8'  => [
            'label' => 'Ambulance',
            'model' => "ambulance/ambulance_model",
        ],
        '10' => [
            'label' => 'Operation',
            'model' => "operation/operation_payment_model",
        ],
    ];

    public function search($service_id, $mr_no, $source_id = 0) {
    	//print_r($service_id);exit;
        $search_model = $this->getModel($service_id);
        //print_r($query_fields);
        return $this->{$search_model}->search_money_receipt_for_discount($mr_no, $source_id);
    }

    public function save($mr_discount, $mr_no, $service_id, $net_bill, $created_by) {
        $obj = new Commonservice();
        $search = $this->search($service_id, $mr_no);

        $data = [
            'source_id' => $search['id'],
            'service_id' => $service_id,
            'mr_discount' => $mr_discount,
            'net_bill' => $net_bill,
            'created_by' => $created_by
        ];

        $this->db->insert('discount_money_receipt_discount', $data);
        $insert_id = $this->db->insert_id();

        $this->mrUpdate(1,$service_id, $search['id'], 0, 0, 0);

        $obj->patientHistoryAdd($search['patient_id'], $insert_id, 59);

        return $insert_id;

    }

    public function collection_save($id = 0) {
        $row = $this->mrDiscountInfo($id);
       //echo '<pre>'; print_r($row);exit;
        $service_id = 0;
        $source_id = 0;
        if (!$row) {
            return false;
        }

       $tot_net_bill = ($row['result']->total_bill_amount) - ($row['result']->less_discount + $row['result']->refund);
       $tot_cu_payable = ($row['mr_discount_info']->mr_approve_discount + $row['result']->collection);
       $total_refund = ($tot_cu_payable > $tot_net_bill) ? ($tot_cu_payable - $tot_net_bill) : 0;
       $mr_approve_discount = ($row['mr_discount_info']->mr_approve_discount) ? $row['mr_discount_info']->mr_approve_discount : 0;
       $due = ($tot_net_bill - $tot_cu_payable);
       $service_id = ($row['mr_discount_info']->service_id) ? $row['mr_discount_info']->service_id : 0;
       $source_id = ($row['mr_discount_info']->source_id) ? $row['mr_discount_info']->source_id : 0;
       $patient_type = $row['result']->patient_type;
       $admission_id = $row['result']->admission_id;
       $patient_id = $row['result']->patient_id;
       $discount_id = $row['mr_discount_info']->id;
      // print_r($_SESSION['user_id']);exit;
       //     discount update 
      $this->mrDiscountUpdate($id);
      $this->mrUpdate(3, $service_id, $source_id, $mr_approve_discount, $total_refund, $due);
       // tranjaction table update
      $this->tranjactionTable($service_id, $source_id, $mr_approve_discount, $total_refund);
      if ($service_id == 1 && $patient_type == 1)  {
          // if admission then admission tanaction table update
        $this->adminPatientTranjaction($service_id, $source_id, $mr_approve_discount, $total_refund, $admission_id);
      }
       // if refund then refund table insert
      if ($total_refund > 0) {
        $this->mrRefund($service_id, $source_id, $total_refund, $patient_id);
      }
     // echo 'hi';exit;
       // discount table insert
      $this->allDiscount($service_id, $source_id, $mr_approve_discount, $patient_id, $admission_id, $discount_id);
       // patient history
      $obj = new Commonservice();
      $obj->patientHistoryAdd($patient_id, $discount_id, 60);

    }

    public function mrDiscountInfo($id) {
        $source_id = 0;
        $data['mr_discount_info'] = $this->db->where('id', $id)->get('discount_money_receipt_discount')->row();
        $mr_no = '';
        if ($data['mr_discount_info']) {
            $source_id = $data['mr_discount_info']->source_id;
            $service_id = $data['mr_discount_info']->service_id;
        }
        $data['result'] = (object)$this->search($service_id, $mr_no, $source_id);
        return $data;
    }

    public function mrUpdate($status, $service_id, $source_id, $mr_discount, $refund, $due) {
        $search_model = $this->getModel($service_id);
        
        $this->{$search_model}->money_receipt_discount_update($source_id, $status, $mr_discount, $refund, $due);
    }

    public function allDiscount($service_id, $source_id, $mr_approve_discount, $patient_id, $admission_id, $discount_id) 
    {
        if ($service_id == 1) {
            $source = 3;
        }
        $data = [
            'discount_id' => $discount_id,
            'source_id' => $source_id,
            'source' => $source,
            'patient_id' => ($patient_id) ? $patient_id : 0,
            'admission_id' => ($admission_id) ? $admission_id : 0,
            'discount_type' => 1,
            'service_id' => $service_id,
            'mr_discount' => $mr_approve_discount,
            'sub_service_id' => 0,
            'created_by' => $_SESSION['user_id'],
            'specific_discount' => 1,
            'type' => 2
        ];
        $this->db->insert('all_discount', $data);
    }

    public function mrRefund($service_id, $source_id, $refund, $patient_id) 
    {
        /*               refund master               */
        $master_data = [
            'mr_no' => $this->get_mr_no(),
            'service_id' => $service_id,
            'patient_id' => ($patient_id) ? $patient_id : 0,
            'master_id' => $source_id,
            'refund_type' => 3,
            'total_refund_amount' => $refund,
            'is_collected' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_id'],
            'refunded_at' => date('Y-m-d H:i:s'),
            'refunded_by' => $_SESSION['user_id']
        ];
        $this->db->insert('refund_collection_master', $master_data);
        $insert_id = $this->db->insert_id();

        /*               refund details               */
        $details_data = [
            'refund_col_master_id' => $insert_id,
            'details_id' => 0,
            'amount' => $refund,
            'qnty' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user_id']
        ];
        $this->db->insert('refund_collection_details', $details_data);
    }

    public function get_mr_no()
    {
        $row = $this->db
            ->select('mr_no')
            //->select_max('id')
            ->where('Date(created_at)',date('Y-m-d'))
            ->order_by('id','desc')
            ->get('refund_collection_master')
            ->row();
        
        if(!$row){
            $mr_no = "RCP-".date('ymd')."1";
        }else{
            $incr_id = substr($row->mr_no,10);
            $incr_id++;
            $mr_no = "RCP-".date('ymd').$incr_id;
        }
        return $mr_no;
    }

   public function mrDiscountUpdate($id) {
        $this->db
            ->where('id',$id)
            ->update('discount_money_receipt_discount',
                [
                'status'=>3,
                'discount_collection_by'=>$_SESSION['user_id'],
                'discount_collection_at'=>date('Y-m-d H:i:s')
                ]
                );
    }

    public function getModel($service_id) {
        if ( ! isset($this->services[$service_id])) {
            return [];
        }

        $search_model = $this->services[$service_id]['model'];

        $this->load->model($search_model);
        $search_model = ltrim(substr($search_model, strpos($search_model,'/')),'/');
        return $search_model;
    }

    public function getMoneyReceiptDiscount($type) {
        $where = [];
        if ($type == 0) {
            $where = ['dmrd.status' => 0];
        }
        if ($type == 1) {
            $where = ['dmrd.status' => 1];
        }
        $result = $this->db
            ->select('dmrd.*, emp.EMP_NAME as emp_name')
            ->from('discount_money_receipt_discount as dmrd')
            ->join('users as u','dmrd.approved_by = u.id', 'left')
            ->join('hrm_ls_employee as emp', 'u.employee_id = emp.EMP_ID','left')
            ->where($where)
            ->get()
            ->result();
        return $result;
    }

    public function tranjactionTable($service_id, $source_id, $mr_discount = 0, $refund = 0){
                            /*     master tranjaction      */ 
       $where = ['service_id'=> $service_id, 'source_id' => $source_id];

       $master_info = $this->db->where($where)->get('transaction_mst')->row();

       $this->db->where('id',$master_info->id)->update('transaction_mst',['mr_discount' => $mr_discount]);

                    /*         tranjaction payment              */
        if ($refund > 0) {
            $data = [
            'mst_id' => $master_info->id,
            'amount' => $refund,
            'transaction_type' => 3,
            'refund_type' => 3,
            'counter_id' => $this->session->userdata('counter_id'),
            'collection_by' => $_SESSION['user_id']
            ];
        $this->db->insert('transaction_payment', $data);
        }
    }

    public function adminPatientTranjaction($service_id, $source_id, $mr_discount = 0, $refund = 0, $admission_id = 0) {
        if ($service_id == 1) {
            $source_type = 4;
        }
        /*         discount            */
        $data = [
            'admission_id' => $admission_id,
            'counter_id' => $this->session->userdata('counter_id'),
            'source_id' =>  $source_id,
            'source_type' =>  $source_type,
            'paid' => $refund,
            'mr_discount' => $mr_discount,
            'paid_type' => 3,
            'created_by' => $_SESSION['user_id'],
        ];

        $this->db->insert('admission_patient_transaction');
    }
}
