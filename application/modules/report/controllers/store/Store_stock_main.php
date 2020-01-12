<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Store_stock_main extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
		$this->load->model('store/stock_model');
		$this->load->model('store/store_model');
		$this->load->model('store/product_model');
    }

    public function stocks($offset = 0)
    {
        $this->auth->restrict('Report.StoreStockMain.Stocks');

        $data = array();
		$offset = (int) $this->input->get('per_page');
		$limit = (int) $this->input->post('per_page') ?:25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user);
		$search_box['from_date_flag'] = 1;
		$search_box['to_date_flag'] = 1;
        $search_box['store_product_list_flag'] = 1;
        $search_box['store_name_list_flag'] = 1;
        $search_box['store_company_list_flag'] = 1;
		$search_box['patient_name_flag'] = 0;
		$search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = "Product, Category, Sub Category, Company Name";
        $search_box['per_page'] = $limit;
        $data['total_stocks'] = $this->getTotalStock(0);
        if ($this->input->is_ajax_request()) {
            $data['store_name'] = 'All';
            $data['store_id'] = trim($this->input->post('store_name_id', true));
            $store_name = $this->db
                    ->select('STORE_NAME as store_name')
                    ->where('STORE_ID', $data['store_id'])
                    ->get('bf_store')->row();
           if ($store_name){
            $data['store_name'] = $store_name->store_name;
           }
           $data['from_date'] = custom_date_format(trim($this->input->post('from_date', true)));
           $data['to_date'] = custom_date_format(trim($this->input->post('to_date', true)));
           $data['total_stocks'] = $this->getTotalStock($data['store_id']);
        }

        $data['records'] = $this->stock_model->getMainStock($limit, $offset);
        //echo '<pre>';print_r($data['records']);exit();

		$this->load->library('pagination');
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
		$this->pager['base_url'] = site_url('admin/store/report/store_stock_main/stocks');
        $this->pager['total_rows'] = $total;
		$this->pager['per_page'] = $limit;
		$this->pager['page_query_string'] = TRUE;
		$this->pagination->initialize($this->pager);

        $data['hospital'] = $this->db->get('lib_hospital')->row();
		$data['search_box'] = $search_box;
		$data['list_view'] = 'store/stock/main/main_stocks_report';

		if ($this->input->is_ajax_request()) {

            if(isset($_POST['print'])){
                echo $this->load->view('store/stock/main/main_stocks_print_report', $data, true);
                exit;
            }
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}

        Template::set($data);
		Template::set('toolbar_title', 'Store Stock Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/main/_sub_report_store_main_stock');
        Template::render();
    }

    private function getTotalStock($store_id = 0, $product_id = 0)
    {
        $con = [];
        if ($store_id) {
            $con ['s.store_id'] = $store_id;
        }
        if ($product_id) {
            $con ['s.product_id'] = $product_id;
        }
        $records = $this->db
                        ->select('IFNULL(ROUND(SUM(s.quantity_level)), 0) AS total_stock, IFNULL(ROUND(SUM(s.quantity_level * p.purchase_price)), 0) as total_stock_tk')
                        ->from('store_stock as s')
                        ->join('store_products as p','s.product_id = p.id')
                        ->where($con)
                        ->get()
                        ->row();
        return $records;
    }

    public function datewise($store_id = 0)
    {
    	$this->auth->restrict('Report.StoreStockMain.Datewise');

        $data = array();
		$filter = array();
		$offset = (int) $this->input->get('per_page') ?: 0;
		$limit = (int) $this->input->post('per_page') ?: 25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['store_product_id_flag'] = 1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['store_issue_purchase_select_flag'] = 1;
		$search_box['common_text_search_flag'] = 0;

        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
        if (trim($this->input->post('store_product_id'))) {
            $filter['product_id'] = trim($this->input->post('store_product_id'));
        }
        if (trim($this->input->post('store_issue_purchase_select'))) {
            $filter['issue_purchase'] = trim($this->input->post('store_issue_purchase_select'));
        }

        if ($store_id > 0) {
            $data['store'] = $this->store_model->find($store_id);
            if (empty($data['store'])) {
                $store_id = 0;
            } else {
                $filter['store_id'] = $store_id;
            }
        }

        if ($store_id == 0) {
            $store = $this->store_model->limit(1)->find_all();
            if (isset($store[0])) {
                redirect('admin/store_stock_main/report/datewise/'.$store[0]->STORE_ID);
            } else {
                die('No Stores found. Add some stores.');
            }
        }

        if (strtolower($this->current_user->role_name) == 'administrator') {
            $data['stores'] = $this->store_model->as_array()->order_by('STORE_NAME')->find_all();
        }

        $data['store_id'] = $store_id;
        $data['records']  = $this->stock_model->get_main_datewise_stocks($filter, $limit, $offset);

        if ($offset > 0) {
            $data['prev_page_stock'] = $this->stock_model->get_main_datewise_prev_page_stocks($filter, $limit, $offset);
        }
		$this->load->library('pagination');
		$this->pager['base_url'] = site_url('admin/store_stock_main/report/datewise/'.$store_id);
		$this->pager['total_rows'] = $this->stock_model->get_main_datewise_stocks($filter, 0);
		$this->pager['per_page'] = $limit;
		$this->pager['page_query_string'] = TRUE;
		$this->pagination->initialize($this->pager);
		$data['pagination'] = $this->pagination->create_links();

        if (empty($data['from_date'])) {
            $row = $this->db->select('DATE(MIN(created_date)) as min_date')->get('store_stock_mst')->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }

        if (empty($data['to_date'])) {
            $row = $this->db->select('DATE(MAX(created_date)) as max_date')->get('store_stock_mst')->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }


        $data['offset'] = $offset;
        $data['limit'] = $limit;
        $data['total_rows'] = $this->pager['total_rows'];

        $data['hospital'] = $this->db->get('lib_hospital')->row();
		$data['search_box'] = $search_box;
		$data['list_view'] = 'store/stock/main/main_datewise_stocks_report';

		if ($this->input->is_ajax_request()) {
            $data['current_user'] = $this->current_user;
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}

        Assets::add_module_js('report','store_stock_main_report.js');

        Template::set($data);
		Template::set('toolbar_title', 'Main Store Stock Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/main/_sub_report_datewise');
        Template::render();
    }


    public function details($product_id =0, $store_id = 0)
    {
        $this->auth->restrict('Report.StoreStockMain.Details');

        if (!$product_id || !$store_id) {
            redirect('admin/store/report/store_stock_main/stocks');
        }

        $data = array();
        $filter = array();
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 2;
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Bill No';
        $search_box['store_source_name_list_flag'] = 1;
        $search_box['pharmacy_stock_type_flag'] = 1;
        $search_box['department_test_list_flag'] = 1;

        $data['product'] = $this->product_model->as_array()->find($product_id);
        if ( ! $data['product'] || $data['product']['status'] != '1') {
            show_404();
        }

        $product = $this->stock_model->find_by('product_id', $product_id);
        $data['product']['quantity'] = $product ? round($product->quantity_level) : 0;

        $filter['product_id'] = $product_id;
        $filter['store_id'] = $store_id;
       // $data['records'] = $this->product_model->get_product_main_stocks($filter, $limit, $offset);
        $data['total_stock'] = $this->getTotalStock($store_id, $product_id);
        //echo '<pre>';print_r($data['total_stock']);exit();
        $data['records'] = $this->product_model->getMainStockDetails($filter, $limit, $offset);
        $data['search_box'] = $search_box;
        $data['list_view'] = 'store/stock/main/main_details_stocks_report';

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/store/report/store_stock_main/details/'.$product_id.'/'.$store_id);
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
           $data['from_date'] = custom_date_format(trim($this->input->post('from_date', true)));
           $data['to_date'] = custom_date_format(trim($this->input->post('to_date', true)));

            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }

        Template::set($data);
        Template::set('toolbar_title', 'Store Stock Details Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/main/_sub_report_stocks_details');
        Template::render();
    }


    public function datewise_details($store_id, $product_id, $date, $offset = 0)
    {
        $this->auth->restrict('Report.StoreStockMain.DatewiseDetails');

        $data = array();
		$filter = array();
		$offset = (int) $this->input->get('per_page');
        $limit = (int) $this->input->post('per_page') ?: 25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user);
		$search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
		$search_box['store_stock_main_sources_flag'] = 1;
		$search_box['common_text_search_flag'] = 0;

		$data['store'] = $this->store_model->as_array()->find($store_id);
        if ( ! $data['store'] ) {
            show_404();
        }

        if (strtolower($this->current_user->role_name) != 'administrator') {
            if ($this->current_user->store_id != $data['store_id']) {
                redirect(site_url('admin/store_stock/report/main_datewise/'.$this->current_user->store_id));
            }
        }

        $data['product'] = $this->product_model->as_array()->find($product_id);
		if ( ! $data['product'] || $data['product']['status'] != '1') {
		    show_404();
		}

        $product_search = array(
            'store_id' => $store_id,
            'product_id' => $product_id,
        );
		$product = $this->stock_model->find_by($product_search);
		$data['product']['quantity'] = $product ? $product->quantity_level : 0;

        if ($postSource = trim($this->input->post('store_stock_main_source'))) {
            $filter['ssm.source'] = $postSource;
            $search_box['store_stock_main_source'] = $postSource;
        }

        $filter['DATE(ssm.created_date)'] = $date;
        $filter['ssd.product_id'] = $product_id;
        $data['date'] = $date;
		$data['records'] = $this->product_model->get_product_main_datewise_stocks($filter, $limit, $offset);
		$data['hospital'] = $this->db->get('lib_hospital')->row();
        $data['search_box'] = $search_box;
		$data['list_view'] = 'store/stock/main/main_datewise_details_stocks_report';

		$this->load->library('pagination');
		$this->pager['base_url'] = site_url('admin/store_stock_main/report/datewise_details/'.$store_id.'/'.$product_id.'/'.$date);
		$this->pager['total_rows'] = $this->product_model->get_product_main_datewise_stocks($filter, 0);
		$this->pager['per_page'] = $limit;
		$this->pager['page_query_string'] = TRUE;
        // if ($this->input->is_ajax_request()) {
        //     $this->pager['full_tag_open'] = preg_replace('/container/i','container modal-mode', $this->pager['full_tag_open']);
        // }
		$this->pagination->initialize($this->pager);

		if ($this->input->is_ajax_request()) {
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}

        Template::set($data);
		Template::set('toolbar_title', 'Store Stock Main Product Details Report:' . $data['store']['STORE_NAME']);
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/main/_sub_report_datewise_details');
        Template::render();
    }

    /**
     * For Development Purpose Only (May remove if not needed)
     *
     * Used to fillup opening stocks of each products in main store stock
     * Require empty tables of store_stock, mst and dtls
     */
    public function _fill_up_products_opening_stocks()
    {
        return false;
        $products = $this->db->limit(500)->offset(0)->get('store_products')->result();

        foreach($products as $product) {
            $this->db->trans_start();

            $stock_data = array(
                'store_id' => $product->store_id,
                'product_id' => $product->id,
                'quantity_level' => $product->opening_balance,
            );

            $this->db->insert('store_stock', $stock_data);

            $mst_data = array(
                'stock_source_in_id' => $product->id,
                'store_source_out_id' => 0,
                'store_id' => $product->store_id,
                'type' => 1,
                'source' => 5,
            );

            $this->db->insert('store_stock_mst', $mst_data);

            $dtls_data = array(
                'product_id' => $product->id,
                'stock_mst_id' => $this->db->insert_id(),
                'quantity' => $product->opening_balance,
            );

            $this->db->insert('store_stock_dtls', $dtls_data);

            $this->db->trans_complete();
        }
    }

}