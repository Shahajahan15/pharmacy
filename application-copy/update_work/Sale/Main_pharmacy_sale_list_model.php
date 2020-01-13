<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main_pharmacy_sale_list_model extends BF_Model {

    public function selectAllSaleInformation($offset, $limit, $condition) {
        $records = $this->db->select("
        	 			SQL_CALC_FOUND_ROWS 
                        psm.id,
                        psm.cost_paid_by,
                        psm.customer_type,
                        DATE_FORMAT(psm.created_date, '%d-%m-%Y') as created_date,
                        psm.admission_id,
                        psm.customer_id,
                        psm.patient_id,
                        psm.employee_id,
                        psm.tot_bill,
                        psm.tot_less_discount,
                        psm.tot_paid,
                        psm.sale_no,
                        IFNULL(cusm.customer_name, IFNULL(emp.EMP_NAME, IFNULL(pmast.patient_name, IFNULL(adpmst.patient_name,0)))) as customer_name,
                        IFNULL(cusm.customer_mobile, IFNULL(emp.MOBILE_NUM, IFNULL(pmast.contact_no,0))) as customer_contct_no,
                        bf_users.display_name
                     ", false)
                ->from('pharmacy_sales_mst as psm')
                ->join('bf_patient_master as pmast', 'psm.patient_id=pmast.id', 'left')
                ->join('bf_admission_patient as adp', 'adp.id=psm.admission_id', 'left')
                ->join('bf_hrm_ls_employee as emp', 'emp.EMP_ID=psm.employee_id', 'left')
                ->join('bf_pharmacy_customer as cusm', 'cusm.id=psm.customer_id', 'left')
                ->join('bf_patient_master as adpmst', 'adp.patient_id=adpmst.id', 'left')
                ->join('bf_users', 'psm.created_by=bf_users.id')
                ->where($condition)
                ->order_by('psm.id', 'desc')
                ->limit($limit, $offset)
                ->get()
                ->result();
        // echo '<pre>'; print_r($records);exit();
        return $records;
    }

// update_work 08-01-2020 by Nobi Hossain
    public function getList($table_name, $id, $name) {
        $list = array();
        $query = $this->db->query("SELECT $id, $name FROM $table_name")->result_array();
        foreach ($query as $rows) {
            $list[$rows[$id]] = $rows[$name];
        }
        return $list;
    }

}
