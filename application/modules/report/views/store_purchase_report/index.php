<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
   <?php echo report_header() ?>
    <div class="text-center">
        <h3>Store Purchase Payment Report</h3>

      
        
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>Company Name</th>
                <th>Supplier Name</th>
                <th>Total Bill</th>
                <th>Paid Amount</th>
                <th>Payable</th>
                <th>Received Qnty</th>
                <th>Details</th>
                
            </tr>
        </thead>
        <tbody>
      
     <?php $sl=1;foreach($records as $record){?>
        <tr>
            <td><?php echo $sl++;?></td>
            <td><?php echo $record->company_name;?></td>
            <td><?php echo $record->supplier_name;?></td>
            <td><?php echo round($record->total_bill);?></td>
            <td><?php echo round($record->paid);?></td>
            <td><?php echo round($record->total_bill-$record->paid);?></td>
            <td><?php echo round($record->received_qnty);?></td>
            <td><a class="btn btn-info btn-xs" href="<?php echo site_url(SITE_AREA . '/store_purchase_report/report/per_payment_details/'.$record->supplier_id); ?>" style="text-decoration:none;">view</a></td>
  
        </tr>
 
     <?php }?>

  
         
        
        </tbody>
         
    </table>
   
    
    </div>
    <?php //echo  $this->pagination->create_links(); ?>
</div>