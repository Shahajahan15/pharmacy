<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Searchpanel {

    private $ci;
    private $search_box = array(
        'patient_id' => '',
        'serial_no' => '',
        'token_id' => '',
        'patient_name' => '',
        'doctor_id' => '',
        'doctor_code' => '',
        'doctor_full_name' => '',
        'ticket_no' => '',
        'sex_list' => ['1' => "Male", '2' => 'Female', '3' => 'Common'],
        'appointment_type_list' => ['1' => 'General Physician','2' => 'Consultant/Specialist','3' => 'Emergency'],
        'store_category_list' => [],
        'store_sub_category_list' => [],
        'store_product_list' => [],
        'pharmacy_category_list' => [],
        'pharmacy_sub_category_list' => [],
        'pharmacy_product_list' => [],
        'store_supplier_list' => [],
        'pharmacy_name' => [],

        'pharmacy_supplier_list' => [],
        'pharmacy_company_list' => [],
        'pharmacy_sub_customer_list' => [],
        'store_employee_list' => [],
        'common_text_search' => "",
        'patient_type_list' => [],
        'patient_subtype_list' => [],

        'from_date' => '',
        'to_date' => '',
        'by_date' => '',
        'limit' => 25,        
        'patient_id_flag' => 0,
        'serial_no_flag' => 0,
        'token_id_flag' => 0,
        'patient_name_flag' => 0,
        'doctor_id_flag' => 0,
        'doctor_code_flag' => 0,
        'doctor_full_name_flag' => 0,
        'ticket_no_flag' => 0,
        'sex_list_flag' => 0,
        'appointment_type_flag' => 0,
        'store_product_list_flag' => 0,
        'pharmacy_product_list_flag' => 0,
        'pharmacy_supplier_list_flag'=>0,
        'pharmacy_list_flag' =>0,

        'pharmacy_sub_customer_list_flag'=>0,
        'pharmacy_company_list_flag'=>0,
        'store_supplier_list_flag' => 0,
        'store_employee_list_flag' => 0,
        'patient_type_list_flag' => 0,
        'cash_users_flag' => 0,
        //'patient_subtype_list_flag' => 0,
        'from_date_flag' => 1,
        'to_date_flag' => 1,
        'by_date_flag' => 0,
        'commission_agent_type_flag'=>0,
        'pharmacy_shelf_id_select_flag' => 0,
        'pharmacy_list_flag' => 0,
        
        'common_text_search_flag' => 1,
        'common_text_search_label' => 'Text Search',
        
    );

    function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->database();        
    }

    public function getSearchBox($userObj, $isAll = 1) {
        $this->search_box['patient_type_list']=self:: getPatientType();
        $this->search_box['pharmacy_name']=self:: getPharmacyList();
  		$this->search_box['store_category_list'] = self:: getStoreCategory();
        $this->search_box['pharmacy_category_list'] = self:: getPharmacyCategory();
        $this->search_box['pharmacy_sub_category_list'] = self:: getPharmacySubCategoryByCategoryId();
  		$this->search_box['store_supplier_list'] = self:: getStoreSupplier();
        $this->search_box['pharmacy_supplier_list'] = self:: getPharmacySupplier();
        $this->search_box['pharmacy_company_list'] = self:: getPharmacyCompany();
        $this->search_box['pharmacy_sub_customer_list'] = self:: getPharmacySubcustomer();

        $this->search_box['store_employee_list'] = self:: getStoreEmployee();



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
    
    public function getStoreCategory() {
        $this->ci->db->select('id, category_name');
        $records = $this->ci->db->get('store_category')->result();
        if (!$records) {
            return [];
        }

        $p_category_list = array();
        foreach ($records as $record) {
            $p_category_list[$record->id] = $record->category_name;
        }

        return $p_category_list;
    }
    public function getPatientType(){
        $this->ci->db->select('id,type_name');
        $records= $this->ci->db->get('bf_lib_patient_type_setup')->result();
        if(!$records){
            return [];
        }
        $result=array();
        foreach ($records as $record) {
           $result[$record->id]=$record->type_name;
        }
        return $result;
    }
     public function getPharmacyCategory() {
        $this->ci->db->select('id, category_name');
        $records = $this->ci->db->get('pharmacy_category')->result();
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
        $records = $this->ci->db->get('pharmacy_supplier')->result();
        if (!$records) {
            return [];
        }

        $p_supplier_list = array();
        foreach ($records as $record) {
            $p_supplier_list[$record->id] = $record->supplier_name;
        }

        return $p_supplier_list;
    }
   public function getPharmacySubcustomer() {
        $this->ci->db->select('id, sub_customer_name');
        $records = $this->ci->db->get('sub_customer')->result();
        if (!$records) {
            return [];
        }

        $p_sub_customer_list = array();
        foreach ($records as $record) {
            $p_sub_customer_list[$record->id] = $record->sub_customer_name;
        }

        return $p_sub_customer_list;
    }

    public function getPharmacyCompany() {
        $this->ci->db->select('id, company_name');
        $records = $this->ci->db->get('pharmacy_product_company')->result();
        if (!$records) {
            return [];
        }

        $p_company_list = array();
        foreach ($records as $record) {
            $p_company_list[$record->id] = $record->company_name;
        }

        return $p_company_list;
    }

  public function getPharmacyList() {
        $this->ci->db->select('id, name');
        $records = $this->ci->db->get('pharmacy_setup')->result();
        if (!$records) {
            return [];
        }

        $p_pharmacy_list = array();
        foreach ($records as $record) {
            $p_pharmacy_list[$record->id] = $record->name;
        }

        return $p_pharmacy_list;
    }
  
     public function getStoreSupplier() {
        $this->ci->db->select('SUPPLIER_ID as id, SUPPLIER_NAME as supplier_name');
        $records = $this->ci->db->get('bf_store_supplier_mst')->result();
        if (!$records) {
            return [];
        }

        $p_supplier_list = array();
        foreach ($records as $record) {
            $p_supplier_list[$record->id] = $record->supplier_name;
        }

        return $p_supplier_list;
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
     public function getStoreEmployee() {
        $this->ci->db->select('EMP_ID as id, EMP_NAME as emp_name');
        $records = $this->ci->db->get('hrm_ls_employee')->result();
        if (!$records) {
            return [];
        }

        $p_employee_list = array();
        foreach ($records as $record) {
            $p_employee_list[$record->id] = $record->emp_name;
        }

        return $p_employee_list;
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
    public function getPharmacyList() {
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

}
