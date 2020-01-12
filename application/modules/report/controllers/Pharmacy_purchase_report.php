<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy_purchase_report extends Admin_Controller 
{
	    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict('Report.Pharmacy.purchase.payment.View');
        $this->auth->restrict('Report.MedicinePayment.Print');
        
    }

        public function index()
    { 
        
      $search_box = $this->searchpanel->getSearchBox($this->current_user);
      $search_box['company_name_flag'] = 1;
      $search_box['supplier_name_flag'] = 1;  
      $search_box['from_date_flag'] = 0;
      $search_box['to_date_flag'] = 0;

      $this->load->library('pagination');
      $offset=(int)$this->input->get('per_page');
      $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
      $data['sl']=$offset;
      if(isset($_POST['print'])){
          $limit=1000;
          $offset=0;
         }
      

      $con = '';
      $con .= "rm.id > 0";
      if(count($_POST)>0){
      if($this->input->post('company_name')){
                $company_name=trim($this->input->post('company_name'));
                $con .= " AND c.company_name LIKE '%$company_name'";
            }
     if($this->input->post('supplier_name')){
                $supplier_name=trim($this->input->post('supplier_name'));
                $con .= " AND s.supplier_name LIKE '%$supplier_name'";
       } 
       }     
      $sql = "
            SELECT
               SQL_CALC_FOUND_ROWS 
               rm.supplier_id, 
               s.supplier_name,
               SUM(pm.paid) AS  paid,
               sum(rm.total_price) as total_bill,
               sum(rm.received_qnty) as received_qnty,
               c.company_name,
               rm.id
            FROM 
               (
               SELECT
                 rms.id,
                 rms.supplier_id,
                 rms.order_id,
                 /*for received quantity*/
                 (SELECT 
                   sum(rd.received_qnty) 
                  FROM
                    bf_pharmacy_purchase_order_received_dtls as rd where rd.master_id=rms.id
                  GROUP BY rd.master_id
                         
                 ) as received_qnty,
                  /*for total price*/
                 (SELECT 
                   sum(rd.total_price) 
                  FROM
                    bf_pharmacy_purchase_order_received_dtls as rd where rd.master_id=rms.id
                  GROUP BY rd.master_id
                         
                 ) as   total_price


               FROM
                  bf_pharmacy_purchase_order_received_mst as rms
                    
               ) as rm   

            JOIN  
                bf_pharmacy_supplier as s ON s.id=rm.supplier_id
            JOIN
                bf_pharmacy_product_company AS c on c.id = s.company_id     
          /*for paid amount*/
          LEFT JOIN 
                 (
                 SELECT
                    pms.receive_order_id,

                   (SELECT 
                      sum(pd.paid) 
                    FROM
                       bf_pharmacy_payment_dtls as pd where pd.master_id = pms.id
                    GROUP BY pd.master_id
                         
                  ) as  paid
                 FROM  
                   bf_pharmacy_payment_mst as pms
                  )
                AS pm ON pm.receive_order_id=rm.id 
            where {$con}  

            GROUP BY rm.supplier_id
            LIMIT {$offset},{$limit}";
        

        $records=$this->db->query($sql)->result();
       

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/pharmacy_purchase_report/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);


   

        $list_view = 'purchase_report/pur_com_view';
        $data['search_box'] = $search_box;
        $data['records'] = $records;
        $data['toolbar_title'] = 'Medicine Purchase  Payment Report';
        $data['list_view'] = $list_view ;


        if ($this->input->is_ajax_request()) {
          if(isset($_POST['print'])){
              echo $this->load->view('purchase_report/print_report', $data, true);
                                   exit;
          }
        echo $this->load->view($list_view, $data, true);
                                   exit;
                            }

        Template::set($data);
        Template::set_block('sub_nav', 'purchase_report/_sub_report');
        Template::set_view('report_template');
        Template::render();
    }	

    public function per_pay_details($supplier_id)
    {
      $search_box = $this->searchpanel->getSearchBox($this->current_user);
      $search_box['bill_no_flag'] = 1;  
      $search_box['from_date_flag'] = 0;
      $search_box['to_date_flag'] = 0;
   
      $this->load->library('pagination');
      $offset=(int)$this->input->get('per_page');
      $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
      $data['sl']=$offset;

      if(isset($_POST['print'])){
      $limit=1000;
      $offset=0;
      }

      $con = '';
      $con .= "rm.supplier_id= '$supplier_id'";
      if(count($_POST)>0){
     if($this->input->post('bill_no')){
                $bill_no=trim($this->input->post('bill_no'));
                $con .= " AND rm.bill_no= '$bill_no'";
       } 
       }      

    $sql = "
            SELECT
               SQL_CALC_FOUND_ROWS 
               s.supplier_name,
               rm.total_price as total_price,
               rm.received_qnty as received_qnty,
               rm.bill_no,
               rm.id,
               pm.paid as paid
            FROM
              (
               SELECT
                 rms.id, 
                 rms.supplier_id,
                 /*for received quantity*/
                 (SELECT 
                   sum(rd.received_qnty) 
                  FROM
                    bf_pharmacy_purchase_order_received_dtls as rd where rd.master_id=rms.id
                  GROUP BY rd.master_id
                         
                 ) as received_qnty,
                  /*for total price*/
                 (SELECT 
                   sum(rd.total_price) 
                  FROM
                    bf_pharmacy_purchase_order_received_dtls as rd where rd.master_id=rms.id
                  GROUP BY rd.master_id
                         
                 ) as   total_price,
                 rms.bill_no
                FROM 
                   bf_pharmacy_purchase_order_received_mst as rms
               ) as rm  
            JOIN  
                  bf_pharmacy_supplier as s ON s.id=rm.supplier_id 
           /*for paid amount*/       
          LEFT JOIN 
                 (
                 SELECT
                    pms.receive_order_id,

                   (SELECT 
                      sum(pd.paid) 
                    FROM
                       bf_pharmacy_payment_dtls as pd where pd.master_id = pms.id
                    GROUP BY pd.master_id
                         
                  ) as  paid
                 FROM  
                   bf_pharmacy_payment_mst as pms
                  )
                AS pm ON pm.receive_order_id=rm.id        
            where 
                   {$con} LIMIT {$offset},{$limit}";


   
                  
      $records = $this->db->query($sql)->result();  
       //echo "<pre>"; print_r($records); exit();


      $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
      $this->pager['base_url'] = site_url()."/admin/pharmacy_purchase_report/report/per_pay_details/$supplier_id".'?';
      $this->pager['total_rows'] = $total;
      $this->pager['per_page'] = $limit;
      $this->pager['page_query_string'] = TRUE;
      $this->pagination->initialize($this->pager);



      $list_view = 'purchase_report/pur_com_details';
      $data['search_box'] = $search_box;
      $data['records'] = $records;
      $data['toolbar_title'] = 'Medicine Purchase Payment  Details';
      $data['list_view'] = $list_view ;

      if ($this->input->is_ajax_request()) {
        if(isset($_POST['print'])){
        echo $this->load->view('purchase_report/print_report_details', $data, true);
                             exit;
          }
         echo $this->load->view($list_view, $data, true);
                                   exit;
                            }

      Template::set($data);
      Template::set_block('sub_nav', 'purchase_report/_sub_report_details');
      Template::set_view('report_template');
      Template::render();
    }

        public function per_pay_details_details($master_id)
    {

    $sql = "
            SELECT 
                   s.supplier_name,
                   rm.bill_no as bill_no,
                   rd.total_price  as total_price, 
                   rd.received_qnty  as received_qnty,
                   rd.unit_price as unit_price,
                   rm.created_at as mydate,
                   p.product_name as product_name,
                   0 as paid
            FROM
                   bf_pharmacy_purchase_order_received_mst as rm
            JOIN
                   bf_pharmacy_purchase_order_received_dtls as rd ON rd.master_id=rm.id
            JOIN  
                   bf_pharmacy_supplier as s ON s.id=rm.supplier_id        
            JOIN
                   bf_pharmacy_products AS p ON p.id = rd.product_id
            where 
                   rm.id = $master_id                       



     UNION
             SELECT
                   s.supplier_name,
                   0 as bill_no,
                   0 as total_price, 
                   0 as received_qnty,
                   0 as unit_price,
                   pd.created_date as mydate,
                   0 as product_name,
                   pd.paid as paid
                   
             FROM
                   bf_pharmacy_payment_mst as pm
             JOIN
                   bf_pharmacy_payment_dtls as pd on pd.master_id=pm.id
             JOIN  
                   bf_pharmacy_supplier as s ON s.id=pm.supplier_id               

             where 
                   pm.receive_order_id = $master_id
             ORDER BY mydate";

   
                  
      $records = $this->db->query($sql)->result();  
       //echo "<pre>"; print_r($records); exit();
      $list_view = 'purchase_report/pur_com_details_details';

       if ($this->input->is_ajax_request()) {
        if(isset($_POST['print'])){
        echo $this->load->view('purchase_report/print_report_details_details', $data, true);
                             exit;
          }
         echo $this->load->view($list_view, $data, true);
                                   exit;
                            }


      //$data['search_box'] = $search_box;
      $data['records'] = $records;
      $data['toolbar_title'] = 'Medicine Purchase Payment Details';
      $data['list_view'] = $list_view ;

      Template::set($data);
      Template::set_block('sub_nav', 'purchase_report/_sub_report_details_details');
      Template::set_view('report_template');
      Template::render();
    }



}