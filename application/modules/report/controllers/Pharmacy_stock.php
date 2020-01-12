<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Pharmacy_stock extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
        $this->load->model('pharmacy/stock_model');
        $this->load->model('pharmacy/stock_in_model');
        $this->load->model('pharmacy/stock_in_dtls_model');
        $this->load->model('pharmacy/product_model');
    }


    public function datewise($offset = 0)
    {
        $this->auth->restrict('Report.PharmacyStock.Datewise');

        $data = array();
        $filter = array();
        $offset = (int) $this->input->get('per_page');
        $limit = (int) $this->input->post('per_page') ?: 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['pharmacy_product_id_select_flag'] = 1;
        $search_box['common_text_search_flag'] = 0;

        if ($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))) {
            $filter['from_date'] = $from_date->format('Y-m-d');
            $data['from_date'] = $from_date->format('Y-m-d');
        }
        if ($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))) {
            $filter['to_date'] = $to_date->format('Y-m-d');
            $data['to_date'] = $to_date->format('Y-m-d');
        }
        if (trim($this->input->post('pharmacy_product_id_select'))) {
            $filter['product_id'] = trim($this->input->post('pharmacy_product_id_select'));
        }
        // if (trim($this->input->post('store_issue_purchase_select'))) {
        //     $filter['issue_purchase'] = trim($this->input->post('store_issue_purchase_select'));
        // }

        $data['records'] = $this->stock_model->get_datewise_stocks($filter, $limit, $offset);

        // if ($offset > 0) {
        //     $data['prev_page_stock'] = $this->stock_model->get_datewise_prev_page_stocks($filter, $limit, $offset);
        // }

        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/pharmacy_stock/report/datewise');
        $this->pager['total_rows'] = $this->stock_model->get_datewise_stocks($filter, 0);
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if (empty($data['from_date'])) {
            $row = $this->db->select('DATE(MIN(created_date)) as min_date')->get('pharmacy_stock_mst')->row();
            $data['from_date'] = $row ? $row->min_date : null;
        }

        if (empty($data['to_date'])) {
            $row = $this->db->select('DATE(MAX(created_date)) as max_date')->get('pharmacy_stock_mst')->row();
            $data['to_date'] = $row ? $row->max_date : null;
        }

        $data['hospital'] = $this->db->get('lib_hospital')->row();

        $data['search_box'] = $search_box;
        $data['list_view'] = 'pharmacy/stock/report';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }

        Template::set($data);
        Template::set('toolbar_title', 'Pharmacy Stock Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'pharmacy/stock/_sub_report');
        Template::render();
    }

    public function details($product_id, $date, $offset = 0)
    {
        $this->auth->restrict('Report.PharmacyStock.Details');

        $data = array();
        $filter = array();
        $offset = (int) $this->input->get('per_page');
        $limit = (int) $this->input->post('per_page') ?: 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['pharmacy_stock_sources_flag'] = 1;
        $search_box['common_text_search_flag'] = 0;

        $data['product'] = $this->product_model->as_array()->find($product_id);
        if ( ! $data['product'] || $data['product']['status'] != '1') {
            show_404();
        }

        $product_search = array(
            'product_id' => $product_id,
        );
        $product = $this->stock_model->find_by($product_search);
        $data['product']['quantity'] = $product ? $product->quantity_level : 0;

        if ($postSource = trim($this->input->post('pharmacy_stock_source'))) {
            $filter['ssm.source'] = $postSource;
            $search_box['pharmacy_stock_source'] = $postSource;
        }

        $filter['DATE(ssm.created_date)'] = $date;
        $filter['ssd.product_id'] = $product_id;
        $data['date'] = $date;
        $data['records'] = $this->product_model->get_product_datewise_stocks($filter, $limit, $offset);
        $data['hospital'] = $this->db->get('lib_hospital')->row();
        $data['search_box'] = $search_box;
        $data['list_view'] = 'pharmacy/stock/details';

        $this->load->library('pagination');
        $this->pager['base_url'] = site_url('admin/pharmacy_stock/report/details/'.$product_id.'/'.$date);
        $this->pager['total_rows'] = $this->product_model->get_product_datewise_stocks($filter, 0);
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
        Template::set('toolbar_title', 'Pharmacy Product Stock Report');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'pharmacy/stock/_sub_details');
        Template::render();
    }

}