
<div class="box" id="print_id">
    <style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<?php echo report_header() ?>

       <div class="text-center">
          <?php if(!empty($records)):?>
              <h3>Money Receipt Wise Collection(<?php echo $pharmacy_name; ?>)</h3>
          <?php endif; ?>
          <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
            <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
            <?php else: ?>
                <h6>Date <?php echo date('d/m/Y'); ?> </h6>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered report-table">
                <thead>
                    <tr>
                       <th>SL</th>
                       <th>MR. No</th>
                       <th>MR. Date</th>
                       <th>Client Type</th>
                       <th>Client Name</th>
                       <th>Contact No</th>
                       <th>P.Bill</th>   
                       <th>R.Bill</th>
                       <th>P.Amount</th>
                       <th>R.Amount</th>
                       <th>L.Discount</th>
                       <th>R.L.Discount</th>
                       <th>Over.Discount</th>
                       <th>R.Over.Discount</th>
                       <th>NS.Discount</th>
                       <th>Due</th>
                       
                   </tr>
               </thead>
               <tbody>
                  <?php if($records):
                  foreach($records as $key => $record){  ?>
                  <tr>
                    <td><?php echo $key+1; ?></td>
                    <td><?php echo $record->mr_no; ?></td>        		
                    <td><?php echo date('d/m/Y (h:i:sa)',strtotime($record->created_date)); ?></td>
                    <td><?php echo $record->customer_type_name; ?></td>
                    <td><?php echo $record->client_name;?></td>
                    <td></td>
                    <td><?php echo $record->tot_bill;?></td>
                    <td><?php echo $record->return_bill;?></td>
                    <td><?php echo ($record->paid + $record->due_paid); ?></td>
                    <td><?php echo $record->return_paid; ?></td>
                    <td><?php echo $record->less_discount; ?></td>
                    <td><?php echo $record->return_less_discount; ?></td>
                    <td><?php echo $record->overall_discount; ?></td>
                    <td><?php echo $record->retrun_overall_discount; ?></td>
                    <td><?php echo $record->ns_discount; ?></td>
                    <td><?php echo $record->due; ?></td>
        
                </tr>
                <?php } ?>

          <?php else : ?>
                <tr>
                    <td colspan="16" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
                </tr>
            <?php endif; ?>
            </tbody>    
        </table>





        
    </div>
</div>
<script type="text/javascript">
    window.print();
    window.close();
</script>
