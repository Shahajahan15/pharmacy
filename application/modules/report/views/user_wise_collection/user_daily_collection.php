
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
.date1{ color: white;}
@media print
{

.date1{color: black;}




}
table {
    width:100%;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
table#t01 tr:nth-child(even) {
    background-color: #eee;
}
table#t01 tr:nth-child(odd) {
   background-color:#fff;
}
table#t01 th {
    background-color: #005B96  ;
    color: white;
}
</style>
<?php echo report_header() ?>
<div class="text-center">
        <h3 style="background-color: #005B96; color: white;">User Wise Daily Cash Collection</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
           <span>Date <?php echo date('d/m/Y') ; ?></span> <span class="date1"><?php echo date("h:i:sa"); ?></span>

<?php //echo date('h:i:A', strtotime(('d/m/Y')));?>
           
        <?php endif; ?>
    </div>
    <div class="table-responsive">
    <table id="t01">
        
        <thead>
            <tr>
                <th>User Name</th>
                <?php if ($service_list) : 
                	foreach ($service_list as $row) : 
                ?>
                <th><?php echo $row->service_name;  ?></th>
                <?php endforeach; endif; ?>
                <th>Refund</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        	<?php 
        	if ($user_list) : 
        	foreach ($user_list as $key1 => $user) : 
        	$bc = ($key1 % 2 == 1) ? "#E4E4E4" : "#B0C4DE";
        	?>
        	<tr style="background: <?php echo $bc; ?>">
        		<td><?php echo $user->username; ?></td>
        		<?php if ($service_list) : 
        		$tot_paid_collection = 0;
        		$tot_due_collection = 0;
        			foreach ($service_list as $row) : 
        			$paid_collection = isset($user_wise_collection[$user->id][$row->id]['paid_amount']) ? $user_wise_collection[$user->id][$row->id]['paid_amount'] : 0;
        			$due_collection = isset($user_wise_collection[$user->id][$row->id]['due_paid_amount']) ? $user_wise_collection[$user->id][$row->id]['due_paid_amount'] : 0;
        			$refund = isset($user_wise_refund[$user->id]['refund_amount']) ? $user_wise_refund[$user->id]['refund_amount'] : 0;
        			$tot_paid_collection += $paid_collection;
        			$tot_due_collection += $due_collection;
        		?>
        		
        			<td><?php echo  ($paid_collection + $due_collection); ?></td>
        		<?php endforeach; endif; ?>
        		<td><?php echo $refund ?></td>
        		<td><?php echo (($tot_paid_collection + $tot_due_collection) - $refund); ?></td>
        	</tr>
        	<?php endforeach;
        		endif;
        	 ?>
        </tbody>
        <tfoot>
        	<tr>
        		<th>Total</th>
        	<?php 
        		if ($service_list) :
        		$tot_paid_coll = 0;  
        		$tot_due_coll = 0;  
        		$tot_refund = 0;  
        		foreach ($service_list as $row) : 
        		
        		$paid_coll_last = isset($user_wise_collection_last[$row->id]['paid_amount']) ? $user_wise_collection_last[$row->id]['paid_amount']: 0;
        		$due_coll_last = isset($user_wise_collection_last[$row->id]['due_paid_amount']) ? $user_wise_collection_last[$row->id]['due_paid_amount']: 0;
        		$refund_coll_last = isset($user_wise_collection_last[$row->id]['refund_amount']) ? $user_wise_collection_last[$row->id]['refund_amount']: 0;
        		$tot_paid_coll += $paid_coll_last;
        		$tot_due_coll += $due_coll_last;
        		$tot_refund += $refund_coll_last;
        	?>
        		<th><?php echo $paid_coll_last + $due_coll_last; ?></th>
        	<?php endforeach; endif; ?>
        	    <th><?php echo $tot_refund; ?></th>
        	    <th><?php echo ($tot_paid_coll + $tot_due_coll) - ($tot_refund); ?></th>
        	</tr>
        </tfoot>
        
    </table>
    </div>
</div>