
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
                <th>Doctor Type</th>
                <th>SV.Doctor</th>
                <th>Last Bed Name</th>
                <th>Admission Date</th>
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
                <th>Medicine Cost</th>
                <?php if ($os_list) :
                  foreach ($os_list as $os):
                ?>
                <th><?php echo $os->otherservice_name; ?></th>
                <?php endforeach; endif; ?>
                <th>Investigations</th> 
                <th>Pt.Meal</th>
                <th>Bill</th>
                <th>Discount</th>
                <th>Less Discount</th>
                <th>MR.Discount</th>
                <th>Overall Discount</th> 
                <th>Return Bill</th>        
                <th>Net Bill</th>
                <th>Receivable Amount</th>
                <th>Payable Amount</th>
                <th>Due</th>
                <th>Status</th>
                <th>Remark(if any)</th>
            </tr>
           
        </thead>
        <tbody>
        <?php foreach($records as $key => $record){
          ?>
           <tr>
           <td><?php echo $key+1; ?></td>
           <td class="c_display"><a href="<?php echo $record->id; ?>"><?php echo $record->admission_code; ?> </a></td>
           <td class="c_display_none"><?php echo $record->admission_code;?></td>
           <td><?php echo $record->patient_name; ?></td>
           <td><?php echo $record->reference_name; ?></td>
           <td><?php echo $record->doctor_type; ?></td>
           <td><?php echo $record->sv_name; ?></td>
           <td><?php echo $record->bed_name; ?></td>
          <td><?php  echo date('d-m-Y h:i:s a',strtotime($record->admission_date)); ?></td>
          <td><?php 
            echo ($record->release_date) ? date('d-m-Y h:i:s a',strtotime($record->release_date)) : ""; 
          ?></td>
           <td><?php echo $record->admission_fee; ?></td>
           <td><?php echo $record->bed_cost."(".$record->tot_day.")"; ?></td>
           <td><?php echo $record->package_operation_cost; ?></td>
           <td><?php echo $record->operation_theater_cost; ?></td>
           <td><?php echo $record->post_operative_bed_cost; ?></td>
           <td><?php echo $record->surgeon_cost; ?></td>
           <td><?php echo $record->surgeon_team_cost; ?></td>
           <td><?php echo $record->anesthesia_cost; ?></td>
           <td><?php echo $record->guest_doctor_cost; ?></td>
           <td><?php echo $record->blood_cost; ?></td>
           <td><?php echo $record->consultant_cost; ?></td>
           <td><?php echo $record->out_consultant_cost; ?></td>
           <td><?php echo $record->medicine_cost; ?></td>
           <?php $tot_other_bill = 0; 
            foreach ($os_list as $val) :
              $other_cost = $record->{"service_cost_$val->id"};
              //$other_cost = "$".$other_cost;
             //print_r($dd);exit;
           ?>
           <td><?php echo $other_cost; ?></td>
           <?php endforeach; ?>
           <td><?php echo $record->investigation_cost; ?></td>
           <td><?php echo $record->meal_cost; ?></td>
           <td><?php echo $record->bill_amount; ?></td>
           <td><?php echo $record->discount; ?></td>
           <td><?php echo $record->less_discount; ?></td>
           <td><?php echo $record->mr_discount; ?></td>
           <td><?php echo $record->over_all_discount; ?></td>
           <td><?php echo $record->return_bill_amount; ?></td>
           <td><?php echo $record->net_bill; ?></td>
           <td><?php echo $record->tot_receivable; ?></td>
           <td><?php echo $record->tot_return_payable; ?></td>
           <td><?php echo $record->due; ?></td>
           <td><?php echo $record->status_name; ?></td>
           <td><?php echo isset($reason[$record->discharge_reason]) ? $reason[$record->discharge_reason] : ""; ?></td>
           </tr>
           <?php } ?>
        </tbody>
        
    </table>
    <?php echo ($records != null) ?  $this->pagination->create_links() : "" ; ?>
    </div>
</div>

