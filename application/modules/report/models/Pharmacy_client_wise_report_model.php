<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy_client_wise_report_model extends CI_Model {

    public function getPharmacyClientList($tcon, $limit, $offset, $pharmacy_id = 0, $type = 0)

    {
        $con = '';
        $hcon = '';
        if (!$type) {
           // $hcon = 'HAVING due > 0';
        }
        $cmr_no = '';
        $pmr_no = '';
        $apmr_no = '';
        $emr_no = '';
        $hmr_no = '';
         if ($pharmacy_id == 200 || !$pharmacy_id) {
            $master_sale = 'bf_pharmacy_sales_mst';
            $tran_table = 'bf_pharmacy_payment_transaction';
            $f_admission_id = "admission_patient_id";
            $con .= " AND pt.pharmacy_id = 200";
        } else {
            $master_sale = 'bf_pharmacy_indoor_sales_mst';
            $tran_table = 'bf_pharmacy_sub_payment_transaction';
            $f_admission_id = "admission_id";
            $con .= " AND pt.pharmacy_id = $pharmacy_id";
        }
        if ($type == 1) {
            $customer_type= $tcon['customer_type']; 
            $client_id = $tcon['client_id'];
            $con .= " AND pt.customer_type = $customer_type";
            if ($customer_type == 3) {
             
                $con .= " AND pt.customer_id = $client_id";
            } elseif ($customer_type == 4) {
                $con .= " AND pt.employee_id = $client_id";
            }
        }

        if ($this->input->is_ajax_request()) {
            $c_type = $this->input->post('pharmacy_customer_type', true);
            if ($c_type) {
                $con .= " AND pt.customer_type = $c_type";
                $mr_no = trim($this->input->post('mr_num', true));
                if ($mr_no && $c_type == 3) {
                    $cmr_no = " AND cpsm.sale_no LIKE '%$mr_no'";
                }  elseif (($mr_no && $c_type == 4)) {
                    $emr_no = " AND epsm.sale_no LIKE '%$mr_no%'";
                } 
            }
            $c_name = trim($this->input->post('common_text_search', true));
            if ($c_name) {
                $con .= " AND pc.customer_name LIKE '%$c_name%' OR emp.EMP_NAME LIKE '%$c_name%'";
            }
            $due_paid = $this->input->post('due_paid', true);
            if ($due_paid && !$type) {
                if ($due_paid == 1) {
                    
                    $hcon = "HAVING due > 0";
                } else {
                    $hcon = "HAVING due <= 0";
                }
            }
        }
       // print_r($con);
        $sql = "";
        $sql .= "
                SELECT
                    SQL_CALC_FOUND_ROWS
                    pt.id,
                   
                    ROUND(IFNULL(SUM(IF(pt.type = 2, pt.overall_discount, 0)),0)) as overall_discount,
                    ROUND(IFNULL(SUM(IF(pt.type = 3, pt.overall_discount, 0)),0)) as return_overall_discount,
                    (
                        CASE
                           
                            WHEN pt.customer_type = 3 THEN pt.customer_id
                            WHEN pt.customer_type = 4 THEN pt.employee_id
                            
                        END
                    ) AS client_id,
                    (
                        CASE
                           
                            WHEN pt.customer_type = 3 THEN pc.customer_name
                            WHEN pt.customer_type = 4 THEN emp.EMP_NAME
                           
                        END
                    ) AS client_name,
                    (
                        CASE
                          
                            WHEN pt.customer_type = 3 THEN 'Customer'
                            WHEN pt.customer_type = 4 THEN 'Employee'
                          
                        END
                    ) AS customer_type_name,
                    pt.customer_type,
                    (
                        CASE
                            WHEN pt.customer_type = 3 THEN cpsmtable.bill
                            WHEN pt.customer_type = 4 THEN epsmtable.bill
                           
                        END
                    ) AS bill,
                    (
                        CASE
                            WHEN pt.customer_type = 3 THEN cpsmtable.return_bill
                            WHEN pt.customer_type = 4 THEN epsmtable.return_bill
                            
                        END
                    ) AS return_bill,

                    (
                        CASE
                            WHEN pt.customer_type = 3 THEN cpsmtable.discount
                            WHEN pt.customer_type = 4 THEN epsmtable.discount
                           
                        END
                    ) AS discount,
                    (
                        CASE
                            WHEN pt.customer_type = 3 THEN cpsmtable.less_discount
                            WHEN pt.customer_type = 4 THEN epsmtable.less_discount
                            
                        END
                    ) AS less_discount,
                    (
                        CASE
                            WHEN pt.customer_type = 3 THEN cpsmtable.return_less_discount
                            WHEN pt.customer_type = 4 THEN epsmtable.return_less_discount
                           
                        END
                    ) AS return_less_discount,

                    SUM(IF(pt.type = 1, pt.amount, 0)) AS cash_amount,
                    SUM(IF(pt.type = 2, pt.amount, 0)) AS due_cash_amount,
                    SUM(IF(pt.type = 3, pt.amount, 0)) AS return_amount,
                    (SUM(IF(pt.type = 1, pt.amount, 0)) + SUM(IF(pt.type = 2, pt.amount, 0))) as payment,
                    ((SUM(IF(pt.type = 1, pt.amount, 0)) + SUM(IF(pt.type = 2, pt.amount, 0))) - SUM(IF(pt.type = 3, pt.amount, 0))) as total_cash_amount,

                    ROUND(
                        /*            sale          */
                        (
                            IF (
                                (
                                
                                    CASE
                                        WHEN pt.customer_type = 3 THEN cpsmtable.bill
                                        WHEN pt.customer_type = 4 THEN epsmtable.bill
                                       
                                    END
                                ) 
                                >
                                (IFNULL(SUM(IF(pt.type = 1, pt.amount, 0)), 0)),
                                (
                                    (
                                        CASE
                                            WHEN pt.customer_type = 3 THEN cpsmtable.bill
                                            WHEN pt.customer_type = 4 THEN epsmtable.bill
                                            
                                        END
                                    ) 
                                    -
                                    (
                                        (
                                            CASE
                                                WHEN pt.customer_type = 3 THEN cpsmtable.less_discount
                                                WHEN  pt.customer_type = 4 THEN epsmtable.less_discount
                                               
                                            END
                                        )
                                        +
                                        (
                                            IFNULL(SUM(IF(pt.type = 1, pt.amount, 0)), 0)
                                        )
                                    )
                                ),

                                (
                                     
                                    (
                                        IFNULL(SUM(IF(pt.type = 1, pt.amount, 0)), 0)
                                    )
                                    -
                                    (
                                        (
                                            CASE
                                                WHEN pt.customer_type = 3 THEN cpsmtable.less_discount
                                                WHEN pt.customer_type = 4 THEN epsmtable.less_discount
                                               
                                            END
                                        )
                                        +
                                        (
                                            CASE
                                                WHEN pt.customer_type = 3 THEN cpsmtable.bill
                                                WHEN pt.customer_type = 4 THEN epsmtable.bill
                                                
                                            END
                                        )
                                    )
                                )

                            )
                            /* endif; */
                        )
                        /*               due paid           */
                        -
                        (
                            (
                                SUM(IF(pt.type = 2, pt.amount, 0)) 
                            )
                            +
                            (
                                IFNULL(SUM(IF(pt.type = 2, pt.overall_discount, 0)),0)
                            )
                        )
                        /*               Retrun Bill           */
                        -
                        (
                            IF(
                                (
                                    CASE
                                        WHEN pt.customer_type = 3 THEN cpsmtable.return_bill
                                        WHEN pt.customer_type = 4 THEN epsmtable.return_bill
                                       
                                    END
                                )
                                >
                                IFNULL(SUM(IF(pt.type = 3, pt.amount, 0)), 0),
                                (
                                    (
                                        CASE
                                            WHEN pt.customer_type = 3 THEN cpsmtable.return_bill
                                            WHEN pt.customer_type = 4 THEN epsmtable.return_bill
                                            
                                        END
                                    )
                                    -
                                    (
                                        (
                                            CASE
                                                WHEN pt.customer_type = 3 THEN cpsmtable.return_less_discount
                                                WHEN pt.customer_type = 4 THEN epsmtable.return_less_discount
                                               
                                            END
                                        )
                                        +
                                        (
                                            IFNULL(SUM(IF(pt.type = 3, pt.overall_discount, 0)),0)
                                        )
                                        +
                                        (
                                            IFNULL(SUM(IF(pt.type = 3, pt.amount, 0)), 0)
                                        )
                                    )
                                ),
                                (
                                    (
                                        IFNULL(SUM(IF(pt.type = 3, pt.amount, 0)), 0)
                                    )
                                    -
                                    (
                                        (
                                            CASE
                                                WHEN pt.customer_type = 3 THEN cpsmtable.return_less_discount
                                                WHEN pt.customer_type = 4 THEN epsmtable.return_less_discount
                                               
                                            END
                                        )
                                        +
                                        (
                                            IFNULL(SUM(IF(pt.type = 3, pt.overall_discount, 0)),0)
                                        )
                                        +
                                        (
                                            CASE
                                                WHEN pt.customer_type = 3 THEN cpsmtable.return_bill
                                                WHEN pt.customer_type = 4 THEN epsmtable.return_bill
                                                
                                            END
                                        )
                                    )
                                )
                            )
                        )
                    ) as due
                   
                FROM 
                    {$tran_table} as pt 
                /*          customer             */
                LEFT JOIN 
                    (
                        SELECT
                            cpsm.customer_id as customer_id,
                            ROUND(SUM(cpsm.tot_bill)) AS bill,
                            ROUND(SUM(cpsm.return_bill)) AS return_bill,
                            ROUND(SUM(cpsm.tot_normal_discount) + SUM(cpsm.tot_service_discount)) AS discount,
                            ROUND(SUM(cpsm.tot_less_discount)) AS less_discount,
                            ROUND(SUM(cpsm.return_less_discount)) AS return_less_discount
                        FROM
                            {$master_sale} as cpsm
                        WHERE
                            cpsm.customer_type = 3
                            {$cmr_no}
                        GROUP BY
                            cpsm.customer_id
                    ) as cpsmtable
                     ON cpsmtable.customer_id = pt.customer_id
                /*          employee             */
                LEFT JOIN 
                    (
                        SELECT
                            epsm.employee_id as employee_id,
                            ROUND(SUM(epsm.tot_bill)) AS bill,
                            ROUND(SUM(epsm.return_bill)) AS return_bill,
                            ROUND(SUM(epsm.tot_normal_discount) + SUM(epsm.tot_service_discount)) AS discount,
                            ROUND(SUM(epsm.tot_less_discount)) AS less_discount,
                            ROUND(SUM(epsm.return_less_discount)) AS return_less_discount
                        FROM
                            {$master_sale} as epsm
                        WHERE 
                            epsm.customer_type =4
                            {$emr_no}
                        GROUP BY
                            epsm.employee_id
                    ) as epsmtable
                     ON epsmtable.employee_id = pt.employee_id

              

               
                LEFT JOIN
                    bf_hrm_ls_employee as emp ON pt.employee_id = emp.EMP_ID 
                LEFT JOIN
                    bf_pharmacy_customer as pc ON pt.customer_id = pc.id
                
                WHERE
                    pt.id > 0
                    {$con}
                GROUP BY 
                    
                     pt.customer_id,
                   
                     pt.employee_id,
                     pt.customer_type 
                    {$hcon}
                ORDER BY
                    due DESC,
                    pt.id DESC
                ";
            if ($type != 1) {
                $sql .= "
                    LIMIT 
                    {$offset},{$limit}
                ";
            }

            if ($type == 1) {
                $result = $this->db->query($sql)->row();
            } else {
                $result = $this->db->query($sql)->result();
            }
           // print_r($this->db->last_query($result));
           //echo '<pre>';print_r($result);exit;
            return $result;
    }

	public function select_all_pharmcy_client_information($condition,$limit,$offset,$pharmacy_id)
	{

        if($this->input->post('pharmacy_name')=="200" || $this->input->post('pharmacy_name')=="")
        {

		 $records = $this->db->select("
        	 			SQL_CALC_FOUND_ROWS
                        psm.id,
                        psm.cost_paid_by,
                        psm.customer_type,
                        psm.created_date,
                        psm.admission_id,
                        psm.customer_id,
                        psm.patient_id,
                        psm.employee_id,
                        SUM(psm.tot_bill) as tot_bill,
                        SUM(psm.tot_less_discount) as tot_less_discount,
                        SUM(psm.tot_normal_discount+psm.tot_service_discount) as total_discount,
                        IFNULL(cusm.customer_name, IFNULL(emp.EMP_NAME, IFNULL(pmast.patient_name, IFNULL(adpmst.patient_name,0)))) as customer_name
                     ",false)
                    ->from('pharmacy_sales_mst as psm')

                    ->join('bf_patient_master as pmast','psm.patient_id=pmast.id AND psm.customer_type!=6','left')
                    ->join('bf_admission_patient as adp','adp.id=psm.admission_id AND psm.customer_type!=6','left')
                    ->join('bf_hrm_ls_employee as emp','emp.EMP_ID=psm.employee_id AND psm.customer_type!=6','left')
                    ->join('bf_pharmacy_customer as cusm','cusm.id=psm.customer_id AND psm.customer_type!=6','left')
                    ->join('bf_patient_master as adpmst','adp.patient_id=adpmst.id','left')
                    ->where('psm.customer_type != ',6)
                    ->where($condition)
                    ->group_by('psm.admission_id')
                    ->group_by('psm.customer_id')
                    ->group_by('psm.patient_id')
                    ->group_by('psm.employee_id')
                    ->limit($limit,$offset)
                    ->get()
                    ->result();

        $records[] = $this->db->select("
        	 			SQL_CALC_FOUND_ROWS 
                        psm.id,
                        '6' as customer_type,
                        0 as admission_id,
                        0 as customer_id,
                        0 as patient_id,
                        0 as employee_id,
                        IFNULL(SUM(psm.tot_bill),0) as tot_bill,
                        IFNULL(SUM(psm.tot_less_discount),0) as tot_less_discount,
                        SUM(psm.tot_normal_discount+psm.tot_service_discount) as total_discount,
                        'Hospital' as customer_name
                     ",false)
                    ->from('pharmacy_sales_mst as psm')
                    ->where('psm.customer_type',6)
                    ->limit($limit,$offset)
                    ->get()
                    ->row();

        foreach ($records as $key => $record) {
        	if($record->customer_id && $record->customer_type!=6){
        		$tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
        										IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
        										')
        								->where('customer_id',$record->customer_id)
        								->where('customer_type != ',6)
        								->get('bf_pharmacy_payment_transaction')
        								->row();
        	}elseif($record->patient_id && $record->customer_type!=6){
        		$tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
        										IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
        										')
        								->where('patient_id',$record->patient_id)
        								->where('customer_type != ',6)
        								->get('bf_pharmacy_payment_transaction')
        								->row();
        	}elseif($record->admission_id && $record->customer_type!=6){
        		$tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
        										IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
        										')
        								->where('admission_patient_id',$record->admission_id)
        								->where('customer_type != ',6)
        								->get('bf_pharmacy_payment_transaction')
        								->row();
        	}elseif($record->employee_id && $record->customer_type!=6){
        		$tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
        										IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
        										')
        								->where('employee_id',$record->employee_id)
        								->where('customer_type != ',6)
        								->get('bf_pharmacy_payment_transaction')
        								->row();
        	}elseif($record->customer_type==6){
        		$tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
        										IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
        										')
        								->where('customer_type',6)
        								->get('bf_pharmacy_payment_transaction')
        								->row();
        	}
        	$records[$key]->tot_paid=$tot_paid->total_paid;
        	$records[$key]->total_return=$tot_paid->total_return;


        }
        }
        else 
        {
            $records = $this->db->select("
                        SQL_CALC_FOUND_ROWS
                        psm.id,
                        psm.customer_type,
                        psm.created_date,
                        psm.admission_id,
                        psm.customer_id,
                        psm.patient_id,
                        psm.employee_id,
                        psm.sale_no,
                        SUM(psm.tot_bill) as tot_bill,
                        SUM(psm.tot_return) as total_return,
                        SUM(psm.tot_less_discount) as tot_less_discount,
                        SUM(psm.tot_normal_discount+psm.tot_service_discount) as total_discount,
                        IFNULL(cusm.customer_name, IFNULL(emp.EMP_NAME, IFNULL(pmast.patient_name, IFNULL(adpmst.patient_name,0)))) as customer_name
                     ",false)
                    ->from('pharmacy_indoor_sales_mst as psm')

                    ->join('bf_patient_master as pmast','psm.patient_id=pmast.id AND psm.customer_type!=6','left')
                    ->join('bf_admission_patient as adp','adp.id=psm.admission_id AND psm.customer_type!=6','left')
                    ->join('bf_hrm_ls_employee as emp','emp.EMP_ID=psm.employee_id AND psm.customer_type!=6','left')
                    ->join('bf_pharmacy_customer as cusm','cusm.id=psm.customer_id AND psm.customer_type!=6','left')
                    ->join('bf_patient_master as adpmst','adp.patient_id=adpmst.id','left')

                    ->where($condition)
                    ->where('psm.customer_type != ',6)
                    ->group_by('psm.admission_id')
                    ->group_by('psm.customer_id')
                    ->group_by('psm.patient_id')
                    ->group_by('psm.employee_id')
                    ->limit($limit, $offset)
                    ->get()
                    ->result();

        $records[] = $this->db->select("
                        SQL_CALC_FOUND_ROWS
                        psm.id,
                        '6' as customer_type,
                        0 as admission_id,
                        0 as customer_id,
                        0 as patient_id,
                        0 as employee_id,
                        IFNULL(SUM(psm.tot_bill),0) as tot_bill,
                        IFNULL(SUM(psm.tot_return),0) as total_return,
                        IFNULL(SUM(psm.tot_less_discount),0) as tot_less_discount,
                        SUM(psm.tot_normal_discount+psm.tot_service_discount) as total_discount,
                        'Hospital' as customer_name
                     ",false)
                    ->from('pharmacy_indoor_sales_mst as psm')
                    ->where('psm.customer_type',6)
                    ->get()
                    ->row();

        foreach ($records as $key => $record) {
            if($record->customer_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('customer_id',$record->customer_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_sub_payment_transaction')
                                        ->row();
            }elseif($record->patient_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('patient_id',$record->patient_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_sub_payment_transaction')
                                        ->row();
            }elseif($record->admission_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('admission_patient_id',$record->admission_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_sub_payment_transaction')
                                        ->row();
            }elseif($record->employee_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('employee_id',$record->employee_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_sub_payment_transaction')
                                        ->row();
            }elseif($record->customer_type==6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('customer_type',6)
                                        ->get('bf_pharmacy_sub_payment_transaction')
                                        ->row();
            }
            $records[$key]->tot_paid=$tot_paid->total_paid;
            $records[$key]->total_refund=$tot_paid->total_return;


        }
        }
        // echo "<pre>";
        // print_r($records);
        // exit();
		return $records;
	}
	
}