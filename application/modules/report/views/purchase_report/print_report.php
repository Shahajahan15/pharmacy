<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
    .page-break{
    page-break-before: always;
    page-break-after: always;
  }
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
   <?php echo report_header() ?>
    <div class="text-center">
        <h3>Medicine Purchase Payment Report</h3>

      
        
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
            
  
        </tr>
 
     <?php }?>
         
        
        </tbody>
         
    </table>
   
    
    </div>
    
</div>
<script type="text/javascript">
    window.print();
    window.close();
</script>