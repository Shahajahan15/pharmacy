<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy_money_receipt_cash_collection extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('userwise');
        //$this->auth->restrict('Report.PharmacyMR.View');
    }

    public function getQueryTest($limit, $offset)
    {
        $con = '';
        $hcon = '';

        $role = $this->current_user->role_id;
        $pharmacy_id = ($role == 23) ? 1 : 200;

        if ($this->input->is_ajax_request()) {

            $pharmacy_name = $this->input->post('pharmacy_name', true);
            if ($pharmacy_name) {
                $pharmacy_id = $pharmacy_name;
            }
            $from_date = custom_date_format(trim($this->input->post('from_date')));
            $to_date = custom_date_format(trim($this->input->post('to_date')));

            if (isset($from_date) && $from_date) {
                $con .= " AND psm.created_date >= '$from_date 00:00:00'";
            } else {
                $con .= " AND psm.created_date >= '" . date('Y-m-d 00:00:00') . "'";
            }
            if (isset($to_date) && $to_date) {
                $con .= " AND psm.created_date <= '$to_date 23:59:59'";
            } else {
                $con .= " AND psm.created_date <= '" . date('Y-m-d 23:59:59') . "'";
            }


            $mr_no = trim($this->input->post('mr_num', true));
            if ($mr_no) {
                $con .= " AND psm.sale_no LIKE '%$mr_no'";
            }

            $c_type = $this->input->post('pharmacy_customer_type', true);
            if ($c_type) {
                $con .= " AND psm.customer_type = $c_type";
            }
            $c_name = trim($this->input->post('common_text_search', true));
            if ($c_name) {
                $con .= " AND pc.customer_name LIKE '%$c_name%' OR em.EMP_NAME LIKE '%$c_name%' OR pm.patient_name LIKE '%$c_name%' OR ppm.patient_name LIKE '%$c_name%'";
            }
            $due_paid = $this->input->post('due_paid', true);
            if ($due_paid) {
                if ($due_paid == 1) {
                    $hcon = "HAVING due > 0";
                } else {
                    $hcon = "HAVING due <= 0";
                }
            }
        } else {
            $con .= " AND psm.created_date >= '" . date('Y-m-d 00:00:00') . "'";
            $con .= " AND psm.created_date <= '" . date('Y-m-d 23:59:59') . "'";
        }
        //print_r($pharmacy_id);
        $s_table = 'bf_pharmacy_sales_mst';
        $t_table = 'bf_pharmacy_payment_transaction';
        $r_table = 'bf_pharmacy_sale_return_mst';

        if ($pharmacy_id != 200) {
            $s_table = 'bf_pharmacy_indoor_sales_mst';
            $t_table = 'bf_pharmacy_sub_payment_transaction';
            $r_table = 'bf_pharmacy_indoor_sale_return_mst';
            $con .= " AND psm.pharmacy_id = $pharmacy_id";
        }
        $sql = "
        SELECT
          SQL_CALC_FOUND_ROWS
            psm.id,
            psm.sale_no as mr_no,
            psm.created_date,
            psm.customer_type,
            (
              CASE
                  WHEN psm.customer_type = 1 THEN psm.admission_id
                  WHEN psm.customer_type = 2 THEN psm.patient_id
                  WHEN psm.customer_type = 3 THEN psm.customer_id
                  WHEN psm.customer_type = 5 || psm.customer_type = 4 THEN psm.employee_id
                  WHEN psm.customer_type = 6 THEN 6
              END
            ) AS client_id,
            (
              CASE
                  WHEN psm.customer_type = 1 THEN 'Admission Patient'
                  WHEN psm.customer_type = 2 THEN 'Patient'
                  WHEN psm.customer_type = 3 THEN 'Customer'
                  WHEN psm.customer_type = 4 THEN 'Employee'
                  WHEN psm.customer_type = 5 THEN 'Doctor'
                  WHEN psm.customer_type = 6 THEN 'Hospital'
              END
            ) AS customer_type_name,
            (
              CASE
                  WHEN psm.customer_type = 1 THEN pm.patient_name
                  WHEN psm.customer_type = 2 THEN ppm.patient_name
                  WHEN psm.customer_type = 3 THEN pc.customer_name
                  WHEN psm.customer_type = 5 || psm.customer_type = 4 THEN em.EMP_NAME
                  WHEN psm.customer_type = 6 THEN 'Hospital'
              END
            ) AS client_name,
            ROUND(psm.tot_bill) as tot_bill,
            ROUND(psm.tot_paid) as paid,
            ROUND(psm.tot_less_discount) as less_discount,
            ROUND(psm.tot_normal_discount + psm.tot_service_discount) as ns_discount,
            ROUND(psm.return_bill) as return_bill,
            ROUND(IFNULL(SUM(rpsm.tot_paid_return_amount), 0)) as return_paid,
            ROUND(IFNULL(SUM(rpsm.overall_discount), 0)) as retrun_overall_discount,
            ROUND(psm.return_less_discount) as return_less_discount,
            ROUND(IFNULL(SUM(dppt.amount), 0)) as due_paid,
            ROUND(IFNULL(SUM(dppt.overall_discount), 0)) as overall_discount,

            ROUND(
              (
                IF(
                  (psm.tot_bill) > (psm.tot_paid),

                  (
                    (psm.tot_bill)
                    -
                    (
                      (psm.tot_less_discount)
                      +
                      (psm.tot_paid)
                    )
                  ),

                  (
                    (psm.tot_paid)
                    -
                    (
                      (psm.tot_less_discount)
                      +
                      (psm.tot_bill)
                    )
                  )

                )
                /*--   endif       --*/
              ) 
              -
              (
                (IFNULL(SUM(dppt.amount), 0))
                +
                (IFNULL(SUM(dppt.overall_discount), 0))
              )
              -
              (
                IF(
                  (psm.return_bill) > (IFNULL(SUM(rpsm.tot_paid_return_amount), 0) ),

                  (
                    (psm.return_bill)
                    -
                    (
                      (psm.return_less_discount)
                      +
                      (IFNULL(SUM(rpsm.overall_discount), 0))
                      +
                      (IFNULL(SUM(rpsm.tot_paid_return_amount), 0) )
                    )
                  ),

                  (
                    (IFNULL(SUM(rpsm.tot_paid_return_amount), 0) )
                    -
                    (
                      (psm.return_less_discount)
                      +
                      (IFNULL(SUM(rpsm.overall_discount), 0))
                      +
                      (psm.return_bill)
                    )
                  )
                )
                 /*--   endif       --*/
              )
            ) as due

          FROM 
            {$s_table} as psm
          LEFT JOIN
          (
            SELECT 
              trpsm.sale_id,
              IFNULL(SUM(trpsm.tot_paid_return_amount), 0) as tot_paid_return_amount,
              IFNULL(SUM(trpsm.overall_discount), 0) as overall_discount
            FROM 
              {$r_table} as trpsm
            GROUP BY
              trpsm.sale_id
          ) as rpsm
           ON rpsm.sale_id = psm.id
         LEFT JOIN
         (
            SELECT 
              tdppt.source_id,
              IFNULL(SUM(tdppt.amount), 0) as amount,
              IFNULL(SUM(tdppt.overall_discount), 0) as overall_discount
            FROM 
              {$t_table} as tdppt
            WHERE
              tdppt.type = 2
            GROUP BY 
              tdppt.source_id
         ) as dppt
          ON psm.id = dppt.source_id
          LEFT JOIN
            bf_admission_patient as ap
              ON ap.id = psm.admission_id and psm.customer_type = 1
          LEFT JOIN 
            bf_patient_master as pm
              ON pm.id = ap.patient_id
          LEFT JOIN
            bf_pharmacy_customer as pc
              ON pc.id = psm.customer_id and psm.customer_type = 3
          LEFT JOIN
            bf_hrm_ls_employee as em
              ON em.EMP_ID = psm.employee_id and (psm.customer_type = 4 | psm.customer_type = 5)
          LEFT JOIN
            bf_patient_master as ppm
              ON ppm.id = psm.patient_id and psm.customer_type = 2
          WHERE 
            psm.id > 0
              {$con}
          GROUP BY
            psm.id
            {$hcon}
          ORDER BY
            psm.created_date DESC
          LIMIT {$offset},{$limit}
      ";


        $result = $this->db->query($sql)->result();
        // echo '<pre>';print_r($result);exit;
        // echo '<pre>';print_r($this->db->last_query($result));exit;

        return $result;
    }


    public function index()
    {
        $data = [];
        $data['pharmacy_name'] = "Main Pharmacy";
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 50;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['contact_no_flag'] = 0;
        $search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['pharmacy_list_flag'] = 1;

        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Customer Name';
        $search_box['due_paid_flag'] = 1;
        $search_box['mr_no_flag'] = 1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['per_page'] = $limit;

        $data['records'] = $this->getQueryTest($limit, $offset);

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/pharmacy_money_receipt_cash_collection/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        //echo '<pre>';print_r($result);exit;
        $list_view = 'pharmacy/moneny_receipt/index';
        $data['pharmacy_id'] = 200;
        if ($this->input->is_ajax_request()) {


            $pharmacy_id = $this->input->post('pharmacy_name', true);
            if ($pharmacy_id) {
                $data['pharmacy_id'] = $pharmacy_id;
            } else {
                $data['pharmacy_id'] = 200;
            }


            if ($pharmacy_id && ($pharmacy_id != 200)) {
                $pharmacy_name = $this->db->where('id', $pharmacy_id)->get('bf_pharmacy_setup')->row();
                $data['pharmacy_name'] = ($pharmacy_name->name) ? $pharmacy_name->name : "";
            }
            $data['from_date'] = custom_date_format(trim($this->input->post('from_date')));
            $data['to_date'] = custom_date_format(trim($this->input->post('to_date')));


            echo $this->load->view($list_view, $data, true);
            exit();
        }


        //echo "<pre>";print_r($data['pharmacy_id']);exit();

        Template::set("toolbar_title", "Money Receipt Wise Report");
        Template::set_block('sub_nav', 'pharmacy/moneny_receipt/_sub_report');
        Template::set('list_view', $list_view);
        Template::set($data);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

    /*

    public function index(){
       $data['pharmacy_id']=0;
          $data = array();
          $filter = array();
          $this->load->library('pagination');
            $offset = (int)$this->input->get('per_page');
            $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
            $sl=$offset;
            $data['sl']=$sl;
            $search_box = $this->searchpanel->getSearchBox($this->current_user);
          $search_box['contact_no_flag'] = 0;
          $search_box['pharmacy_customer_type_flag']=1;
          $search_box['pharmacy_list_flag']=1;

          $search_box['client_name_flag'] = 1;
          $search_box['client_id_flag'] = 0;
          $search_box['patient_id_flag'] = 0;
          $search_box['mr_no_flag'] = 1;
          $search_box['from_date_flag'] = 1;
          $search_box['to_date_flag'] = 1;
          $condition =[];
          if(count($_POST)>0){


          if($_POST['mr_num']){


            $condition['psm.sale_no like']='%'.trim($this->input->post('mr_num')).'%';
           }

                //var_dump($condition);die();
            if($_POST['pharmacy_customer_type']){


            $condition['psm.customer_type like']='%'.trim($this->input->post('pharmacy_customer_type')).'%';
          }

        if($this->input->post('pharmacy_name')){

            $pharmacy_id=$this->input->post('pharmacy_name');
                   // print_r($pharmacy_id);exit();
            if($_POST['pharmacy_name']!=200){
              $condition['psm.pharmacy_id']=$pharmacy_id;
            }

          }




                  if($_POST['pharmacy_customer_type']==1){
                    if($_POST['clien_name']){
                      $condition['pm.patient_name like']='%'.trim($this->input->post('clien_name')).'%';
                    }
                  }
                  elseif($_POST['pharmacy_customer_type']==2){
                    if($_POST['clien_name']){
                      $condition['ppm.patient_name like']='%'.trim($this->input->post('clien_name')).'%';
                    }

                  }
                   elseif ($_POST['pharmacy_customer_type']==3){
                     if($_POST['clien_name']){
                      $condition['pc.customer_name like']='%'.trim($this->input->post('clien_name')).'%';
                    }
                  }
                    elseif ($_POST['pharmacy_customer_type']==4){
                               if($_POST['clien_name']){
                      $condition['em.EMP_NAME like']='%'.trim($this->input->post('clien_name')).'%';
                    }
                  }
                    elseif ($_POST['pharmacy_customer_type']==5){
                               if($_POST['clien_name']){
                      $condition['em.EMP_NAME like']='%'.trim($this->input->post('clien_name')).'%';
                    }
                  }
                //   if($this->input->post('from_date')){
                //     $from_date = custom_date_format(trim($this->input->post('from_date')));
                //     $condition['psm.created_date >='] = $from_date." 00:00:00";

                // }

                // if($this->input->post('to_date')){
                //     $to_date = custom_date_format(trim($this->input->post('to_date')));
                //     $condition['psm.created_date <='] = $to_date." 23:59:59";
                // }

     if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
                $filter['from_date'] = $from_date->format('Y-m-d');
                $data['from_date'] = $from_date->format('Y-m-d');
              }
              if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
                  $filter['to_date'] = $to_date->format('Y-m-d');
                  $data['to_date'] = $to_date->format('Y-m-d');
              }

               //$like_where = array();
            if (isset($filter['from_date'])) {
                $condition['psm.created_date >='] = $filter['from_date']." 00:00:00";
            }
            if (isset($filter['to_date'])) {
                $condition['psm.created_date <='] = $filter['to_date']." 23:59:59";
            }

            if (!isset($filter['from_date']) && !isset($filter['to_date'])) {
                $condition['psm.created_date >='] = date('Y-m-d 00:00:00');
                $condition['psm.created_date <='] = date('Y-m-d 23:59:59');
            }
          }
           else
                {
                  $condition["DATE_FORMAT(psm.created_date,'%Y-%m-%d')"]=date("Y-m-d");
                }


    $pharmacy_name=array();

       if($this->input->post('pharmacy_name')=="200" || $this->input->post('pharmacy_name')=="")
       {
          $c_info = $this->db
                      ->select("
                        SQL_CALC_FOUND_ROWS
                        (
                          CASE
                            WHEN
                              psm.customer_type=1
                            THEN
                              pm.patient_name
                          ELSE
                        (CASE WHEN psm.customer_type=2 THEN ppm.patient_name ELSE
                        (CASE WHEN psm.customer_type=3 THEN pc.customer_name ELSE
                        (CASE WHEN psm.customer_type=4||psm.customer_type=5 THEN em.EMP_NAME ELSE 'ghjh' END) END) END) END) as name,
                        (CASE WHEN psm.customer_type=1 THEN pm.contact_no ELSE
                        (CASE WHEN psm.customer_type=2 THEN ppm.contact_no ELSE
                        (CASE WHEN psm.customer_type=3 THEN pc.customer_mobile ELSE
                        '' END) END) END) as mobile,
                        (CASE WHEN psm.customer_type=1 THEN ap.admission_code ELSE
                        (CASE WHEN psm.customer_type=2 THEN pm.patient_id ELSE
                        (CASE WHEN psm.customer_type=4||psm.customer_type=5 THEN em.EMP_CODE ELSE
                        '' END) END) END) as customer_id,
                        psm.sale_no,
                        psm.created_date,
                        psm.tot_bill,
                        psm.tot_paid,
                        psm.tot_due,
                        psm.customer_type,
                        psm.return_bill,


                        psm.tot_normal_discount,
                        psm.tot_service_discount,
                        psm.tot_less_discount,

                        'Main Pharmacy' as pharmacy_name

                        ",false)

      ->from('bf_pharmacy_sales_mst as psm')
      ->join('bf_admission_patient as ap','ap.id=psm.admission_id','left')
      ->join('bf_patient_master as pm','pm.id=psm.patient_id','left')
      ->join('bf_pharmacy_customer as pc','pc.id=psm.customer_id','left')
      ->join('bf_hrm_ls_employee as em','em.EMP_ID=psm.employee_id','left')
      ->join('bf_patient_master as ppm','ppm.id=psm.patient_id','left')
      //->join('bf_pharmacy_sales_mst as psm','psm.id=psm.id')
      ->where($condition)
     // ->group_by('psm.customer_type')
      //->group_by('ppt.id')
      ->order_by('psm.created_date','DESC')
      ->limit($limit,$offset)

      ->get()
      ->result();




    }

    else{
      $pharmacy_name=$this->db->select("
          pst.name as pharmacy_name
          ")
        ->from("bf_pharmacy_setup as pst")
        ->where("pst.id",$pharmacy_id)
        ->get()
        ->row();
        $c_info=$this->db
        ->select("
          SQL_CALC_FOUND_ROWS

          (CASE WHEN psm.customer_type=1 THEN pm.patient_name ELSE
          (CASE WHEN psm.customer_type=2 THEN ppm.patient_name ELSE
          (CASE WHEN psm.customer_type=3 THEN pc.customer_name ELSE
          (CASE WHEN psm.customer_type=4||psm.customer_type=5 THEN em.EMP_NAME ELSE 'ghjh' END) END) END) END) as name,
          (CASE WHEN psm.customer_type=1 THEN pm.contact_no ELSE
          (CASE WHEN psm.customer_type=2 THEN ppm.contact_no ELSE
          (CASE WHEN psm.customer_type=3 THEN pc.customer_mobile ELSE
          '' END) END) END) as mobile,
          (CASE WHEN psm.customer_type=1 THEN ap.admission_code ELSE
          (CASE WHEN psm.customer_type=2 THEN pm.patient_id ELSE
          (CASE WHEN psm.customer_type=4||psm.customer_type=5 THEN em.EMP_CODE ELSE
          '' END) END) END) as customer_id,
          psm.sale_no,
          psm.created_date,
          psm.tot_bill,
          psm.tot_paid,
          psm.tot_due,

          psm.id,
          psm.pharmacy_id,
          psm.customer_type,
          psm.tot_normal_discount,
          psm.tot_service_discount,
          psm.tot_less_discount,
          pst.name as pharmacy_name,
          psm.return_bill
          ",false)

        ->from('bf_pharmacy_indoor_sales_mst as psm')
        ->join('bf_pharmacy_setup as pst','pst.id=psm.pharmacy_id')
        ->join('bf_admission_patient as ap','ap.id=psm.admission_id','left')
        ->join('bf_patient_master as pm','pm.id=psm.patient_id','left')
        ->join('bf_pharmacy_customer as pc','pc.id=psm.customer_id','left')
        ->join('bf_hrm_ls_employee as em','em.EMP_ID=psm.employee_id','left')
        ->join('bf_patient_master as ppm','ppm.id=psm.patient_id','left')
       // ->join('bf_pharmacy_indoor_sales_mst as psm','psm.id=psm.id')
        ->where($condition)
        ->limit($limit,$offset)
        //->group_by('ppt.type')
        //->group_by('ppt.id')
        ->order_by('psm.created_date','DESC')
        ->get()
        ->result();




      }

    $data['pharmacy_name']=$pharmacy_name;
    $data['c_info'] = $c_info;
    $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
            $this->pager['base_url'] = site_url() . '/admin/pharmacy_money_receipt_cash_collection/report/index' . '?';
            $this->pager['total_rows'] = $total;
            $this->pager['per_page'] = $limit;
            $this->pager['page_query_string'] = TRUE;
            $this->pagination->initialize($this->pager);

    //echo '<pre>';print_r($data['c_info']);exit();
    $list_view='Pharmacy_money_receipt_cash_collection/index';
    if($this->input->is_ajax_request()){
     if(isset($_POST['print'])){
                    echo $this->load->view('Pharmacy_money_receipt_cash_collection/print_report', $data, true);
                    exit;
                }

      echo $this->load->view($list_view, $data,true);
      exit();
    }


    Template::set("toolbar_title", "Pharmacy Money Receipt Wise Collection List");
    Template::set_block('sub_nav', 'Pharmacy_money_receipt_cash_collection/_sub_report');
    Template::set('list_view',$list_view);
    Template::set($data);
    Template::set('search_box',$search_box);
    Template::set_view('report_template');
    Template::render();












    } */

    public function money_receipt_details($id, $mr_no, $pharmacy_id)
    {

        //echo "<pre>";print_r($pharmacy_id);exit();
        $s_mst = 'bf_pharmacy_sales_mst';
        $s_dtls = 'bf_pharmacy_sales_dtls';
        $r_mst = 'bf_pharmacy_sale_return_mst';
        $r_dtls = 'bf_pharmacy_sale_return_dtls';
        $s_trans = 'bf_pharmacy_payment_transaction';
        if ($pharmacy_id != 200) {
            $s_mst = 'bf_pharmacy_indoor_sales_mst';
            $s_dtls = 'bf_pharmacy_indoor_sales_dtls';
            $r_mst = 'bf_pharmacy_indoor_sale_return_mst';
            $r_dtls = 'bf_pharmacy_indoor_sale_return_dtls';
            $s_trans = 'bf_pharmacy_sub_payment_transaction';
        }

        $sql = "
        SELECT
         psm.customer_type,
         psm.tot_paid,
         psm.tot_bill,
         psm.tot_less_discount,
         psm.created_date,
         IF(psm.customer_type=1,apm.patient_name,0) AS admission_patient,
         IF(psm.customer_type=2,pm.patient_name,0) AS patient_name, 
         IF(psm.customer_type=3,pc.customer_name,0) AS  customer_name,
         IF(psm.customer_type=4 || psm.customer_type=5,emp.EMP_NAME,0) AS  EMP_NAME,
         pp.product_name,
         psd.qnty,
         psd.normal_discount_percent,
         psd.normal_discount_taka,
         psd.service_discount_percent,
         psd.service_discount_taka,
         (psd.normal_discount_taka+psd.service_discount_taka) as total_discount,
         psd.unit_price
        FROM
         {$s_mst} AS psm
        JOIN
          {$s_dtls} AS psd ON psd.master_id =  psm.id
        JOIN
          bf_pharmacy_products AS pp ON pp.id =  psd.product_id
        LEFT JOIN
            bf_admission_patient AS ap ON ap.id = psm.admission_id
        LEFT JOIN
            bf_patient_master AS apm ON apm.id = ap.patient_id
        LEFT JOIN
            bf_patient_master AS pm ON  pm.id = psm.patient_id
        LEFT JOIN 
            bf_pharmacy_customer AS pc ON  pc.id = psm.customer_id 
        LEFT JOIN
             bf_hrm_ls_employee AS emp ON  emp.EMP_ID = psm.employee_id
        WHERE psm.id = $id";

        $record1 = $this->db->query($sql)->result();

        // echo "<pre>";print_r($record1);exit();


        $sql = "
        SELECT
            psrd.r_qnty, 
            psrd.r_sub_total,
            psrd.tot_discount,
            psrd.price,
            psrm.mr_no,
            psrm.created_date,
            pp.product_name
         from
             {$r_mst} AS psrm
         join
             {$r_dtls} AS psrd on  psrd.master_id =  psrm.id
         JOIN
            bf_pharmacy_products AS pp on pp.id =  psrd.product_id
          where  psrm.sale_id = $id";

        $record2 = $this->db->query($sql)->result();

        $sql = "
        SELECT
            SUM(psrm.tot_return_amount) AS tot_return_amount,
            SUM(psrm.tot_paid_return_amount) AS tot_paid_return_amount,
            SUM(psrm.tot_return_due) AS tot_return_due,
            SUM(psrm.tot_less_discount) AS tot_less_discount,
            SUM(psrm.overall_discount) AS overall_discount
         from
             {$r_mst} AS psrm

          where  psrm.sale_id = $id
          group by psrm.sale_id ";

        $record4 = $this->db->query($sql)->result();
//echo '<pre>'; echo $id; print_r($record4);exit;
        $sql = "
        SELECT
         strans.create_time,
         strans.due_mr_no,
         strans.amount
        from {$s_trans} AS strans
        where   strans.type=2 AND strans.source_id = $id";

        $record3 = $this->db->query($sql)->result();


        //echo '<pre>'; echo $id; print_r($record3);exit;
        $data['id'] = $id;
        $data['mr_no'] = $mr_no;
        $data['pharmacy_id'] = $pharmacy_id;

        $data['record1'] = $record1;
        $data['record2'] = $record2;
        $data['record3'] = $record3;
        $data['record4'] = $record4;
        $list_view = 'pharmacy/moneny_receipt/details';

        if ($this->input->is_ajax_request()) {
            if (isset($_POST['print'])) {
                echo $this->load->view('pharmacy/moneny_receipt/print_details', $data, true);
                exit;
            }
            echo $this->load->view($list_view, $data, true);
            exit;
        }

        Template::set("toolbar_title", "Money Receipt Wise Details Report");
        Template::set('list_view', $list_view);
        Template::set($data);
        //Template::set('search_box',$search_box);
        Template::set_block('sub_nav', 'pharmacy/moneny_receipt/_sub_details');
        Template::set_view('report_template');
        Template::render();

    }
}