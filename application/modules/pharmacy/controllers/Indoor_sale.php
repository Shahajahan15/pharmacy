<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Indoor_sale extends Admin_Controller
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

		$this->load->model('pharmacy_sales_dtls_model', NULL, TRUE);
		$this->load->model('pharmacy_sales_mst_model', NULL, TRUE);
		$this->load->model('patient/admission_medicine_mst_model', NULL, TRUE);
		$this->load->model('product_model', NULL, TRUE);
		$this->load->model('customer_model', NULL, TRUE);
		$this->load->model('category_model', NULL, true);
		$this->load->library('pharmacyCommonService');
		$this->lang->load('indoor_sale');
		Assets::add_module_js('pharmacy', 'indoor_sale.js');
		Template::set_block('sub_nav', 'indoor_sale/_sub_nav');

	}

	public function test()
	{
		exit;
		/*$result = $this->db
				//->select('COUNT(*) as count')
				->group_by('category_id')
				->get('pharmacy_products_temp')->result();
		/*$result1 = $this->db
				->select('COUNT(*) as count')
				->group_by('company_id')
				->get('pharmacy_products_temp1')->result(); */
		//echo '<pre>';print_r($result);
		//echo '<pre>';print_r($result);exit;
		/*$k = 0;
		foreach ($result as $key => $val) {
			if ($val->category_id) {
				$k = $k+1;
				$arr = array(
				'id' => $k,
				'category_name' => $val->category_id,
				'status' => 1,
				'created_by' => 1,
				'created_date' => date('Y-m-d')
				);
			$this->db->insert('pharmacy_category', $arr);
			}
		}  */

		$result = $this->db
				->select('id, category_name')
				->get('pharmacy_category')->result();

		//echo '<pre>';print_r($result);exit;

		foreach ($result as $key => $val) {
			$data['category_id'] = $val->id;
			$this->db->where('TRIM(category_id)', trim($val->category_name));
			$this->db->update('pharmacy_products_temp1', $data);
		} 
	}
	/* customer sale */

	public function show_list()
	{
		$data = array();
        $this->auth->restrict('Pharmacy.Indoor.Sale.View');
        $id = $this->session->userdata('master_id');
        Template::set('toolbar_title', lang("indoor_sale_pending_list"));
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Admission ID';
        $search_box['ticket_no_flag'] = 0;
        $search_box['contact_no_flag'] = 1;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['patient_id_flag']=1;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['patient_name_flag'] = 1;
        $condition['mst.sale_pending']=0;
            if(count($_POST)>0){

            if($this->input->post('patient_id')){
                $condition['pmast.patient_id']=$this->input->post('patient_id');
            }
            if($this->input->post('patient_name')){
                $condition['pmast.patient_name']=$this->input->post('patient_name');
            }
              if($this->input->post('contact_no')){
                $condition['pmast.contact_no']=$this->input->post('contact_no');
            }
             if($this->input->post('common_text_search')){
                $condition['ap.admission_code']=$this->input->post('common_text_search');
            }
            }
        $records = $this->db->select("
        	                    SQL_CALC_FOUND_ROWS
                                mst.*,
                                pmast.patient_name,
                                pmast.patient_id as patient_code,
                                pmast.contact_no,
                                ap.admission_code
                                ",false)
                    ->from('bf_admission_medicine_mst as mst')
                    ->join('bf_patient_master as pmast','mst.patient_id=pmast.id','left')
                   	->join('bf_admission_patient as ap','mst.admission_id=ap.id','left')
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->get()
                    ->result();
        // echo '<pre>';print_r($condition);die();
        if ($id) {
			$data['print'] = $this->sale_print($id);
			$this->session->unset_userdata('master_id');
		}
        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/indoor_sale/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('indoor_sale/list', compact('records','sl'), true);
            exit;
        }
       //echo '<pre>'; print_r($data['records']);exit;
        Template::set($data);
        $list_view='indoor_sale/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
	}

	public function sale($id = 0)
	{
		$this->auth->restrict('Pharmacy.Indoor.Sale.Add');
		$data = array();
		$data['categories'] = $this->db->order_by('category_name','asc')->get('bf_pharmacy_category')->result();
		$data['patient_info'] = $this->get_patient_info($id);
		$data['medicine_info'] = $this->get_medicine_info($id);
		//echo '<pre>';print_r($data['medicine_info']);exit;
		Template::set($data);
		Template::set('toolbar_title', lang("sale_new"));
		Template::set_view('indoor_sale/create');
		Template::render();
	}

	private function get_medicine_info($id)
	{
		$result = $this->db->select("
                                mst.*,
                                dtls.qnty,
                                dtls.id as did,
                                pp.id as medicine_id,
                                pp.product_name,
                                pp.sale_price,
                                s.quantity_level as stock,
                                pc.category_name
                                ")
                    ->from('bf_admission_medicine_mst as mst')
                    ->join('bf_admission_medicine_dtls as dtls','mst.id=dtls.master_id')
                    ->join('bf_pharmacy_products as pp','pp.id = dtls.medicine_id')
                    ->join('bf_pharmacy_stock as s','s.product_id = dtls.medicine_id','left')
                    ->join('bf_pharmacy_category as pc','pc.id = pp.category_id')
                    ->where('mst.id', $id)
                    ->order_by('dtls.id')
                    ->get()
                    ->result();
            return $result;
	}

	private function get_patient_info($id)
	{
		$row = $this->db->select("
                                mst.*,
                                pmast.patient_name,
                                pmast.patient_id as patient_code,
                                pmast.contact_no,
                                pmast.sex,
                                pmast.birthday,
                                ap.admission_code
                                ")
                    ->from('bf_admission_medicine_mst as mst')
                    ->join('bf_admission_medicine_dtls as dtls','mst.id=dtls.master_id')
                    ->join('bf_patient_master as pmast','mst.patient_id=pmast.id')
                    ->join('bf_admission_patient as ap','mst.admission_id=ap.id')
                    ->where('mst.id', $id)
                    ->get()
                    ->row();
        return $row;
	}

	/* sale */

	public function save()
	{
		//echo '<pre>';print_r($_POST);exit;
		$this->auth->restrict('Pharmacy.Indoor.Sale.Add');
		$PObj = new pharmacyCommonService();
		$Obj = new Commonservice();
	 	/*  tot normal discount    */
	 	$ncount = count($this->input->post('product_id',true));
	 	$tot_less_discount = $this->input->post('pharmacy_total_less_discount',true);
	 	$tot_paid = $this->input->post('pharmacy_total_paid',true);
	 	$tot_price = $this->input->post('pharmacy_total_price',true);
	 	$tot_due = $this->input->post('pharmacy_total_due',true);
	 	$customer_type = $this->input->post('customer_type',true);
	 	$product_id = $this->input->post('product_id', true);
	 	$qnty = $this->input->post('qnty', true);
	 	$source_id = $this->input->post('master_id');
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
		$tot_net_bill = ($tot_price -($tot_normal_discount + $tot_less_discount + $tot_service_discount));
		$this->db->trans_begin();
		/*   sales master insert  */
		$mst_data = array(
			'sale_no' => $PObj->getSaleNo(1, $this->current_user),
			'admission_id' => $this->input->post('admission_id',true),
			'cost_paid_by' => $this->input->post('cost_paid_by',true),
			'source_id' => $source_id,
			'tot_bill' => $tot_price,
			'tot_normal_discount' => $tot_normal_discount,
			'tot_service_discount' => $tot_service_discount,
			'tot_less_discount' => $tot_less_discount,
			'tot_paid' => $tot_paid,
			'tot_due' => $tot_due,
			'created_by' => $this->current_user->id,
			'customer_type' => ($this->input->post('cost_paid_by',true) == 1) ?: 6,
			'type' => 1
		);
		//echo '<pre>';print_r($mst_data);exit;
		$master_id = $this->pharmacy_sales_mst_model->insert($mst_data);

		/*     tranjaction payment         */

		

		//$PObj->pharmacyTranjactionPayment($tot_paid, $master_id, $this->current_user);

		$sale_pay['patient_id']=0;
		$sale_pay['admission_id']=$mst_data['admission_id'];
		$sale_pay['employee_id']=0;
		$sale_pay['customer_id']=0;
		$sale_pay['source_id']=$master_id;
		$sale_pay['due_paid']=$mst_data['tot_paid'];
		$PObj->pharmacyTranjactionPayment($sale_pay,$this->current_user,1,$mst_data['customer_type']);

		/*   sales dtls insert  */
		for ($j = 0; $j < $ncount; $j++){
			$dtl_data = array(
			'master_id' => $master_id,
			'product_id' => $product_id[$j],
			'qnty' => $qnty[$j],
			'unit_price' => $sale_price[$j],
			'normal_discount_percent' => $this->input->post('nd_percent', true)[$j],
			'normal_discount_taka' => $this->input->post('nd_amount', true)[$j],
			'service_discount_percent' => $this->input->post('sd_percent', true)[$j],
			'service_discount_taka' => $this->input->post('sd_amount', true)[$j],
			'n_discount_id' => $this->input->post('nd_discount_id', true)[$j],
			'n_discount_type' => $this->input->post('nd_discount_type', true)[$j],
			's_discount_id' => $this->input->post('sd_discount_id', true)[$j],
			's_discount_type' => $this->input->post('sd_discount_type', true)[$j]
		);
			$this->pharmacy_sales_dtls_model->insert($dtl_data);
		}

		/*      pharmacy stock  */

		$PObj->stock($master_id, $product_id, $qnty, 1, 1, 2, $this->current_user);
		$update_data = array('sale_pending' => 1);
		$this->admission_medicine_mst_model->update($source_id,$update_data);

		/*    admission patient   */
		if ($this->input->post('cost_paid_by',true) == 1) {
			$admission_data = array(
					'admission_id' => $this->input->post('admission_id',true),
					'source_id' => $master_id,
					'amount' => $tot_price,
					'source_type' => 5,
					'discount' => ($tot_normal_discount + $tot_service_discount),
					'less_discount' => $tot_less_discount,
					'paid' => $tot_paid,
					'paid_type' => 1,
				);
			$Obj->admissionPatientTransaction($admission_data, $this->current_user);
		}
		$patient_id = $this->db
						->where('id',$this->input->post('admission_id',true))
						->get('bf_admission_patient')
						->row()
						->patient_id;
		$discount_arr = array(
			'id' => $master_id,
			'source' => 7,
			'patient_id' => $patient_id,
			'service_id' => 2
		);
		 $Obj->patientHistoryAdd($patient_id, $master_id, 30);
		//$DiscoutObj->allDiscountAdd($discount_arr, $product_id, $sale_price, $qnty, 2);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
				echo json_encode(array('success' => false,'message' => $this->pharmacy_sales_mst->error));
			} else {
				$this->db->trans_commit();
				//$print = $this->sale_print($master_id);
				$sdata['master_id'] = $master_id;
				$this->session->set_userdata($sdata);
				echo json_encode(array('success' => true,'print' => 1,'message' => 'Successfully done'));
			}
		exit;

	}

	/*      sale print      */

	public function sale_print($id)
	{
		$data = array();
		$data['hospital']=$this->db->select('lib_hospital.*')->get('lib_hospital')->row();
        $data['current_user']=$this->current_user->username;
		$data['records']=$this->db->select('bf_pharmacy_sales_mst.*,bf_pharmacy_products.product_name,bf_pharmacy_sales_dtls.unit_price,bf_pharmacy_products.category_id,bf_pharmacy_sales_dtls.qnty,bf_pharmacy_sales_dtls.normal_discount_percent,bf_pharmacy_sales_dtls.normal_discount_taka,bf_pharmacy_sales_dtls.service_discount_percent,bf_pharmacy_sales_dtls.service_discount_taka,bf_pharmacy_category.category_name')
		->join('bf_pharmacy_sales_dtls','bf_pharmacy_sales_dtls.master_id=bf_pharmacy_sales_mst.id')
		->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_sales_dtls.product_id')
		->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id')
		->where('bf_pharmacy_sales_mst.id',$id)
		->get('bf_pharmacy_sales_mst')
		->result();
		// echo "<pre>";
		// print_r($data['records']);exit();
		$data['patient_info'] = currentAdmissionPatientBedInfo($data['records'][0]->admission_id);
		// echo "<pre>";
		// print_r($data['patient_info']);exit();
		return $this->load->view('indoor_sale/sale_print', $data, true);
	}

	/*   medicine info */
	public function getMedicineInfo($id,$patient_id)
	{
		$data = array();
		$obj = new Commonservice();
		$nDiscountObj = new pharmacyCommonService();
		$data['product_info'] = $this->product_model->find($id);
		$sale_price = $data['product_info']->sale_price;
		$medicine_id = $data['product_info']->id;
		$category_id = $data['product_info']->category_id;
		$data['category_info'] = $this->category_model->find($category_id);
		$stock_level = $this->db
							->where('product_id', $medicine_id)
							->get('pharmacy_stock')
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
			$service_discount = $obj->patient_discount($patient_id,2,$id);
			if($service_discount) {
			$data['s_discount_percent'] = $service_discount->discount;
			$data['s_discount_amount'] =percent_convert_amount($service_discount->discount,$sale_price);
			$data['s_discount_id']=$service_discount->id;
			$data['s_discount_type']=3;
		     }
		     else {
		     	     $data['s_discount_percent']=0;
		     	     $data['s_discount_amount']=0;
		     	     $data['s_discount_id']=0;
		     	     $data['s_discount_type']=0;
		          }
		    }
		
		// $data['s_discount_percent'] = $obj->patient_discount(0,2,$id);
		// $data['s_discount_amount'] = percent_convert_amount($data['s_discount_percent'],$sale_price);


		// $data['n_discount_percent'] = $nDiscountObj->discount_product($medicine_id, 0, 1);
		// $data['n_discount_amount'] = percent_convert_amount($data['n_discount_percent'],$sale_price);
		// $data['s_discount_percent'] = $obj->patient_discount($patient_id,2,$id);
		// if(!$data['s_discount_percent']){
  //       	$data['s_discount_percent'] = 0;
		// }else{
		// 	$data['s_discount_percent'] = $data['s_discount_percent']->discount;
		// }

		// $data['s_discount_amount'] = percent_convert_amount($data['s_discount_percent'],$sale_price);
		//print_r($data['product_info']);exit;
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

}