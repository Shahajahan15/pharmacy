
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Searchpanel {

    private $ci;
    private $search_box = array(
        'patient_id' => '',
        'department_id' => '',
        'admission_id' => '',

        'department_name' => '',
        'requisition_no'=>'',
        'test_name'=>'',
        'lab_name'=>'',
        'pharmacy_name'=>'',
        'Pharmacy_name'=>'',

        'serial_no' => '',
        'token_id' => '',
        'patient_name' => '',
        'category_name'=>'',
        'company_name'=>'',
        'supplier_name'=>'',
        'employee_name'=>'',
        'thana_name'=>'',
        'doctor_id' => '',
        'product_code'=>'',
        'product_name'=>'',
        'store_product_id'=>'',
        'doctor_code' => '',
        'emp_code' => '',
        'bed_short_name' =>'',
        'doctor_full_name' => '',
        'contact_no' => '',
        'ticket_no' => '',
        'bill_no'=>'',
        'price'=>'',
        'sex_list' => ['1' => "Male", '2' => 'Female', '3' => 'Common'],
        'due_paid' => ['1' => "Yes", '2' => 'No'],
        'amount_type' => ['1' => 'Paid', '2' => 'Due Paid', '3' => 'Return'],
        'pharmacy_stock_type' => ['1' => "Stock In ", '2' => 'Stock Out'],
        'main_pharmacy_stock_list' => ['100' => 'Purchase', '1' => "Sale", '2' => 'Issue Send', '3' => 'Sale Return','4' => 'Purchase Return','5' => 'Opening Balance', '6' => 'Requisition Receive', '7' => 'Medicine Replace'],

        'sub_pharmacy_stock_list' => ['100' => "Requisition Receive", '1' => 'Issue Send','2' => "Sale", '3' => 'Sale Return','5' => 'Opening Balance'],

        'report_type' => ['1' => "Single", '2' => 'Multiple', '3' => 'No Report'],

        'pharmacy_customer_type' => ['3' => "Customer",'4' => "Employee"],
        'bed_type_list'=>['1'=>"Cabin",'2'=>'Ward', '3' => 'Operation'],
        'admitted_bed_status'=>['1'=>"Avilable",'2'=>"Booking",'3'=>'Admitted'],
        'room_type_list'=>['1'=>"AC",'2'=>'Non AC'],
        'customer_type_list'=>['1'=>"normal",'2'=>"Employee",'3'=>'Doctor'],
        'doctor_type_list' => ['1' => 'External', '2' => 'Reference', '3' => 'Internal', '4' => 'Surgeon'],
        'payment_type_list' =>['1' =>'Commission Payment','2'=>'Round Payment','3'=>'Operation Payment','4'=>'Report Payment'],
        'referred_doctor_list' => [],
        'doctor_list' => [],
        'store_stock_main_source'=> '',
        'store_stock_dept_source'=> '',
        'store_issue_purchase_select'=> '',
        'pharmacy_stock_source'=> '',
        'sub_pharmacy_stock_source'=> '',
        'sub_pharmacy_id_select'=> '',
        'pharmacy_shelf_id_select'=> '',
        'bed_name'=>'',
        'customer_name'=>'',
        'client_name'=>'',
        'floor_no'=>'',
        'serial_id'=>'',
        'department_name_list'=>[],
        'store_department_name_list'=>[],


        'appointment_type_list' => ['1' => 'General Physician','2' => 'Consultant/Specialist','3' => 'Emergency'],
        'store_category_list' => [],
        'sample_room_list' => [],
        'test_group_list' => [],
        'main_pharmacy_stock_type_list' => [],
        'sub_pharmacy_stock_type_list' => [],
        'store_sub_category_list' => [],
        'pharmacy_list_flag' => [],
        'pharmacy_name_flag' => [],
        'serial_id_flag' => [],
        'store_product_list' => [],
        'store_company_list' => [],
        'store_source_name_list' => ['1' => "Product Return",'2' => "Product Replace",'3' => "Department Issue",'4' => "Purchase Received",'5' => "Opening Balance"],
        'pharmacy_category_list' => [],
        'pharmacy_sub_category_list' => [],
        'pharmacy_product_list' => [],
        'store_supplier_list' => [],
        'store_name_list' => [],
        'pharmacy_shelf_id_select_list' => [],
        'location'=>[],
        'code'=>[],
        'bed_name'=>[],
        
       

        'mr_num'=>[],
        'designation_name' => [],
        'department_name' => [],
        
        'diagnosis_test_list' => [],

        'discount_service_list' => [],
        'department_test_list' => [],
        'designation_list'=>[],

        'pharmacy_supplier_list' => [],
        'pharmacy_company_list' => [],
        'pharmacy_sub_customer_list' => [],
        'sub_customer_name_flag' => 0,
        'store_employee_list' => [],
        'common_text_search' => "",
        'patient_type_list' => [],
        'empType_list' => [],
        'employee_code'=>[],
        'pharmacy_supplier_name'=>[],

        'patient_subtype_list' => [],
        'admission_status_list' => [],
        'admission_discharge_reason_list' => [],
        /*           hrm       */
        'hrm_policy_type_list' => [],
        'shift_list' => [],
        'roster_list' => [],
        

        'from_date' => '',
        'to_date' => '',
        'by_date' => '',
        'limit' => 25,

        'patient_id_flag' => 0,
        'mr_no_flag' => 0,
        'floor_no_flag' =>0,
        'designation_name_flag'=>0,
        'department_name_flag'=>0,
        'designation_list_flag'=>0,
        'bed_name_flag'=>0,
        'bed_short_name_flag'=>0,

        'department_id_flag' => 0,
        'price_flag'=>0,
        'admission_id_flag' =>0,
        'serial_no_flag' => 0,
        'test_name_flag'=>0,
        'lab_name_flag'=>0,
        'sample_room_list_flag'=>0,
        'token_id_flag' => 0,
        'company_name_flag'=>0,
        'category_name_flag'=>0,
        'supplier_name_flag'=>0,
        'pharmacy_supplier_name_flag'=>0,
        'bill_no_flag'=>0,
        'location_flag'=>0,
        'requisition_no_flag'=>0,
        'code_flag'=>0,
        'department_name_flag'=>0,
        'patient_name_flag' => 0,
        'doctor_id_flag' => 0,
        'doctor_code_flag' => 0,
        'doctor_full_name_flag' => 0,
        'ticket_no_flag' => 0,
        'sex_list_flag' => 0,
        'main_pharmacy_stock_list_flag' => 0,
        'sub_pharmacy_stock_list_flag' => 0,
        'pharmacy_stock_type_flag' => 0,
        'admitted_bed_status_flag'=>0,
        'report_type_flag' => 0,
        'test_group_list_flag' =>0,
        'appointment_type_flag' => 0,
        'store_product_list_flag' => 0,
        'sstore_product_list_flag' => 0,
        'product_code_flag'=>0,
        'product_name_flag'=>0,
        'store_product_id_flag'=>0,
        'pharmacy_product_list_flag' => 0,
        'pharmacy_supplier_list_flag'=>0,
        'pharmacy_company_list_flag'=>0,
        'pharmacy_sub_customer_list_flag'=>0,
        'store_supplier_list_flag' => 0,
        'store_employee_list_flag' => 0,
        'patient_type_list_flag' => 0,
        'customer_type_list_flag' => 0,
		'store_name_list_flag' => 0,
        'store_stock_main_sources_flag' => 0,
        'store_stock_dept_sources_flag' => 0,
        'store_issue_purchase_select_flag' => 0,
        'store_source_name_list_flag' => 0,
        'store_company_list_flag' => 0,
        'pharmacy_stock_sources_flag' => 0,
        'sub_pharmacy_stock_sources_flag' => 0,
        'pharmacy_product_id_select_flag' => 0,
        'sub_pharmacy_id_select_flag' => 0,
        'pharmacy_shelf_id_select_flag' => 0,
        'test_department_list_flag'=>0,
        'store_department_name_list_flag'=>0,

        'discount_service_list_flag' => 0,
        'diagnosis_test_list_flag' => 0,
        'empType_list_flag' => 0,
        'employee_code_flag' => 0,
        'pharmacy_customer_type_flag' =>0,
        'client_name_flag' =>0,
        'amount_type_flag' => 0,
        

        'from_date_flag' => 1,
        'cash_users_flag' => 0,
        'to_date_flag' => 1,
        'by_date_flag' => 0,
        'contact_no_flag'=>0,
        'employee_name_flag'=>0,
        'thana_name_flag'=>0,
        'bed_type_list_flag'=>0,
        'room_type_list_flag'=>0,
        'main_pharmacy_stock_type_list_flag' =>0,
        'sub_pharmacy_stock_type_list_flag' =>0,
        'department_test_list_flag'=>0,
        'common_text_search_flag' => 0,
        'per_page_flag' => 1,
        'commission_agent_type_flag'=>0,
        'customer_name_flag'=>0,
        'pharmacy_customer_name_flag'=>0,
        'hrm_policy_type_with_list_flag'=>0,
        'hrm_policy_type_without_list_flag'=>0,
        'shift_list_flag' => 0,
        'roster_list_flag' => 0,
        'common_text_search_label' => 'Text Search',
        'due_paid_flag' => 0,
        'admission_status_list_flag' => 0,
        'admission_discharge_reason_list_flag' => 0,
        'referred_doctor_list_flag' => 0,
        'doctor_type_list_flag' => 0,
        'payment_type_list_flag' => 0,

        'department_name_list_flag' => 0,

        'doctor_list_flag' => 0
    );

    function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->database();
    }

    public function getSearchBox($userObj, $isAll = 1) {
  

        $this->search_box['pharmacy_category_list'] = self:: getPharmacyCategory();
        $this->search_box['pharmacy_sub_category_list'] = self:: getPharmacySubCategoryByCategoryId();
  		
        $this->search_box['pharmacy_supplier_list'] = self:: getPharmacySupplier();
        $this->search_box['pharmacy_company_list'] = self:: getPharmacyCompany();
        $this->search_box['discount_service_list'] = self:: getDiscountService();
        $this->search_box['pharmacy_sub_customer_list'] = self:: getPharmacySubcustomer();
        
       // $this->search_box['main_pharmacy_stock_type_list'] = self:: getMainPharmacyStockTypeList();
        // $this->search_box['sub_pharmacy_stock_type_list'] = self:: getSubPharmacyStockTypeList();
       
        $this->search_box['pharmacy_name'] = self:: getPharmaList();

        
        $this->search_box['cash_users_list'] = self:: getCashUsers();

        $this->search_box['pharmacy_shelf_id_select_list'] = self:: getPharmacyShelfSelect();
        $this->search_box['hrm_policy_type_list'] = self:: getHrmPolicyType();
       

        $this->search_box['admission_status_list'] = self:: getAdmissionStatusList();
        $this->search_box['admission_discharge_reason_list'] = self:: getAdmissionDischargeReasonList();
    

        return $this->search_box;
    }

    public function customDateFormat($date, $format = 'Y-m-d') {
        if ((int) $date <= 0) {
            $date = "";
        } else {
            $date = date_create(str_replace('/', '-', $date));
            $date = date_format($date, $format);
        }

        return $date;
    }
  
   
  
   

    

    
 
    public function getCashUsers() {
        $this->ci->db->select('id,display_name')->where('role_id',10);
        $records = $this->ci->db->get('bf_users')->result();
        if (!$records) {
            return [];
        }

        $cash_users_list = array();
        foreach ($records as $record) {
            $cash_users_list[$record->id] = $record->display_name;
        }

        return $cash_users_list;
    }
  
   
     public function getPharmacyCategory() {
        $this->ci->db->select('id, category_name');
        $records = $this->ci->db->order_by('category_name','asc')->get('pharmacy_category')->result();
        if (!$records) {
            return [];
        }

        $p_category_list = array();
        foreach ($records as $record) {
            $p_category_list[$record->id] = $record->category_name;
        }

        return $p_category_list;
    }
     public function getPharmacySupplier() {
        $this->ci->db->select('id, supplier_name');
        $records = $this->ci->db->order_by('supplier_name','asc')->get('pharmacy_supplier')->result();
        if (!$records) {
            return [];
        }

        $p_supplier_list = array();
        foreach ($records as $record) {
            $p_supplier_list[$record->id] = $record->supplier_name;
        }

        return $p_supplier_list;
    }
 
   public function getPharmacyCompany() {
        $this->ci->db->select('id, company_name');
        $records = $this->ci->db->order_by('company_name','asc')->get('pharmacy_product_company')->result();
        if (!$records) {
            return [];
        }

        $p_company_list = array();
        foreach ($records as $record) {
            $p_company_list[$record->id] = $record->company_name;
        }

        return $p_company_list;
    }

    public function getPharmacySubcustomer() {
        $this->ci->db->select('id, sub_customer_name');
        $records = $this->ci->db->order_by('sub_customer_name','asc')->get('sub_customer')->result();
        if (!$records) {
            return [];
        }

        $p_company_list = array();
        foreach ($records as $record) {
            $p_company_list[$record->id] = $record->sub_customer_name;
        }

        return $p_company_list;
    }

     
     
    
   
     public function getDiscountService() {
        $this->ci->db->select('id, service_name');
        $records = $this->ci->db->get('bf_lib_discount_service_setup')->result();
        if (!$records) {
            return [];
        }

        $service_list = array();
        foreach ($records as $record) {
            $service_list[$record->id] = $record->service_name;
        }

        return $service_list;
    }
    public function getStoreSubCategoryByCategoryId($category_id = 0) {
        $this->ci->db->select('id, subcategory_name');

        $this->ci->db->where('category_id', $category_id);


        $records = $this->ci->db->get('store_subcategory')->result();

        if (!$records) {
            return [];
        }

        $result_list = array();
        foreach ($records as $record) {
            $result_list[$record->id] = $record->subcategory_name;
        }

        return $result_list;
    }
    public function getPatientSubTypeByPatientType($id=0){
        $this->ci->db->select('id,sub_type_name');
        $this->ci->db->where('patient_type_id',$id);
        $records=$this->ci->db->get('bf_lib_patient_sub_type_setup')->result();
        if(!$records){
            return [];
        }
       $result_list=array();
       foreach ($records as $record) {
        $result_list[$record->id]=$record->sub_type_name;
       }
       return $result_list;
    }
     public function getPharmacySubCategoryByCategoryId($category_id = 0) {
        $this->ci->db->select('id, subcategory_name');

        $this->ci->db->where('category_id', $category_id);


        $records = $this->ci->db->get('bf_pharmacy_subcategory')->result();

        if (!$records) {
            return [];
        }

        $result_list = array();
        foreach ($records as $record) {
            $result_list[$record->id] = $record->subcategory_name;
        }

        return $result_list;
    }
     public function getStoreProductBySubCategoryId($sub_category_id = 0) {
        $this->ci->db->select('id, product_name');

        $this->ci->db->where('sub_category_id', $sub_category_id);


        $records = $this->ci->db->get('store_products')->result();

        if (!$records) {
            return [];
        }

        $result_list = array();
        foreach ($records as $record) {
            $result_list[$record->id] = $record->product_name;
        }

        return $result_list;
    }
     public function getStoreProductByCategoryId($category_id = 0) {
        $this->ci->db->select('id, product_name');

        $this->ci->db->where('category_id', $category_id);


        $records = $this->ci->db->get('store_products')->result();

        if (!$records) {
            return [];
        }

        $result_list = array();
        foreach ($records as $record) {
            $result_list[$record->id] = $record->product_name;
        }

        return $result_list;
    }

    public function getPharmacyProductBySubCategoryId($sub_category_id = 0) {
        $this->ci->db->select('id, product_name');

        $this->ci->db->where('sub_category_id', $sub_category_id);


        $records = $this->ci->db->get('pharmacy_products')->result();

        if (!$records) {
            return [];
        }

        $result_list = array();
        foreach ($records as $record) {
            $result_list[$record->id] = $record->product_name;
        }

        return $result_list;
    }

    public function getPharmacyProductByCategoryId($category_id = 0) {
        $this->ci->db->select('id, product_name');

        $this->ci->db->where('category_id', $category_id);


        $records = $this->ci->db->order_by('product_name','asc')->get('pharmacy_products')->result();

        if (!$records) {
            return [];
        }

        $result_list = array();
        foreach ($records as $record) {
            $result_list[$record->id] = $record->product_name;
        }

        return $result_list;
    }

   


    public function getPharmacyShelfSelect() {
        $this->ci->db->select('id,self_name');
        $records= $this->ci->db->get('bf_pharmacy_shelf')->result();
        if(!$records){
            return [];
        }
        $result=array();
        foreach ($records as $record) {
           $result[$record->id]=$record->self_name;
        }
        return $result;
    }

     public function getPharmaList() {
        $this->ci->db->select('id,name');
        $records= $this->ci->db->get('pharmacy_setup')->result();
        if(!$records){
            return [];
        }
        $result=array();
        $result['200']="Main Pharmacy";
        foreach ($records as $record) {
           $result[$record->id]=$record->name;
        }
        return $result;
    }

    public function getAdmissionStatusList()
    {
        $ad_status = [
                    '50' => 'Bed Assign',
                    '1' => 'Bed Booked',    
                    '2' => 'Admitted',
                    '3' => 'Pending Discharge',
                    '4' => 'Discharge',
                    '5' => 'Bed Booked Cancel',
                    '6' => 'Bed Request Release'
                    ];
        return $ad_status;
    }

    public function getAdmissionDischargeReasonList()
    {
        $dis_reason = [
                    '1' => 'Cure',
                    '2' => 'Referred',
                    '3' => 'On Request (DOR)',  
                    '4' => 'On Bonding (DORB)', 
                    '5' => 'Death', 
                    '6' => 'Flee (Absent)',
                    '7' => 'Others'
                    ];
        return $dis_reason;
    }

    /*             hrm                */

    public function getHrmPolicyType() {
        //this->ci->config->load('hrm/config_policy');
        //$policy_name = $this->config->item('policy_name');
        $policy_name = [
                    '1' => 'Leave Policy',
                    '2' => 'Medical Policy',    
                    '3' => 'Absent Policy',
                    '4' => 'Shift Policy',
                    '5' => 'Maternity Policy',
                    '6' => 'Bonus Policy',
                    '7' => 'Roster Policy',
                    '8' => 'Overtime Policy',
                    '9' => 'Provident Fund Policy'
                    ];
        return $policy_name;
    }

    
  

}
