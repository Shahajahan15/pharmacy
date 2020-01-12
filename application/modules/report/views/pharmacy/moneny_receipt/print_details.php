
<div class="box" id="print_id">
    <style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<?php echo report_header() ?>

       <div class="text-center">
<p><strong>MR No. <?php echo $mr_no; ?></strong></p>
<p><strong>Client Name. <?php echo $client_name; ?></strong></p>
        </div> 

        <div class="table-responsive">
        <div class="col-sm-6 col-md-6 col-lg-6">
        <h3>Sale Report</h3>
        <table class="table table-bordered report-table">
                <thead>
                    <tr>
                       <th>SL</th>
                       <th>Product Name</th>
                       <th>Quantity</th>
                       <th>Unit Price</th>
                       <th>Sub Total</th>
         
                   </tr>
               </thead>
                 <tbody>
                  <?php if($record1):
                  $total = 0;
                  foreach($record1 as $key => $record){  ?>
                  <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $record->product_name; ?></td>  
                  <td><?php echo $record->qnty; ?></td> 
                  <td><?php echo $record->unit_price; ?></td>  
                  <td><?php echo ($sub_total=($record->unit_price*$record->qnty)); ?></td> 

                  </tr>
                <?php 
               $total = $total + $sub_total;


                } ?>

            <tr>
         <td></td>
         <td></td>
     
         <td></td>
         <td></td>
         <td>Total:<?php echo $total; ?></td>
         </tr>

         <?php endif; ?>
            </tbody> 
       </table>
      </div>  

      <div class="col-sm-6 col-md-6 col-lg-6">
      <h3>Return Report</h3>
        <table class="table table-bordered report-table">
                <thead>
                    <tr>
                       <th>SL</th>
                       <th>Product Name</th>
                       <th>Quantity</th>
                       <th>Sub Total</th>
         
                   </tr>
               </thead>
                 <tbody>
                  <?php if($record2):
                  $total = 0;
                  foreach($record2 as $key => $record){  ?>
                  <tr>
                   <td><?php echo $key+1; ?></td>
                  <td><?php echo $record->product_name; ?></td>  
                  <td><?php echo $record->r_qnty; ?></td>
                  <td><?php echo $record->r_sub_total; ?></td> 

                    </tr>
                <?php 

$total = $total + $record->r_sub_total;

                } ?>

         <tr>
     
         <td></td>
         <td></td>
         <td></td>
         <td>Total:<?php echo $total; ?></td>
         </tr>

         <?php endif; ?>
            </tbody> 
       </table>
       
      </div>  

            <div class="col-sm-12 col-md-12 col-lg-12">
      <h3>Payment Report</h3>
        <table class="table table-bordered report-table">
                <thead>
                    <tr>
                       <th>SL</th>
                       <th>Total Bill</th>
                       <th>Total Discount</th>
                       <th>Total Paid</th>
                       <th>Return Bill</th>
                       <th>Return Less Discount</th>
                       <th>Return Amount</th>
                       <th>Total Due</th>
         
                   </tr>
               </thead>
                 <tbody>
                  <?php if($record3):
                  foreach($record3 as $key => $record){  ?>
                  <tr>
                   <td><?php echo $key+1; ?></td>
                  <td><?php echo $record->tot_bill; ?></td> 
                  <td><?php echo $record->discount; ?></td>  
                  <td><?php echo $record->tot_paid; ?></td> 
                  <td><?php echo $record->return_bill; ?></td> 
                  <td><?php echo $record->return_less_discount; ?></td>
                  <td><?php echo $record->tot_return; ?></td>  
                  <td><?php echo $record->tot_due; ?></td> 

                    </tr>
                <?php } ?>

      

         <?php endif; ?>
            </tbody> 
       </table>
       
      </div> 
    </div>
</div>

<script type="text/javascript">
    window.print();
    window.close();
</script>