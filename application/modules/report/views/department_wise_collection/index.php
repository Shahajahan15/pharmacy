<style>
	.table{font-size: 12px;}
	.table thead{background: #999999}
	.table thead tr{}
	.table thead tr th{padding: 4px;}
	.table tbody{}
	.table tbody tr{}
	.table tbody tr td{padding: 2px;vertical-align: middle;}
	.table tfoot{background: #F9F8DB;}
	.table tfoot tr{}
	.table tfoot tr th{padding: 4px;}
</style
<div class="admin-box">
    <div class="table-responsive">
    <table class="table table-bordered">
        
        <thead>
            <tr>
            	<th>#</th>
                <th>Departemnt</th>
                <th>Service Name</th>
                <th>Qty.</th>
                <th>Collected Amount</th>
                <th>Refund Amount</th>
                <th>Discount Amount</th>
                <th>Cancel Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($department_list as $key => $d_list) : ?>
        	<tr>
        		<td><?php echo $key + 1; ?></td>
        		<td><?php echo $d_list->department_name; ?></td>
        		<td>
        			<table>
        				<tbody>
        					<?php foreach ($service_list as $key1 => $s_list) : ?>
        					<?php if (($s_list->department_id == $d_list->id) && ($s_list->id != 1)) : ?>
        					<tr>
        						<td><?php echo $s_list->service_name; ?>
        						</td>
        					</tr>
        					<?php endif; 
        					 if ($s_list->id == 1 && $d_list->servie_id == ''):
        					?>
        					<tr>
        						<td><?php echo $s_list->service_name; ?></td>
        					</tr>
        					
        					 <?php endif; endforeach; ?>
        				</tbody>
        			</table>
        		</td>
        		<td></td>
        		<td></td>
        		<td></td>
        		<td></td>
        		<td></td>
        		<td></td>
        	</tr>
        	<?php endforeach; ?>
        </tbody>
        
    </table>
    </div>
</div>