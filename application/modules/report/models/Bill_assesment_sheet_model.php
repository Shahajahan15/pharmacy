<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bill_assesment_sheet_model extends CI_Model {

    public function getBillAssesmentSheet($limit, $offset, $admission_id = 0)
    {
        $con = "ap.id > 0";
        $hcon = "";
       if ($this->input->is_ajax_request()) {
            $patient_name = trim($this->input->post('patient_name', true));
            if ($patient_name) {
                $con .= " AND pm.patient_name LIKE '%$patient_name%'";
            }
            $admission_id = trim($this->input->post('admission_id', true));
            if ($admission_id) {
                $con .= " AND ap.admission_code LIKE '%$admission_id%'";
            }
            $bed_type = trim($this->input->post('bed_type', true));
            if ($bed_type) {
                $con .= " AND ar.room_type = $bed_type";
            }
            $room_type = trim($this->input->post('room_type', true));
            if ($room_type) {
                $con .= " AND ar.room_condition = $room_type";
            }

            $admission_status = trim($this->input->post('admission_status_list', true));
            if ($admission_status) {
                if ($admission_status == 50) {
                    $admission_status = 0;
                }
                $con .= " AND ap.status = $admission_status";
            } else {
                $con.= " AND ap.status IN(2,3)";
            }
            $discharge_reason = trim($this->input->post('admission_discharge_reason_list', true));
            if ($discharge_reason) {
                $con .= " AND ap.discharge_reason = $discharge_reason";
            }
            $common = trim($this->input->post('common_text_search', true));
            if ($common) {
                $con .= " AND ab.bed_name LIKE '%$common%' OR pm.contact_no LIKE '%$common%'";
            }
            $ref_doctor = trim($this->input->post('admission_referred_doctor', true));
            if ($ref_doctor) {
                $con .= " AND ap.reference_doctor = $ref_doctor";
            }
            $doctor_type = trim($this->input->post('doctor_type_list', true));
            if ($doctor_type) {
                $con .= " AND ap.superv_doctor_type = $doctor_type";
            }

            $sex = trim($this->input->post('sex', true));

            if ($sex) {
                $con .= " AND pm.sex = $sex";
            }

            $doctor_list = trim($this->input->post('doctor_list', true));
            if ($doctor_list)
            {
                $con .= " AND ap.supervision_doctor = $doctor_list";
            }

            $from_date = trim($this->input->post('from_date', true));
            $to_date = trim($this->input->post('to_date', true));
            if ($from_date) {
                $from_date = custom_date_format($from_date)." 00:00:00";
                $con .= " AND ap.admission_date >= '$from_date'";
            }
            if ($to_date) {
                $to_date = custom_date_format($to_date)." 23:59:59";
                $con .= " AND ap.admission_date <= '$to_date'";
            }

            $due = $this->input->post('due_paid', true);
            if ($due) {
                if ($due == 1) {
                    $hcon = "HAVING due > 0";
                } else {
                    $hcon = "HAVING due <= 0";
                }
            }
       } else {
        $con.= " AND ap.status IN(2,3)";
       }
      $this->db->query("SET @a := 0");
       $sql =
       " 
            SELECT
                SQL_CALC_FOUND_ROWS
                @a:= @a+1 as a,
                ap.id,
                ap.mr_no,
                ap.admission_code,
                pm.patient_id AS patient_code,
                pm.patient_name,
                pm.birthday,
                (
                    CASE 
                        WHEN pm.sex = 1 THEN 'Male'
                        WHEN pm.sex = 2 THEN 'Female'
                        WHEN pm.sex = 3 THEN 'Common'
                    END
                ) AS sex,
                IF(pm.contact_no = 0, '', pm.contact_no) AS patient_contact_no,
                ref.ref_name AS reference_name,
                IF(ap.superv_doctor_type = 2,rref.ref_name, emp.EMP_NAME) AS sv_name,
                (
                    CASE 
                         WHEN ap.superv_doctor_type = 1 THEN 'External'
                         WHEN ap.superv_doctor_type = 2 THEN 'Reference'
                         WHEN ap.superv_doctor_type = 3 THEN 'Internal'
                    END
                ) AS doctor_type,
                ap.admission_date,
                ap.release_date,
                avi.tot_day,
                (
                    CASE 
                        WHEN ap.status = 0 THEN 'Bed Assign'
                        WHEN ap.status = 1 THEN 'Bed Booked'
                        WHEN ap.status = 2 THEN 'Admitted'
                        WHEN ap.status = 3 THEN 'Pending Discharge'
                        WHEN ap.status = 4 THEN 'Discharge'
                        WHEN ap.status = 5 THEN 'Bed Booked Cancel'
                        WHEN ap.status = 6 THEN 'Bed Request Release'
                    END
                ) AS status_name,
                ap.discharge_reason,
                ab.bed_name,
                ROUND(IFNULL(avi.bed_bill, 0)) AS bed_cost,
                ROUND(IFNULL(ap.admission_fee, 0)) AS admission_fee,
                ROUND(IFNULL(atdvc.out_consultant_amount, 0)) AS out_consultant_cost,
                ROUND(IFNULL(atdvc.consultant_amount, 0)) AS consultant_cost,
                ROUND(IFNULL(atdv.package_operation_cost, 0)) AS package_operation_cost,
                ROUND(IFNULL(atdv.operation_theater_cost, 0)) AS  operation_theater_cost,
                ROUND(IFNULL(atdv.post_operative_bed_cost, 0)) AS post_operative_bed_cost,
                ROUND(IFNULL(atdv.surgeon_cost, 0)) AS surgeon_cost,
                ROUND(IFNULL(atdv.surgeon_team_cost, 0)) AS surgeon_team_cost,
                ROUND(IFNULL(atdv.anesthesia_cost, 0)) AS anesthesia_cost,
                ROUND(IFNULL(atdv.guest_doctor_cost, 0)) AS guest_doctor_cost,
                ROUND(IFNULL(atdv.blood_cost, 0)) AS blood_cost,
                ROUND(IFNULL(atdv.medicine_cost, 0)) AS medicine_cost,
                ROUND(IFNULL(atdvd.investigation_amount, 0)) AS investigation_cost,
                0 AS meal_cost,
                ROUND(IFNULL(oatvtable.service_cost_1, 0)) AS service_cost_1,
                ROUND(IFNULL(oatvtable.service_cost_2, 0)) AS service_cost_2,
                ROUND(IFNULL(oatvtable.service_cost_3, 0)) AS service_cost_3,
                ROUND(IFNULL(oatvtable.service_cost_4, 0)) AS service_cost_4,
                ROUND(IFNULL(oatvtable.service_cost_5, 0)) AS service_cost_5,
                ROUND(IFNULL(oatvtable.service_cost_6, 0)) AS service_cost_6,
                ROUND(IFNULL(oatvtable.service_cost_7, 0)) AS service_cost_7,
                ROUND(IFNULL(oatvtable.service_cost_8, 0)) AS service_cost_8,
                ROUND(IFNULL(oatvtable.service_cost_9, 0)) AS service_cost_9,
                ROUND(IFNULL(oatvtable.service_cost_10, 0)) AS service_cost_10,
                ROUND(IFNULL(oatvtable.service_cost_11, 0)) AS service_cost_11,
                ROUND(IFNULL(oatvtable.service_cost_12, 0)) AS service_cost_12,
                ROUND(IFNULL(oatvtable.service_cost_13, 0)) AS service_cost_13,
                ROUND(IFNULL(oatvtable.service_cost_14, 0)) AS service_cost_14,
                ROUND(IFNULL(oatvtable.service_cost_15, 0)) AS service_cost_15,
                ROUND(atv.bill_amount + IFNULL(avi.bed_bill, 0) + IFNULL(atdvc.out_consultant_amount, 0) + IFNULL(atdvc.consultant_amount, 0)) AS bill_amount,
                ROUND(atv.bill_refund_amount) AS return_bill_amount,
                ROUND(atv.discount) AS discount,
                ROUND(atv.mr_discount) AS mr_discount,
                ROUND(atv.less_discount) AS less_discount,
                ROUND(ap.over_all_discount) AS over_all_discount,
                ROUND(
                        (atv.bill_amount + IFNULL(avi.bed_bill, 0) + IFNULL(atdvc.out_consultant_amount, 0) + IFNULL(atdvc.consultant_amount, 0))
                        -
                        (
                            atv.bill_refund_amount + atv.mr_discount + atv.less_discount + ap.over_all_discount
                        )
                    ) AS net_bill,
                ROUND(atv.bill_receivable) AS bill_receivable,
                ROUND(atv.due_receivable) AS due_receivable,
                ROUND(atv.bill_receivable + atv.due_receivable) AS tot_receivable,
                ROUND(atv.return_payable) AS tot_return_payable,
                ROUND(
                    (
                        (
                            (atv.bill_amount + IFNULL(avi.bed_bill, 0) + IFNULL(atdvc.out_consultant_amount, 0) + IFNULL(atdvc.consultant_amount, 0))
                            -
                            (
                                atv.bill_refund_amount + atv.mr_discount + atv.less_discount + ap.over_all_discount
                            )
                        )
                        +
                        (
                           atv.return_payable 
                        )
                    )
                    -
                    (
                        atv.bill_receivable + atv.due_receivable
                    )
                ) AS due
            FROM 
                bf_admission_patient AS ap
            LEFT JOIN 
                bf_admission_virtual_info AS avi
                    ON ap.id = avi.admission_id
            LEFT JOIN 
                bf_admission_transaction_view AS atv
                    ON ap.id = atv.admission_id
            LEFT JOIN 
                bf_admission_bed AS ab
                    ON avi.last_bed_id = ab.id
            LEFT JOIN 
                bf_admission_room AS ar
                    ON ab.bed_room_id = ar.id
            LEFT JOIN 
                bf_patient_master AS pm 
                    ON pm.id = ap.patient_id
            LEFT JOIN 
                bf_lib_reference AS ref
                    ON ap.reference_doctor = ref.id
            LEFT JOIN 
                bf_lib_reference AS rref 
                    ON ap.supervision_doctor = rref.id AND ap.superv_doctor_type = 2
            LEFT JOIN 
                bf_hrm_ls_employee AS emp
                    ON ap.supervision_doctor = emp.EMP_ID AND (ap.superv_doctor_type = 1 OR ap.superv_doctor_type = 3)
            LEFT JOIN 
                bf_admission_transaction_dtls_view AS atdv
                    ON ap.id = atdv.admission_id AND atdv.service_id = 10
            LEFT JOIN 
                bf_admission_transaction_dtls_view AS atdvd
                    ON ap.id = atdvd.admission_id AND atdvd.service_id = 1
            LEFT JOIN 
                bf_admission_transaction_dtls_view AS atdvc
                    ON ap.id = atdvc.admission_id AND atdvc.service_id = 20
            LEFT JOIN 
                (

                    SELECT 
                        oatv.admission_id,
                        oatv.service_id,
                        SUM(IF(o.id = 1, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_1,
                        SUM(IF(o.id = 2, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_2,
                        SUM(IF(o.id = 3, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_3,
                        SUM(IF(o.id = 4, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_4,
                        SUM(IF(o.id = 5, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_5,
                        SUM(IF(o.id = 6, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_6,
                        SUM(IF(o.id = 7, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_7,
                        SUM(IF(o.id = 8, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_8,
                        SUM(IF(o.id = 9, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_9,
                        SUM(IF(o.id = 10, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_10,
                        SUM(IF(o.id = 11, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_11,
                        SUM(IF(o.id = 12, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_12,
                        SUM(IF(o.id = 13, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_13,
                        SUM(IF(o.id = 14, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_14,
                        SUM(IF(o.id = 15, IFNULL(oatv.other_service_amount, 0), 0)) AS service_cost_15
                    FROM 
                        bf_lib_otherservice AS o
                    LEFT JOIN 
                        bf_admission_transaction_dtls_view AS oatv
                            ON oatv.other_service_id = o.id
                    GROUP BY 
                        oatv.admission_id
                ) AS oatvtable 
                    ON ap.id = oatvtable.admission_id AND oatvtable.service_id = 4
                WHERE 
                    {$con}
                {$hcon}
                LIMIT {$offset},{$limit}
        ";
        $records = $this->db->query($sql)->result();
       // print_r($this->db->last_query($records));
       // echo '<pre>';print_r($records);exit;
        return $records;
    }
}