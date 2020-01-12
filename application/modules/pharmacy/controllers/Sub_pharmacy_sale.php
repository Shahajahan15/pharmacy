<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Sub_pharmacy_sale extends Admin_Controller
{

    /**
     * Constructor
     *
     * @return void
     */
    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->load->model('pharmacy_indoor_sales_dtls_model', NULL, TRUE);
        $this->load->model('pharmacy_indoor_sales_mst_model', NULL, TRUE);
        $this->load->model('product_model', NULL, TRUE);
        $this->load->model('customer_model', NULL, TRUE);
        $this->load->model('category_model', NULL, true);
        $this->lang->load('sub_pharmacy');
        $this->load->library('pharmacyCommonService');
        Assets::add_module_js('pharmacy', 'sub_pharmacy_sale.js');
        Template::set_block('sub_nav', 'sub_pharmacy/_sub_nav');

    }
    //--------------------------------------------------------------------
	/* customer sale */

	public function customer_sale()
	{

        //echo'<img src="http://[::1]/pharmacy-v1/public/assets/images/hospital/1557805950.jpg\">';
        //echo img(base_url('assets/images/hospital/1557804744.jpg'));


		$this->auth->restrict('Pharmacy.Sub.Sale.Add');
		$data = array();
        $data['img']=base_url('assets/images/hospital/1557804744.jpg');
		$data['categories'] = $this->db->order_by('category_name','asc')->get('bf_pharmacy_category')->result();
		Template::set($data);
		Template::set('toolbar_title', lang("sale_new"));
		Template::set_view('sub_pharmacy/create');
		Template::render();
	}

	/* sale */

	public function save()
	{
		//echo '<pre>';print_r($_POST);exit;
		$this->auth->restrict('Pharmacy.Sub.Sale.Add');
		$PObj = new pharmacyCommonService();
	 	/*  tot normal discount    */
	 	$customer_idd=$this->input->post('cusomer_id',true);
	 	$ncount = count($this->input->post('product_id',true));
	 	$tot_less_discount = $this->input->post('pharmacy_total_less_discount',true);
	 	$tot_paid = $this->input->post('pharmacy_total_paid',true);
	 	$tot_price = $this->input->post('pharmacy_total_price',true);
	 	$tot_due = $this->input->post('pharmacy_total_due',true);
	 	$customer_type = $this->input->post('customer_type',true);
	 	$product_id = $this->input->post('product_id', true);
	 	$qnty = $this->input->post('qnty', true);
	 	$sale_price = $this->input->post('sale_price', true);
	 	$tot_normal_discount = 0;
	 	$tot_service_discount = 0;
	 	for ($i = 0; $i < $ncount; $i++)
	 	{
			$tot_n_discount = ((float)($this->input->post('nd_amount',true)[$i]) * (float)$qnty[$i]);
	 		$tot_s_discount = ((float)($this->input->post('sd_amount',true)[$i]) * (float)$qnty[$i]);
			$tot_normal_discount += $tot_n_discount;
			$tot_service_discount += $tot_s_discount;
		}
		$this->db->trans_begin();
		if ($customer_type == 1) {
			/* customer insert   */
			$cutomer_info=$this->customer_model->find($customer_idd);
            if($cutomer_info)
            {
                $customer_id=$cutomer_info->id;
                $employee_id = 0;
                $type = 3;
            }
            else
            {
                $cus_data = array(
                'customer_name' => $this->input->post('pharmacy_customer_name',true),
                'customer_mobile' => $this->input->post('pharmacy_customer_phone',true),
                'created_by' => $this->current_user->id,
                'type' => 1
                );
                $customer_id = $this->customer_model->insert($cus_data);
                $employee_id = 0;
                $type = 3;
            }
        } else {
            $customer_id = 0;
            $employee_id = $this->input->post('emp_id', true);
            $type = ($customer_type == 2) ? 4 : 5;
            /*   sales master insert  */
        }
        $mst_data = array(
            'sale_no' => $PObj->getSaleNo(2, $this->current_user),
            'pharmacy_id' => $this->current_user->pharmacy_id,
            'customer_id' => $customer_id,
            'employee_id' => $employee_id,
            'tot_bill' => $tot_price,
            'tot_normal_discount' => $tot_normal_discount,
            'tot_service_discount' => $tot_service_discount,
            'tot_less_discount' => $tot_less_discount,
            'tot_paid' => $tot_paid,
            'tot_due' => $tot_due,
            'created_by' => $this->current_user->id,
            'customer_type' => $type,
            'type' => 2
        );
        //	echo '<pre>';print_r($mst_data);exit;
        $master_id = $this->pharmacy_indoor_sales_mst_model->insert($mst_data);

        /*     tranjaction payment         */

        $sale_pay['patient_id'] = 0;
        $sale_pay['admission_id'] = 0;
        $sale_pay['employee_id'] = 0;
        $sale_pay['customer_id'] = 0;
        $sale_pay['paid_amount'] = $mst_data['tot_paid'];
        $sale_pay['source_id'] = $master_id;

        if ($_POST['customer_type'] == 1) {
            $sale_pay['customer_id'] = $customer_id;
        }
        if ($_POST['customer_type'] == 2 || $_POST['customer_type'] == 3) {
            $sale_pay['employee_id'] = $_POST['emp_id'];
        }

        $PObj->subPharmacyTranjactionPayment($sale_pay, $this->current_user, 1, $mst_data['customer_type']);


        /*   sales dtls insert  */
        for ($j = 0; $j < $ncount; $j++) {
            $dtl_data = array(
                'master_id' => $master_id,
                'product_id' => $product_id[$j],
                'qnty' => $qnty[$j],
                'unit_price' => $this->input->post('sale_price', true)[$j],
                'normal_discount_percent' => $this->input->post('nd_percent', true)[$j],
                'normal_discount_taka' => $this->input->post('nd_amount', true)[$j],
                'service_discount_percent' => $this->input->post('sd_percent', true)[$j],
                'service_discount_taka' => $this->input->post('sd_amount', true)[$j],
                'n_discount_id' => $this->input->post('n_discount_id', true)[$j],
                'n_discount_type' => $this->input->post('n_discount_type', true)[$j],
                's_discount_id' => $this->input->post('s_discount_id', true)[$j],
                's_discount_type' => $this->input->post('s_discount_type', true)[$j]
            );
            $this->pharmacy_indoor_sales_dtls_model->insert($dtl_data);
        }

        /*      pharmacy stock  */

        $StockObj = new pharmacyCommonService();
        $StockObj->stock($master_id, $product_id, $qnty, 2, 2, 2, $this->current_user);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('success' => false, 'message' => $this->pharmacy_sales_mst->error));
        } else {
            $this->db->trans_commit();
            $print = $this->sale_print($master_id);
            echo json_encode(array('success' => true, 'print' => $print, 'message' => 'Successfully done'));
        }
        exit;

    }

    public function sub_pharmacy_sale_list()
    {
        $this->auth->restrict('Pharmacy.Sub.Sale.View');
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Sale No';
        $search_box['ticket_no_flag'] = 0;
        $search_box['contact_no_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag'] = 0;
        $search_box['patient_id_flag'] = 0;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_name_flag'] = 0;
        $condition['pism.created_by>='] = 0;
        if (count($_POST) > 0) {

            if ($_POST['common_text_search']) {
                $condition['pism.sale_no'] = trim($this->input->post('common_text_search'));
            }
            if ($this->input->post('from_date')) {

                $condition["DATE_FORMAT(pism.created_date, '%Y-%m-%d') >="] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('from_date'))));

            }
            if ($this->input->post('to_date')) {

                $condition["DATE_FORMAT(pism.created_date, '%Y-%m-%d') <="] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('to_date'))));

            }
        }
        $records = $this->db
            ->select('pism.id,pism.sale_no,pism.tot_paid,pism.tot_bill,pism.tot_due,pism.created_date,pism.tot_less_discount')
            ->from('pharmacy_indoor_sales_mst as pism')
            ->where('pism.pharmacy_id', $this->current_user->pharmacy_id)
            ->where($condition)
            ->order_by('pism.id', 'desc')
            ->limit($limit, $offset)
            ->get()
            ->result();
        // echo '<pre>'; print_r($this->current_user->pharmacy_id);exit();


        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/sub_pharmacy_sale/pharmacy/sub_pharmacy_sale_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('sub_pharmacy/list', compact('records', 'sl'), true);
            exit;
        }


        Template::set($data);
        // Template::set_view('indoor_stock_requisition/list');
        $list_view = 'sub_pharmacy/list';
        Template::set('records', $records);
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::set('toolbar_title', 'Sub Pharmacy Sale List');
        Template::render();
    }

    /*      sale print      */

    public function sale_print($id)
    {
        $row = $this->db->where('id', $id)->get('bf_pharmacy_indoor_sales_mst')->row();
        //print_r($row);exit();
        if ($row->customer_type == 1) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master .age,patient_master.contact_no as mobile")
                ->from("bf_pharmacy_indoor_sales_mst")
                ->join("admission_patient", "admission_patient.id=bf_pharmacy_indoor_sales_mst.admission_id")
                ->join('patient_master', 'patient_master.id=admission_patient.patient_id')
                ->where('bf_pharmacy_indoor_sales_mst.id', $id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 2) {
            $c_info = $this->db
                ->select("patient_master.patient_id as code,patient_master.patient_name as name,patient_master .age,patient_master.contact_no as mobile")
                ->from("bf_pharmacy_indoor_sales_mst")
                ->join("patient_master", "patient_master.id=bf_pharmacy_indoor_sales_mst.patient_id")
                ->where('bf_pharmacy_indoor_sales_mst.id', $id)
                ->get()
                ->row();

        } elseif ($row->customer_type == 3) {
            $c_info = $this->db
                ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as age,bf_pharmacy_customer.customer_mobile as mobile")
                ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                ->from("bf_pharmacy_indoor_sales_mst")
                ->join('bf_pharmacy_customer', 'bf_pharmacy_customer.id=bf_pharmacy_indoor_sales_mst.customer_id')
                ->where('bf_pharmacy_indoor_sales_mst.id', $id)
                ->get()
                ->row();
        } elseif ($row->customer_type == 4 || $row->customer_type == 5) {
            $c_info = $this->db
                ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as age,0 as mobile,bf_pharmacy_indoor_sales_mst.id,bf_hrm_ls_employee.EMP_ID")
                ->from("bf_pharmacy_indoor_sales_mst")
                ->join('bf_hrm_ls_employee', ' bf_hrm_ls_employee.EMP_ID=bf_pharmacy_indoor_sales_mst.employee_id')
                ->where('bf_pharmacy_indoor_sales_mst.id', $id)
                ->get()
                ->row();
        }
      // print_r($c_info);exit();

		//$hospital=$this->db->Select('*')->get('lib_hospital')->row();
        $role =$this->current_user->role_id;
        $pharmacy_id =$this->current_user->pharmacy_id;
        if($role === 2):
            $hospital=$this->db->Select('*, "1"as type')->get('lib_hospital')->row();
            else:
                $this->load->model('pharmacy_setup_model',null,false);
            $hospital = $this->pharmacy_setup_model->Select('*, "2"as type')->find_by('id', $pharmacy_id);
                endif;

		$records=$this->db->Select('bf_pharmacy_indoor_sales_mst.*,pharmacy_products.product_name,bf_pharmacy_indoor_sales_dtls.unit_price,bf_pharmacy_indoor_sales_dtls.normal_discount_percent,bf_pharmacy_indoor_sales_dtls.normal_discount_taka,bf_pharmacy_indoor_sales_dtls.service_discount_percent,bf_pharmacy_indoor_sales_dtls.service_discount_taka
	    	,bf_pharmacy_indoor_sales_dtls.qnty,pharmacy_category.category_name')
	    ->join('bf_pharmacy_indoor_sales_dtls','bf_pharmacy_indoor_sales_dtls.master_id=bf_pharmacy_indoor_sales_mst.id')
	    
	    ->join('pharmacy_products','pharmacy_products.id=bf_pharmacy_indoor_sales_dtls.product_id')
	    ->join('pharmacy_category','pharmacy_category.id=pharmacy_products.category_id')
	    ->where('bf_pharmacy_indoor_sales_mst.id',$id)
	    ->get('bf_pharmacy_indoor_sales_mst')
	    ->result();
	    // $patient_info=$this->db->Select('patient_master.patient_id')
	    // ->join('patient_master','patient_master.id=bf_pharmacy_indoor_sales_mst.patient_id')
	    // ->where('bf_pharmacy_indoor_sales_mst.id',$id)
	    // ->get('bf_pharmacy_indoor_sales_mst')
	    // ->result();
	   // // echo $this->db->last_query();
	   //   print_r($patient_info);exit();
	    $current_user=$this->current_user->username;

	    $sendData=array(
            'hospital'=>$hospital,
            'records'=>$records,
            'c_info'=>$c_info,
            'current_user'=>$current_user
            //'patient_info'=>$patient_info
	    	);
//echo '<pre>'; print_r($sendData); exit();
	    //print_r($this->load->view('sub_pharmacy/sale_print', $sendData, true));
		return $this->load->view('sub_pharmacy/sale_print', $sendData, true);
		 // $this->load->view('sub_pharmacy/sale_print', $sendData);
	}
 public function sub_pharmacy_sale_reprint($id){
            if ($this->input->is_ajax_request()) {
            $print_body=$this->sale_print($id);
            echo $print_body;
        }
        }
	/*   medicine info */
	public function getMedicineInfo($id, $customer_type = 1)
	{
		$data = array();
		$obj = new Commonservice();
		$nDiscountObj = new pharmacyCommonService();
		$data['product_info'] = $this->product_model->find($id);
		$pharmacy_id = $this->current_user->pharmacy_id;
		$sale_price = $data['product_info']->sale_price;
		$category_id = $data['product_info']->category_id;
		$data['category_info'] = $this->category_model->find($category_id);
		$stock_level = $this->db
							->where('product_id',$id)
							->where('pharmacy_id',$pharmacy_id)
							->get('pharmacy_indoor_stock')
							->row();
		$data['stock'] = (isset($stock_level)) ? $stock_level->quantity_level: 0;




		$data['n_discount_percent'] =0;
			$data['n_discount_amount']=0;
			$data['n_discount_id']=0;
			$data['n_discount_type']=0;


		$over_all_discount= $nDiscountObj->normal_discount();
		if($over_all_discount)
		{
			$data['n_discount_percent'] =$over_all_discount->discount_parcent;
			$data['n_discount_amount']=percent_convert_amount($over_all_discount->discount_parcent,$sale_price);

			$data['n_discount_id']=$over_all_discount->id;
			$data['n_discount_type']=1;

		} else {
			$normal_discount= $nDiscountObj->discount_emp_doctor($customer_type, 2);

			if($normal_discount)
			{
            $data['n_discount_percent'] = $normal_discount->discount_parcent;
		    $data['n_discount_amount'] = percent_convert_amount($data['n_discount_percent'],$sale_price);

		    $data['n_discount_id']=$normal_discount->id;
			$data['n_discount_type']=2;
		    }
		}

		$data['s_discount_percent'] = 0;
		$data['s_discount_amount'] = 0;
		$data['s_discount_id']=0;
		$data['s_discount_type']=3;


		// if ($customer_type == 1) {
		// 	$data['n_discount_percent'] = $nDiscountObj->discount_product($id, 0, 1);
		// } else {
		// 	$data['n_discount_percent'] = $nDiscountObj->discount_emp_doctor($customer_type);
		// }

		// $data['n_discount_amount'] = percent_convert_amount($data['n_discount_percent'],$sale_price);
		// $data['s_discount_percent'] = $obj->patient_discount(0,2,$id);
		// $data['s_discount_amount'] = percent_convert_amount($data['s_discount_percent'],$sale_price);

		$data['total_discount_amount'] = $data['s_discount_amount'] + $data['n_discount_amount'];
		$data['qnty'] = 1;
		$data['sub_total'] = (($sale_price - $data['total_discount_amount'])*1);
		echo json_encode($data);
	}

	 public function getSubCategoryByCategoryId($category_id=0){

        $records=$this->db->where('category_id',$category_id)->get('bf_pharmacy_subcategory')->result();


            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->subcategory_name.'</option>';
        }
        echo $options;
    }

    public function getProductByCategoryId($category_id=0){

        $records=$this->db->where('category_id',$category_id)->order_by('product_name','asc')->get('bf_pharmacy_products')->result();

        	$options='<option value="">Select Product</option>';
	        foreach ($records as $record) {
	            $options.='<option value="'.$record->id.'">'.$record->product_name.'</option>';
	        }
        	echo $options;
    }

    public function getProductBySubCategoryId($sub_cat_id=0){
        $records=$this->db->where('sub_category_id',$sub_cat_id)->get('bf_pharmacy_products')->result();


            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->product_name.'</option>';
        }
        echo $options;
    }

    public function getCustomerType()
    {
		$c_type = $this->input->post('c_type');
		if ($c_type == 2) {
			$emp = $this->db
					->where('EMP_TYPE !=', 1)
					->get('hrm_ls_employee')
					->result();
			$type = "Employee";
		}
		if ($c_type == 3) {
			$emp = $this->db
				->where('EMP_TYPE', 1)
				->get('hrm_ls_employee')
				->result();
			$type = "Doctor";
		}
		$options = '';
		$options='<option value="">Select '.$type.'</option>';
        foreach ($emp as $row) {
            $options.='<option value="'.$row->EMP_ID.'">'.$row->EMP_NAME.'</option>';
        }
        echo $options;
        //echo json_encode($options);

	}

	public function checkSubPharmacyMedicineStock(){
		$medicine_id = $this->input->post("product_id", true);
        $row = $this->db->where('product_id',$medicine_id)
				->where('pharmacy_id',$this->current_user->pharmacy_id)
				->get('bf_pharmacy_indoor_stock')
				->row();
		$res = false;
		if ($row){
			$stock = ($row->quantity_level) ? $row->quantity_level : 0;

			if ($stock > 0) {
				$res = true;
			}
		}
		echo $res;
		//print_r($res);
		//return (int)$res;
		//echo json_encode(array('stock' => $res));
		exit;
	}

	public function fakestock(){
		$records=$this->db->get('bf_pharmacy_products')->result();
		foreach ($records as $key => $value) {
			$data['product_id']=$value->id;
			$data['pharmacy_id']=1;
			$data['quantity_level']=500;

			$this->db->insert('bf_pharmacy_indoor_stock',$data);
		}
	}



}