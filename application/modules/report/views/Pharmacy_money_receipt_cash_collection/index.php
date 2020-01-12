
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<?php echo report_header() ?>

    <div class="text-center">
      <h3>Pharmacy Money Receipt Wise Collection</h3>
    <?php if(!empty($c_info)):?>
      
        <h4><?php if($c_info['0']->pharmacy_name!=''){ echo $c_info['0']->pharmacy_name;} else {echo $pharmacy_name->pharmacy_name;}; ?></h4>
          <?php endif; ?>

         <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
          <?php echo date('d/m/Y');?> 
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
                <th>Client ID</th>
                <th>Contact No</th>
                <th>Total Bill</th>   
                <th>Total Discount</th>   
                <th>Total Paid</th>
                <th>Total Due</th>
                <th>total Return</th>
             
            </tr>
        </thead>
        <tbody>
      

      <?php if($c_info):

        $sl=0;
        $tot_bill=0;
        $discount=0;
        $tot_paid=0;
        $tot_due=0;
        $tot_return=0;
      foreach($c_info as $record){ 
                $tot_bill+= $record->tot_bill;  
                $tot_paid+= $record->tot_paid;   
                $tot_due+= $record->tot_due;
                $tot_return+= $record->return_bill;
                $discount+= intval($record->tot_normal_discount+$record->tot_service_discount+$record->tot_less_discount);
                 ?>
        	<tr>
                <td><?php echo $sl+=1;?></td>
                <td><?php echo $record->sale_no; ?></td>        		
                <td><?php echo date('d/m/Y (h:i:sa)',strtotime($record->created_date)); ?></td>
                <td>
                <?php 
                if($record->customer_type==1){
                    echo 'Admission patient';
                }elseif ($record->customer_type==2) {
                   echo 'Patient';
                }
                elseif ($record->customer_type==3) {
                   echo 'Customer' ;               
                }
                elseif ($record->customer_type==4) {
                    echo 'Employee';
                }
                 elseif ($record->customer_type==5) {
                    echo 'Doctor';
                }
                elseif ($record->customer_type==6) {
                    echo 'Hospital';
                }


                ?>
                        
                </td>
                <td><?php echo $record->name;?></td>
                <td><?php echo $record->customer_id;?></td>
                <td><?php echo $record->mobile;?></td>
                <td><?php echo intval($record->tot_bill);?></td>
                <td><?php echo intval($record->tot_normal_discount)+intval($record->tot_service_discount)+intval($record->tot_less_discount);?></td>
                <td><?php echo intval($record->tot_paid)?></td>
                <td><?php echo intval($record->tot_due)?></td>
                <td><?php echo intval($record->return_bill)?></td>


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
                <th> Paid=<?php echo $tot_paid;?></th>
                <th> Due=<?php echo $tot_due;?></th>
                <th> Return=<?php echo $tot_return;?></th>
            </tr>
         <tr>
            <th colspan="13">Total Bill</th>
            <th><?php echo $tot_bill;?></th>
        </tr>
        <tr>
            <th colspan="13">Total Paid</th>
            <th><?php echo $tot_paid;?></th>
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
                <td colspan="12" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
        <?php endif; ?>
        </tbody>   
    </table>


  
 
    <?php echo ($c_info != null) ?  $this->pagination->create_links() : "" ; ?>
    
    </div>
</div>

