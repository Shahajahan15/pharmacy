
<div class="box" id="print_id">
    <style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
    @media print
   {
      p.bodyText {font-family:georgia, times, serif;}
      .not_display{
        display: none;
      }
   }
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
              
                       <th>P.Bill</th>   
                       <th>R.Bill</th>
                       <th>P.Amount</th>
                       <th>DP.Amount</th>
                       <th>R.Amount</th>
                       <th>L.Discount</th>
                       <th>R.L.Discount</th>
                       <th>Over.Discount</th>
                       <th>R.Over.Discount</th>
                       <th>NS.Discount</th>
                       <th>Due</th>
                       <th class="not_display">Details</th>
                   </tr>
               </thead>
               <tbody>
                  <?php if($records):

                    $tot_bill=0;
                    $discount=0;
                    $tot_paid=0;
                    $tot_due_paid = 0;
                    $tot_due=0;
                    $tot_return=0;
                  foreach($records as $key => $record){
                    $tot_bill+= $record->tot_bill;  
                    //$tot_paid+= $record->paid+ $record->due_paid;   
                    $tot_paid+= $record->paid;
                    $tot_due_paid += $record->due_paid;
                    $tot_due+= $record->due;
                    $tot_return+= $record->return_bill;
                    $discount+= $record->ns_discount+$record->less_discount+$record->return_less_discount+$record->overall_discount+$record->retrun_overall_discount;
                   
                
                    ?>
                  <tr>
                    <td><?php echo $key+1; ?></td>
                    <td><?php echo $record->mr_no; ?></td>        		
                    <td><?php echo date('d/m/Y (h:i:sa)',strtotime($record->created_date)); ?></td>
                    <td><?php echo $record->customer_type_name; ?></td>
                    <td><?php echo $record->client_name;?></td>
                    <td><?php echo $record->tot_bill;?></td>
                    <td><?php echo $record->return_bill;?></td>
                    <td><?php echo $record->paid; ?></td>
                    <td><?php echo $record->due_paid; ?></td>
                    <td><?php echo $record->return_paid; ?></td>
                    <td><?php echo $record->less_discount; ?></td>
                    <td><?php echo $record->return_less_discount; ?></td>
                    <td><?php echo $record->overall_discount; ?></td>
                    <td><?php echo $record->retrun_overall_discount; ?></td>
                    <td><?php echo $record->ns_discount; ?></td>
                    <td><?php echo $record->due; ?></td>

                    <td class="not_display"><a class="btn btn-info btn-xs" href="<?php echo site_url(SITE_AREA . '/pharmacy_money_receipt_cash_collection/report/money_receipt_details/'.$record->id."/".$record->mr_no."/".$pharmacy_id); ?>" style="text-decoration:none;">view</a></td>
                </tr>
                <?php } ?>
           <!--  <tfoot>
                <tr>
                    <th colspan="5">Total</th>
                    <th colspan="3" pull-right>Tot Bill=<?php echo $tot_bill; ?></th>
                    <th colspan="3">Tot paid=<?php echo $tot_paid;?></th>
                    <th>Tot due=<?php echo $tot_due;?></th>
                </tr>
            </tfoot> -->

        </table>
            <table class="table table-bordered">
              <tr>
                    <th colspan="9">Total</th>
                    <th> Bill=<?php echo $tot_bill; ?></th>
                    <th> Discount=<?php echo $discount;?></th>
                    
                    <th> Paid Amount=<?php echo $tot_paid;?></th>
                    <th> Due Paid Amount=<?php echo $tot_due_paid;?></th>
                    <th> Due=<?php echo $tot_due;?></th>
                    <th> Return=<?php echo $tot_return;?></th>
                </tr>
             <tr>
                <th colspan="13">Total Bill</th>
                <th><?php echo $tot_bill;?></th>
            </tr>
            <tr>
                <th colspan="13">Total Discount</th>
                <th><?php echo $discount;?></th>
            </tr>
            <tr>
                <th colspan="13">Total Paid</th>
                <th><?php echo ($tot_paid + $tot_due_paid);?></th>
            </tr>
            <tr>
                <th colspan="13">Total Due</th>
                <th><?php echo $tot_due; ?></th>
            </tr>
            <tr>
                <th colspan="13">Total Return</th>
                <th><?php echo $tot_return; ?></th>
            </tr>
          
          <?php else : ?>
                <tr>
                    <td colspan="16" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
                </tr>
            <?php endif; ?>
            </tbody>    
        </table>




        <span class="not_display"><?php echo ($records != null) ?  $this->pagination->create_links() : "" ; ?></span>
        
    </div>
</div>

