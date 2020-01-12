
<div class="box" id="print_id">
    <style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<?php echo report_header() ?>

       <div class="text-center">
 <p>
  <strong>
  Pharmacy Name:
    <?php
      if($pharmacy_id==200)
      {
        echo "Main Pharmacy";
      }
      else
      {
        echo "Sub Pharmacy";
      }  
    ?>
  </strong>
</p>      
<p><strong>MR No. <?php echo $mr_no; ?></strong></p>
<p><strong>Client Name. <?php 
if($record1[0]->customer_type == 1) {
  echo $record1[0]->admission_patient; 
}
elseif($record1[0]->customer_type == 2) {
  echo $record1[0]->patient_name; 
}
elseif($record1[0]->customer_type == 3) {
  echo $record1[0]->customer_name; 
}
elseif($record1[0]->customer_type == 4) {
  echo $record1[0]->EMP_NAME; 
}
elseif($record1[0]->customer_type == 5) {
  echo $record1[0]->EMP_NAME; 
}
else{
  echo "Hospital";
}




?></strong></p>

        </div> 

        <div class="table-responsive">
        <div class="col-sm-12 col-md-12 col-lg-12">
        <h3>Sale Report</h3>
        <table class="table table-bordered report-table">
                <thead>
                    <tr>
                       <th>SL</th>
                       <th>Date</th>
                       <th>Medicine Name</th>
                       <th>Unit Price</th>
                       <th>N.Discount(%)</th>
                       <th>N.Discount(Tk)</th>
                       <th>S.Discount(%)</th>
                       <th>S.Discount(Tk)</th>
                       <th>T.Discount(Tk)</th>
                       <th>Qnty</th>
                       <th>Sub Total</th>
         
                   </tr>
               </thead>
                 <tbody>
                  <?php if($record1):
                  $total = 0;
                  $total_qnty = 0;
                  foreach($record1 as $key => $record){  ?>
                  <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $record->created_date; ?></td>
                  <td><?php echo $record->product_name; ?></td>
                  <td><?php echo $record->unit_price; ?></td> 
                  <td><?php echo $record->normal_discount_percent; ?></td> 
                  <td><?php echo $record->normal_discount_taka; ?></td>
                  <td><?php echo $record->service_discount_percent; ?></td> 
                  <td><?php echo $record->service_discount_taka; ?></td>
                  <td><?php echo $record->total_discount; ?></td> 
                  <td><?php echo ($qnty=$record->qnty); ?></td> 
                  <td><?php echo ($sub_total=($record->unit_price*$record->qnty-$record->total_discount)); ?></td> 

                  </tr>
                <?php 
               $total = $total + $sub_total;
                $total_qnty = $total_qnty + $qnty;


                } ?>

         <tr>
          <td colspan="9"></td>
         <td><strong>Total Quantity:</strong><?php echo $total_qnty; ?></td>
         <td><strong>Total Price:</strong><?php echo $total; ?></td>
         </tr>
         <tr>
         <td colspan="2"><strong>Less Discount:</strong></td>
         <td><?php echo $record->tot_less_discount;  ?></td>
         <td colspan="2"><strong>Total Bill:</strong></td>
         <td><?php echo round($record->tot_bill);  ?></td>
         <td colspan="4"><strong>Paid Amount:</strong></td>
         <td><?php echo $record->tot_paid;  ?></td>

         </tr>
         <?php endif; ?>
            </tbody> 
       </table>
      </div>  

      <div class="col-sm-12 col-md-12 col-lg-12">
      <h3>Return Report</h3>
        <table class="table table-bordered report-table">
                <thead>
                    <tr>
                       <th>SL</th>
                       <th>Date</th>
                       <th>MR No.</th>
                       <th>Medicine Name</th>
                       <th>Price</th>
                       <th>Per Tot.Discount</th>
                       <th>Return Qnty</th>
                       <th>Return Sub Amount</th>
         
                   </tr>
               </thead>
                 <tbody>
                  <?php if($record2):
                  $total = 0;
                  $qnty = 0;
                  foreach($record2 as $key => $record){  ?>
                  <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $record->created_date; ?></td>  
                  <td><?php echo $record->mr_no; ?></td>
                  <td><?php echo $record->product_name; ?></td>  
                  <td><?php echo $record->price; ?></td>
                  <td><?php echo $record->tot_discount; ?></td> 
                  <td><?php echo $record->r_qnty; ?></td>  
                  <td><?php echo $record->r_sub_total; ?></td>
                  </tr>
                <?php 

$total = $total + $record->r_sub_total;
$qnty = $qnty + $record->r_qnty;

                } ?>

         <tr>
     
         <td colspan="6"></td>
         <td><strong>Total Qnty:</strong><?php echo $qnty; ?></td>
         <td><strong>Total Price:</strong><?php echo $total; ?></td>
         </tr>
        <tr>
         <td colspan="3"><strong>Less Discount:</strong></td>
         <td><?php echo round($record4[0]->tot_less_discount);  ?></td>
         <td colspan="3"><strong>Overall Discount:</strong></td>
         <td><?php echo round($record4[0]->overall_discount);  ?></td>

         </tr>
         <tr>
         <td colspan="1"><strong>T.Return Amount:</strong></td>
         <td><?php echo round($record4[0]->tot_return_amount);  ?></td>
         <td colspan="2"><strong>Paid Return Amount:</strong></td>
         <td><?php echo round($record4[0]->tot_paid_return_amount);  ?></td>
         <td colspan="2"><strong>Return Due:</strong></td>
         <td><?php echo round($record4[0]->tot_return_due);  ?></td>
         </tr>

         <?php endif; ?>
            </tbody> 
       </table>
       
      </div>  

            <div class="col-sm-12 col-md-12 col-lg-12">
      <h3>Due Payment Report</h3>
        <table class="table table-bordered report-table">
                <thead>
                    <tr>
                       <th>SL</th>
                       <th>Date</th>
                       <th>MR No</th>
                       <th>Due Paid</th>
         
                   </tr>
               </thead>
                 <tbody>
                  <?php if($record3):
                  $amount= 0;
                  foreach($record3 as $key => $record){  ?>
                  <tr>
                   <td><?php echo $key+1; ?></td>
                  <td><?php echo $record->create_time; ?></td> 
                  <td><?php echo $record->due_mr_no; ?></td>  
                  <td><?php echo $record->amount; ?></td> 

                    </tr>
                <?php 

$amount = $amount + $record->amount;

              } ?>

               <tr>
          <td colspan="3"><strong>Total Due Paid Amount:</strong></td>
         <td><?php echo $amount; ?></td>
         </tr>

         <?php endif; ?>
            </tbody> 
       </table>
       
      </div> 
    </div>
</div>

