<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Booth controller
 */
class Pharmacy_wise_stock extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
        $this->load->model('pharmacy/stock_model');
        $this->load->config('config_employee');
        $this->auth->restrict('Report.PharmacyStock.View');
    }


    public function index()
    {
        //echo '<pre>'; print_r($this->get_current_user()); exit();


        $data = [];
        $con = [];
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 50;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag'] = 1;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['common_text_search_flag'] = 0;
        $search_box['pharmacy_company_list_flag'] = 1;
        $search_box['pharmacy_list_flag'] = 1;
        $search_box['product_name_flag'] = 1;
        $search_box['per_page'] = $limit;

        $role = $this->current_user->role_id;
        $pharmacy_id = ($role == 23) ? 1 : 200;

        if ($this->input->is_ajax_request()) {
            $product_name = trim($this->input->post('product_name'));
            if ($product_name) {
                $con["pp.product_name LIKE '%$product_name%'"] = null;
            }
            $cate_id = $this->input->post('pharmacy_category_name', true);
            if ($cate_id) {
                $con['pc.id'] = $cate_id;
            }
            $com_id = $this->input->post('pharmacy_company_id', true);
            if ($com_id) {
                $con['ppc.id'] = $com_id;
            }
            $product_id = $this->input->post('pharmacy_product_id', true);
            if ($product_id) {
                $con['pp.id'] = $product_id;
            }
            $pharmacy_id = $this->input->post('pharmacy_name');
            if($pharmacy_id ){
                $data['pharmacy_id']=$pharmacy_id;
            }
        }

        if ($pharmacy_id == 200 || !$pharmacy_id) {
            $data['total_records'] = $this->getMainPharmacyTotalStock($con);
            $data['records'] = $this->getMainPharmacyStock($con, $limit, $offset);

        } else {
            $data['total_records'] = $this->getSubPharmacyTotalStock($con, $pharmacy_id);
            $data['records'] = $this->getSubPharmacyStock($con, $limit, $offset, $pharmacy_id);
        }

        // print_r($data['total_records']);

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/pharmacy_wise_stock/report/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $data['list_view'] = 'pharmacy/stock_report/index';
        $data['search_box'] = $search_box;
        $data['pharmacy_type'] = $pharmacy_id;

        if ($this->input->is_ajax_request()) {

            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
//echo '<pre>';print_r($data['records']);exit;
        Template::set($data);
        Template::set_block('sub_nav', 'pharmacy/stock_report/_sub_report');
        Template::set_view('report_template');
        Template::render();


    }

    /*      main pharmacy total stock */

    private function getMainPharmacyTotalStock($con = [])
    {
        $row = $this->db
            ->select("
        IFNULL(SUM(round(ps.quantity_level)),0) as total_qnty,

        IFNULL(ROUND(SUM(pp.sale_price*ps.quantity_level)), 0) as total_stock_price
        ")
            ->from("pharmacy_products as pp")
            ->join("pharmacy_stock as ps", "pp.id = ps.product_id")
            ->join('pharmacy_product_company as ppc', 'ppc.id=pp.company_id', 'left')
            ->where($con)
            ->get()
            ->row();
        return $row;
    }

    /*      sub pharmacy total stock */

    private function getSubPharmacyTotalStock($con = [], $pharmacy_id = 0)
    {
        $row = $this->db->select("
        IFNULL(SUM(round(ps.quantity_level)),0) as total_qnty,
        IFNULL(ROUND(SUM(pp.sale_price*ps.quantity_level)), 0) as total_stock_price
        ")
            ->from("pharmacy_products as pp")
            ->join("pharmacy_indoor_stock as ps", "pp.id = ps.product_id")
            ->join('pharmacy_product_company as ppc', 'ppc.id=pp.company_id', 'left')
            ->where('ps.pharmacy_id', $pharmacy_id)
            ->where($con)
            ->get()
            ->row();
        return $row;
    }


    /*         main pharmacy stock    */

    private function getMainPharmacyStock($con = [], $limit, $offset)
    {
        $records = $this->db->select("
        SQL_CALC_FOUND_ROWS
        IFNULL(ROUND(SUM(IF(psm.source = 0, psd.quantity, 0))), 0) AS purchase,
        IFNULL(ROUND(SUM(IF(psm.source = 1, psd.quantity, 0))), 0) AS sale,
        IFNULL(ROUND(SUM(IF(psm.source = 2, psd.quantity, 0))), 0) AS issue_send,
        IFNULL(ROUND(SUM(IF(psm.source = 3, psd.quantity, 0))), 0) AS sale_return,
        IFNULL(ROUND(SUM(IF(psm.source = 4, psd.quantity, 0))), 0) AS purchase_return,
        IFNULL(ROUND(SUM(IF(psm.source = 5, psd.quantity, 0))), 0) AS opening_balance,
        IFNULL(ROUND(SUM(IF(psm.source = 6, psd.quantity, 0))), 0) AS req_receive,
        IFNULL(ROUND(SUM(IF(psm.source = 7, psd.quantity, 0))), 0) AS purchase_replace,
        (
            (
                IFNULL(ROUND(SUM(IF(psm.source = 5, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 0, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 3, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 6, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 7, psd.quantity, 0))), 0)
            )
            -
            (
                IFNULL(ROUND(SUM(IF(psm.source = 1, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 2, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 4, psd.quantity, 0))), 0)
            )
        ) AS stock_qnty,
        pp.id as product_id,
        pp.product_name,
        psm.source,
        pp.sale_price,
        pc.category_name,
        ppc.company_name,
        '200' as pharmacy_id,
        'Main Pharmacy' as pharmacy_name
        ", false)
            ->from("pharmacy_stock_mst as psm")
            ->join('pharmacy_stock_dtls as psd', 'psd.stock_mst_id = psm.id')
            ->join('pharmacy_products as pp', 'pp.id=psd.product_id', 'left')
            ->join('pharmacy_category as pc', 'pc.id=pp.category_id', 'left')
            ->join('pharmacy_product_company as ppc', 'ppc.id=pp.company_id', 'left')
            ->where($con)
            ->group_by('psd.product_id')
            ->order_by('pp.id', 'asc')
            ->limit($limit, $offset)
            ->get()
            ->result();
        return $records;
    }

    /*         sub pharmacy stock    */

    private function getSubPharmacyStock($con = [], $limit, $offset, $pharmacy_id = 0)
    {
        $records = $this->db->select("
        SQL_CALC_FOUND_ROWS
        IFNULL(ROUND(SUM(IF(psm.source = 0, psd.quantity, 0))), 0) AS req_receive,
        IFNULL(ROUND(SUM(IF(psm.source = 1, psd.quantity, 0))), 0) AS issue_send,
        IFNULL(ROUND(SUM(IF(psm.source = 2, psd.quantity, 0))), 0) AS sale,
        IFNULL(ROUND(SUM(IF(psm.source = 3, psd.quantity, 0))), 0) AS sale_return,
        IFNULL(ROUND(SUM(IF(psm.source = 5, psd.quantity, 0))), 0) AS opening_balance,
        0 AS purchase,
        0 AS purchase_return,
        0 AS purchase_replace,
        (
            (
                IFNULL(ROUND(SUM(IF(psm.source = 0, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 3, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 5, psd.quantity, 0))), 0)
            )
            -
            (
                IFNULL(ROUND(SUM(IF(psm.source = 1, psd.quantity, 0))), 0)
                +
                IFNULL(ROUND(SUM(IF(psm.source = 2, psd.quantity, 0))), 0)
            )
        ) AS stock_qnty,
        pp.id as product_id,
        pp.product_name,
        psm.source,
        pp.sale_price,
        pc.category_name,
        ppc.company_name,
        ps.id as pharmacy_id,
        ps.name as pharmacy_name
        ", false)
            ->from("pharmacy_indoor_stock_mst as psm")
            ->join('pharmacy_indoor_stock_dtls as psd', 'psd.stock_mst_id = psm.id')
            ->join("pharmacy_setup as ps", "ps.id = psm.pharmacy_id and ps.id = $pharmacy_id")
            ->join('pharmacy_products as pp', 'pp.id=psd.product_id', 'left')
            ->join('pharmacy_category as pc', 'pc.id=pp.category_id', 'left')
            ->join('pharmacy_product_company as ppc', 'ppc.id=pp.company_id', 'left')
            ->where($con)
            ->group_by('psd.product_id')
            ->order_by('pp.id', 'asc')
            ->limit($limit, $offset)
            ->get()
            ->result();
        //print_r($this->db->last_query($records));
        return $records;
    }


    public function details($product_id, $pharmacy_id)
    {
        $data = [];
        if (!$product_id) {
            //return false;
            redirect('admin/pharmacy_wise_stock/report/index');
        }

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Sale No';
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['pharmacy_stock_type_flag'] = 1;
        $search_box['segment_id'] = $this->uri->segment(6);
        if ($search_box['segment_id'] == 200) {
            $search_box['main_pharmacy_stock_list_flag'] = 1;
        } else {
            $search_box['sub_pharmacy_stock_list_flag'] = 1;
        }
        $con['psd.product_id'] = $product_id;
        if ($this->input->is_ajax_request()) {

            $con = $this->getPharmacyStockDetailsCondition($con);
        }
        if ($pharmacy_id == 200) {
            $data['total_qty_price'] = $this->getMainPharmacyTotalStockDetails($product_id);
            $data['records'] = $this->getMainPharmacyStockDetails($con, $limit, $offset);
        } else {
            $data['total_qty_price'] = $this->getSubPharmacyTotalStockDetails($product_id, $pharmacy_id);
            $data['records'] = $this->getSubPharmacyStockDetails($con, $limit, $offset, $pharmacy_id);
        }
        $data['search_box'] = $search_box;
        $data['pharmacy_id'] = $pharmacy_id;

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/pharmacy_wise_stock/report/details/' . $product_id . '/' . $pharmacy_id . '' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $data['list_view'] = 'pharmacy/stock_report/details';

        if ($this->input->is_ajax_request()) {
            $data['from_date'] = custom_date_format(trim($this->input->post('from_date')));
            $data['to_date'] = custom_date_format(trim($this->input->post('to_date')));
            echo $this->load->view($data['list_view'], $data, true);
            exit;
        }
        $data['toolbar_title'] = 'Pharmacy Stock Details';
        Template::set($data);
        Template::set_view('report_template');
        Template::render();

    }

    private function getMainPharmacyTotalStockDetails($product_id)
    {
        $record = $this->db
            ->select("
            (round(ps.quantity_level)) as quantity_level,
        ")
            ->from("pharmacy_stock as ps")
            ->where('ps.product_id', $product_id)
            ->get()
            ->row();
        return $record;
    }

    private function getSubPharmacyTotalStockDetails($product_id, $pharmacy_id)
    {
        $record = $this->db
            ->select("
       (round(ps.quantity_level)) as quantity_level,
        ")
            ->from("pharmacy_indoor_stock as ps")
            ->where('ps.product_id', $product_id)
            ->where('ps.pharmacy_id', $pharmacy_id)
            ->get()
            ->row();
        return $record;
    }


    private function getPharmacyStockDetailsCondition($con)
    {
        $mp_stock_list = $this->input->post('main_pharmacy_stock_list');
        if ($mp_stock_list) {
            $con['psm.source'] = $mp_stock_list;
            $stock_source = $mp_stock_list;
            if ($stock_source == 100) {
                $stock_source = 0;
            }
            $con['psm.source'] = $stock_source;
        }
        $sp_stock_list = $this->input->post('sub_pharmacy_stock_list');
        if ($sp_stock_list) {
            $stock_source = $sp_stock_list;
            if ($stock_source == 100) {
                $stock_source = 0;
            }
            $con['psm.source'] = $stock_source;
        }
        $stock_type = $this->input->post('pharmacy_stock_type');
        if ($stock_type) {
            $con['psm.type'] = $stock_type;
        }
        if ($this->input->post('common_text_search')) {
            $con['sm.sale_no like'] = '%' . trim($this->input->post('common_text_search')) . '%';
        }
        $from_date = custom_date_format(trim($this->input->post('from_date')));
        $to_date = custom_date_format(trim($this->input->post('to_date')));

        if ($from_date) {
            $con["psm.created_date >="] = $from_date . " 00:00:00";
        }
        if ($to_date) {
            $con["psm.created_date <="] = $to_date . " 23:59:59";
        }

        return $con;
    }

    /*         main pharmacy stock details  */

    private function getMainPharmacyStockDetails($con = [], $limit, $offset)
    {
        $records = $this->db
            ->select("
                SQL_CALC_FOUND_ROWS
                psm.created_date,
                IF(psm.type = 1, 'Stock In','Stock Out') AS stock_type,
                psm.source,
                (
                    CASE 
                        WHEN psm.source = 0 THEN 'Purchase'
                        WHEN psm.source = 1 THEN 'Sale'
                        WHEN psm.source = 2 THEN 'Issue Send'
                        WHEN psm.source = 3 THEN 'Sale Return'
                        WHEN psm.source = 4 THEN 'Purchase Return'
                        WHEN psm.source = 5 THEN 'Opening Balance'
                        WHEN psm.source = 6 THEN 'Requisition Receive'
                        WHEN psm.source = 7 THEN 'Purchase Replace'
                    END
                ) AS source_name,
                pp.product_name,
                u.display_name,
                sm.sale_no,
                ROUND(psd.quantity) as quantity,
                'Main Pharmacy' as pharmacy_name,
                '200' as pharmacy_id
                ", false)
            ->from('pharmacy_stock_mst as psm')
            ->join('pharmacy_stock_dtls as psd', 'psd.stock_mst_id = psm.id')
            ->join('pharmacy_products as pp', 'pp.id = psd.product_id')
            ->join('pharmacy_sales_mst as sm', 'sm.id = psm.stock_source_out_id', 'left')
            ->join('users as u', 'u.id = psm.created_by')
            ->where($con)
            ->order_by('psm.created_date', 'asc')
            ->limit($limit, $offset)
            ->get()
            ->result();

        // print_r($this->db->last_query($records));
        return $records;
    }

    /*         main pharmacy stock details  */

    private function getSubPharmacyStockDetails($con = [], $limit, $offset, $pharmacy_id)
    {
        $con['ps.id'] = $pharmacy_id;
        $records = $this->db
            ->select("
                SQL_CALC_FOUND_ROWS
                psm.created_date,
                IF(psm.type = 1, 'Stock In','Stock Out') AS stock_type,
                psm.source,
                (
                    CASE 
                        WHEN psm.source = 0 THEN 'Requisition Receive'
                        WHEN psm.source = 1 THEN 'Issue Send'
                        WHEN psm.source = 2 THEN 'Sale'
                        WHEN psm.source = 3 THEN 'Sale Return'
                        WHEN psm.source = 5 THEN 'Opening Balance'
                    END
                ) AS source_name,
                pp.product_name,
                u.display_name,
                sm.sale_no,
                ROUND(psd.quantity) as quantity,
                'Main Pharmacy' as pharmacy_name,
                ps.id as pharmacy_id
                ", false)
            ->from('pharmacy_indoor_stock_mst as psm')
            ->join('pharmacy_indoor_stock_dtls as psd', 'psd.stock_mst_id = psm.id')
            ->join('pharmacy_setup as ps', 'ps.id = psm.pharmacy_id')
            ->join('pharmacy_products as pp', 'pp.id = psd.product_id')
            ->join('pharmacy_indoor_sales_mst as sm', 'sm.id = psm.store_source_out_id', 'left')
            ->join('users as u', 'u.id = psm.created_by')
            ->where($con)
            ->order_by('psm.created_date', 'asc')
            ->limit($limit, $offset)
            ->get()
            ->result();
        return $records;
    }


}