
<div class="" id="admission_bill">
  <style type="text/css">
    .c_display_none{display: none;}
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
    @media print 
    {

    }
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Sheet for Collection/Bill Assesment Sheet</h3>

      
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table" id="example">
        
        <thead>
            <tr>
                <th>SL No</th>
                <th>Admission Id</th>
                <th>Patient Name</th>
                <th>Ref.Doctor</th>
                <th style="width: 100px;">Admission Date</th>
                <th>Discharge on</th>
                <th>Admisssion Fee</th>
                <th>Rent Bed/Cabin</th>
                <th>Operation/<br>Package</th>
                <th>O.T</th>
                <th>Post Op.</th>
                <th>Surgeon</th>
                <th>Surgeon Team</th>
                <th>Anesthesia</th>
                <th>Guest Dr.</th>
                <th>O.Blood</th>
                <th>Consultant</th>
                <th>Out Consultant</th>
                <?php if ($os_list) :
                  foreach ($os_list as $os):
                ?>
                <th><?php echo $os->otherservice_name; ?></th>
                <?php endforeach; endif; ?>
                <th>Investigations</th> 
                <th>Pt.Meal</th>
                <th>Total Bill</th>
                <th>Discount</th>
                <th>Refund</th> 
                <th>MR.Discount</th>
                <th>Overall Discount</th>        
                <th>Net Bill</th>
                <th>Paid Amount</th>
                <th>Return Amount</th>
                <th>Due</th>
                <th>Remark(if any)</th>
            </tr>
           
        </thead>
        <tbody>
        <?php $sl=0;foreach($records as $record){ 
         
          $over_all_discount = isset($virtual_bill[$record->id]) ? $virtual_bill[$record->id]['over_all_discount'] : 0 ;
           $tot_virtual_bill = isset($virtual_bill[$record->id]) ? ($virtual_bill[$record->id]['tot_bill'] - $virtual_bill[$record->id]['total_discount']) : 0 ;
           $op_bill = isset($operation_service_bill[$record->id]['operatin_package_amount']) ? $operation_service_bill[$record->id]['operatin_package_amount'] : 0 ;
           $op_theater_bill = isset($operation_service_bill[$record->id]['operation_theater_cost']) ? $operation_service_bill[$record->id]['operation_theater_cost'] : 0 ;
           $post_opeative_bill = isset($operation_service_bill[$record->id]['post_operative_bed_cost']) ? $operation_service_bill[$record->id]['post_operative_bed_cost'] : 0 ;
           $surgeon_bill = isset($operation_service_bill[$record->id]['surgeon_cost']) ? $operation_service_bill[$record->id]['surgeon_cost'] : 0 ;
           $surgeon_team_bill = isset($operation_service_bill[$record->id]['surgeon_team_cost']) ? $operation_service_bill[$record->id]['surgeon_team_cost'] : 0 ;
           $anesthesia_bill = isset($operation_service_bill[$record->id]['anesthesia_cost']) ? $operation_service_bill[$record->id]['anesthesia_cost'] : 0 ;
           $guest_doctor_bill = isset($operation_service_bill[$record->id]['guest_doctor_cost']) ? $operation_service_bill[$record->id]['guest_doctor_cost'] : 0 ;
           $blood_cost_bill = isset($operation_service_bill[$record->id]['blood_cost']) ? $operation_service_bill[$record->id]['blood_cost'] : 0 ;
           $tot_consultant_bill = isset($consultant_bill[$record->id]['consultant_price']) ? $consultant_bill[$record->id]['consultant_price'] : 0 ;
           $tot_out_consultant_bill = isset($consultant_bill[$record->id]['out_consultant_price']) ? $consultant_bill[$record->id]['out_consultant_price'] : 0 ;

          /* $diagnosis_bill = isset($diagnosis_service_bill[$record->id]['bill_amount']) ? (($diagnosis_service_bill[$record->id]['bill_amount'] + $diagnosis_service_bill[$record->id]['refund_paid']) - ($diagnosis_service_bill[$record->id]['bill_refund_amount'] + $diagnosis_service_bill[$record->id]['less_discount'] + $diagnosis_service_bill[$record->id]['mr_discount'])) : 0; */
          $diagnosis_bill = isset($diagnosis_service_bill[$record->id]['bill_amount']) ? ($diagnosis_service_bill[$record->id]['bill_amount'] - $diagnosis_service_bill[$record->id]['less_discount']) : 0;
          $mr_discount = isset($diagnosis_service_bill[$record->id]['mr_discount']) ? $diagnosis_service_bill[$record->id]['mr_discount'] : 0;

           $tot_bill = ($record->admission_fee + $tot_virtual_bill + $op_bill + $op_theater_bill + $post_opeative_bill + $surgeon_bill + $surgeon_team_bill + $anesthesia_bill + $guest_doctor_bill + $blood_cost_bill + $tot_consultant_bill + $tot_out_consultant_bill + $diagnosis_bill);
           $other_discount = isset($discount[$record->id]['tot_discount']) ? $discount[$record->id]['tot_discount'] : 0 ;
            $virtual_discount = isset($virtual_bill[$record->id]['total_discount']) ? $virtual_bill[$record->id]['total_discount'] : 0 ;

            $tot_refund_bill = isset($refund[$record->id][8]) ? $refund[$record->id][8] : 0;
            $tot_discount = $other_discount + $virtual_discount;
            $paid_amount = isset($total_paid[$record->id]['payable']) ? $total_paid[$record->id]['payable'] : 0 ;
            $return_amount = isset($total_paid[$record->id]['receiveable']) ? $total_paid[$record->id]['receiveable'] : 0 ;
          ?>
           <tr>
           <td><?php echo $sl+=1; ?></td>
           <td class="c_display"><a href="<?php echo site_url("/admin/patient_admission/report/index?admission_id=$record->id&admission_fee=$record->admission_fee&bed_charge=$tot_virtual_bill&operation_charge=$op_bill&ot=$op_theater_bill&post_op=$post_opeative_bill&surgeon=$surgeon_bill&surgeon_team=$surgeon_team_bill&anesthesia=$anesthesia_bill&guest_doctor=$guest_doctor_bill&o_blood=$blood_cost_bill&consultant=$tot_consultant_bill&out_consultant=$tot_out_consultant_bill&diagnosis=$diagnosis_bill&tot_bill=$tot_bill&discount=$tot_discount&refund=$tot_refund_bill&over_all_discount=$over_all_discount&mr_discount=$mr_discount&paid_amount=$paid_amount&return_amount=$return_amount"); ?>"><?php echo $record->admission_code; ?> </a></td>
           <td class="c_display_none"><?php echo $record->admission_code;?></td>
           <td><?php echo $record->patient_name; ?></td>
           <td><?php echo $record->doctor_name; ?></td>
           <!--<td><?php //echo date('d-m-Y h:m:i a',strtotime($record->admission_date)); ?></td>-->
          <!-- <td><?php //echo ($record->release_date) ? date('d-m-Y h:m:i a',strtotime($record->release_date)) : ""; ?></td>-->
          <td><?php  echo date('d-m-Y h:i:s a',strtotime($virtual_bill[$record->id]['start_date_time'])); ?></td>
          <td><?php 
            echo ($record->release_date) ? date('d-m-Y h:i:s a',strtotime($virtual_bill[$record->id]['end_date_time'])) : ""; 
          ?></td>
          <!-- <td><?php echo $record->release_date; ?></td>-->
           <td><?php echo $record->admission_fee; ?></td>
           <td><?php echo $tot_virtual_bill."(".$virtual_bill[$record->id]['tot_dayd'].")";; ?></td>
           <td><?php echo $op_bill; ?></td>
           <td><?php echo $op_theater_bill; ?></td>
           <td><?php echo $post_opeative_bill; ?></td>
           <td><?php echo $surgeon_bill; ?></td>
           <td><?php echo $surgeon_team_bill; ?></td>
           <td><?php echo $anesthesia_bill; ?></td>
           <td><?php echo $guest_doctor_bill; ?></td>
           <td><?php echo $blood_cost_bill; ?></td>
           <td><?php echo $tot_consultant_bill; ?></td>
           <td><?php echo $tot_out_consultant_bill; ?></td>
           <?php $tot_other_bill = 0; 
            foreach ($os_list as $val) : 
              $other_bill = isset($other_service_bill[$record->id][$val->id]) ? $other_service_bill[$record->id][$val->id] : 0;
              $tot_other_bill += $other_bill;
           ?>
           <td><?php echo $other_bill; ?></td>
           <?php endforeach; ?>
           <td><?php echo $diagnosis_bill; ?></td>
           <td>0</td>
           <td><?php echo $total_bill = ($tot_bill + $tot_other_bill); ?></td>
           <td><?php echo $tot_discount; ?></td>
           <td><?php echo $tot_refund_bill; ?></td>
           <td><?php echo round($mr_discount); ?></td>
           <td><?php echo $over_all_discount; ?></td>
           <td><?php echo $net_bill = ($total_bill - ($over_all_discount + $mr_discount + $tot_refund_bill)); ?></td>
           <td><?php echo $paid_amount; ?></td>
           <td><?php echo $return_amount; ?></td>
           <td><?php echo ($net_bill + $return_amount ) - ($paid_amount); ?></td>
           <td>&nbsp;</td>
           </tr>
           <?php } ?>
        </tbody>
        
    </table>
    <?php echo ($records != null) ?  $this->pagination->create_links() : "" ; ?>
    </div>
</div>

