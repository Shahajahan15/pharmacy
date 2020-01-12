<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Store_stock_ledger extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
		$this->load->model('store/product_model');
    }

    public function stocks($offset = 0)
    {
        $this->auth->restrict('Report.StoreStockLedger.Stocks');

        $data = array();
		$filter = array();
		$offset = (int) $this->input->get('per_page');
		$limit = (int) $this->input->post('per_page') ?: 25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user);
		$search_box['from_date_flag'] = 0;
		$search_box['to_date_flag'] = 0;
		$search_box['store_product_id_flag'] = 1;
		$search_box['common_text_search_flag'] = 0;

        if (trim($this->input->post('store_product_id'))) {
            $filter['product_id'] = (int) $this->input->post('store_product_id');
        }

		$data['records'] = $this->product_model->get_stocks_ledger($filter, $limit, $offset);

		$this->load->library('pagination');
		$this->pager['base_url'] = site_url('admin/store_stock_ledger/report/stocks');
		$this->pager['total_rows'] = $this->product_model->get_stocks_ledger($filter, 0);
		$this->pager['per_page'] = $limit;
		$this->pager['page_query_string'] = TRUE;
		$this->pagination->initialize($this->pager);

		$data['current_user'] = $this->current_user;
        $data['hospital'] = $this->db->get('lib_hospital')->row();
		$data['search_box'] = $search_box;
		$data['list_view'] = 'store/stock/ledger/report';

		if ($this->input->is_ajax_request()) {
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}

		// Assets::add_module_js('report','store_stock_dept_report.js');

        Template::set($data);
		Template::set('toolbar_title', 'Stocks & Value');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/ledger/_sub_ledger');
        Template::render();
    }


    public function details($product_id)
    {
        $this->auth->restrict('Report.StoreStock.DeptDetails');

        $data = array();
		$filter = array();
		$offset = 0;
		$limit = 25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user);
		$search_box['from_date_flag'] = 1;
		$search_box['to_date_flag'] = 1;
        $search_box['store_stock_main_sources_flag'] = 1;
		$search_box['store_stock_dept_sources_flag'] = 1;
		$search_box['common_text_search_flag'] = 0;

		$data['product'] = $this->product_model->as_array()->find($product_id);
		if ( ! $data['product'] || $data['product']['status'] != '1') {
		    show_404();
		}

		$data['product']['quantity'] = $this->product_model->current_product_total_stock($product_id);

        if ($main_source = trim($this->input->post('store_stock_main_source'))) {
            $filter['main_source'] = $main_source;
        }
        if ($dept_source = trim($this->input->post('store_stock_dept_source'))) {
            $filter['dept_source'] = $dept_source;
        }
        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }

        if (empty($data['from_date'])) {
            $where = array('sd.product_id' => $product_id);
            $row = $this->db->select('DATE(MIN(sm.created_date)) as min_date')
                            ->from('store_stock_mst sm')
                            ->join('store_stock_dtls sd','sd.stock_mst_id = sm.id')
                            ->where($where)
                            ->get()->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }

        if (empty($data['to_date'])) {
            $where = array('sd.product_id' => $product_id);
            $row = $this->db->select('DATE(MAX(sm.created_date)) as max_date')
                            ->from('store_stock_mst sm')
                            ->join('store_stock_dtls sd','sd.stock_mst_id = sm.id')
                            ->where($where)
                            ->get()->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }

        $filter['product_id'] = $product_id;
		$data['records'] = $this->product_model->get_product_ledger_details($filter, $limit, $offset);
		$data['hospital'] = $this->db->get('lib_hospital')->row();
        $data['search_box'] = $search_box;
		$data['list_view'] = 'store/stock/ledger/details';

		$this->load->library('pagination');
		$this->pager['base_url'] = site_url('admin/store_stock_ledger/report/details/'.$product_id);
		$this->pager['total_rows'] = $this->product_model->get_product_ledger_details($filter, 0);
		$this->pager['per_page'] = $limit;
		$this->pager['page_query_string'] = TRUE;
		$this->pagination->initialize($this->pager);

		if ($this->input->is_ajax_request()) {
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}

        Template::set($data);
		Template::set('toolbar_title', 'Store Stock & Value Details');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/ledger/_sub_details');
        Template::render();
    }


}