
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Prescription_sale extends Admin_Controller
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
		$this->load->model('prescription_model', NULL, TRUE);
		$this->load->model('product_model', NULL, TRUE);	
		$this->load->model('hrm/employee_model', NULL, TRUE);
		$this->load->model('category_model', NULL, true);
		$this->lang->load('prescription_sale');
		$this->load->library('pharmacyCommonService');
		Assets::add_module_js('pharmacy', 'prescription_sale.js');
		Template::set_block('sub_nav', 'prescription_sale/_sub_nav');

	}
	/* customer sale */

	public function show_list()
	{
		$data = array();
        $this->auth->restrict('Pharmacy.Pres.Sale.View');
        $id = $this->session->userdata('master_id');
        Template::set('toolbar_title', lang("prescription_pending_list"));
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Requisition No';
        $search_box['ticket_no_flag'] = 1;
        $search_box['contact_no_flag'] = 1;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['patient_id_flag']=1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_name_flag'] = 1;
        $condition=[];
            if(count($_POST)>0){

            if($this->input->post('patient_id')){
                $condition['pmast.patient_id like']='%'.trim($this->input->post('patient_id')).'%';
            }
            if($this->input->post('patient_name')){
                $condition['pmast.patient_name like']='%'.trim($this->input->post('patient_name')).'%';
            }

             if($this->input->post('contact_no')){
                $condition['pmast.contact_no like']='%'.trim($this->input->post('contact_no')).'%';
            }
            if($this->input->post('ticket_no')){
                $condition['pt.receipt_no like']='%'.trim($this->input->post('ticket_no')).'%';
            }
               if($this->input->post('from_date')){
                $from_date = custom_date_format(trim($this->input->post('from_date')));
                $condition['mst.create_date >='] = $from_date." 00:00:00";
               
            }
 
            if($this->input->post('to_date')){
                $to_date = custom_date_format(trim($this->input->post('to_date')));
                $condition['mst.create_date <='] = $to_date." 23:59:59";
            }
            }

        $records = $this->db->select("
        	 SQL_CALC_FOUND_ROWS 
                                mst.*,
                                pmast.patient_name,
                                pmast.patient_id as patient_code,
                                pmast.contact_no,
                                pt.receipt_no,
                                emp.EMP_NAME as doctor_name
                                ",false)
                    ->from('bf_prescription_master as mst')
                    ->join('bf_patient_master as pmast','mst.patient_id=pmast.id')
                    ->join('bf_outdoor_patient_ticket as pt','mst.ticket_id=pt.id')
                    ->join('bf_hrm_ls_employee as emp','emp.EMP_ID=pt.doctor_id')
                    ->where('mst.status !=',1)
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->get()
                    ->result();
              if ($id) {
			$data['print'] = $this->sale_print($id);
			$this->session->unset_userdata('master_id');
		}
        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/prescription_sale/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('prescription_sale/list', compact('records','sl'), true);
            exit;
        }

       /*if ($this->input->is_ajax_request()) {
       	echo $this->load->view('prescription_sale/list', $data);
       	}*/
        Template::set($data);
        $list_view='prescription_sale/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
	}

	public function sale($id = 0)
	{
		$this->auth->restrict('Pharmacy.Pres.Sale.Add');
		$data = array();
		$data['categories'] = $this->db->order_by('category_name','asc')->get('bf_pharmacy_category')->result();
		$data['patient_info'] = $this->get_patient_info($id);
		$data['medicine_info'] = $this->get_medicine_info($id);
		//echo '<pre>';print_r($data);exit;
		Template::set($data);
		Template::set('toolbar_title', lang("sale_new"));
		Template::set_view('prescription_sale/create');
		Template::render();
	}

	private function get_medicine_info($id)
	{
		$result = $this->db->select("
                                mst.id,
                                mst.patient_id,
                                pp.id as medicine_id,
                                pp.product_name,
                                pp.sale_price,
                                s.quantity_level as stock,
                                dtls.roule_id,
                                dtls.duration,
                                pc.category_name
                                ")
                    ->from('bf_prescription_master as mst')
                    ->join('bf_prescription_medicine as dtls','mst.id=dtls.prescription_id')
                    ->join('bf_pharmacy_products as pp','pp.id = dtls.medicine_id')
                    ->join('bf_pharmacy_stock as s','s.product_id = dtls.medicine_id','left')
                    ->join('bf_pharmacy_category as pc','pc.id = pp.category_id')
                    ->where('mst.id', $id)
                    ->get()
                    ->result();
         // echo '<pre>';  print_r($result);exit;
            return $result;
	}

	/*   print    */
	public function prescription_print($id)
	{
		$Obj = new Commonservice();
		if (!$id) {
				echo json_encode(array('success' => false,'message' => $this->pharmacy_sales_mst->error));
			} else {
				$update_data = array('status' => 1);
				$this->db->where('id',$id);
				$sucess = $this->db->update('prescription_master',$update_data);
				$patient_id = $this->db->where('id',$id)->get('prescription_master')->row()->patient_id;
				$Obj->patientHistoryAdd($patient_id, $id, 32);
				if ($sucess) {
					$print = $this->p_print($id);
					echo json_encode(array('success' => true,'print' => $print,'message' => 'Successfully print'));
				} else {
					echo json_encode(array('success' => false,'message' => $this->pharmacy_sales_mst->error));
				}

			}
		exit;
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
                                pt.receipt_no,
                                emp.EMP_NAME as doctor_name
                                ")
                    ->from('bf_prescription_master as mst')
                    ->join('bf_patient_master as pmast','mst.patient_id=pmast.id')
                    ->join('bf_outdoor_patient_ticket as pt','mst.ticket_id=pt.id')
                    ->join('bf_hrm_ls_employee as emp','emp.EMP_ID=pt.doctor_id')
                    ->where('mst.id', $id)
                    ->get()
                    ->row();
        return $row;
	}

	/* sale */

	public function save()
	{
		//echo '<pre>';print_r($_POST);exit;

		$this->auth->restrict('Pharmacy.Pres.Sale.Add');
	
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
	 	$tot_bill = 0;
	 	for ($i = 0; $i < $ncount; $i++)
	 	{
			$tot_normal_discount += $this->input->post('nd_amount',true)[$i];
			$tot_service_discount += $this->input->post('sd_amount',true)[$i];
			$tot_bill += $this->input->post('sd_amount',true)[$i];
		}
		/*$tot_net_bill = ($tot_price -($tot_normal_discount + $tot_less_discount + $tot_service_discount));*/
		$this->db->trans_begin();
		/*   sales master insert  */
		$mst_data = array(
			'sale_no' => $PObj->getSaleNo(1, $this->current_user),
			'patient_id' => $this->input->post('patient_id',true),
			'source_id' => $source_id,
			'tot_bill' => $tot_price,
			'tot_normal_discount' => $tot_normal_discount,
			'tot_service_discount' => $tot_service_discount,
			'tot_less_discount' => $tot_less_discount,
		/*	'tot_net_bill' => $tot_net_bill, */
			'tot_paid' => $tot_paid,
			'tot_due' => $tot_due,
			'created_by' => $this->current_user->id,
			'customer_type' => 2,
			'type' => 3
		);
	//	echo '<pre>';print_r($mst_data);exit;
		$master_id = $this->pharmacy_sales_mst_model->insert($mst_data);

		/*     tranjaction payment         */
		$sale_pay['patient_id']=$mst_data['patient_id'];
		$sale_pay['admission_id']=0;
		$sale_pay['employee_id']=0;
		$sale_pay['customer_id']=0;
		$sale_pay['source_id']=$master_id;
		$sale_pay['due_paid']=$mst_data['tot_paid'];
		$PObj->pharmacyTranjactionPayment($sale_pay,$this->current_user,1,$mst_data['customer_type']);


		/*   sales dtls insert  */
		for ($j = 0; $j < $ncount; $j++){
			$roule_id = $this->input->post('roule_id', true);
			$duration = $this->input->post('duration', true);
			$dtl_data = array(
			'master_id' => $master_id,
			'product_id' => $product_id[$j],
			'roule_id' => isset($roule_id[$j]) ? (int)$roule_id[$j] : 0,
			'duration' => isset($duration[$j]) ? (int)$duration[$j] : 0,
			'qnty' => $qnty[$j],
			'unit_price' => $this->input->post('sale_price', true)[$j],
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

		$patient_id = $this->input->post('patient_id',true);
		$PObj->stock($master_id, $product_id, $qnty, 1, 1, 2, $this->current_user);
		$update_data = array('status' => 2);
		$this->db->where('id',$source_id);
		$this->db->update('prescription_master',$update_data);
		$Obj->patientHistoryAdd($patient_id, $master_id, 31);
		/*
		$DiscoutObj = new Commonservice();
		$discount_arr = array(
			'id' => $master_id,
			'source' => 7,
			'patient_id' => $this->input->post('patient_id',true),
			'service_id' => 2
		);
		$DiscoutObj->allDiscountAdd($discount_arr, $product_id, $sale_price, $this->current_user); */

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
				echo json_encode(array('success' => false,'message' => $this->pharmacy_sales_mst->error));
			} else {
				$this->db->trans_commit();
				//redirect(SITE_AREA .'/prescription_sale/pharmacy/show_list/'.$master_id);
				//$sdata['print'] = $this->sale_print($master_id);

				$sdata['master_id'] = $master_id;
				$this->session->set_userdata($sdata);
				echo json_encode(array('success' => true,'print' => 1,'message' => 'Successfully done'));
			}
		exit;

	}

	/*      sale print      */

	public function sale_print($id)
	{
	  //$sex= $this->config->item('gender_list');
		$data = array();
		$hospital=$this->db->select('lib_hospital.*')->get('lib_hospital')->row();
		//print_r($hospital);
		 $records=$this->db->Select('pharmacy_sales_mst.*,pharmacy_products.product_name,pharmacy_sales_dtls.unit_price,pharmacy_sales_dtls.normal_discount_percent,pharmacy_sales_dtls.normal_discount_taka,pharmacy_products.category_id,pharmacy_sales_dtls.service_discount_percent,pharmacy_sales_dtls.service_discount_taka,pharmacy_sales_dtls.qnty,patient_master.patient_name,patient_master.age,patient_master.contact_no,patient_master.patient_id,pharmacy_category.category_name')
	    ->join('pharmacy_sales_dtls','bf_pharmacy_sales_dtls.master_id=pharmacy_sales_mst.id')
	    ->join('pharmacy_products','pharmacy_products.id=bf_pharmacy_sales_dtls.product_id')
	    ->join('patient_master','patient_master.id=pharmacy_sales_mst.patient_id')
	    ->join('pharmacy_category','pharmacy_category.id=pharmacy_products.category_id')
       // ->join('hrm_ls_employee','hrm_ls_employee.EMP_ID=pharmacy_sales_mst.employee_id')
	    ->where('pharmacy_sales_mst.id',$id)
	    ->get('pharmacy_sales_mst')
	    ->result();
	    $current_user=$this->current_user->username;
	 
	     $sendData = array(
			'records' 		=> $records,
			'hospital' 			=> $hospital,
			'current_user'=>$current_user,
		
		);
	     //print_r($sendData);
		return $this->load->view('prescription_sale/sale_print', $sendData,true);
	}
	/*      sale print      */

	public function p_print($id)
	{
		$data = array();
		$data['id'] = $id;
		return $this->load->view('prescription_sale/prescription_print', $data, true);
	}

	/*   medicine info */
	public function getMedicineInfo($id,$patient_id)
	{
		$data = array();
		$obj = new Commonservice();
		$nDiscountObj = new pharmacyCommonService();
		$data['product_info'] = $this->product_model->find($id);
		$sale_price = $data['product_info']->sale_price;
		$category_id = $data['product_info']->category_id;
		$data['category_info'] = $this->category_model->find($category_id);
		$stock_level = $this->db
							->where('product_id', $id)
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
			// print_r($service_discount);
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

		// $data['n_discount_percent'] = 0;
		// $data['n_discount_amount'] = percent_convert_amount($data['n_discount_percent'],$sale_price);
		// $data['s_discount_percent'] = $obj->patient_discount($patient_id,2,$id);

		// if (!$data['s_discount_percent']->discount) {
		// 	 $data['s_discount_percent'] = 0;
		// } else {
		// 	$data['s_discount_percent'] = $data['s_discount_percent']->discount;
		// }
		
		// $data['s_discount_amount'] = percent_convert_amount($data['s_discount_percent'],$sale_price);
		// //print_r($data['product_info']);exit;


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

}