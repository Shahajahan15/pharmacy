<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
</style>
   <?php echo report_header() ?>
    <div class="text-center">
        <h3>Commission Report</h3>

      
        
    </div>
    <div class="table-responsive">
    <table class="table table-bordered ">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>Agent Type</th>
                <th>Agent Name(internal/External/Reference)</th>
             
               
                <th>Doctor Commission</th>
                <th>Doctor Discount</th>
                <th>Total Commission</th>   
                
            </tr>
        </thead>
        <tbody>
      


     <?php $sl=1;foreach($records as $record){?>
        <tr>
            <td><?php echo $sl++;?></td>
            <td>
            <?php 
                if($record->agent_type==1){
                    echo 'External Doctor';
                }
                elseif ($record->agent_type==2) {
                    echo 'Reference';
                }
                elseif ($record->agent_type==3) {
                    echo 'Internal Doctor';
                }
            ?> 
                
            </td>
            <td><?php ?></td>
          
            <td><?php echo intval($record->commission_amount);?></td>
            <td><?php echo $record->discount_amount;?></td>
            <td><?php echo $record->commission_amount-$record->discount_amount;?></td>
  
        </tr>
 
     <?php }?>
         
        
        </tbody>
         
    </table>
   
    
    </div>
</div>