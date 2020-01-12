<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        //$this->load->model('emergency_ticket_model', NULL, TRUE);
       // $this->load->model('pathology/package_master_model', NULL, TRUE);

    }

    /**
     * created by aminur
     * created date 25-01-2017
     * @return name
     * @parm name
     */
    public function getAutoNameAjax()
    {
        $auto_name = $this->input->post('auto_name', true);
        $width = $this->input->post('width', true);
        $type = $this->input->post('type', true);
        if ($type == 1):
            $result = $this->db
                ->select('patient_name')
                ->like('patient_name', $auto_name)
                ->distinct('patient_name')
                ->limit(6)
                ->get('bf_patient_master')
                ->result();
            $re = "";
            $re .= "<ul class='auto_name_list' style='width:" . $width . "px'>";
            if ($result) {
                foreach ($result as $row) {
                    $re .= "<li onClick='selectAutoPatientName(" . '"' . $row->patient_name . '"' . ")'>$row->patient_name</li>";
                }
                $re .= "<li class='c-remove patient-name-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
            }
            $re .= "</ul>";
            echo $re;
        elseif ($type == 2) :
            $result = $this->getDoctorList($auto_name);
            /*$result = $this->db
            ->select('EMP_ID,EMP_NAME')
            ->like('EMP_NAME', $auto_name)
            ->where('EMP_TYPE',1)
            ->limit(6)
            ->get('bf_hrm_ls_employee')
            ->result();*/
            $re = "";
            $re .= "<ul class='auto_name_list' style='width:" . $width . "px'>";
            if ($result) {
                foreach ($result as $row) {
                    $name = $row->code . "-" . $row->designation . "." . $row->doctor_name . "(" . trim($row->qualification) . ")";
                    $re .= "<li onClick='selectAutoDoctorName(" . $row->id . "," . '"' . $name . '"' . ")'>$name</li>";
                }
                $re .= "<li class='c-remove doctor-name-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
            }

            $re .= "</ul>";
            echo $re;
        endif;

    }

    private function getDoctorList($auto_name)
    {
        $result = $this->db
            ->select('emp.EMP_ID as id,degi.DESIGNATION_NAME as designation,emp.EMP_NAME as doctor_name,emp.QUALIFICATION as qualification,CODE as code')
            ->from('hrm_ls_employee as emp')
            ->join('lib_designation_info as degi', 'emp.EMP_DESIGNATION	= degi.DESIGNATION_ID')
            ->where('emp.EMP_TYPE', 1)
            ->like('EMP_NAME', $auto_name)
            ->limit(6)
            ->get()
            ->result();
        return $result;
    }

    /**
     * purpose get all patient code
     * created by aminur
     * created date 25-01-2017
     * @return name
     * @parm name
     */
    public function getPatientAjax()
    {
        $patient_id = $this->input->post('patient_id', true);
        $width = $this->input->post('width', true);
        $type = $this->input->post('type', true);
        $result = $this->db
            ->select('patient_id,id,patient_name')
            ->like('patient_id', $patient_id)
            ->limit(6)
            ->get('bf_patient_master')
            ->result();
        $re = "";
        $re .= "<ul class='auto_name_list' style='width:" . $width . "px'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= '<li class="patient_show" id="' . $row->id . '">' . $row->patient_id . '&nbsp;->&nbsp;' . $row->patient_name . '</li>';
            }
            $re .= "<li class='c-remove patient-search-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }

    /**
     * purpose get all admission code
     * created by aminur
     * created date 06-02-2017
     * @return admission code
     * @parm admission code name
     */
    public function getAddmissionCodeAjax()
    {
        $admission_auto_name = $this->input->post('admission_auto_name', true);
        $result = $this->db
            ->select('id,admission_code')
            ->like('admission_code', $admission_auto_name)
            ->where('release_date is NULL')
            ->where_in('status', [2, 3, 4])
            ->get('admission_patient')
            ->result();
        //	echo '<pre>';print_r($result);exit;
        $re = "";
        $re .= "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='admission_show' id='$row->id'>$row->admission_code</li>";
            }
            $re .= "<li class='c-remove admission-search-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }

        $re .= "</ul>";
        echo $re;
    }

    /**
     * purpose get all doctor code
     * created by aminur
     * created date 28-01-2017
     * @return doctor code
     * @parm doctor name
     */

    public function getDoctorAjax()
    {
        $doctor_id = $this->input->post('doctor_id', true);
        $width = $this->input->post('width', true);
        $type = $this->input->post('type', true);
        if ($type == 3) {
            $search ['TRIM(day_name)'] = date('l');
        }

        $result = $this->db
            ->select('doctor_code,doctor_id')
            ->like('doctor_code', $doctor_id, 'after')
            ->where($search)
            ->join('doctor_time_schedule', 'doctor_time_schedule.doctor_info_id = doctor_doctor_info.id')
            ->group_by('doctor_time_schedule.doctor_info_id')
            ->get('doctor_doctor_info')
            ->result();
        $re = "";
        $re .= "<ul class='auto_name_list' style='width:" . $width . "px'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='doctor_show' id='$row->doctor_id'>$row->doctor_code</li>";
            }
            $re .= "<li class='c-remove doctor-search-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }

        $re .= "</ul>";
        echo $re;
    }

    /**
     * purpose get all reference
     * created by aminur
     * created date 22-01-2017
     * @return reference id
     * @parm null
     */
    public function getReferenceNameAjax()
    {
        $auto_name = $this->input->post('auto_name', true);
        $width = $this->input->post('width', true);

        $result = $this->db
            ->select('ref_name,ref_quali,id')
            ->like('ref_name', $auto_name)
            ->get('lib_reference')
            ->result();
        $re = "";
        $re .= "<ul class='auto_name_list' style='width:" . $width . "px'>";
        if ($result) {
            foreach ($result as $row) {
                $ref_name = $row->ref_name . "(" . $row->ref_quali . ")";
                $re .= "<li onClick='selectAutoReferenceName(" . $row->id . "," . '"' . $ref_name . '"' . ")'>$ref_name</li>";
            }
            $re .= "<li class='c-remove reference-name-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }

    public function getTestNameSuggestion()
    {
        $test_name = $this->input->get('test_name');
        $limit = 7;
        $result = $this->db->where("test_name LIKE '$test_name%'")->limit($limit)->get('bf_pathology_test_name')->result();

        if (!$result) {
            $result = $this->db->where("test_name LIKE '%$test_name%'")->limit($limit)->get('bf_pathology_test_name')->result();
        }


        $re = "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='search-test-item' id='$row->id'  price='$row->test_taka'>$row->test_name</li>";
            }
            $re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }

    public function getDoctorNameList($doctor_type = 0)
    {
        if ($doctor_type == 1) {
            $records = $this->db->select('
						bf_hrm_ls_employee.EMP_ID as id,
						bf_hrm_ls_employee.EMP_NAME as agent
					')
                ->where('EMP_TYPE', 1)
                ->where('IS_EXTERNAL', 1)
                ->get('bf_hrm_ls_employee')
                ->result();
        } elseif ($doctor_type == 2) {
            $records = $this->db->select('
						bf_lib_reference.id as id,
						bf_lib_reference.ref_name as agent
					')
                ->get('bf_lib_reference')
                ->result();
        } elseif ($doctor_type == 3) {
            $records = $this->db->select('
						bf_hrm_ls_employee.EMP_ID as id,
						bf_hrm_ls_employee.EMP_NAME as agent
					')
                ->where('EMP_TYPE', 1)
                ->where('IS_EXTERNAL', 0)
                ->get('bf_hrm_ls_employee')
                ->result();
        }

        $options = '';
        foreach ($records as $record) {
            $options .= '<option value="' . $record->id . '">' . $record->agent . '</option>';
        }

        echo $options;
    }

    public function getTestById($id)
    {
        $result = $this->db->select("
						test_name,
						id,
						test_taka,
					")
            ->where('id', $id)
            ->get('bf_pathology_test_name')
            ->row();
        echo json_encode($result);
    }


    public function getMedicineProByKey()
    {
        $medicine_name = $this->input->get('medicine_name');
        $limit = 7;
        $result = $this->db->where("product_name LIKE '$medicine_name%'")->limit($limit)->get('bf_pharmacy_products')->result();

        if (!$result) {
            $result = $this->db->where("product_name LIKE '%$medicine_name%'")->limit($limit)->get('bf_pharmacy_products')->result();
        }


        $re = "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='medicine_item' id='$row->id'>$row->product_name</li>";
            }
            $re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }

    public function getMedicineSaleInfoBySaleNo()
    {
        $sale_no = $this->input->get('sale_no');
        $type = $this->input->get('type');
        //print_r($this->current_user);exit;
        $limit = 5;
        if ($type == 2) {
            $where = array(
                'pharmacy_id' => $this->current_user->pharmacy_id
            );
            $result = $this->db
                ->where($where)
                ->where("sale_no LIKE '$sale_no%'")
                ->limit($limit)
                ->get('pharmacy_indoor_sales_mst')
                ->result();

            if (!$result) {
                $result = $this->db
                    ->where($where)
                    ->where("sale_no LIKE '%$sale_no%'")
                    ->limit($limit)
                    ->get('pharmacy_indoor_sales_mst')
                    ->result();
            }
        } else {
            $result = $this->db->where("sale_no LIKE '$sale_no%'")->limit($limit)->get('pharmacy_sales_mst')->result();

            if (!$result) {
                $result = $this->db->where("sale_no LIKE '%$sale_no%'")->limit($limit)->get('pharmacy_sales_mst')->result();
            }
        }


        $re = "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='sale_no' id='$row->id'>$row->sale_no</li>";
            }
            $re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }


    public function getStoreSubCategoryList()
    {
        $store_category_id = $this->input->post('store_category_id');
        $records = $this->searchpanel->getStoreSubCategoryByCategoryId($store_category_id);
        if (!$records) {
            echo "";
            return;
        }

        $options = "";
        foreach ($records as $id => $subcategory_name) {
            $options = $options . "<option value='$id'>$subcategory_name</option>";
        }

        echo $options;
    }

    public function getPharmacySubCategoryList()
    {
        $pharmacy_category_id = $this->input->post('pharmacy_category_id');
        $records = $this->searchpanel->getPharmacySubCategoryByCategoryId($pharmacy_category_id);
        if (!$records) {
            echo "";
            return;
        }

        $options = "";
        foreach ($records as $id => $subcategory_name) {
            $options = $options . "<option value='$id'>$subcategory_name</option>";
        }

        echo $options;
    }


    public function getPatientSubtypeList()
    {
        $patient_type_id = $this->input->post('patient_type_id');
        $records = $this->searchpanel->getPatientSubTypeByPatientType($patient_type_id);
        if (!$records) {
            echo "";
            return;
        }

        $options = "";
        foreach ($records as $id => $sub_type_name) {
            $options = $options . "<option value='$id'>$sub_type_name</option>";
        }

        echo $options;
    }

    public function getStoreProductList()
    {
        $store_sub_category_id = $this->input->post('store_sub_category_id');
        $records = $this->searchpanel->getStoreProductBySubCategoryId($store_sub_category_id);
        if (!$records) {
            echo "";
            return;
        }

        $options = "";
        foreach ($records as $id => $product_name) {
            $options = $options . "<option value='$id'>$product_name</option>";
        }

        echo $options;
    }

    public function getStoreProductListbyCategoryId()
    {
        $store_category_id = $this->input->post('store_category_id');
        $records = $this->searchpanel->getStoreProductByCategoryId($store_category_id);
        if (!$records) {
            echo "";
            return;
        }

        $options = "";
        foreach ($records as $id => $product_name) {
            $options = $options . "<option value='$id'>$product_name</option>";
        }

        echo $options;
    }

    public function getPharmacyProductList()
    {
        $pharmacy_sub_category_id = $this->input->post('pharmacy_sub_category_id');
        $pharmacy_category_id = $this->input->post("pharmacy_category_id");
        // $records = $this->searchpanel->getPharmacyProductBySubCategoryId($pharmacy_sub_category_id);
        $records = $this->searchpanel->getPharmacyProductByCategoryId($pharmacy_category_id);
        if (!$records) {
            echo "";
            return;
        }

        $options = "";
        foreach ($records as $id => $product_name) {
            $options = $options . "<option value='$id'>$product_name</option>";
        }

        echo $options;
    }

    public function getProductinfoById($id = 0, $pharmacy_id = 0)
    {
        $record = $this->db->select("
                        bf_pharmacy_products.id,
                        bf_pharmacy_products.product_name,
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


    public function getStoreProductBykey()
    {
        $product_name = $this->input->get('product_name');
        $limit = 7;
        $result = $this->db->where("product_name LIKE '$product_name%'")->limit($limit)->get('bf_store_products')->result();

        if (!$result) {
            $result = $this->db->where("product_name LIKE '%$product_name%'")->limit($limit)->get('bf_store_products')->result();
        }


        $re = "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='store_item' id='$row->id'>$row->product_name</li>";
            }
            $re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }

    public function getStoreProductBykeyWithStore()
    {
        $product_name = $this->input->get('product_name');
        $limit = 7;
        $result = $this->db->where('store_id', $this->current_user->store_id)->where("product_name LIKE '$product_name%'")->limit($limit)->get('bf_store_products')->result();

        if (!$result) {
            $result = $this->db->where('store_id', $this->current_user->store_id)->where("product_name LIKE '%$product_name%'")->limit($limit)->get('bf_store_products')->result();
        }


        $re = "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='store_item' id='$row->id'>$row->product_name</li>";
            }
            $re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }


    public function getStoreProductinfoById($id = 0)
    {
        $record = $this->db->select("
                        bf_store_products.id,
                        bf_store_products.product_name,
                        bf_store_products.store_id,
                        bf_store_subcategory.subcategory_name,
                        bf_store_subcategory.id as sub_cat_id,
                        bf_store_category.category_name,
                        bf_store_category.id as cat_id,
                    ")
            ->join('bf_store_subcategory', 'bf_store_subcategory.id=bf_store_products.sub_category_id', 'left')
            ->join('bf_store_category', 'bf_store_category.id=bf_store_subcategory.category_id', 'left')
            ->where('bf_store_products.id', $id)
            ->get('bf_store_products')
            ->row();

        // echo "<pre>";
        // print_r($record);
        // exit();
        $current_stock = $this->db->where('product_id', $id)->get('bf_store_stock')->row();

        if ($current_stock) {
            $record->current_stock = $current_stock->quantity_level;
        } else {
            $record->current_stock = 0;
        }
        echo json_encode($record);
    }


    public function getStoreProductsinfoById($product_id = 0, $store_id = 0)
    {
        $record = $this->db->select("
                        sp.id,
                        sp.product_name,
                        ssc.subcategory_name,
                        ssc.id as sub_cat_id,
                        sc.category_name,
                        sc.id as cat_id,
                    ")
            ->from('store_products as sp')
            ->join('store_subcategory as ssc', 'ssc.id = sp.sub_category_id', 'left')
            ->join('store_category as sc', 'sc.id = sp.category_id', 'left')
            ->where('sp.id', $product_id)
            ->get()
            ->row();
        $c_stock = $this->db
            ->select('IFNULL(quantity_level, 0) as quantity_level')
            ->where('product_id', $product_id)
            ->where('store_id', $store_id)
            ->get('bf_store_stock')
            ->row();
        $s_name = $this->db
            ->select('STORE_NAME as store_name')
            ->where('STORE_ID', $store_id)
            ->get('bf_store')
            ->row();
        $record->current_stock = 0;
        if ($c_stock) {
            $record->current_stock = $c_stock->quantity_level;
        }
        $record->store_name = $s_name->store_name;
        $record->store_id = $store_id;
        echo json_encode($record);
    }


    public function getCanteenPurchaseProductByKey()
    {
        $product_name = $this->input->get('product_name');
        $limit = 7;
        $result = $this->db
            ->where("product_name LIKE '$product_name%'")
            ->where('bf_canteen_products.direct_sale', 1)
            ->limit($limit)
            ->get('bf_canteen_products')
            ->result();

        if (!$result) {
            $result = $this->db
                ->where("product_name LIKE '%$product_name%'")
                ->where('bf_canteen_products.direct_sale', 1)
                ->limit($limit)
                ->get('bf_canteen_products')
                ->result();
        }


        $re = "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='cp_product' id='$row->id'>$row->product_name</li>";
            }
            $re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }

    public function getCanteenPurchaseProductinfoById($id = 0)
    {
        $record = $this->db->select("
                        bf_canteen_products.id,
                        bf_canteen_products.sale_price,
                        bf_canteen_products.product_name,
                        bf_canteen_products.direct_sale,
                        bf_canteen_category.category_name,
                        bf_canteen_category.id as cat_id,
                        bf_lib_measurement_unit.unit_name
                    ")
            ->join('bf_canteen_category', 'bf_canteen_category.id=bf_canteen_products.category_id', 'left')
            ->join('bf_lib_measurement_unit', 'bf_lib_measurement_unit.id=bf_canteen_products.unit_id', 'left')
            ->where('bf_canteen_products.id', $id)
            ->get('bf_canteen_products')
            ->row();
        $current_stock = $this->db->where('product_id', $id)->get('bf_canteen_stock')->row();

        if ($current_stock) {
            $record->current_stock = $current_stock->quantity_level;
        } else {
            $record->current_stock = 0;
        }

        if (isset($_GET['customer_type'])) {
            $date = date('Y-m-d');
            $customer_type = (int)$_GET['customer_type'];
            $discount = $this->db->select('bf_lib_customer_discount.*')
                ->where('bf_lib_customer_discount.start_date <=', $date)
                ->where('bf_lib_customer_discount.end_date >=', $date)
                ->where('bf_lib_customer_discount.discount_for', 1)
                ->where('bf_lib_customer_discount.customer_type', $customer_type)
                ->order_by('id', 'DESC')
                ->get('bf_lib_customer_discount')
                ->row();

        }
        if (isset($discount->discount_parcent)) {
            $record->discount_parcent = $discount->discount_parcent;
            $record->discount_id = $discount->id;
        } else {
            $record->discount_parcent = 0;
            $record->discount_id = 0;
        }
        echo json_encode($record);
    }

    public function getPharmacyPurchaseProductinfoById($id = 0)
    {
        $record = $this->db->select("
                        bf_pharmacy_products.id,
                        bf_pharmacy_products.sale_price,
                        bf_pharmacy_products.product_name,
                        bf_pharmacy_category.category_name,
                        bf_pharmacy_category.id as cat_id,
                        bf_lib_measurement_unit.unit_name
                    ")
            ->join('bf_pharmacy_category', 'bf_pharmacy_category.id=bf_pharmacy_products.category_id', 'left')
            ->join('bf_lib_measurement_unit', 'bf_lib_measurement_unit.id=bf_pharmacy_products.unit_id', 'left')
            ->where('bf_pharmacy_products.id', $id)
            ->get('bf_pharmacy_products')
            ->row();
        $current_stock = $this->db->where('product_id', $id)->get('bf_pharmacy_stock')->row();

        if ($current_stock) {
            $record->current_stock = $current_stock->quantity_level;
        } else {
            $record->current_stock = 0;
        }

        if (isset($_GET['customer_type'])) {
            $date = date('Y-m-d');
            $customer_type = (int)$_GET['customer_type'];
            $discount = $this->db->select('bf_lib_customer_discount.*')
                ->where('bf_lib_customer_discount.start_date <=', $date)
                ->where('bf_lib_customer_discount.end_date >=', $date)
                ->where('bf_lib_customer_discount.discount_for', 1)
                ->where('bf_lib_customer_discount.customer_type', $customer_type)
                ->order_by('id', 'DESC')
                ->get('bf_lib_customer_discount')
                ->row();

        }
        if (isset($discount->discount_parcent)) {
            $record->discount_parcent = $discount->discount_parcent;
            $record->discount_id = $discount->id;
        } else {
            $record->discount_parcent = 0;
            $record->discount_id = 0;
        }
        echo json_encode($record);
    }


    public function getCanteenRawProductByKey()
    {
        $product_name = $this->input->get('product_name');
        $limit = 7;
        $result = $this->db->where("product_name LIKE '$product_name%'")->where('bf_canteen_products.direct_sale', 0)->limit($limit)->get('bf_canteen_products')->result();

        if (!$result) {
            $result = $this->db->where("product_name LIKE '%$product_name%'")->where('bf_canteen_products.direct_sale', 0)->limit($limit)->get('bf_canteen_products')->result();
        }


        $re = "<ul class='auto_name_list'>";
        if ($result) {
            foreach ($result as $row) {
                $re .= "<li class='cp_product' id='$row->id'>$row->product_name</li>";
            }
            $re .= "<li class='c-remove'><i class='fa fa-times' aria-hidden='true'></i></li>";
        }
        $re .= "</ul>";
        echo $re;
    }

    public function getAllTestpackage()
    {
        $data = array();
        $data['testPack'] = $this->getPackage();
        $data['test_pack_dtls'] = $this->getPackageDtls();

        echo $this->load->view('test_list/package_list', $data);
    }

    private function getPackage()
    {
        $result = $this->db
            ->select('ppm.*,SUM(ppd.test_price) as total_price, COUNT(ppd.id) as count')
            ->from('pathology_package_master as ppm')
            ->join('pathology_package_details as ppd', 'ppd.package_id = ppm.id')
            ->group_by('ppd.package_id')
            ->get()
            ->result();
        return $result;
        //echo '<pre>';print_r($result);exit;
    }

    private function getPackageDtls()
    {
        $result = $this->db
            ->select('ppd.*,ptm.test_name,ptg.test_group_name')
            ->from('pathology_package_details as ppd')
            ->join('pathology_test_name as ptm', 'ppd.test_id = ptm.id')
            ->join('path_test_group as ptg', 'ptm.test_group = ptg.id')
            ->get()
            ->result();
        return $result;
    }

    public function getAllPharmacypackage()
    {
        $data = array();
        $data['testPack'] = $this->getPharmacyPackage();
        $data['test_pack_dtls'] = $this->getPharmacyPackageDtls();

        echo $this->load->view('test_list/pharmacy_package_list', $data);
    }

    private function getPharmacyPackage()
    {
        $result = $this->db
            ->select('ppm.*,SUM(ppd.unit_price) as total_price, COUNT(ppd.id) as count')
            ->from('bf_pharmacy_package as ppm')
            ->join('bf_pharmacy_package_details as ppd', 'ppd.master_id = ppm.id')
            ->group_by('ppd.master_id')
            ->get()
            ->result();
        return $result;
        //echo '<pre>';print_r($result);exit;
    }

    private function getPharmacyPackageDtls()
    {
        $result = $this->db
            ->select('ppd.*,pp.product_name,ROUND(IFNULL(ps.quantity_level,0)) AS quantity_level')
            ->from('bf_pharmacy_package_details as ppd')
            ->join('bf_pharmacy_products as pp', 'ppd.product_id = pp.id')
            ->join('bf_pharmacy_stock as ps', 'ps.product_id = ppd.product_id', 'left')
            ->get()
            ->result();
        return $result;
    }

    public function getAllTest()
    {
        $data = array();
        //$data['test_group'] = $this->db->get('bf_path_test_group')->result();
        $data['test_group'] = $this->getTestGroup();
        $data['test_dtls'] = $this->db->get('bf_pathology_test_name')->result();

        echo $this->load->view('test_list/test_list', $data);
    }

    private function getTestGroup()
    {
        $result = $this->db
            ->select('ptg.*, COUNT(ptm.id) as count')
            ->from('path_test_group as ptg')
            ->join('pathology_test_name as ptm', 'ptm.test_group = ptg.id')
            ->group_by('ptm.test_group')
            ->get()
            ->result();
        return $result;
    }

    /*public function getAllTest1(){
        // $records=$this->test_name_model->find_all();
         $records=$this->db->get('bf_pathology_test_name')->result_array();
         $boxs='<table>';
         for($i=0;$i<count($records);$i+=3){
             $boxs.='<tr>';
             $boxs.="<td><input type='checkbox' name='test_id[]' value='".$records[$i]['id']."'>".$records[$i]['test_name']."";
             if(!isset($records[$i+1])){
                 break;
             }
             $boxs.="<td><input type='checkbox' name='test_id[]' value='".$records[$i+1]['id']."'>".$records[$i+1]['test_name']."";
             if(!isset($records[$i+2])){
                 break;
             }
             $boxs.="<td><input type='checkbox' name='test_id[]' value='".$records[$i+2]['id']."'>".$records[$i+2]['test_name']."";
         }
         $boxs.='<table>';

         //echo $boxs;

        $test_group=$this->db->get('bf_path_test_group')->result();


        $test_list='<div class="panel-group test-panel-body" id="accordion" role="tablist" aria-multiselectable="true">';

            foreach ($test_group as $key => $group){


              $test_list.='

              <div class="panel panel-default test-panel">
                <div class="panel-heading" role="tab" id="heading'.$group->id.'">
                      <h4 class="panel-title ac-button">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$group->id.'" aria-expanded="false" aria-controls="collapse'.$group->id.'">
                          '.$group->test_group_name.'
                        </a>
                      </h4>
                </div>
                <div id="collapse'.$group->id.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'.$group->id.'">
                      <div class="panel-body">';

                      $records=$this->db->where('test_group',$group->id)->get('bf_pathology_test_name')->result_array();
                      $test_list.='<table>';
                      for($i=0;$i<count($records);$i+=2){
                          $test_list.='<tr>';

                          $test_list.='<td><label><input name="test_id" class="group_test_id" value="'.$records[$i]['id'].'" type="checkbox"/>'.$records[$i]['test_name'].'</label></td>';
                          if(!isset($records[$i+1])){
                              break;
                          }
                          $test_list.='<td><label><input name="test_id" class="group_test_id" value="'.$records[$i+1]['id'].'" type="checkbox"/>'.$records[$i+1]['test_name'].'</label></td>';

                          $test_list.='<tr>';
                      }
                      $test_list.='</table>';

                       $test_list.='</div>
                </div>
              </div>
              ';

             }
         $test_list.='</div>';
    echo $test_list;


    } */

    public function getPharmacyProductById($id)
    {
        $record = $this->db->where('id', $id)->get('bf_pharmacy_products')->row();
        echo json_encode($record);
    }


    public function getStoreSupplierByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(SUPPLIER_CODE LIKE '" . $query . "%' OR SUPPLIER_NAME LIKE '%" . $query . "%' OR SUPPLIER_CONTACT_PHONENO_1 LIKE '%" . $query . "%')";


            $records = $this->db->where($condition)->limit(10)->get('bf_store_supplier_mst')->result();


            $items = array();
            $assoc = array();
            foreach ($records as $record) {
                $items[] = array(
                    'id' => $record->SUPPLIER_ID,
                    'text' => $record->SUPPLIER_NAME,
                );

                $assoc[$record->SUPPLIER_ID] = $record;
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

    public function getPharmacySupplierByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(supplier_code LIKE '" . $query . "%' OR supplier_name LIKE '%" . $query . "%' OR contact_no1 LIKE '%" . $query . "%' OR contact_no2 LIKE '%" . $query . "%')";


            $records = $this->db->where($condition)->limit(10)->get('bf_pharmacy_supplier')->result();


            $items = array();
            $assoc = array();
            foreach ($records as $record) {
                $items[] = array(
                    'id' => $record->id,
                    'text' => $record->supplier_name,
                );

                $assoc[$record->id] = $record;
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

    // Edit by shamim 11/1/2020

    public function getPharmacySubCustomerByKey() {

        if ($query = $this->input->get('q')) {

            $condition = "(sub_customer_phone_number LIKE '" . $query . "%' OR sub_customer_name LIKE '%" . $query . "%')";


            $records = $this->db->where($condition)->limit(10)->get('bf_sub_customer')->result();


            $items = array();
            $assoc = array();
            foreach ($records as $record) {
                $items[] = array(
                    'id' => $record->id,
                    'text' => $record->sub_customer_name,
                );

                $assoc[$record->id] = $record;
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


    public function store_supplier_list()
    {

        $this->pager['full_tag_open'] = '<div class="container modal-pagination pagination-right"><ul class="pagination">';

        $this->load->library('pagination');
        $offset = $this->input->get('per_page');

        $limit = 30;
        $sl = $offset;
        $data['sl'] = $sl;


        $records = $this->db->select("
                            SQL_CALC_FOUND_ROWS null,
                            store_supplier_mst.*
                        ", false)
            ->from('store_supplier_mst')
            ->limit($limit, $offset)
            ->get()
            ->result();

        $length = count($records);

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/common/report/store_supplier_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $query_company = $this->db->select()->get('store_supplyer_dtls_view');
        $data['companies'] = $query_company->result();
        $data['records'] = $records;


        $list_view = 'store/supplier_list';
        if ($this->input->is_ajax_request()) {
            echo $this->load->View($list_view, compact('records', 'companies', 'sl'), true);
            exit();

        }
    }

    public function pharmacy_supplier_list()
    {

        $this->pager['full_tag_open'] = '<div class="container modal-pagination pagination-right"><ul class="pagination">';

        $this->load->library('pagination');
        $offset = $this->input->get('per_page');

        $limit = 30;
        $sl = $offset;
        $data['sl'] = $sl;


        $records = $this->db->select("
                            SQL_CALC_FOUND_ROWS null,
                            bf_pharmacy_supplier.*,
                            bf_pharmacy_product_company.company_name
                        ", false)
            ->join('bf_pharmacy_product_company', 'bf_pharmacy_product_company.id=bf_pharmacy_supplier.company_id', 'left')
            ->limit($limit, $offset)
            ->get('bf_pharmacy_supplier')
            ->result();

        //echo "<pre>";print_r($records);die();

        $length = count($records);

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/common/report/pharmacy_supplier_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records'] = $records;


        $list_view = 'pharmacy/supplier_list';
        if ($this->input->is_ajax_request()) {
            echo $this->load->View($list_view, compact('records', 'sl'), true);
            exit();

        }
    }
    // get employee by name or code
    public function getEmployeeByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(EMP_NAME LIKE '" . $query . "%' OR EMP_CODE LIKE '%" . $query . "%')";


            $employees = $this->db->where($condition)->limit(50)->get('bf_hrm_ls_employee')->result();


            $items = array();
            $assoc = array();
            foreach ($employees as $employee) {
                $items[] = array(
                    'id' => $employee->EMP_ID,
                    'text' => $employee->EMP_NAME,
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

    // get employee by mobile no

    public function getAllEmployeeByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(MOBILE LIKE '%" . $query . "%')";


            $employees = $this->db->where($condition)->limit(10)->get('bf_hrm_ls_emp_contacts')->result();


            $items = array();
            $assoc = array();
            foreach ($employees as $employee) {
                $items[] = array(
                    'id' => $employee->EMP_ID,
                    'text' => $employee->MOBILE,
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

    // GET NURSE BY KEY
    public function getNurseByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(EMP_NAME LIKE '" . $query . "%' OR EMP_CODE LIKE '%" . $query . "%')";

            $employees = $this->db->where(array('EMP_TYPE' => 2))->where($condition)->limit(50)->get('bf_hrm_ls_employee')->result();

            $items = array();
            $assoc = array();
            foreach ($employees as $employee) {
                $items[] = array(
                    'id' => $employee->EMP_ID,
                    'text' => $employee->EMP_NAME,
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

    public function getCanteenCustomerByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(customer_name LIKE '" . $query . "%' OR customer_mobile LIKE '%" . $query . "%')";


            $customers = $this->db->where($condition)->limit(50)->get('bf_canteen_customer')->result();


            $items = array();
            $assoc = array();
            foreach ($customers as $customer) {
                if (strlen($customer->customer_mobile) > 0) {
                    $text = $customer->customer_name . '->' . $customer->customer_mobile;
                } else {
                    $text = $customer->customer_name;
                }
                $items[] = array(
                    'id' => $customer->id,
                    'text' => $text,
                );

                $assoc[$customer->id] = $customer;
            }

            if (!$customers) {
                $items[] = array(
                    'id' => $query,
                    'text' => $query . ' (New customer)',
                );
                $assoc[$query] = $items[0];

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

    /*
    @return customer name (including canteen customer,patient, doctor, employee)
    */
    public function getCanteenAllCustomerByKey()
    {

        if ($query = $this->input->get('q')) {
            $c_type = $this->input->get('c_type');
            if ($c_type == 1) {
                $condition['bf_canteen_customer.customer_name like'] = '%' . trim($this->input->get('q')) . '%';
            } elseif ($c_type == 2 || $c_type == 3) {
                $condition['bf_hrm_ls_employee.EMP_NAME like'] = '%' . trim($this->input->get('q')) . '%';
            } elseif ($c_type == 4) {
                $condition['bf_patient_master.patient_name like'] = '%' . trim($this->input->get('q')) . '%';
            }
            $customers = $this->db->select("
				IFNULL(bf_patient_master.patient_name,IFNULL(bf_hrm_ls_employee.EMP_NAME,bf_canteen_customer.customer_name)) as customer_name
				", false)
                ->from('bf_canteen_sales_mst')
                ->join('bf_patient_master', 'bf_patient_master.id=bf_canteen_sales_mst.patient_id', 'left')
                ->join('bf_hrm_ls_employee', 'bf_hrm_ls_employee.EMP_ID=bf_canteen_sales_mst.employee_id', 'left')
                ->join('bf_canteen_customer', 'bf_canteen_customer.id=bf_canteen_sales_mst.customer_id', 'left')
                ->where($condition)
                ->distinct()
                ->limit(50)->get()->result();

            $items = array();
            $assoc = array();
            foreach ($customers as $customer) {
                $text = trim($customer->customer_name);
                $items[] = array(
                    'id' => $customer->customer_name,
                    'text' => $text,
                );

                $assoc[$customer->customer_name] = $customer;
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

    public function getAllDoctorByKey()
    {

        if ($query = $this->input->get('q')) {
            $d_type = $this->input->get('d_type');
            if ($d_type == 2) {
                $condition['bf_lib_reference.ref_name like'] = '%' . trim($this->input->get('q')) . '%';
            }

            $doctors = $this->db->select("bf_lib_reference.ref_name")
                ->from('bf_doctor_commission')
                ->join('bf_lib_reference', 'bf_lib_reference.id=bf_doctor_commission.agent_id', 'left')
                //->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_canteen_sales_mst.employee_id','left')
                //->join('bf_canteen_customer','bf_canteen_customer.id=bf_canteen_sales_mst.customer_id','left')
                ->where($condition)
                ->distinct()
                ->limit(50)->get()->result();

            $items = array();
            $assoc = array();
            foreach ($doctors as $doctor) {
                $text = trim($doctor->ref_name);
                $items[] = array(
                    'id' => $doctor->ref_name,
                    'text' => $text,
                );

                $assoc[$doctor->ref_name] = $doctor;
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

    /************For all type of Patient Name Auto Field Kabir **************/
    public function getAllTypePatientByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(bf_patient_master.patient_name LIKE '%" . $query . "%' OR bf_admission_bed.bed_short_name LIKE '%" . $query . "%' OR bf_patient_master.contact_no LIKE '%" . $query . "%')";
            // $condition = "(patient_name LIKE '".$query."%' OR patient_name LIKE '%".$query."%')";

            $patients = $this->db->select("
    							bf_admission_bed.bed_short_name,
								bf_patient_master.patient_name,
								bf_patient_master.contact_no,
								bf_patient_master.id as p_id
							 ")
                ->join('bf_admission_patient', 'bf_admission_patient.patient_id=bf_patient_master.id', 'LEFT OUTER')
                ->join('bf_admission_bed', 'bf_admission_bed.id=bf_admission_patient.bed_id', 'LEFT OUTER')
                ->where_in('bf_patient_master.patient_type', array('1', '2', '3', '7'))
                ->where($condition)
                ->limit(50)
                ->get('bf_patient_master')
                ->result();


            $items = array();
            $assoc = array();
            foreach ($patients as $patient) {
                $items[] = array(
                    'id' => $patient->p_id,
                    'text' => $patient->patient_name . '->' . $patient->contact_no . '->' . $patient->bed_short_name
                );

                $assoc[$patient->patient_name] = $patient;
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

    /************ Patient ID Auto Field **************/


    /************All Admitted Patient ID Auto Field Kabir **************/

    public function getAllPatientByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(bf_patient_master.patient_name LIKE '%" . $query . "%' OR bd.bed_short_name LIKE '%" . $query . "%' OR bf_patient_master.contact_no LIKE '%" . $query . "%')";
            /*  if(is_numeric($query)){
                  $condition = "(bd.bed_short_name LIKE '%" . $query . "%') OR (bf_patient_master.contact_no LIKE '%".$query."%')";
              }
               $condition = "(patient_name LIKE '".$query."%' OR patient_name LIKE '%".$query."%')";*/

            $patients = $this->db->select("

                    bd.bed_short_name,
		            bf_patient_master.patient_name,
		            bf_patient_master.contact_no,
		            bf_patient_master.id as p_id
		          ")
                ->join('bf_admission_patient', 'bf_admission_patient.patient_id=bf_patient_master.id', 'LEFT OUTER')
                ->join('(SELECT  abm.admission_id, MAX(abm.id) AS id
					 	FROM bf_admission_bed_migrate AS abm 
					 	WHERE abm.status != 2
						GROUP  BY abm.admission_id 
						) AS adp1', 'adp1.admission_id = bf_admission_patient.id', 'LEFT OUTER')
                ->join('(SELECT abm2.id,abm2.bed_id 
                        FROM bf_admission_bed_migrate AS abm2 
						)AS adp2', 'adp2.id = adp1.id', 'left')
                ->join('(SELECT bed_short_name,id FROM bf_admission_bed
						)AS bd', 'adp2.bed_id = bd.id', 'LEFT OUTER')
                ->where_in('bf_admission_patient.status', [2, 3])
                ->where($condition)
                ->limit(50)
                ->get('bf_patient_master')
                ->result();

            $items = array();
            $assoc = array();
            foreach ($patients as $patient) {
                $items[] = array(
                    'id' => $patient->p_id,
                    'text' => $patient->patient_name . '->' . $patient->contact_no . '->' . $patient->bed_short_name
                );

                $assoc[$patient->patient_name] = $patient;
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

    /************All Admitted Patient ID Auto Field **************/

//*************All patient auto**********

    public function get_bf_master_patient_key()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(patient_name LIKE '%" . $query . "%')";
            // $condition = "(patient_name LIKE '".$query."%' OR patient_name LIKE '%".$query."%')";

            $patients = $this->db->select("
								bf_patient_master.patient_name,
								bf_patient_master.id
							 ")
                ->from('bf_patient_master')
                ->where($condition)
                ->limit(50)
                ->get()
                ->result();

            $items = array();
            $assoc = array();
            foreach ($patients as $patient) {
                $items[] = array(
                    'id' => $patient->patient_name,
                    'text' => $patient->patient_name
                );

                $assoc[$patient->patient_name] = $patient;
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


//********************************End*****************


    public function get_test_key()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(test_name LIKE '%" . $query . "%')";
            // $condition = "(patient_name LIKE '".$query."%' OR patient_name LIKE '%".$query."%')";

            $patients = $this->db->select("
								bf_pathology_test_name.test_name,
								bf_pathology_test_name.id
							 ")
                ->from('bf_pathology_test_name')
                ->where($condition)
                ->limit(50)
                ->get()
                ->result();

            $items = array();
            $assoc = array();
            foreach ($patients as $patient) {
                $items[] = array(
                    'id' => $patient->test_name,
                    'text' => $patient->test_name
                );

                $assoc[$patient->test_name] = $patient;
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


//******************************************************
    public function getAllPatientIdByKey()
    {

        if ($query = $this->input->get('q')) {

            $con = "(patient_id LIKE '%" . $query . "%')";


            $patients = $this->db->where($con)->limit(50)->get('bf_patient_master')->result();


            $items = array();
            $assoc = array();
            foreach ($patients as $patient) {
                $items[] = array(
                    'id' => $patient->patient_id,
                    'text' => $patient->patient_id,
                );

                $assoc[$patient->patient_id] = $patient;
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


    public function getDoctorByKey()
    {
        if ($query = $this->input->get('q')) {
            $condition = "(EMP_NAME LIKE '%" . $query . "%' OR CODE LIKE '%" . $query . "%')";
            $doctors = $this->db
                ->select('emp.EMP_ID as id,degi.DESIGNATION_NAME as designation,emp.EMP_NAME as doctor_name,emp.QUALIFICATION as qualification,CODE as code')
                ->from('hrm_ls_employee as emp')
                ->join('lib_designation_info as degi', 'emp.EMP_DESIGNATION	= degi.DESIGNATION_ID')
                ->where('emp.EMP_TYPE', 1)
                ->where($condition)
                ->limit(20)
                ->get()
                ->result();


            $items = array();
            $assoc = array();
            foreach ($doctors as $doctor) {
                $text = $doctor->code . '->' . $doctor->designation . ' ' . $doctor->doctor_name;
                $items[] = array(
                    'id' => $doctor->id,
                    'text' => $text,
                );

                $assoc[$doctor->id] = $doctor;
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

    public function getConsultantDoctorByKey()
    {
        if ($query = $this->input->get('q')) {
            $condition = "(lr.ref_name LIKE '%" . $query . "%' OR lr.ref_phone LIKE '%" . $query . "%' OR lr.ref_mobile LIKE '%" . $query . "%')";
            $consultants = $this->db
                ->select('clp.doctor_id as id,lr.ref_name,lr.ref_phone,lr.ref_mobile')
                ->from('bf_lib_reference as lr')
                ->join('bf_lib_consultant_price as clp', 'lr.id	= clp.doctor_id')
                ->where('clp.status', 1)
                ->where($condition)
                ->limit(10)
                ->get()
                ->result();

            $items = array();
            $assoc = array();
            foreach ($consultants as $consultant) {
                $text = $consultant->ref_name . '->' . $consultant->ref_mobile;
                $items[] = array(
                    'id' => $consultant->id,
                    'text' => $text
                );

                $assoc[$consultant->id] = $consultant;
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

    public function getReferenceByKey()
    {
        if ($query = $this->input->get('q')) {
            $condition = "(ref_name LIKE '%" . $query . "%' OR ref_phone LIKE '%" . $query . "%' OR ref_mobile LIKE '%" . $query . "%')";
            $references = $this->db->where($condition)->limit(10)->get('bf_lib_reference')->result();


            $items = array();
            $assoc = array();
            foreach ($references as $reference) {
                if ($reference->ref_type == 1) {
                    $reference_type = 'Doctor';
                } elseif ($reference->ref_type == 2) {
                    $reference_type = 'Organization Name';
                } else {
                    $reference_type = 'Other';
                }

                $text = $reference->ref_name;
                $items[] = array(
                    'id' => $reference->id,
                    'text' => $text . '->' . $reference_type,
                );

                $assoc[$reference->id] = $reference;
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

    public function getPatientByKey()
    {
        if ($query = $this->input->get('q')) {
            $condition = "(bf_patient_master.patient_id LIKE '%" . $query . "%' OR bf_patient_master.patient_name LIKE '%" . $query . "%' OR bf_patient_master.contact_no LIKE '%" . $query . "%' OR bf_admission_bed.bed_short_name LIKE '%" . $query . "%' OR bf_lib_reference.ref_name LIKE '%" . $query . "%')";

            $results = $this->db
                ->select('bf_patient_master.patient_id,bf_patient_master.id,bf_patient_master.patient_name,bf_patient_master.contact_no,bf_admission_bed.bed_short_name,bf_lib_reference.ref_name')
                ->from('bf_admission_bed_migrate')
                ->join('bf_patient_master', 'bf_patient_master.id=bf_admission_bed_migrate.patient_id', 'left')
                ->join('bf_admission_patient', 'bf_admission_patient.id=bf_admission_bed_migrate.admission_id')
                ->join('bf_lib_reference', 'bf_lib_reference.id=bf_admission_patient.	reference_doctor')
                ->join('bf_admission_bed', 'bf_admission_bed.id=bf_admission_bed_migrate.bed_id', 'left')
                ->where('bf_admission_bed_migrate.bed_reservation_end_time', NULL)
                ->where('bf_admission_patient.status=', 2)
                //->where('bf_patient_master.admission_status=',0)

                ->where($condition)
                ->limit(10)
                ->get()
                ->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->patient_id . '->' . $result->patient_name . '->' . $result->contact_no . '->' . $result->bed_short_name . '->' . $result->ref_name;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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

    public function getDischargePatientByKey()
    {
        if ($query = $this->input->get('q')) {
            $condition = "(bf_patient_master.patient_id LIKE '%" . $query . "%' OR bf_patient_master.patient_name LIKE '%" . $query . "%' OR bf_patient_master.contact_no LIKE '%" . $query . "%' OR bf_admission_bed.bed_short_name LIKE '%" . $query . "%' OR bf_lib_reference.ref_name LIKE '%" . $query . "%')";

            $results = $this->db
                ->select('bf_patient_master.patient_id,bf_patient_master.id,bf_patient_master.patient_name,bf_patient_master.contact_no,bf_admission_bed.bed_short_name,bf_lib_reference.ref_name')
                ->from('bf_admission_bed_migrate')
                ->join('bf_patient_master', 'bf_patient_master.id=bf_admission_bed_migrate.patient_id', 'left')
                ->join('bf_admission_patient', 'bf_admission_patient.id=bf_admission_bed_migrate.admission_id')
                ->join('bf_lib_reference', 'bf_lib_reference.id=bf_admission_patient.reference_doctor')
                ->join('bf_admission_bed', 'bf_admission_bed.id=bf_admission_bed_migrate.bed_id', 'left')
                // ->where('bf_admission_bed_migrate.bed_reservation_end_time',NULL)
                ->where('bf_admission_patient.status=', 3)
                ->where('bf_admission_bed.bed_status=', 3)
                //->where('bf_patient_master.admission_status=',0)

                ->where($condition)
                ->limit(10)
                ->get()
                ->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->patient_id . '->' . $result->patient_name . '->' . $result->contact_no . '->' . $result->bed_short_name . '->' . $result->ref_name;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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

    public function getNormalPatientByKey()
    {
        if ($query = $this->input->get('q')) {
            $condition = "(patient_id LIKE '%" . $query . "%' OR patient_name LIKE '%" . $query . "%' OR contact_no LIKE '%" . $query . "%')";

            $results = $this->db
                ->select('patient_id,id,patient_name,contact_no')
                ->where('source!=', 3)
                ->where('source!=', 6)
                ->where('source!=', 7)
                ->where($condition)
                ->limit(10)
                ->get('bf_patient_master')
                ->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->patient_id . '->' . $result->patient_name . '->' . $result->contact_no;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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

    public function getPharmacyCustomerByKey()
    {
        if ($query = $this->input->get('q')) {

            $condition = "(customer_name LIKE '%" . $query . "%' OR customer_mobile LIKE '%" . $query . "%')";

            $results = $this->db
                ->select('customer_name,customer_mobile,id')
                ->where($condition)
                ->limit(10)
                ->get('bf_pharmacy_customer')
                ->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->customer_mobile . '->' . $result->customer_name;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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

    public function getTestByKey()
    {
        if ($query = $this->input->get('q')) {
            $limit = 30;
            $results = $this->db->where("test_name LIKE '%$query%'")->limit($limit)->get('bf_pathology_test_name')->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->test_name;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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

    // public function getMedicineName(){
    //     if ($query = $this->input->get('q')) {
    //     	$limit=30;
    //     	print_r($query);
    //     	exit();
    //     	$results = $this->db
    //     				// ->where("product_name LIKE '%$query%'")
    //     				->like('product_name',$query,'after')
    //     				->limit($limit)->get('pharmacy_products')
    //     				->result();


    // 		$items = array();
    // 		$assoc = array();
    // 		foreach($results as $row) {

    // 			$text=$row->category_id.'->'.$row->product_name;
    // 			$items[] = array(
    // 				'id' => $row->id,
    // 				'text' =>$text,
    // 			);

    // 			$assoc[$row->id] = $row;
    // 		}

    // 		$status_code = 200;
    // 		$json = array(
    // 			'items' => $items,
    // 			'assoc' => $assoc,
    // 		);

    // 	} else {

    // 		$status_code = 422;
    // 		$json = array(
    // 			'error' => 'Required Parameters not found.',
    // 		);

    // 	}

    // 	return $this->output->set_status_header($status_code)
    // 						->set_content_type('application/json')
    // 						->set_output(json_encode($json));

    // }

    public function getPatientByAdmissionCode()
    {
        if ($query = $this->input->get('q')) {
            $condition = "admission_patient.admission_code LIKE '%" . $query . "%'";

            $condition = "(admission_patient.admission_code LIKE '%" . $query . "%' OR bf_patient_master.patient_name LIKE '%" . $query . "%' OR bf_patient_master.contact_no LIKE '%" . $query . "%' OR
	    	    admission_bed.bed_short_name LIKE '%" . $query . "%'
	    )";

            $results = $this->db
                ->select('admission_patient.status,
							admission_patient.id,
							admission_patient.admission_code,
							bf_patient_master.patient_name,
							admission_bed.bed_short_name
						')
                ->from('admission_patient')
                ->join('bf_patient_master', 'bf_patient_master.id=admission_patient.patient_id')
                ->join('admission_bed_migrate', 'admission_bed_migrate.admission_id=admission_patient.id')
                ->join('admission_bed', 'admission_bed.id=admission_bed_migrate.bed_id')
                ->where($condition)
                ->where('admission_bed_migrate.bed_reservation_end_time is NULL')
                ->where_in('admission_patient.status', [2])
                ->limit(10)
                ->get()
                ->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->admission_code . '->' . $result->patient_name . '->' . $result->bed_short_name;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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


    /*     store product    */

    public function getStoreProductsByKey()
    {

        if ($query = $this->input->get('q')) {
            $limit = 30;
            $results = $this->db
                ->select('sp.id, sp.product_name, sc.category_name')
                ->from('store_products as sp')
                ->join('store_category as sc', 'sp.category_id = sc.id', 'left')
                ->like('sp.product_name', $query, 'after')
                ->order_by('sp.product_name', 'asc')
                ->limit($limit)
                ->get()
                ->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {
                if ($result->category_name) {
                    $text = $result->product_name . " >> " . $result->category_name;
                } else {
                    $text = $result->product_name;
                }
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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

    /****************Store Product Name Field Auto Complete ***************/

    public function getStoreAllProductsByKey()
    {

        if ($query = $this->input->get('q')) {

            $condition = "(product_name LIKE '%" . $query . "%')";

            $results = $this->db->where($condition)->limit(50)->get('store_products')->result();


            $items = array();
            $assoc = array();
            foreach ($results as $result) {
                $items[] = array(
                    'id' => $result->product_name,
                    'text' => $result->product_name,
                );

                $assoc[$result->product_name] = $result;
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

    public function getMedicineByKey()
    {
        $pharmacy_id = $this->current_user->pharmacy_id;
        $stable = 'pharmacy_indoor_stock as ps';
        $con = " and $pharmacy_id";
        if (!$pharmacy_id) {
            $stable = 'pharmacy_stock as ps';
            $con = '';
        }
        if ($query = $this->input->get('q')) {
            $limit = 30;
            $results = $this->db
                ->select('pp.id, pp.product_name, pc.category_name, ROUND(IFNULL(ps.quantity_level, 0)) as current_stock')
                ->from('pharmacy_products as pp')
                ->join('pharmacy_category as pc', 'pp.category_id = pc.id', 'left')
                ->join($stable, "pp.id = ps.product_id $con", 'left')
                // ->where("pp.product_name LIKE '$query%'")
                ->like('pp.product_name', $query, 'after')
                ->order_by('ps.quantity_level', 'desc')
                ->order_by('pp.product_name', 'asc')
                ->limit($limit)
                ->get()
                ->result();

            // if(!$results){
            // 	$results=$this->db->where("product_name LIKE '%$query%'")->limit($limit)->get('bf_pharmacy_products')->result();
            // }


            $items = array();
            $assoc = array();
            foreach ($results as $result) {

                $text = $result->product_name . " >> " . $result->category_name . ">>" . $result->current_stock;
                $items[] = array(
                    'id' => $result->id,
                    'text' => $text,
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

    /*        Canteen Product  for purchase   */

    public function getCanteenProductByKey()
    {
        if ($query = $this->input->get('q')) {
            $limit = 10;
            $results = $this->db
                ->where("product_name LIKE '$query%'")
                ->where('product_type', 0)
                ->limit($limit)
                ->get('canteen_products')
                ->result();
            $items = [];
            $assoc = [];
            foreach ($results as $row) {
                $text = $row->product_name;
                $items[] = array(
                    'id' => $row->id,
                    'text' => $text,
                );
                $assoc[$row->id] = $row;
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

    /*        Canteen Product  for sale   */

    public function getCanteenProductSaleByKey()
    {
        if ($query = $this->input->get('q')) {
            $limit = 10;
            $results = $this->db
                ->where("product_name LIKE '%$query%'")
                ->where('product_type', 1)
                ->limit($limit)
                ->get('canteen_products')
                ->result();
            $items = [];
            $assoc = [];
            foreach ($results as $row) {
                $text = $row->product_name;
                $texto = $row->product_name_bangla;
                $textt = $row->sale_price;
                $items[] = array(
                    'id' => $row->id,
                    'text' => $text . "(" . $texto . ")" . "=>" . $textt,
                );
                $assoc[$row->id] = $row;
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

    /*        Canteen Direct Product  for purchase   */

    public function getCanteenProductDirectByKey()
    {
        if ($query = $this->input->get('q')) {
            $limit = 10;
            $results = $this->db
                ->where("product_name LIKE '%$query%'")
                ->where('product_type', 1)
                ->where('direct_sale', 1)
                ->limit($limit)
                ->get('canteen_products')
                ->result();
            //$qu = $this->db->last_query();
            //echo "<pre>"; print_r($qu);exit();
            //echo "<pre>";print_r($results);exit();
            $items = [];
            $assoc = [];
            foreach ($results as $row) {
                $text = $row->product_name;
                $items[] = array(
                    'id' => $row->id,
                    'text' => $text,
                );
                $assoc[$row->id] = $row;
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

    public function getCanteenSaleProductByKey()
    {
        if ($query = $this->input->get('q')) {
            $limit = 10;
            $results = $this->db
                ->where("product_name LIKE '$query%'")
                ->where('direct_sale', 0)
                ->limit($limit)
                ->get('canteen_products')
                ->result();

            $items = [];
            $assoc = [];
            foreach ($results as $row) {
                $text = $row->product_name;
                $items[] = array(
                    'id' => $row->id,
                    'text' => $text,
                );
                $assoc[$row->id] = $row;
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

    // test  by doctor
    public function getCommonList($ref_doctor_id)
    {
        $data = array();
        $data['records'] = $this->selectAllTestByDoctor($ref_doctor_id);
        echo $this->load->view('report/test_list/test_short_list', $data, true);
        exit();
    }

    private function selectAllTestByDoctor($ref_doctor_id)
    {
        $con = [];
        if ($ref_doctor_id) {
            $con['pdm.reference_id'] = $ref_doctor_id;
        }
        $result = $this->db->select('count(pdd.test_id) as count,ptm.id,ptm.test_name,ptm.test_taka')
            ->from('bf_pathology_diagnosis_master as pdm')
            ->join('bf_pathology_diagnosis_details as pdd', 'pdm.id=pdd.diagnosis_id')
            ->join('pathology_test_name as ptm', 'pdd.test_id = ptm.id')
            ->where($con)
            ->group_by('pdd.test_id')
            ->order_by('count', 'desc')
            ->limit(200, 0)
            ->get()
            ->result();
        return $result;
    }


    // pharmacy product
    public function getPharmacyList()
    {
        $data = array();
        $data['records'] = $this->getPharmacyLists();
        echo $this->load->view('report/test_list/test_short_list', $data, true);
        exit();
    }

    private function getPharmacyLists()
    {
        $result = $this->db->select('count(psd.product_id) as count,pp.id,pp.product_name as test_name,pp.sale_price as test_taka')
            ->from('bf_pharmacy_sales_mst as psm')
            ->join('bf_pharmacy_sales_dtls as psd', 'psm.id=psd.master_id')
            ->join('bf_pharmacy_products as pp', 'psd.product_id = pp.id')
            ->group_by('psd.product_id')
            ->order_by('count', 'desc')
            ->limit(200, 0)
            ->get()
            ->result();
        return $result;
    }

    // pharmacy product
    public function getSubPharmacyList()
    {
        $data = array();
        $data['records'] = $this->getSubPharmacyLists();
        echo $this->load->view('report/test_list/test_short_list', $data, true);
        exit();
    }

    private function getSubPharmacyLists()
    {
        $result = $this->db->select('count(psd.product_id) as count,pp.id,pp.product_name as test_name,pp.sale_price as test_taka')
            ->from('pharmacy_indoor_sales_mst as psm')
            ->join('pharmacy_indoor_sales_dtls as psd', 'psm.id=psd.master_id')
            ->join('bf_pharmacy_products as pp', 'psd.product_id = pp.id')
            ->group_by('psd.product_id')
            ->order_by('count', 'desc')
            ->limit(200, 0)
            ->get()
            ->result();
        return $result;
    }

}