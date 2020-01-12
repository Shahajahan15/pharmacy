<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
@media print
{

.view{display: none;!important}
.detail{display: none;!important}



}
</style>
   <?php echo report_header() ?>
    <div class="text-center">
        <h3>Commission Report</h3>
  <div class="text-center">
        

      <?php if (isset($first_date, $second_date) && $first_date != date('Y-m-d 00:00:00')  && !empty($first_date) && !empty($second_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($first_date)) ?> to <?php echo date('d/m/Y',strtotime($second_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
      
        
    </div>
    <div class="table-responsive">
    <table class="table table-bordered ">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>Agent Type</th>
                <th>Agent Name</th>
                <th>Number of Test</th>
        
                <th>Agent Commission</th>
                <th>Agent Discount</th>
                <th>Total Commission</th>  
                <th class="view">View</th>  
                
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
            <td>   <?php 
                if($record->agent_type==1){
                    echo $record->ex_emp;
                }
                elseif ($record->agent_type==2) {
                    echo $record->ref_nam;
                }
                elseif ($record->agent_type==3) {
                    echo $record->in_emp;
                }
            ?></td>
            <td><?php echo round($record->total_test);?></td>
            <td><?php echo round($record->commission_amount);?></td>
            <td><?php echo round($record->discount_amount);?></td>
            <td><?php echo round($record->commission_amount-$record->discount_amount);?></td>
            <td class="detail"><a class="btn btn-info btn-xs" href="<?php echo site_url(SITE_AREA . '/commission_report/report/ref_com_details/'.$record->agent_id."/".$record->agent_type); ?>" style="text-decoration:none;">view</a></td>
  
        </tr>
 
     <?php }?>
         
        
        </tbody>
         
    </table>
   
    
    </div>
</div>