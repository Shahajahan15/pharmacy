<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Store_stock_dept extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
		$this->load->model('store/product_model');
		$this->load->model('store/department_stock_model');
    }

    public function stocks($dept_id = 0, $offset = 0)
    {
        $this->auth->restrict('Report.StoreStockDept.Stocks');

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
            $filter['sp.id'] = (int) $this->input->post('store_product_id');
        }
        // if (trim($this->input->post('patient_name'))) {
        //     $filter['apa.patient_name LIKE'] = '%'.trim($this->input->post('patient_name')).'%';
        // }
        // if (trim($this->input->post('patient_id'))) {
        //     $filter['pa.patient_id LIKE'] = '%'.trim($this->input->post('patient_id')).'%';
        // }
        // if (trim($this->input->post('contact_no'))) {
        //     $filter['apa.contact_no LIKE'] = '%'.trim($this->input->post('contact_no')).'%';
        // }
        // if (trim($this->input->post('common_text_search'))) {
        //     $filter['d.doctor_full_name LIKE']= '%'.trim($this->input->post('common_text_search')).'%';
        // }
        // if (trim($this->input->post('token_id'))) {
        //     $filter['app.token_no LIKE'] = '%'.trim($this->input->post('token_id')).'%';
        // }

        $filter['sp.status'] = 1;

        if (strtolower($this->current_user->role_name) == 'administrator') {
        	$data['departments'] = $this->db->where('status','1')
        									->order_by('department_name')
        									->get('bf_lib_department')->result_array();
        }

        // Redirect for non-admin & unauthorized store employee,
        if ($dept_id == 0
        	&& strtolower($this->current_user->role_name) != 'administrator'
        	&& $this->current_user->department_id != '0'
        	) {
        	redirect(site_url('admin/store_stock/report/dept/'.$this->current_user->department_id));
        } elseif ($dept_id > 0
        	&& strtolower($this->current_user->role_name) != 'administrator'
        	&& $dept_id != $this->current_user->department_id
        	) {
        	redirect(site_url('admin/store_stock/report/dept/'.$this->current_user->department_id));
    	}

        // Search for the given store
        if ($dept_id != 0) {
	    	$data['department'] = $this->db->where('status','1')
	    									->where('dept_id',$dept_id)
	    									->get('bf_lib_department')->row_array();

	    	if (empty($data['department'])) {
        		redirect(site_url('admin/store_stock/report/dept'));
	    	}

	    	$filter['ss.dept_id'] = $dept_id;
        }

		$data['records'] = $this->department_stock_model->get_dept_stocks($filter, $limit, $offset);
		$data['sl_no'] = $offset;

		$this->load->library('pagination');
		$this->pager['base_url'] = site_url('admin/store_stock_dept/report/stocks/'.$dept_id);
		$this->pager['total_rows'] = $this->department_stock_model->get_dept_stocks($filter, 0);
		$this->pager['per_page'] = $limit;
		$this->pager['page_query_string'] = TRUE;
		$this->pagination->initialize($this->pager);

		$data['current_user'] = $this->current_user;
        $data['hospital'] = $this->db->get('lib_hospital')->row();
		$data['search_box'] = $search_box;
		$data['list_view'] = 'store/stock/dept/dept_stocks_report';

		if ($this->input->is_ajax_request()) {
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}

		Assets::add_module_js('report','store_stock_dept_report.js');

        Template::set($data);
		Template::set('toolbar_title', 'Store Stock Department Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/dept/_sub_report_dept');
        Template::render();
    }

    public function details($dept_id, $product_id)
    {
        $this->auth->restrict('Report.StoreStockDept.Details');

        $data = array();
		$filter = array();
		$offset = 0;
		$limit = 25;

		$search_box = $this->searchpanel->getSearchBox($this->current_user);
		$search_box['from_date_flag'] = 1;
		$search_box['to_date_flag'] = 1;
		$search_box['store_stock_dept_sources_flag'] = 1;
		$search_box['common_text_search_flag'] = 0;

		if ($dept_id == 0) {
			redirect(site_url('admin/store_stock/report/dept'));
		}

        $data['department'] = $this->db->where('status','1')
                                            ->where('dept_id',$dept_id)
                                            ->get('bf_lib_department')->row_array();
		if ( ! $data['department']) {
		    show_404();
		} elseif (strtolower($this->current_user->role_name) != 'administrator'
        	&& $dept_id != $this->current_user->department_id
        	) {
        	redirect(site_url('admin/store_stock/report/dept/'.$this->current_user->department_id));
    	}

		$product_filter = array(
			'id' => $product_id,
		);

		$data['product'] = $this->product_model->as_array()->find_by($product_filter);
		if ( ! $data['product'] || $data['product']['status'] != '1') {
		    show_404();
		}

		$dept_stock_filter = array(
			'dept_id' => $dept_id,
			'product_id' => $product_id,
		);
		$dept_product = $this->department_stock_model->find_by($dept_stock_filter);
		$data['product']['quantity'] = $dept_product ? $dept_product->quantity_level : 0;

        if ($this->input->post('store_stock_dept_source')) {
            $filter['ssm.source'] = $this->input->post('store_stock_dept_source');
        }
        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['ssm.created_date >='] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['ssm.created_date <='] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }

        if (empty($data['from_date'])) {
            $where = array('sd.product_id' => $product_id, 'sm.dept_id' => $dept_id);
            $row = $this->db->select('DATE(MIN(sm.created_date)) as min_date')
                            ->from('store_department_stock_mst sm')
                            ->join('store_department_stock_dtls sd','sd.stock_mst_id = sm.id')
                            ->where($where)
                            ->get()->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }

        if (empty($data['to_date'])) {
            $where = array('sd.product_id' => $product_id, 'sm.dept_id' => $dept_id);
            $row = $this->db->select('DATE(MAX(sm.created_date)) as max_date')
                            ->from('store_department_stock_mst sm')
                            ->join('store_department_stock_dtls sd','sd.stock_mst_id = sm.id')
                            ->where($where)
                            ->get()->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }

        $filter['ssd.product_id'] = $product_id;
        $filter['ssm.dept_id'] = $dept_id;
		$data['records'] = $this->product_model->get_product_department_stocks($filter, $limit, $offset);
		$data['hospital'] = $this->db->get('lib_hospital')->row();
        $data['search_box'] = $search_box;
		$data['list_view'] = 'store/stock/dept/dept_details_stocks_report';

		$this->load->library('pagination');
		$this->pager['base_url'] = site_url('admin/store_stock_dept/report/dept_details/'.$dept_id.'/'.$product_id);
		$this->pager['total_rows'] = $this->product_model->get_product_department_stocks($filter, 0);
		$this->pager['per_page'] = $limit;
		$this->pager['page_query_string'] = TRUE;
		$this->pagination->initialize($this->pager);

		if ($this->input->is_ajax_request()) {
		    echo $this->load->view($data['list_view'], $data, true);
		    exit;
		}

        Template::set($data);
		Template::set('toolbar_title', 'Store Stock Department Product Report:' . $data['department']['department_name']);
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/dept/_sub_report_dept_details');
        Template::render();
    }

    public function all()
    {
        $this->auth->restrict('Report.StoreStockDept.All');

        $data = array();
        $filter = array();
        $offset = (int) $this->input->get('per_page') ?: 0;
        $limit = (int) $this->input->post('per_page') ?: 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['store_product_id_flag'] = 1;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['common_text_search_flag'] = 0;

        if (trim($this->input->post('store_product_id'))) {
            $filter['product_id'] = trim($this->input->post('store_product_id'));
        }

        $data['records']  = $this->department_stock_model->get_dept_all_products_stocks($filter, $limit, $offset);

        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/store_stock_dept/report/dept_all');
        $this->pager['total_rows'] = $this->department_stock_model->get_dept_all_products_stocks($filter, 0);
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['pagination'] = $this->pagination->create_links();

        if (empty($data['from_date'])) {
            $row = $this->db->select('DATE(MIN(created_date)) as min_date')->get('store_department_stock_mst')->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }

        if (empty($data['to_date'])) {
            $row = $this->db->select('DATE(MAX(created_date)) as max_date')->get('store_department_stock_mst')->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }

        $data['offset'] = $offset;
        $data['limit'] = $limit;
        $data['total_rows'] = $this->pager['total_rows'];
        $data['hospital'] = $this->db->get('lib_hospital')->row();
        $data['search_box'] = $search_box;
        $data['list_view'] = 'store/stock/dept/dept_all_stocks_report';

        if ($this->input->is_ajax_request()) {
            $data['current_user'] = $this->current_user;
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }

        Assets::add_module_js('report','store_stock_dept_report.js');

        Template::set($data);
        Template::set('toolbar_title', 'Store Stock Department Overall Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/dept/_sub_report_dept_all');
        Template::render();
    }

    /** @ignore */
    public function datewise($dept_id = 0)
    {
        $this->auth->restrict('Report.StoreStockDept.Datewise');

        $data = array();
        $filter = array();
        $offset = (int) $this->input->get('per_page') ?: 0;
        $limit = (int) $this->input->post('per_page') ?: 25;

        $this->load->model('library/department_model');

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['product_code_flag'] = 1;
        $search_box['product_name_flag'] = 1;
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
        if (trim($this->input->post('product_code'))) {
            $filter['product_code'] = trim($this->input->post('product_code'));
        }
        if (trim($this->input->post('product_name'))) {
            $filter['product_name'] = trim($this->input->post('product_name'));
        }
        if (trim($this->input->post('store_issue_purchase_select'))) {
            $filter['issue_purchase'] = trim($this->input->post('store_issue_purchase_select'));
        }

        if ($dept_id > 0) {
            $data['department'] = $this->department_model->find($dept_id);
            if (empty($data['department'])) {
                $dept_id = 0;
            } else {
                $filter['dept_id'] = $dept_id;
            }
        }

        if ($dept_id == 0) {
            $dept_id = $this->department_model->limit(1)->find_all();
            if (isset($store[0])) {
                redirect('admin/store_stock/report/dept_datewise/'.$store[0]->STORE_ID);
            } else {
                die('No department found. Add some departments.');
            }
        }

        if (strtolower($this->current_user->role_name) == 'administrator') {
            $data['departments'] = $this->department_model->as_array()->order_by('department_name')->find_all();
        }

        $data['dept_id'] = $dept_id;
        $data['records']  = $this->department_stock_model->get_dept_datewise_stocks($filter, $limit, $offset);

        if ($offset > 0) {
            $data['prev_page_stock'] = $this->department_stock_model->get_dept_datewise_prev_page_stocks($filter, $limit, $offset);
        }

        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/store_stock_dept/report/dept_datewise/'.$dept_id);
        $this->pager['total_rows'] = $this->department_stock_model->get_dept_datewise_stocks($filter, 0);
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['pagination'] = $this->pagination->create_links();

        if (empty($data['from_date'])) {
            $row = $this->db->select('DATE(MIN(created_date)) as min_date')->get('store_department_stock_mst')->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }

        if (empty($data['to_date'])) {
            $row = $this->db->select('DATE(MAX(created_date)) as max_date')->get('store_department_stock_mst')->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }

        $data['offset'] = $offset;
        $data['limit'] = $limit;
        $data['total_rows'] = $this->pager['total_rows'];
        $data['hospital'] = $this->db->get('lib_hospital')->row();
        $data['search_box'] = $search_box;
        $data['list_view'] = 'store/stock/dept/dept_datewise_stocks_report';

        if ($this->input->is_ajax_request()) {
            $data['current_user'] = $this->current_user;
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }

        Assets::add_module_js('report','store_stock_dept_report.js');

        Template::set($data);
        Template::set('toolbar_title', 'Store Stock Department Datewise Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/dept/_sub_report_dept_datewise');
        Template::render();
    }

    public function prod_details($product_id)
    {
        $this->auth->restrict('Report.StoreStockDept.ProdDetails');

        $data = array();
        $filter = array();
        $offset = (int) $this->input->get('per_page') ?: 0;
        $limit = (int) $this->input->post('per_page') ?: 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['store_issue_purchase_select_flag'] = 1;
        $search_box['department_id_flag'] = 1;
        $search_box['common_text_search_flag'] = 0;

        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date']   = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date']   = $to_date->format('Y-m-d');
        }
        if (trim($this->input->post('store_issue_purchase_select'))) {
            $filter['issue_purchase'] = trim($this->input->post('store_issue_purchase_select'));
        }
        if (trim($this->input->post('department_id'))) {
            $filter['department_id']  = trim($this->input->post('department_id'));
        }

        $data['product'] = $this->product_model->find($product_id);
        if (empty($data['product'])) {
            show_error('Product for "'.$product_id.'" not found.');
        }

        $prod_quantity = $this->department_stock_model->select('SUM(quantity_level) as quantity')
                                                    ->find_by(array('product_id' => $product_id));
        $data['product']->quantity = $prod_quantity ? $prod_quantity->quantity : 0;

        $data['product_id'] = $product_id;
        $data['records']  = $this->department_stock_model->get_dept_product_datewise_stocks($filter, $limit, $offset);
        $data['hospital'] = $this->db->get('lib_hospital')->row();
        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/store_stock_dept/report/dept_prod_details/'.$product_id);
        $this->pager['total_rows'] = $this->department_stock_model->get_dept_product_datewise_stocks($filter, 0);
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['pagination'] = $this->pagination->create_links();

        if (empty($data['from_date'])) {
            $where = array('sd.product_id' => $product_id);
            if ($this->input->post('department_id')) {
                $where['sm.dept_id'] = $this->input->post('department_id');
            }
            $row = $this->db->select('DATE(MIN(sm.created_date)) as min_date')
                            ->from('store_department_stock_mst sm')
                            ->join('store_department_stock_dtls sd','sd.stock_mst_id = sm.id')
                            ->where($where)
                            ->get()->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }

        if (empty($data['to_date'])) {
            $where = array('sd.product_id' => $product_id);
            if ($this->input->post('department_id')) {
                $where['sm.dept_id'] = $this->input->post('department_id');
            }
            $row = $this->db->select('DATE(MAX(sm.created_date)) as max_date')
                            ->from('store_department_stock_mst sm')
                            ->join('store_department_stock_dtls sd','sd.stock_mst_id = sm.id')
                            ->where($where)
                            ->get()->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }

        $data['offset'] = $offset;
        $data['limit'] = $limit;
        $data['total_rows'] = $this->pager['total_rows'];

        $data['search_box'] = $search_box;
        $data['list_view'] = 'store/stock/dept/dept_product_details_stocks_report';

        if ($this->input->is_ajax_request()) {
            $data['current_user'] = $this->current_user;
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }

        Assets::add_module_js('report','store_stock_dept_report.js');

        Template::set($data);
        Template::set('toolbar_title', 'Store Stock Departments Product Stock Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'store/stock/dept/_sub_report_dept_prod_details');
        Template::render();
    }

}
