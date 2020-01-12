
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Cash Collection</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
                <th>Collection Head</th>
                <th>Collection</th>
                <th>Refund</th>
                <th>Total</th>
            </tr>
        </thead>
        
        <tbody>
            <?php 
            	$tot_collection = 0;
            	$tot_refund = 0;
            	foreach($cash_collection as $key => $row) : 
            	$bc = ($key % 2 == 1) ? "#E4E4E4" : "#B0C4DE";
            	$collection = ($row['paid_amount'] + $row['due_paid_amount']);
            	
            	$tot_collection += $collection;
            	$tot_refund += $row['refund_amount'];
            ?>
            <tr style="background: <?php echo $bc; ?>">
                <td><?php echo $row['service_name']; ?></td>
                <td><?php echo $collection; ?></td>
                <td><?php echo $row['refund_amount']; ?></td>
                <td><?php echo $collection - ($row['refund_amount']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
        	<tr>
        		<th>Total Balance</th>
        		<th><?php echo $tot_collection; ?></th>
        		<th><?php echo $tot_refund; ?></th>
        		<th><?php echo ($tot_collection - $tot_refund); ?></th>
        	</tr>
        </tfoot>
        
    </table>
    </div>
    </div>
    </div>
</div>