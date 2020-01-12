<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cafetarea_report extends Admin_Controller {
	public function __construct(){
        parent::__construct();
        
        $this->lang->load('userwise');
        $this->auth->restrict('Report.Cafetarea.Stock.View');
    }

    public function stock_report(){

    	$data = array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['product_name_flag'] = 1;
        $search_box['company_name_flag'] = 1;
        $search_box['category_name_flag'] = 1;

        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $data['sl']=$offset;

        $condition['cs.id>=']=0;
        if(count($_POST)>0){

            if($this->input->post('product_name')){
                $condition['cp.product_name']=trim($this->input->post('product_name'));
            }

            if($this->input->post('company_name')){
                $condition['cpc.company_name']=trim($this->input->post('company_name'));
            }

            if($this->input->post('category_name')){
                $condition['cc.category_name']=trim($this->input->post('category_name'));
            }



        }



    	$records=$this->db
        ->select('
            SQL_CALC_FOUND_ROWS 
            cp.product_name,
            cs.quantity_level,
            cc.category_name,
            cp.sale_price,
            cpc.company_name
            ',false)
        ->from('bf_canteen_stock as cs')
        ->join('bf_canteen_products as cp','cp.id=cs.product_id')
        ->join('bf_canteen_category as cc','cc.id=cp.category_id')
        ->join('bf_canteen_product_company as cpc','cpc.id=cp.company_id')
        ->where($condition)
        ->limit($limit,$offset)
        ->get()
        ->result();
       // echo "<pre>" ; print_r($records);exit();
        $total_quantity = 0;
        $total_amount = 0;
        foreach ($records as $key => $value) {
        	$quantity = $value->quantity_level;
        	$amount = $value->sale_price;
        	$total_quantity+= $quantity;
        	$t_amount=$quantity*$amount;
        	$total_amount+= $t_amount;
        }

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/cafetarea_report/report/stock_report' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'canteen_report/can_view';
        $data['search_box'] = $search_box;
        $data['records'] = $records;
        $data['total_quantity'] = $total_quantity;
        $data['total_amount'] = $total_amount;
        $data['toolbar_title'] = 'Cafetarea Stock Report';
        $data['list_view'] = $list_view ;

        if ($this->input->is_ajax_request()) {
        echo $this->load->view($list_view, $data, true);
                                   exit;
        }

        Template::set($data);
        Template::set_view('report_template');
        Template::render();
    }

}