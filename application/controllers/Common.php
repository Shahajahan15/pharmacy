<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common extends Admin_Controller {

     public function __construct()
	{
		parent::__construct();
				
		//$this->load->model('emergency_ticket_model', NULL, TRUE);
		//$this->load->model('pathology/package_master_model', NULL, TRUE);	
		
	}


	public function getProductByKey(){
		$product_name=$this->input->get('product_name');
		$limit=7;
		$result=$this->db->where("product_name LIKE '$product_name%'")->limit($limit)->get('bf_lib_products')->result();

		if(!$result){
			$result=$this->db->where("product_name LIKE '%$product_name%'")->limit($limit)->get('bf_lib_products')->result();
		}


		$re = "<ul class='auto_name_list'>";
		if($result){
			foreach ($result as $row){
			$re .= "<li class='item' id='$row->id'>$row->product_name</li>";
			}
			$re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
		}
		$re .="</ul>";
		echo $re;
	}

	public function getProductById($id)
	{
		
		$data=$this->db->where('id',$id)->get('bf_lib_products')->row();
		$stock=$this->db->select('IFNULL(quantity_level,0) as quantity')
						->where('branch_id',$this->current_user->id)
						->where('product_id',$id)
						->get('bf_store_stock')->row();
		if($stock){
			$data->stock_qntity=$stock->quantity;
		}else{
			$data->stock_qntity=0;
		}
		if($data->is_serial==1){
			$data->serials=$this->db->where('product_id',$id)->where('branch_id',$this->current_user->branch_id)->where('stock_status',1)->get('bf_store_stock_serial_product')->result();
		}else{
			$data->serials=false;
		}

		echo json_encode($data);
	}
	
	public function getMedicineSaleInfoBySaleNo()
	{
		$sale_no = $this->input->get('sale_no');
		$type = $this->input->get('type');
		//print_r($this->current_user);exit;
		$limit=5;
		if ($type == 2) {
			$where = array(
				'pharmacy_id' => $this->current_user->pharmacy_id
			);
			$result=$this->db
					->where($where)
					->where("sale_no LIKE '$sale_no%'")
					->limit($limit)
					->get('pharmacy_indoor_sales_mst')
					->result();

		if(!$result){
			$result=$this->db
						->where($where)
						->where("sale_no LIKE '%$sale_no%'")
						->limit($limit)
						->get('pharmacy_indoor_sales_mst')
						->result();
		}
		} else {
			$result=$this->db->where("sale_no LIKE '$sale_no%'")->limit($limit)->get('pharmacy_sales_mst')->result();

		if(!$result){
			$result=$this->db->where("sale_no LIKE '%$sale_no%'")->limit($limit)->get('pharmacy_sales_mst')->result();
		}
		}


		$re = "<ul class='auto_name_list'>";
		if($result){
			foreach ($result as $row){
			$re .= "<li class='sale_no' id='$row->id'>$row->sale_no</li>";
			}
			$re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
		}
		$re .="</ul>";
		echo $re;
	}
	
	
	
	
	
	public function getProductinfoById($id=0){
        $record=$this->db->select("
                        bf_lib_products.id,
                        bf_lib_products.product_name,
                        bf_lib_subcategory.subcategory_name,
                        bf_lib_subcategory.id as sub_cat_id,
                        bf_lib_category.category_name,
                        bf_lib_category.id as cat_id,
                    ")                
                ->join('bf_lib_subcategory','bf_lib_subcategory.id=bf_lib_products.sub_category_id','left')
                ->join('bf_lib_category','bf_lib_category.id=bf_lib_subcategory.category_id','left')
                ->where('bf_lib_products.id',$id)
                ->get('bf_lib_products')
                ->row();
         $current_stock=$this->db->where('product_id',$id)->where('branch_id',$this->current_user->branch_id)->get('bf_store_stock')->row();
         
         if($current_stock){
         	$record->current_stock=$current_stock->quantity_level;
         }else{
         	$record->current_stock=0;
         }
        echo json_encode($record);
    }
    public function getSubCategoryByCategoryId($category_id=0){

        $records=$this->db->where('category_id',$category_id)->get('bf_lib_subcategory')->result();
       

            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->subcategory_name.'</option>';
        }
        echo $options;
    }

    public function getProductBySubCategoryId($sub_cat_id=0){
        $records=$this->db->where('sub_category_id',$sub_cat_id)->get('bf_lib_products')->result();
       

            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->product_name.'</option>';
        }
        echo $options;
    }

    public function getProductStock($pur_id = 0)
    {
		$row = $this->db->where('product_id',$pur_id)->where('branch_id')->get('bf_store_stock')->row();
		$stock = 0;
		if($row) {
			$stock = $row->quantity_level;
		}
		echo $stock;exit;
	}

	public function getCustomerInfoById($id){
		$record=$this->db->where('id',$id)->get('bf_lib_customer')->row();
		echo json_encode($record);
	}


	public function getCustomerByKey()
	{
		$this->load->model('library/customer_model');
							
		if ($query = $this->input->get('q')) {
			
			$condition = "customer_name LIKE '".$query."%' OR customer_mobile LIKE '%".$query."%'";
			
			
			$customers = $this->db->where($condition)->limit(50)->get('bf_lib_customer')->result();
			
			
			
			$items = array();
			$assoc = array();
			foreach($customers as $customer) {
				$items[] = array(
					'id' => $customer->id,	
					'text' =>$customer->customer_name,
				);
				
				$assoc[$customer->id] = $customer;
			}
			
			$status_code = 200;
			$json = array(
				'items' => $items,	
				'assoc' => $assoc,
			);
			
		} else {
			
			$status_code = 422;
			$json = array(
				'error' => 'Required Parameters not found.',	
			);
			
		}
		
		return $this->output->set_status_header($status_code)
							->set_content_type('application/json')
							->set_output(json_encode($json));
	}

	public function getProductsByKey()
	{
		
							
		if ($query = $this->input->get('q')) {
			
			$condition = "product_name LIKE '".$query."%'";
			$limit=12;
			
			
			$results=$this->db->where($condition)->limit($limit)->get('bf_lib_products')->result();
			
			
			
			$items = array();
			$assoc = array();
			foreach($results as $result) {
				$items[] = array(
					'id' => $result->id,	
					'text' =>$result->product_name,
				);
				
				$assoc[$result->id] = $result;
			}
			
			$status_code = 200;
			$json = array(
				'items' => $items,	
				'assoc' => $assoc,
			);
			
		} else {
			
			$status_code = 422;
			$json = array(
				'error' => 'Required Parameters not found.',	
			);
			
		}
		
		return $this->output->set_status_header($status_code)
							->set_content_type('application/json')
							->set_output(json_encode($json));
	}

	public function getEmployeeByKey()
	{
							
		if ($query = $this->input->get('q')) {
			
			$condition = "EMP_NAME LIKE '".$query."%' OR EMP_CODE LIKE '%".$query."%'";
			
			
			$employees = $this->db->where($condition)->limit(50)->get('bf_hrm_ls_employee')->result();

			
			
			
			$items = array();
			$assoc = array();
			foreach($employees as $employee) {
				$items[] = array(
					'id' => $employee->EMP_ID,	
					'text' =>$employee->EMP_NAME,
				);
				
				$assoc[$employee->EMP_ID] = $employee;
			}
			
			$status_code = 200;
			$json = array(
				'items' => $items,	
				'assoc' => $assoc,
			);
			
		} else {
			
			$status_code = 422;
			$json = array(
				'error' => 'Required Parameters not found.',	
			);
			
		}
		
		return $this->output->set_status_header($status_code)
							->set_content_type('application/json')
							->set_output(json_encode($json));
	}
	public function getEmployeeById($id){
		$employee = $this->db->select("
							bf_hrm_ls_employee.EMP_ID as id,
							bf_hrm_ls_employee.EMP_NAME as emp_name,
							bf_hrm_ls_employee.EMP_FATHER_NAME as father_name,
							bf_hrm_ls_employee.EMP_MOTHER_NAME as mother_name,
							bf_hrm_ls_employee.MOBILE_NUM as mobile,
							bf_lib_department.department_name,
							bf_lib_designation_info.designation_name,
							")
						->join('bf_lib_department','bf_lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT')
						->join('bf_lib_designation_info','bf_lib_designation_info.DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION')
						->where('EMP_ID',$id)
						->get('bf_hrm_ls_employee')
						->row();
		echo json_encode($employee);
	}


	public function getDayDiff(){
		$date1=$this->input->post('date1');
		$date2=$this->input->post('date2');
		if($this->input->is_ajax_request()){
			echo dateDiffDay($date1,$date2);
			exit;
		}
		return dateDiffDay($date1,$date2);
	}


}