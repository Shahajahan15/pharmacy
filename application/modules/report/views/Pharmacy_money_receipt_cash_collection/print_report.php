<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
    .page-break{
    page-break-before: always;
    page-break-after: always;
  }
</style>
<div class="box">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
 <center>
     <?php if (!empty($c_info)) : ?>
    <div class="text-center">
        <h3>Pharmacy Money Receipt Wise Collection(<?php if($c_info['0']->pharmacy_name!=''){ echo $c_info['0']->pharmacy_name;} else {echo $pharmacy_name->pharmacy_name;}; ?>)</h3>

      
        
    </div>
    <?php endif?>
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>MR. No</th>
                <th>MR. Date</th>              
                <th>Total Bill</th>   
                <th>Total Discount</th>   
                <th>Total Paid</th>
                <th>Total Due</th>
                <th>total Return</th>
             
            </tr>
        </thead>
        <tbody>
      

      <?php $sl=0;foreach($c_info as $record){ ?>
     
        	<tr>
                <td><?php echo $sl+=1;?></td>
                <td><?php echo $record->sale_no; ?></td>
        		
                <td><?php echo date('d/m/Y (h:i:sa)',strtotime($record->created_date)); ?></td>
               
                <td><?php echo intval($record->tot_bill);?></td>
                <td><?php echo intval($record->tot_normal_discount)+intval($record->tot_service_discount)+intval($record->tot_less_discount);?></td>
                <td><?php echo  intval($record->tot_paid)?></td>
                 <td><?php echo $record->tot_due?></td>
                <td><?php echo $record->tot_return?></td>


        	</tr>
 
  
           <?php }?>
        
        </tbody>
         
    </table>
   
    
    </div>
    </center>
</div>

<script type="text/javascript">
    window.print();
    window.close();
</script>
<?php echo $this->pagination->create_links(); ?>