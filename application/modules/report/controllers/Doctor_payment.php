<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctor_payment extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
         $this->auth->restrict('Report.TestService.View');
	}

    
    public function index()
    {
      $search_box = $this->searchpanel->getSearchBox($this->current_user);
      $search_box['doctor_type_list_flag'] = 1;
      $search_box['payment_type_list_flag'] = 1;  
      $search_box['doctor_full_name_flag']=1;
      $search_box['from_date_flag'] = 1;
      $search_box['to_date_flag'] = 1;

      $this->load->library('pagination');
      $offset=(int)$this->input->get('per_page');
      $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
      $data['sl']=$offset;
      if(isset($_POST['print'])){
          $limit=1000;
          $offset=0;
         }
      

      $con = '';
      $cons= '';
      $from_date = custom_date_format(trim($this->input->post('from_date')));
      $to_date = custom_date_format(trim($this->input->post('to_date')));
      if(count($_POST)>0){
            if($this->input->post('payment_type_list')){
                $payment_type=$this->input->post('payment_type_list');
                $con .= " AND dp.payment_type ='$payment_type'";
            }
            if($this->input->post('doctor_type_list')){
                $doctor_type=$this->input->post('doctor_type_list');
                $con .= " AND dp.doctor_type ='$doctor_type'";
            }
            if($this->input->post('doctor_full_name')){
                $doctor_name=trim($this->input->post('doctor_full_name'));
                $con .= " AND lr.ref_name like '%$doctor_name%'";
            }
            
            if (isset($from_date) && $from_date) {
              $con .=" AND dp.paid_date >= '$from_date 00:00:00'";

            } else {
               $con .=" AND dp.paid_date >= '".date('Y-m-d 00:00:00')."'";
            }

            if (isset($to_date) && $to_date) {
              $con .=" AND dp.paid_date <= '$to_date 23:59:59'";
            } else {
              $con .=" AND dp.paid_date <= '".date('Y-m-d 23:59:59')."'";
            }
        }else {
            $con .=" AND dp.paid_date >= '".date('Y-m-d 00:00:00')."'";
            $con .=" AND dp.paid_date <= '".date('Y-m-d 23:59:59')."'";
            
        }
        
        $sql = "
            SELECT
                `dp`.`doctor_type`,
                `dp`.`payment_type`,
                `lr`.`ref_name`,
                IF(
                    com.payment_type = 1,
                    com.tot_commission_amount,
                    0
                ) AS tot_commission_amount,
                IF(
                    ROUND.payment_type = 2,
                    ROUND.tot_round_amount,
                    0
                ) AS tot_round_amount,
                 IF(
                    operation.payment_type = 3,
                    operation.tot_ope_amount,
                    0
                ) AS tot_ope_amount,
                 
                IF(
                    dp.payment_type = 1,
                    SUM(dp.payment),
                    0
                ) AS tot_commission_payment,
                IF(
                    dp.payment_type = 2,
                    SUM(dp.payment),
                    0
                ) AS tot_round_payment,
                IF(
                    dp.payment_type = 3,
                    SUM(dp.payment),
                    0
                ) AS tot_operation_payment
               
            FROM
                `bf_lib_reference` AS `lr`
            LEFT JOIN `bf_doctor_payment` AS `dp`
            ON
                `dp`.`doctor_id` = `lr`.`id`
            LEFT JOIN(
                SELECT SUM(c.commission_amount) AS tot_commission_amount,
                    '1' AS payment_type,
                    dc.agent_id AS agent_id
                FROM
                    `bf_doctor_commission` AS dc
                LEFT JOIN `bf_commission` AS `c`
                ON
                    `c`.`commission_id` = `dc`.`id`
                GROUP BY
                    agent_id
            ) com
            ON
                `com`.`agent_id` = `lr`.`id` AND `com`.`payment_type` = `dp`.`payment_type`            
            LEFT JOIN(
                SELECT SUM(acr.per_patient_price) AS tot_round_amount,
                    '2' AS payment_type,
                    acr.consultant_id AS agent_id
                FROM
                    `bf_admission_consultant_round` AS acr
                GROUP BY
                    acr.consultant_id
            ) ROUND
            ON
                `round`.`agent_id` = `lr`.`id` AND `round`.`payment_type` = `dp`.`payment_type`



                LEFT JOIN(
                SELECT SUM(ope.doctor_settle_amount) AS tot_ope_amount,
                    '3' AS payment_type,
                    ope.doctor_id AS agent_id
                FROM
                    `bf_operation_admission_operation` AS ope
                     WHERE
                `ope`.`check`=2 
                GROUP BY
                    ope.doctor_id
            ) operation
            ON
                `operation`.`agent_id` = `lr`.`id` AND `operation`.`payment_type` = `dp`.`payment_type`
              WHERE
                `dp`.`payment_type` IS NOT NULL
            $con           
  
            GROUP BY
                `lr`.`id`,
                `dp`.`payment_type` ";
       
    	$records = $this->db->query($sql)->result();
       // echo '<pre>';print_r($records);exit();
       
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/doctor_payment/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $list_view = 'doctor_payment/index';
        $data['search_box'] = $search_box;
        $data['records']    = $records;
        $data['from_date']  = $from_date;
        $data['to_date']    = $to_date;
        $data['toolbar_title'] = 'Doctor Payment Summary';
        $data['list_view'] = $list_view ;


        if ($this->input->is_ajax_request()) {
          
        echo $this->load->view($list_view, $data, true);
                exit;
        }

        Template::set($data);
        Template::set("toolbar_title", "Doctor Payment Report");
     
       //	Template::set_block('sub_nav', 'test_service/_sub_report');
        Template::set_view('report_template');
		Template::render();
    }
    
   











}