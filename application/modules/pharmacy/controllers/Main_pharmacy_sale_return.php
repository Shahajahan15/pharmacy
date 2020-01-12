<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Main_pharmacy_sale_return extends Admin_Controller
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

        $this->load->model('pharmacy_sales_return_dtls_model', NULL, TRUE);
        $this->load->model('pharmacy_sales_return_mst_model', NULL, TRUE);
        $this->load->model('pharmacy_sales_mst_model', NULL, TRUE);
        $this->load->model('report/pharmacy_client_wise_report_model', NULL, TRUE);
        $this->lang->load('main_pharmacy_sale_return');
        $this->load->library('pharmacyCommonService');
        Assets::add_module_js('pharmacy', 'main_pharmacy_sale_return.js');
        Assets::add_module_js('pharmacy', 'pharmacy.js');
        Template::set_block('sub_nav', 'main_pharmacy_sale_return/_sub_nav');

    }

    /* customer sale */

    public function sale_return()
    {
        $this->auth->restrict('Pharmacy.MSaleReturn.Add');
        $data = array();
        $data['categories'] = $this->db->get('bf_pharmacy_category')->result();
        Template::set($data);
        Template::set('toolbar_title', "Sale Return");
        Template::set_view('main_pharmacy_sale_return/create');
        Template::render();
    }

    public function show_list()
    {
        $this->auth->restrict('Pharmacy.MSaleReturn.View');
        $data = array();
        $id = $this->session->userdata('master_id');
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Sale no';
        $search_box['product_name_flag'] = 0;
        $search_box['contact_no_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag'] = 0;
        $search_box['patient_id_flag'] = 0;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_name_flag'] = 0;
        $condition['rmst.created_by>='] = 0;
        if (count($_POST) > 0) {

            if ($this->input->post('common_text_search')) {
                $condition['smst.sale_no like'] = '%' . trim($this->input->post('common_text_search')) . '%';
            }

            if ($this->input->post('from_date')) {
                $from_date = custom_date_format(trim($this->input->post('from_date')));
                $condition['rmst.created_date >='] = $from_date . " 00:00:00";

            }

            if ($this->input->post('to_date')) {
                $to_date = custom_date_format(trim($this->input->post('to_date')));
                $condition['rmst.created_date  <='] = $to_date . " 23:59:59";
            }
        }

        $records = $this->db->select("
        	 						SQL_CALC_FOUND_ROWS
        	 						rmst.id,  
        	 						smst.sale_no,
        	 						sum(rmst.tot_return_amount) as tot_return_amount,
        	 						sum(rmst.tot_charge_amount) as tot_charge_amount,
        	 						sum(rmst.tot_paid_return_amount) as tot_paid_return_amount,
        	 						sum(rmst.tot_return_due) as tot_return_due,
        	 						sum(rmst.tot_less_discount) as tot_less_discount,
        	 						rmst.created_date
                                    ", false)
            ->from('bf_pharmacy_sale_return_mst as rmst')
            ->join('bf_pharmacy_sales_mst as smst', 'smst.id=rmst.sale_id', 'left')
            ->where($condition)
            ->limit($limit, $offset)
            ->order_by('rmst.created_date', 'desc')
            ->group_by('rmst.sale_id')
            ->get()
            ->result();
        if ($id) {
            $data['print'] = $this->sale_print($id);
            $this->session->unset_userdata('master_id');
        }
        $data['records'] = $records;
        //echo '<pre>';print_r($data['records']);exit;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/main_pharmacy_sale_return/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $list_view = 'main_pharmacy_sale_return/list';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view('main_pharmacy_sale_return/list', compact('records', 'sl'), true);
            exit;
        }

        Template::set($data);
        Template::set('list_view', $list_view);
        Template::set('toolbar_title', "Sale Return List");
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

    /* sale */

    public function save()
    {
        //echo '<pre>';print_r($_POST);exit;
        $this->db->trans_begin();

        $pObj = new pharmacyCommonService();

        $sale_id = $this->input->post('master_id', true);
        $sale_information = $this->pharmacy_sales_mst_model->find($sale_id);
        $data = array();
        $data['return_less_discount'] = $sale_information->return_less_discount + $this->input->post('less_amount');
        $data['return_bill'] = $sale_information->return_bill + $this->input->post('pharmacy_total_return_amount');
        $data['tot_return'] = $sale_information->tot_return + $this->input->post('pharmacy_return_taka');

        $this->db->where('id', $sale_information->id)->update('bf_pharmacy_sales_mst', $data);


        // echo "<pre>";
        // print_r($sale_information);
        // exit();

        /*  tot normal discount    */
        $ncount = count($this->input->post('product_id', true));
        //print_r($ncount);exit;
        if (!$ncount) {
            echo json_encode(array('success' => false, 'message' => "Plz Search By Sale No..."));
            exit;
        }
        $tot_charge = $this->input->post('pharmacy_tot_charge', true);
        $less_amount = $this->input->post('less_amount', true);
        $tot_return_paid = $this->input->post('pharmacy_return_taka', true);
        $overall_discount = $this->input->post('overall_discount', true);

        $product_id = $this->input->post('product_id', true);
        $r_qnty = $this->input->post('r_qnty', true);
        $price = $this->input->post('price', true);
        $tot_return_amount = 0;
        for ($i = 0; $i < $ncount; $i++) {
            $tot_return_amount += $this->input->post('r_sub_amount', true)[$i];
        }
        if (!$tot_return_amount) {
            echo json_encode(array('success' => false, 'message' => "Plz Return Medicine Qnty..."));
            exit;
        }
        $tot_charge_amount = percent_convert_amount($tot_charge, $tot_return_amount);
        $tot_unit_amount = ($tot_return_amount - $tot_charge_amount);
        $total_return_due = $tot_unit_amount - $tot_return_paid - $less_amount;
        if ($total_return_due >= 0) {
            $total_due_amount = $total_return_due;
        } else {
            $total_due_amount = 0;
        }

        /*   sales master insert  */
        $mst_data = array(
            'mr_no' => $pObj->getSaleReturnNo(1, $this->current_user),
            'sale_id' => $sale_id,
            'tot_return_amount' => $tot_return_amount,
            'tot_charge_percent' => $tot_charge,
            'tot_charge_amount' => $tot_charge_amount,
            'tot_return_unit_amount' => $tot_unit_amount,
            'tot_paid_return_amount' => $tot_return_paid,
            'tot_return_due' => $total_due_amount,
            'tot_less_discount' => $less_amount,
            'overall_discount' => $overall_discount,
            'created_by' => $this->current_user->id
        );
        //	echo '<pre>';print_r($mst_data);exit;
        $master_id = $this->pharmacy_sales_return_mst_model->insert($mst_data);

        /*   sales dtls insert  */
        for ($j = 0; $j < $ncount; $j++) {
            if ($r_qnty[$j]) {
                $dtl_data = array(
                    'master_id' => $master_id,
                    'product_id' => $product_id[$j],
                    'price' => $price[$j],
                    'tot_discount' => $this->input->post('tot_discount', true)[$j],
                    'r_qnty' => $r_qnty[$j],
                    'r_sub_total' => $this->input->post('r_sub_amount', true)[$j]
                );
                $this->pharmacy_sales_return_dtls_model->insert($dtl_data);
            }

        }

        /*      pharmacy stock  */
        $StockObj = new pharmacyCommonService();
        $Obj = new Commonservice();
        $StockObj->stock($master_id, $product_id, $r_qnty, 3, 1, 1, $this->current_user);

        /*  tranjaction payment */

        $saledata = array(
            'due_paid' => $tot_return_paid,
            'patient_id' => $sale_information->patient_id,
            'admission_id' => $sale_information->admission_id,
            'employee_id' => $sale_information->employee_id,
            'customer_id' => $sale_information->customer_id,
            'source_id' => $master_id,
            'return_bill' => $this->input->post('pharmacy_total_return_amount'),
            'return_less_discount' => $this->input->post('less_amount'),
            'overall_discount' => $overall_discount
        );

        $customer_type = $sale_information->customer_type;
        $current_user = $this->current_user;
        $type = 3;

        /*      if($tot_return_paid){
                  $StockObj->pharmacyTranjactionPayment($saledata, $current_user, $type,$customer_type);
              }*/

        $StockObj->pharmacyTranjactionPayment($saledata, $current_user, $type, $customer_type);

        $patient_id = $this->getSalePatient($sale_id);
        if ($patient_id) {
            $Obj->patientHistoryAdd($patient_id, $master_id, 33);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('success' => false, 'message' => $this->pharmacy_sales_return_mst_model->error));
        } else {
            $this->db->trans_commit();
            $print = $this->sale_print($master_id);
            echo json_encode(array('success' => true, 'print' => $print, 'message' => 'Successfully done'));
        }
        exit;
    }

    private function getSalePatient($sale_id = 0)
    {
        $row = $this->db->where('id', $sale_id)->get('pharmacy_sales_mst')->row();
        //echo '<pre>';print_r($row);exit();
        $patient_id = 0;
        if ($row) {
            if ($row->customer_type == 2) {
                $patient_id = $row->patient_id;
            } elseif ($row->customer_type == 1) {
                $admission_id = $row->admission_id;

                $patient_id = @$this->db->where('id', $admission_id)->get('admission_patient')->row()->patient_id;
            } else {
                $patient_id = 0;
            }
        }
        return $patient_id;
    }

    /*      sale print      */

    public function sale_print($id)
    {
        $data = array();
        $data['current_user'] = $this->current_user->username;
        $data['hospital'] = $this->db->select('lib_hospital.*')->get('lib_hospital')->row();
        $data['records'] = $this->db->select('bf_pharmacy_sale_return_mst.*,pharmacy_products.product_name,bf_pharmacy_sale_return_dtls.price,bf_pharmacy_sale_return_dtls.tot_discount,bf_pharmacy_sale_return_dtls.r_qnty,bf_pharmacy_sale_return_dtls.r_sub_total,bf_pharmacy_sales_mst.sale_no')
            ->join('bf_pharmacy_sale_return_dtls', 'bf_pharmacy_sale_return_dtls.master_id=bf_pharmacy_sale_return_mst.id')
            ->join('pharmacy_products', 'pharmacy_products.id=bf_pharmacy_sale_return_dtls.product_id')
            ->join('bf_pharmacy_sales_mst', 'bf_pharmacy_sales_mst.id=bf_pharmacy_sale_return_mst.sale_id')
            ->where('bf_pharmacy_sale_return_mst.id', $id)
            ->get('bf_pharmacy_sale_return_mst')
            ->result();

        $data['patient_info'] = $this->db->select('bf_pharmacy_sale_return_mst.*,patient_master.patient_id,patient_master.patient_name,patient_master.age,patient_master.contact_no')
            ->join('bf_pharmacy_sales_mst', 'bf_pharmacy_sales_mst.id=bf_pharmacy_sale_return_mst.sale_id')
            ->join('patient_master', 'patient_master.id=bf_pharmacy_sales_mst.patient_id')
            ->where('bf_pharmacy_sale_return_mst.sale_id', $id)
            ->get('bf_pharmacy_sale_return_mst')
            ->row();
        //print_r($data['patient_info']);exit();
        return $this->load->view('main_pharmacy_sale_return/sale_return_print', $data, true);
    }

    /*    get sale info     */
    public function getSaleInfo()
    {
        $data = [];
        $sale_id = $this->input->post('sale_id', true);
        if (!$sale_id) {
            return false;
        }
        $sale_info = $this->db
            ->select('p.product_name,dtls.*,mst.admission_id,mst.patient_id,mst.employee_id,mst.customer_id,mst.tot_less_discount,mst.customer_type')
            ->from('pharmacy_sales_mst as mst')
            ->where('mst.id', $sale_id)
            ->join('pharmacy_sales_dtls as dtls', 'dtls.master_id = mst.id')
            ->join('pharmacy_products as p', 'p.id = dtls.product_id')
            ->get()
            ->result();

        $data['p_due'] = $this->getClientBillInfo($sale_info);
        $data['total_less_discount'] = $sale_info[0]->tot_less_discount;
        $data['return_less_info'] = $this->getReturnDiscount($sale_id, 1);
        $data['return_overall_discount'] = $this->getReturnDiscount($sale_id, 0);
        $p_overall_discount = $this->getOverAllDiscountBySaleId($sale_id);

        $row = '';
        $t_qnty = 0;
        if ($sale_info && count($sale_info) > 0) {
            $row .= '';
            $tot_r_sub_amount = 0;
            foreach ($sale_info as $key => $record) {
                $t_qnty += $record->qnty;
                //print_r($record->unit_price);
                $p_return_qnty = $this->getSaleReturnQnty($sale_id, $record->product_id);
                $tot_qnty = ($record->qnty - $p_return_qnty);
                $tot_discount = ($record->normal_discount_taka + $record->service_discount_taka);
                //$sub_amount = ($record->unit_price * $record->qnty) - $tot_discount;
                $sub_amount = ($record->unit_price * $tot_qnty) - ($tot_discount * $tot_qnty);
                $r_sub_amount = 0;
                $less_disc_info = 0;

                if ($tot_qnty) {
                    $row .= '<tr class="success">';
                    $row .= '<td>' . $record->product_name . '<input type="hidden" class="product_id" name="product_id[]" value="' . $record->product_id . '" /> <input type="hidden" class="master_id" name="master_id" value="' . $record->master_id . '" /></td>';
                    $row .= '<td>' . $record->unit_price . '<input type="hidden" class="price" name="price[]" value="' . $record->unit_price . '" /> </td>';
                    $row .= '<td>' . $tot_qnty . '<input type="hidden" class="qnty" name="qnty[]" value="' . $tot_qnty . '" /> </td>';
                    $row .= '<td>' . $tot_discount . '<input type="hidden" class="tot_discount" name="tot_discount[]" value="' . $tot_discount . '" /> </td>';
                    $row .= '<td>' . $sub_amount . '<input type="hidden" class="sub_amount" name="sub_amount[]" value="' . $sub_amount . '" /> </td>';
                    $row .= '<td><input type="text" class="r_qnty on-focus-selected" name="r_qnty[]" value="0" autocomplete="off"/> </td>';
                    $row .= '<td><span class="r_sub_amount_text">' . $r_sub_amount . '</span><input type="hidden" class="r_sub_amount" name="r_sub_amount[]" value="' . $r_sub_amount . '" /> <input type="hidden" class="less_disc"  value="' . $less_disc_info . '" /> </td>';
                    $row .= '<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
                    $row .= '<tr>';
                    $tot_r_sub_amount += $r_sub_amount;
                }

            }
            $data['return_info'] = $row;
            $data['sale_info'] = $sale_info;
            $data['per_overall_discount'] = 0;
            if ($tot_qnty) {
                $data['per_overall_discount'] = round(($p_overall_discount / $t_qnty), 2);
            }


            //echo '<pre>';print_r($data['return_info']);die();

            if (empty($data['return_info'])) {
                echo json_encode(array('success' => false, 'message' => "Already All Medicine Return"));
                exit;
            }
            $data['tot_sub_amount'] = round($tot_r_sub_amount);
            $data['tot_charge'] = round(percent_convert_amount(0, $tot_r_sub_amount));
            $data['tot_unit_amount'] = round(($tot_r_sub_amount - $data['tot_charge']));
            echo json_encode($data);
        }
    }

    private function getOverAllDiscountBySaleId($sale_id)
    {
        $overall_discount = 0;
        $row = $this->db
            ->select('overall_discount')
            ->where('source_id', $sale_id)
            ->where('type', 2)
            ->get('pharmacy_payment_transaction')
            ->row();
        if ($row) {
            $overall_discount = $row->overall_discount;
        }

        return $overall_discount;

    }

    private function getReturnDiscount($sale_id, $type)
    {
        $return_less_discount = 0;
        $return_overall_discount = 0;
        $row = $this->db->select("
			IFNULL(SUM(srmst.tot_less_discount),0) as tot_less_discount,
			IFNULL(SUM(srmst.overall_discount),0) as overall_discount,
			")
            ->from('bf_pharmacy_sale_return_mst as srmst')
            ->where('sale_id', $sale_id)
            ->get()
            ->row();
        if ($row) {
            $return_less_discount = $row->tot_less_discount;
            $return_overall_discount = $row->overall_discount;
        }
        if ($type) {
            return $return_less_discount;
        }

        return $return_overall_discount;
    }

    private function getClientBillInfo($sale_info = [])
    {
        $due = 0;
        $customer_type = $sale_info[0]->customer_type;
        $admission_id = $sale_info[0]->admission_id;
        $patient_id = $sale_info[0]->patient_id;
        $employee_id = $sale_info[0]->employee_id;
        $customer_id = $sale_info[0]->customer_id;

        $ncondition['customer_type'] = $customer_type;
        $ncondition['client_id'] = 0;
        if ($ncondition['customer_type'] == 1) {
            $ncondition['client_id'] = $admission_id;
        } elseif ($ncondition['customer_type'] == 2) {
            $ncondition['client_id'] = $patient_id;
        } elseif ($ncondition['customer_type'] == 3) {
            $ncondition['client_id'] = $customer_id;
        } elseif ($ncondition['customer_type'] == 4 || $ncondition['customer_type'] == 5) {
            $ncondition['client_id'] = $employee_id;
        }
        $row = $this->pharmacy_client_wise_report_model->getPharmacyClientList($ncondition, 0, 10, 200, 1);
        if ($row) {
            $due = $row->due;
        }
        return $due;
    }

    public function getSaleReturnQnty($id, $product_id)
    {
        $sale_info = $this->db
            ->select('SUM(dtls.r_qnty) as qnty')
            ->from('pharmacy_sale_return_mst as mst')
            ->where('mst.sale_id', $id)
            ->where('dtls.product_id', $product_id)
            ->join('pharmacy_sale_return_dtls as dtls', 'dtls.master_id = mst.id')
            ->group_by('mst.sale_id')
            ->group_by('dtls.product_id')
            ->get()
            ->row();
        if (!$sale_info) {
            $sale_info = 0;
        } else {
            $sale_info = $sale_info->qnty;
        }
        return $sale_info;
    }

    public function fakestock()
    {
        $records = $this->db->get('bf_pharmacy_products')->result();
        foreach ($records as $key => $value) {
            $data['product_id'] = $value->id;
            $data['quantity_level'] = 500;
            $this->db->insert('bf_pharmacy_stock', $data);
        }
    }

    public function return_reprint($id)
    {
        if ($this->input->is_ajax_request()) {
            $print_body = $this->sale_print($id);
            echo $print_body;
        }
    }

}