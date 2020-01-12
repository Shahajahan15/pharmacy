<?php
/**
 * Created by PhpStorm.
 * User: Robin
 * Date: 5/5/2019
 * Time: 4:08 PM
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Direct_pharmacy_purchase extends Admin_Controller
{
    /**
     * Direct_pharmacy_purchase constructor.
     */
    public function __construct()
    {
        parent::__construct();
       
        $this->load->model('purchase_Order_received_Model', NULL, TRUE);
        $this->load->model('stock_in_model', NULL, TRUE);
        $this->lang->load('common');
        Assets::add_module_js('pharmacy', 'purchase_order');
        Assets::add_module_js('pharmacy', 'direct_pharmacy_purchase');
        Template::set_block('sub_nav', 'direct_pharmacy_purchase/_sub_nav');
    }

    /**
     *  page create
     */
    public function create()
    {
        $data['supliers'] = $this->db
            ->where('is_deleted', 0)
            ->get('bf_pharmacy_supplier')
            ->result();
        $data['stores'] = array(['id' => 1, 'name' => 'Main Pharmacy'], ['id' => 2, 'name' => 'Sub Pharmacy']);
        Template::set($data);
        Template::set('toolbar_title', 'Purchase Medicine');
        Template::set_view('direct_pharmacy_purchase/create');
        Template::render();
    }

    /**
     *  get product info by medicine id for ajax
     * @param int $id
     * @param int $pharmacy_id
     */
    public function getProductInfoById($id = 0, $pharmacy_id = 0)
    {
        $record = $this->db->select("
                        bf_pharmacy_products.id,
                        bf_pharmacy_products.product_name,
                        bf_pharmacy_products.purchase_price,
                        bf_pharmacy_category.category_name,
                        bf_pharmacy_category.id as cat_id,
                    ")
            //  ->join('bf_pharmacy_subcategory','bf_pharmacy_subcategory.id=bf_pharmacy_products.sub_category_id','left')
            // ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_subcategory.category_id','left')
            ->join('bf_pharmacy_category', 'bf_pharmacy_category.id=bf_pharmacy_products.category_id', 'left')
            ->where('bf_pharmacy_products.id', $id)
            ->get('bf_pharmacy_products')
            ->row();
        if ($pharmacy_id == 200 || !$pharmacy_id) {
            $current_stock = $this->db->where('product_id', $id)->get('bf_pharmacy_stock')->row();
        } else {
            $current_stock = $this->db->where('pharmacy_id', $pharmacy_id)->where('product_id', $id)->get('pharmacy_indoor_stock')->row();
        }

        if ($current_stock) {
            $record->current_stock = $current_stock->quantity_level;
        } else {
            $record->current_stock = 0;
        }
        echo json_encode($record);
    }

    /**
     *  pharmacy product add
     */
    public function save()
    {
        $data = array();
        $pharmacy_id = ($this->current_user->role_id == 23) ? $this->current_user->pharmacy_id : 200; // TODO sir decision (200 = main , 0 = sub)

        $data['supplier_id'] = $this->input->post('supplier_id');
        // $data['store_id'] = $this->input->post('store_id'); // TODO talk to sir
        //  need to insert store_purchase_receive_mst
        $data['bill_no'] = date('ymd') . time();
        $data['received_date'] = date('Y-m-d');
        $data['received_by'] = $this->current_user->id;
        // need to insert store_purchase_order_mst
        $order_mst['supplier_id'] = $data['supplier_id'];
        //$order_mst['store_id'] = $data['store_id'];
        $order_mst['supply_date_from'] = date('Y-m-d');
        $order_mst['supply_date_to'] = date('Y-m-d');
        $order_mst['purchase_order_no'] = date('y') . $data['supplier_id'] . time();
        $order_mst['created_by'] = $this->current_user->id;
        $order_mst['status'] = 2; // full receive
        $this->load->model('purchase_Order_Model', NULL, TRUE);
        //  @pharmacy_purchase_order_mst insert
        $this->db->trans_start();

        $order_mst_id = $this->purchase_Order_Model->insert($order_mst);

        $data['order_id'] = $order_mst_id;
        $data['is_direct'] = 1;
        $data['pharmacy_id'] = $pharmacy_id;
        //  @pharmacy_purchase_order_received_mst insert
        $master_id = $this->purchase_Order_received_Model->insert($data);

        if ($pharmacy_id === 200): // only for main pharmacy
            $main_stock_data = array();
            $main_stock_data['stock_source_in_id'] = $master_id;
            $main_stock_data['type'] = 1;      //   1=stock in
            $main_stock_data['pharmacy_id'] = $pharmacy_id; // TODO know to sir ..
            $main_stock_data['created_by'] = $this->current_user->id;
            $main_stock_data['source'] = 6;    //  pharmacy_purchase_order_received_mst(purchase received)

            $stock_mst_id = $this->stock_in_model->insert($main_stock_data);
        endif;
        $data['product_id'] = $this->input->post('product_id');
        $data['order_qnty'] = $this->input->post('order_qnty');
        $data['receive_free_qnty'] = $this->input->post('receive_free_qnty');
        $data['order_unit_price'] = $this->input->post('order_unit_price');
        $data['order_total_price'] = $this->input->post('order_total_price');

        for ($i = 0; $i < count($data['product_id']); $i++) {
            //  @pharmacy_purchase_order_dtls insert
            $dtls['product_id'] = $data['product_id'][$i];
            $dtls['order_qnty'] = $data['order_qnty'][$i];
            $dtls['free_qnty'] = $data['receive_free_qnty'][$i];
            $dtls['order_unit_price'] = $data['order_unit_price'][$i];
            $dtls['total_order_price'] = $data['order_unit_price'][$i] * $data['order_qnty'][$i];
            $dtls['purchase_order_mst_id'] = $order_mst_id;
            $dtls['status'] = 2; //full receive
            $this->db->insert('pharmacy_purchase_order_dtls', $dtls);
            $order_dtls_id = $this->db->insert_id();
            //  @pharmacy_purchase_order_receive_dtls insert

            $receive_dtls['master_id'] = $master_id;
            $receive_dtls['product_id'] = $data['product_id'][$i];
            $receive_dtls['order_dtls_id'] = $order_dtls_id;
            $receive_dtls['received_qnty'] = $data['order_qnty'][$i];
            $receive_dtls['free_qnty'] = $data['receive_free_qnty'][$i];
            $receive_dtls['unit_price'] = $data['order_unit_price'][$i];
            $receive_dtls['total_price'] = $data['order_unit_price'][$i] * $data['order_qnty'][$i];
            $receive_dtls['total_qnty_received'] = $data['receive_free_qnty'][$i] + $data['order_qnty'][$i];

            $this->db->insert('pharmacy_purchase_order_received_dtls', $receive_dtls);
            if ($pharmacy_id === 200) { // only for main pharmacy
                if (!$this->main_stock_dtls($stock_mst_id, $data['product_id'][$i], $receive_dtls['total_qnty_received'])) {
                    return false;
                }
            } else {
                if (!$this->sub_stock($data['product_id'][$i], $receive_dtls['total_qnty_received'])) {
                    return false;
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => false, 'message' => 'Product Insert Not Complete')));
        } else {
            $this->db->trans_commit();
            $print = $this->report_print($master_id);
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => true, 'print' => $print, 'message' => 'Product insert successfully')));
        }

    }

    /**
     *  show list
     */
    public function show_list(): void
    {
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Order No';
        $search_box['ticket_no_flag'] = 0;
        $search_box['sex_list_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['store_supplier_list_flag'] = 1;
        $condition = [];
        if (count($_POST) > 0) {
            if ($this->input->post('store_supplier_id')) {
                $condition['store_supplier_mst.SUPPLIER_ID'] = $this->input->post('store_supplier_id');
            }
            if ($this->input->post('from_date')) {
                $condition['bf_store_purchase_order_received_mst.received_date >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('from_date'))));
            }

            if ($this->input->post('to_date')) {

                $condition['bf_store_purchase_order_received_mst.received_date <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('to_date'))));
            }
            if ($this->input->post('common_text_search')) {
                $condition['bf_store_purchase_order_mst.purchase_order_no like'] = '%' . $this->input->post('common_text_search') . '%';
            }
        }

        $records = $this->db->select("
                      SQL_CALC_FOUND_ROWS 
                        bf_pharmacy_purchase_order_received_mst.*,
                        bf_pharmacy_purchase_order_mst.purchase_order_no,
                        bf_pharmacy_purchase_order_mst.supply_date_from,
                        bf_pharmacy_purchase_order_mst.supply_date_to,
                        bf_pharmacy_supplier.supplier_name,
                        bf_pharmacy_supplier.contact_no1
                        ", false)
            ->join('bf_pharmacy_purchase_order_mst', 'bf_pharmacy_purchase_order_mst.id=bf_pharmacy_purchase_order_received_mst.order_id', 'left')
            ->join('bf_pharmacy_supplier', 'bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id', 'left')
            ->where($condition)
            ->where('bf_pharmacy_purchase_order_received_mst.is_direct', 1)
            ->order_by('received_date', 'DESC')
            ->limit($limit, $offset)
            ->get('bf_pharmacy_purchase_order_received_mst')
            ->result();

        $data['records'] = $records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/direct_pharmacy_purchase/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $list_view = 'direct_pharmacy_purchase/list';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, compact('records', 'sl'), true);
            exit;
        }


        Template::set($data);
        Template::set('toolbar_title', 'Purchase Product list');
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

    /**
     * @param int $master_id
     * @param int $product_id
     * @param int $qnty
     * @return bool
     */
    public function main_stock_dtls($master_id = 0, $product_id = 0, $qnty = 0)
    {
        $dtls['stock_mst_id'] = $master_id;
        $dtls['product_id'] = $product_id;
        $dtls['quantity'] = $qnty;
        $this->db->insert('bf_pharmacy_stock_dtls', $dtls);

        $stock_product = $this->db->where('product_id', $product_id)->get('bf_pharmacy_stock')->row();

        if ($stock_product) {
            $stock_update['quantity_level'] = $qnty + $stock_product->quantity_level;
            $this->db->where('id', $stock_product->id)->update('bf_pharmacy_stock', $stock_update);
        } else {
            $stock_update['quantity_level'] = $qnty;
            $stock_update['product_id'] = $product_id;
            $this->db->insert('bf_pharmacy_stock', $stock_update);
        }

        return true;
    }

    public function sub_stock($product_id = 0, $qnty = 0)
    {
        $stock_product = $this->db->where('product_id', $product_id)->get('bf_pharmacy_indoor_stock')->row();

        if ($stock_product) {
            $stock_update['quantity_level'] = $qnty + $stock_product->quantity_level;
            $this->db->where('id', $stock_product->id)->update('bf_pharmacy_indoor_stock', $stock_update);
        } else {
            $stock_update['quantity_level'] = $qnty;
            $stock_update['product_id'] = $product_id;
            $stock_update['pharmacy_id'] = $this->current_user->pharmacy_id;
            $this->db->insert('bf_pharmacy_indoor_stock', $stock_update);
        }

        return true;

    }

    /**
     * @param $order_received_id
     * @return mixed
     */
    public function report_print($order_received_id)
    {
        $hospital = $this->db->Select('lib_hospital.*')->get('lib_hospital')->row();

        $records = $this->db->select("
                            bf_pharmacy_purchase_order_received_dtls.*,
                            bf_pharmacy_purchase_order_received_mst.bill_no,
                            bf_pharmacy_purchase_order_received_mst.received_date,
                            bf_pharmacy_purchase_order_received_mst.received_by,
                            bf_pharmacy_purchase_order_received_mst.order_id,
                            bf_pharmacy_products.product_name,
                            bf_pharmacy_purchase_order_mst.id,
                            bf_pharmacy_supplier.id,
                            bf_pharmacy_supplier.supplier_name,
                            bf_pharmacy_supplier.contact_no1,
                            bf_users.username
                            ")
            ->join('bf_pharmacy_purchase_order_received_dtls', 'bf_pharmacy_purchase_order_received_dtls.master_id=bf_pharmacy_purchase_order_received_mst.id')
            ->join('bf_pharmacy_products', 'bf_pharmacy_products.id=bf_pharmacy_purchase_order_received_dtls.product_id', 'left')
            ->join('bf_pharmacy_purchase_order_mst', 'bf_pharmacy_purchase_order_mst.id=bf_pharmacy_purchase_order_received_mst.order_id', 'left')
            ->join('bf_pharmacy_supplier', 'bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id', 'left')
            ->join('bf_users', 'bf_users.id=bf_pharmacy_purchase_order_received_mst.received_by')
            ->where('bf_pharmacy_purchase_order_received_mst.id', $order_received_id)
            ->get('bf_pharmacy_purchase_order_received_mst')
            ->result_array();

        //echo '<pre>'; print_r($records); die();

        // $crr_user=$this->current_user->display_name;
        return $this->load->view('purchase_order_receive/receive_print', compact('records', 'hospital'), true);


    }

    public function receive_list()
    {
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Supplier Name';
        $pharmacy_id = ($this->current_user->role_id == 23) ? $this->current_user->pharmacy_id : 200;
        $condition = [];
        $condition['bf_pharmacy_purchase_order_received_mst.pharmacy_id'] = $pharmacy_id;
        if (count($_POST) > 0) {
            if ($this->input->post('common_text_search')) {
                $condition['bf_pharmacy_supplier.supplier_name like'] = '%' . $this->input->post('common_text_search') . '%';
            }
            if ($this->input->post('from_date')) {
                $condition['bf_pharmacy_purchase_order_mst.supply_date_from >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('from_date'))));
            }

            if ($this->input->post('to_date')) {

                $condition['bf_pharmacy_purchase_order_mst.supply_date_to <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('to_date'))));
            }
        }

        $records = $this->db->select("
                        SQL_CALC_FOUND_ROWS 
                        bf_pharmacy_products.product_name,
                        bf_pharmacy_purchase_order_received_dtls.received_qnty as qnty,
                        bf_pharmacy_purchase_order_received_dtls.unit_price,
                        bf_pharmacy_purchase_order_received_dtls.total_price,
                        bf_pharmacy_purchase_order_mst.purchase_order_no,
                        bf_pharmacy_purchase_order_mst.supply_date_from,
                        bf_pharmacy_supplier.supplier_name,
                        bf_pharmacy_supplier.contact_no1
                        ", false)
            ->join('bf_pharmacy_purchase_order_mst', 'bf_pharmacy_purchase_order_mst.id=bf_pharmacy_purchase_order_received_mst.order_id', 'left')
            ->join('bf_pharmacy_supplier', 'bf_pharmacy_purchase_order_mst.supplier_id=bf_pharmacy_supplier.id', 'left')
            ->join('bf_pharmacy_purchase_order_received_dtls', 'bf_pharmacy_purchase_order_received_dtls.master_id = bf_pharmacy_purchase_order_received_mst.id')
            ->join('bf_pharmacy_products','bf_pharmacy_purchase_order_received_dtls.product_id = bf_pharmacy_products.id')
            ->where($condition)
            ->where('bf_pharmacy_purchase_order_received_mst.is_direct', 1)
            ->order_by('received_date', 'DESC')
            ->limit($limit, $offset)
            ->get('bf_pharmacy_purchase_order_received_mst')
            ->result();

        $data['records'] = $records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/direct_pharmacy_purchase/pharmacy/receive_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'direct_pharmacy_purchase/receive_list';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, compact('records', 'sl'), true);
            exit;
        }


        Template::set($data);
        Template::set('toolbar_title', 'Direct Purchase Product list');
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

}