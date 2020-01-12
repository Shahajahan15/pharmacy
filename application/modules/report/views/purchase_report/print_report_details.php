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
        <h3>Medicine Purchase Payment  Details</h3>
        <p><b>Supplier Name:<?php echo $records[0]->supplier_name; ?></b></p>

      
        
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>Bill No</th>
                <th>Total Bill</th>
                <th>Paid Amount</th>
                <th>Payable</th>
                <th>Received Qnty</th>

                
                
                
            </tr>
        </thead>
        <tbody>
      


     <?php $sl=1;
     $total_received_qnty = 0;
     $total_bill = 0;
     $total_paid = 0;
     $total_payable = 0;
           foreach($records as $record) :
                $total_received_qnty+=$record->received_qnty;
                $total_bill+=$record->total_price;
                $total_paid+=$record->paid;
                $total_payable+= $record->total_price - $record->paid;


            ?>

        <tr>
            <td><?php echo $sl++;?></td>
            <td><?php echo $record->bill_no;?></td>
            <td><?php echo round($record->total_price);?></td>
            <td><?php echo round($record->paid);?></td>
            <td><?php echo round($record->total_price-$record->paid);?></td>
            <td><?php echo round($record->received_qnty);?></td>

            
            
        </tr>
 
    <?php endforeach; ?>
         
        <tr>
            <td></td>
            <td></td>
             <td><b>Total Bill:</b><?php echo round($total_bill);?></td>
             <td><b>Total Paid:</b><?php echo round($total_paid);?></td>  
            <td><b>Total Payable:</b><?php echo round($total_payable);?></td>
            <td><b>Total Qnty:</b><?php echo round($total_received_qnty);?></td>
               
        </tr>
        </tbody>
         
    </table>
   
    
    </div>
    
</div>
<script type="text/javascript">
    window.print();
    window.close();
</script>
