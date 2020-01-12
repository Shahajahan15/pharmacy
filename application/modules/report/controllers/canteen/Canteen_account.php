<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Canteen_account extends Admin_controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('canteen_report_model');
    $this->auth->restrict('Report.Canteen.Transection');

	}
	public function index(){
		
        $data = array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        

        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $data['sl']=$offset;

        
        $first_date = date('Y-m-d 00:00:00');
        $second_date = date('Y-m-d 23:59:59');
        if(count($_POST)>0){
             if($this->input->post('from_date')){
                $first_date=date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$this->input->post('from_date'))));
                }
            else{
                $first_date = date("Y-m-d 00:00:00");
                }
            if($this->input->post('to_date')){
                $second_date=date('Y-m-d 23:59:59',strtotime(str_replace('/','-',$this->input->post('to_date'))));
                }
            else{
                $second_date=date("Y-m-d 23:59:59");
                }


        }

        $records = $this->canteen_report_model->canteenCashOut(/*$condition,*/$first_date,$second_date,$limit,$offset);

        $recordes = $this->canteen_report_model->canteenCashIn(/*$conditions,*/$first_date,$second_date,$limit,$offset);

        
        $total_cashout = 0;
        $total_cashin = 0;
  

        foreach ($records as  $cash_out) {
          $total_cashout += $cash_out->total_price;
        }

        foreach ($recordes as  $cash_in) {
          $total_cashin +=  $cash_in->amount;
        }


        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/canteen/report/canteen_purchase/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'canteen/transection_list';
        $data['search_box'] = $search_box;
        $data['records'] = $records;
        $data['recordes'] = $recordes;
        $data['total_cashout'] = $total_cashout;
        $data['total_cashin'] = $total_cashin;
        $data['toolbar_title'] = 'Cafetarea Transection Report';
        $data['list_view'] = $list_view ;

        if ($this->input->is_ajax_request()) {
        echo $this->load->view($list_view, $data, true);
                                   exit;
        }

        Template::set($data);
        Template::set_view('report_template');
        Template::render();

	}

  public function test()
  {
    $sql = "SELECT
                  spay.created_at as mydate,
                  spay.paid_amount as amount,
                  spay.master_id as   master_id 
               FROM
                  bf_canteen_sales_payments AS spay
             
               WHERE spay.created_at >= '2017-12-28 00:00:00'
               AND spay.created_at <= '2017-12-28 23:59:59'";
               $records=$this->db->query($sql)->result();
               echo "<pre>";print_r($records);exit();
  }


}