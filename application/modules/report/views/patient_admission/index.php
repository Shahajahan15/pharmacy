<style type="text/css">
  .cborder{padding: 3px;border:1px solid black;}
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
@media print 
    {
      .cborder{padding: 0px;border:0px solid;font-size: 10px;}
      .cdisplay{display: none;}
    }
</style>
  <a class="cdisplay" href="<?php echo site_url('admin/bill_assesment_sheet/report/index'); ?>">
    <button type="button" class="btn btn-success pull-right">
      <div class="glyphicon glyphicon-arrow-left pull-right"></div>
    </button>
  </a>
    <?php echo report_header() ?>
    <div class="text-center">
<h4>Patient Admission Bill</h4>
      
    </div>
<?php $ad_info = currentAdmissionPatientBedInfo($admission->id);
 ?>
<div class="cborder">
        <table class="table">
          <tr>
            <td colspan="2">Name of Patient &nbsp;:&nbsp;</td>
            <td colspan="2"><?php echo $record->patient_name; ?></td>
            <td colspan="2">Date of Admission &nbsp;:&nbsp;</td>
            <td colspan="2"><?php echo date("Y-m-d", strtotime($admission->admission_date)); ?></td>
            <td>Time &nbsp;:&nbsp;</td>
            <td><?php echo date("h:i:sa", strtotime($admission->admission_date)); ?></td>
          </tr>
          
          <tr>
            <td>Patient ID &nbsp;:&nbsp;</td>
            <td><?php echo $record->patient_no; ?></td>
            <td>Admission ID &nbsp;:&nbsp;</td>
            <td><?php echo $record->patient_no; ?></td>
            <td colspan="2">Date of Discharge :&nbsp;</td>
            <td colspan="2"><?php echo ($admission->release_date) ? date("Y-m-d", strtotime($admission->release_date)) : ""; ?></td>
            <td>Time &nbsp;:&nbsp;</td>
            <td><?php echo ($admission->release_date) ? date("h:i:sa", strtotime($admission->release_date)) : ""; ?></td>
          </tr>
          <tr>
            <td>Contact No&nbsp;:&nbsp;</td>
            <td><?php echo $record->contact_no; ?></td>
            <td >Ward -Surgery&nbsp;:&nbsp;</td>
            <td></td>
            <td>Bed No&nbsp;:&nbsp;</td>
            <td><?php echo $ad_info->bed_name; ?></td>
            <td>Room Type&nbsp;:&nbsp;</td>
            <td><?php echo $ad_info->bed_room_type; ?></td>
            <td>Cabin/Word No&nbsp;:&nbsp;</td>
            <td><?php echo $ad_info->room_name; ?></td>
          </tr>
          <tr>
            <td colspan="2">Reference Doctor Name&nbsp;:&nbsp;</td>
            <td colspan="3"><?php echo isset($emp_name[$admission->reference_doctor]) ? $emp_name[$admission->reference_doctor] : ""; ?></td>
            <td colspan="2">Surgeon Doctor Name&nbsp;:&nbsp;</td>
            <td colspan="3"></td>
          </tr>
        </table>
      </div>
      <br>

    <div class="table-responsive">
    <table class="table table-bordered report-table" id="example">
        <thead>
          <tr>
            <th>#</th>
            <th>Particular</th>
            <th>Cost</th>
          </tr>
        </thead>
        <tbody>
           <tr>
             <td>1</td>
             <td>Admission Fee</td>
             <td><?php echo $admission_fee; ?></td>
           </tr>
          <tr>
             <td>2</td>
             <td>Bed Charge/Cabin</td>
             <td><?php echo $bed_charge; ?></td>
          </tr>
          <tr>
            <td>3</td>
            <td>Operation/Package</td>
            <td><?php echo $operation_charge; ?></td>
          </tr>
          <tr>
            <td>4</td>
            <td>O.T Charge</td>
            <td><?php echo $ot; ?></td>
          </tr>
          <tr>
            <td>5</td>
            <td>Post Operative</td>
            <td><?php echo $post_op; ?></td>
          </tr>
          <tr>
            <td>6</td>
            <td>Surgeon</td>
            <td><?php echo $surgeon; ?></td>
          </tr>
          <tr>
            <td>7</td>
            <td>Surgeon Team</td>
            <td><?php echo $surgeon_team; ?></td>
          </tr>
          <tr>
             <td>8</td>
             <td>Anesthesia Doctor</td>
             <td><?php echo $anesthesia; ?></td>
          </tr>
          <tr>
             <td>9</td>
             <td>Gueset Doctor</td>
             <td><?php echo $guest_doctor; ?></td>
          </tr>
          <tr>
             <td>10</td>
             <td>Operation Blood</td>
             <td><?php echo $o_blood; ?></td>
          </tr>
          <tr>
             <td>11</td>
             <td>Consultent Fee</td>
             <td><?php echo $consultant; ?></td>
          </tr>
          <tr>
             <td>12</td>
             <td>Out Consultent Fee</td>
             <td><?php echo $out_consultant; ?></td>
          </tr>
          <?php 
           $o = 12;
           $tot_other_bill = 0;
            if ($os_list) :
            foreach($os_list as $os) : $o++;
           $other_bill = isset($other_service_bill[$admission->id][$os->id]) ? $other_service_bill[$admission->id][$os->id] : 0;
            $tot_other_bill += $other_bill;
            ?>
           <tr>
           <td><?php echo $o; ?></td>
           <td><?php echo $os->otherservice_name; ?></td>
           <td><?php echo $other_bill; ?></td>
           </tr>
         <?php endforeach; endif; ?>
         <tr>
           <td><?php echo $o+1; ?></td>
           <td>Investigation/Diagnostic</td>
           <td><?php echo $diagnosis; ?></td>
          </tr>
         <tr>
           <td><?php echo $o+2; ?></td>
           <td>Patient Meal</td>
           <td>0</td>
         </tr>
         <tr>
          <td colspan="2" align="right">Total Bill=</td>
          <td><?php echo $total_bill = ($tot_bill + $tot_other_bill); ?></td>
         </tr>
         <tr>
          <td colspan="2" align="right">Discount=</td>
          <td><?php echo $discount; ?></td>
         </tr>
         <tr>
          <td colspan="2" align="right">Refund Bill=</td>
          <td><?php echo $refund; ?></td>
         </tr>
         <tr>
            <td colspan="2" align="right">Money Receive Discount=</td>
          <td><?php echo $mr_discount; ?></td>
         </tr>
         <tr>
          <td colspan="2" align="right">Overall Discount=</td>
          <td><?php echo $over_all_discount; ?></td>
         </tr>
         <tr>
          <td colspan="2" align="right">Net Bill=</td>
          <td><?php echo $net_bill = ($total_bill - ($over_all_discount + $refund + $mr_discount)); ?></td>
         </tr>
         <tr>
          <td colspan="2" align="right">Paid Amount=</td>
          <td><?php echo $paid_amount; ?></td>
         </tr>
         <tr>
          <td colspan="2" align="right">Return Amount=</td>
          <td><?php echo $return_amount; ?></td>
         </tr>
         <tr>
          <td colspan="2" align="right">Due=</td>
          <td><?php echo ($net_bill - ($return_amount + $paid_amount)); ?></td>
         </tr>
          <!-- <tr>
              <td colspan="2">Less Discount(if any)=</td>
              <td>0</td>
           </tr>
           <tr>
              <td colspan="2">Net Payable=</td>
              <td>0</td>
           </tr> -->
           
        </tbody>
        
    </table>
    </div>
</div>

