<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

  /**
   * Time_schedule controller
   */
  class Pharmacy_wise_collection extends Admin_Controller
  {

  	//--------------------------------------------------------------------
  	/**
  	 * Constructor
  	 *
  	 * @return void
  	 */
  	//--------------------------------------------------------------------
  	
  	public function __construct()
  	{
  		parent::__construct();
  		
  		
  		$this->lang->load('common');
  		$this->auth->restrict('Report.Pharmacywise.View');
      $this->load->model('pharmacy/pharmacy_setup_model', NULL, TRUE);
  	}
  	

  	//--------------------------------------------------------------------

  	/**
  	 * Displays a list of form data.
  	 *
  	 * @return void
  	 */
  	//--------------------------------------------------------------------

  	public function index(){
      $data = [];
      $search_box = $this->searchpanel->getSearchBox($this->current_user);    
      $this->load->library('pagination');
      $offset = $this->input->get('per_page');
      $limit = isset($_POST['per_page'])?$this->input->post('per_page'):25;
      $search_box['from_date_flag'] = 1;
      $search_box['to_date_flag'] = 1;  
      $search_box['per_page_flag'] = 0;

      if ($this->input->is_ajax_request()) {
        $from_date = custom_date_format(trim($this->input->post('from_date')));
        $to_date = custom_date_format(trim($this->input->post('to_date')));
        if ($from_date) {
          $data['from_date']=$from_date;
        }
        if ($to_date) {
          $data['to_date']=$to_date;
        }
      }
      $role =$this->current_user->role_id;
      $data['role']=$role;

      $data['pharmacy_id'] = ($role==23) ? 1 : 200;
      $data['records'] = $this->getMainPharmacyCashCollection();
      $data['list_view'] ='pharmacy/pharmacy_wise_collection/index';
      $data['search_box'] = $search_box;

      if ($this->input->is_ajax_request()) {
        echo $this->load->view($data['list_view'], $data, true);
        exit();        
      }        
      Template::set($data);
      Template::set("toolbar_title", "Pharmacy Wise Collection");
      Template::set_block('sub_nav', 'pharmacy/pharmacy_wise_collection/_sub_report');
      Template::set_view('report_template');
      Template::render();
  	}

    /*      main pharmacy cash collection       */
    public function getMainPharmacyCashCollection()
    {
      $con = '';
      if ($this->input->is_ajax_request()) {
        $from_date = custom_date_format(trim($this->input->post('from_date')));
        $to_date = custom_date_format(trim($this->input->post('to_date')));
        if ($from_date) {
          $con .=" AND pspt.create_time >= '$from_date 00:00:00'";
        }
        if (!$from_date) {
          $con .=" AND pspt.create_time >= '".date('Y-m-d 00:00:00')."'";
        }
        if ($to_date) {
          $con .=" AND pspt.create_time <= '$to_date 23:59:59'";
        }
        if (!$to_date) {
          $con .=" AND pspt.create_time <= '".date('Y-m-d 23:59:59')."'";
        }
              
      } else {
        $con .=" AND pspt.create_time >= '".date('Y-m-d 00:00:00')."'";
        $con .=" AND pspt.create_time <= '".date('Y-m-d 23:59:59')."'";
      }

      $sql = "
          SELECT
            200 AS pharmacy_id,
            'Main Pharmacy' AS pharmacy_name,
            IFNULL(ROUND(SUM(IF(pspt.type = 1, pspt.amount, 0))), 0) AS paid_amount,
            IFNULL(ROUND(SUM(IF(pspt.type = 2, pspt.amount, 0))), 0) AS due_paid_amount,
            IFNULL(ROUND(SUM(IF(pspt.type = 3, pspt.amount, 0))), 0) AS return_amount,
            (
              IFNULL(ROUND(SUM(IF(pspt.type = 1, pspt.amount, 0))), 0) 
              + 
              IFNULL(ROUND(SUM(IF(pspt.type = 2, pspt.amount, 0))), 0)
            ) AS cash_receive,
            (
              (
                IFNULL(ROUND(SUM(IF(pspt.type = 1, pspt.amount, 0))), 0) 
                + 
                IFNULL(ROUND(SUM(IF(pspt.type = 2, pspt.amount, 0))), 0)
              )
              -
              (
                IFNULL(ROUND(SUM(IF(pspt.type = 3, pspt.amount, 0))), 0)
              )
            ) As net_cash
          FROM 
            bf_pharmacy_payment_transaction as pspt
          WHERE
            pspt.id > 0
            {$con}
        UNION 
          SELECT
            ps.id AS pharmacy_id,
            ps.name AS pharmacy_name, 
            IFNULL(ROUND(SUM(IF(pspt.type = 1, pspt.amount, 0))), 0) AS paid_amount,
            IFNULL(ROUND(SUM(IF(pspt.type = 2, pspt.amount, 0))), 0) AS due_paid_amount,
            IFNULL(ROUND(SUM(IF(pspt.type = 3, pspt.amount, 0))), 0) AS return_amount,
            (
              IFNULL(ROUND(SUM(IF(pspt.type = 1, pspt.amount, 0))), 0) 
              + 
              IFNULL(ROUND(SUM(IF(pspt.type = 2, pspt.amount, 0))), 0)
            ) AS cash_receive,
            (
              (
                IFNULL(ROUND(SUM(IF(pspt.type = 1, pspt.amount, 0))), 0) 
                + 
                IFNULL(ROUND(SUM(IF(pspt.type = 2, pspt.amount, 0))), 0)
              )
              -
              (
                IFNULL(ROUND(SUM(IF(pspt.type = 3, pspt.amount, 0))), 0)
              )
            ) As net_cash
          FROM 
            bf_pharmacy_setup as ps
          LEFT JOIN 
            bf_pharmacy_sub_payment_transaction as pspt
            ON ps.id = pspt.pharmacy_id {$con}
          GROUP BY 
            ps.id
      ";
      $result = $this->db->query($sql)->result();
     // print_r($this->db->last_query($result));
      return $result;
    }
    /*        user collection details       */
    public function details($pharmacy_id) {
      $search_box = $this->searchpanel->getSearchBox($this->current_user);
      $search_box['common_text_search_flag'] = 1;
      $search_box['per_page_flag'] = 0;
      $search_box['from_date_flag'] = 1;
      $search_box['to_date_flag'] = 1;

      $con = '';
      if ($this->input->is_ajax_request()) {
        $from_date = custom_date_format(trim($this->input->post('from_date')));
        $to_date = custom_date_format(trim($this->input->post('to_date')));
      }
      if ($this->input->is_ajax_request()) {
        $from_date = custom_date_format(trim($this->input->post('from_date')));
        $to_date = custom_date_format(trim($this->input->post('to_date')));
        if ($from_date) {
          $data['from_date']=$from_date;
          $con .=" AND ppt.create_time >= '$from_date 00:00:00'";
        }
        if (!$from_date) {
          $con .=" AND ppt.create_time >= '".date('Y-m-d 00:00:00')."'";
        }
        if ($to_date) {
          $data['to_date']=$to_date;
          $con .=" AND ppt.create_time <= '$to_date 23:59:59'";
        }
        if (!$to_date) {
          $con .=" AND ppt.create_time <= '".date('Y-m-d 23:59:59')."'";
        }
              
      } else {
        $con .=" AND ppt.create_time >= '".date('Y-m-d 00:00:00')."'";
        $con .=" AND ppt.create_time <= '".date('Y-m-d 23:59:59')."'";
      }
      if ($pharmacy_id == 200) {
        $data['records'] = $this->getMainPharmacyCashCollectionByUser($con);
      } else {
        $data['records'] = $this->getSubPharmacyCashCollectionByUser($con, $pharmacy_id);
      }
      $data['search_box'] = $search_box;
      $data['list_view'] = 'pharmacy/pharmacy_wise_collection/details';
      if ($this->input->is_ajax_request()) {
        echo $this->load->view($data['list_view'], $data, true);
        exit();        
      } 
      Template::set($data);

      Template::set("toolbar_title", "User Wise Collection");
      Template::set_block('sub_nav', 'pharmacy/pharmacy_wise_collection/_sub_report_details');
      Template::set_view('report_template');
      Template::render();

    }

    /* Main pharmacy details by uer  */

    private function getMainPharmacyCashCollectionByUser($con = '')
    {
       $records = $this->db
            ->select('
              200 AS pharmacy_id,
              "Main Pharmacy" As pharmacy_name,
              IFNULL(ROUND(SUM(IF(ppt.type = 1, ppt.amount, 0))), 0) AS paid_amount,
              IFNULL(ROUND(SUM(IF(ppt.type = 2, ppt.amount, 0))), 0) AS due_paid_amount,
              IFNULL(ROUND(SUM(IF(ppt.type = 3, ppt.amount, 0))), 0) AS return_amount,
              (
              IFNULL(ROUND(SUM(IF(ppt.type = 1, ppt.amount, 0))), 0) 
              + 
              IFNULL(ROUND(SUM(IF(ppt.type = 2, ppt.amount, 0))), 0)
            ) AS cash_receive,
            (
              (
                IFNULL(ROUND(SUM(IF(ppt.type = 1, ppt.amount, 0))), 0) 
                + 
                IFNULL(ROUND(SUM(IF(ppt.type = 2, ppt.amount, 0))), 0)
              )
              -
              (
                IFNULL(ROUND(SUM(IF(ppt.type = 3, ppt.amount, 0))), 0)
              )
            ) As net_cash,
              u.display_name,
              u.id as user_id
            ')
           ->from('users as u')
           ->join('pharmacy_payment_transaction as ppt','u.id = ppt.collection_by '.$con.'', 'left')
           ->where('u.role_id', 16)
           ->group_by('u.id')
           ->get()
           ->result();
          // print_r($this->db->last_query($records));
      return $records;
    }

    /* Main pharmacy details by uer  */

    private function getSubPharmacyCashCollectionByUser($con = '', $pharmacy_id)
    {
       $records = $this->db
            ->select('
              p.id AS pharmacy_id,
              p.name As pharmacy_name,
              IFNULL(ROUND(SUM(IF(ppt.type = 1, ppt.amount, 0))), 0) AS paid_amount,
              IFNULL(ROUND(SUM(IF(ppt.type = 2, ppt.amount, 0))), 0) AS due_paid_amount,
              IFNULL(ROUND(SUM(IF(ppt.type = 3, ppt.amount, 0))), 0) AS return_amount,
              (
              IFNULL(ROUND(SUM(IF(ppt.type = 1, ppt.amount, 0))), 0) 
              + 
              IFNULL(ROUND(SUM(IF(ppt.type = 2, ppt.amount, 0))), 0)
            ) AS cash_receive,
            (
              (
                IFNULL(ROUND(SUM(IF(ppt.type = 1, ppt.amount, 0))), 0) 
                + 
                IFNULL(ROUND(SUM(IF(ppt.type = 2, ppt.amount, 0))), 0)
              )
              -
              (
                IFNULL(ROUND(SUM(IF(ppt.type = 3, ppt.amount, 0))), 0)
              )
            ) As net_cash,
              u.display_name,
              u.id as user_id
            ')
           ->from('users as u')
           ->join('pharmacy_sub_payment_transaction as ppt','u.id = ppt.collection_by '.$con.'', 'left')
           ->join('bf_pharmacy_setup as p','ppt.pharmacy_id = p.id','left')
           ->where('ppt.pharmacy_id', $pharmacy_id)
           ->where('u.role_id', 23)
           ->group_by('u.id')
           ->get()
           ->result();
         //  print_r($this->db->last_query($records));
      return $records;
    }
     
      	
  }