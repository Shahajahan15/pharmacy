
<div class="admin-box">

    <?php echo report_header() ?>

	<div class="text-center">
    	<strong>Product Name:</strong> <span><?php echo $product['product_name'] ?></span>
		<br/>
		<strong>Current Stock:</strong> <span><?php echo $total_stock->total_stock; ?></span>&nbsp;
		<strong>Total Stock(Tk):</strong> <span><?php echo ($total_stock->total_stock * $product['purchase_price']); ?></span>
		<h6>
    	<?php 
    	   if (isset($from_date) || isset($to_date)) :
    		if ($from_date && $to_date) : 
    			echo "Date from $from_date to $to_date";
    		elseif ($from_date) :
    			echo $from_date;
    		elseif ($to_date) :
    			echo $to_date;
    		endif;
    		endif;
    	?>
    	</h6>
	</div>
	<br>
		<table class="table table-striped table-bordered report-table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Source Name</th>
					<th>Bill No/Department Name</th>
					<th>Stock Type</th>
					<th>Qnty</th>
					<th>Supplier/Dept.Emp Name</th>
					<th>Created By</th>
				</tr>
			</thead>
			<?php if (!empty($records)) : ?>
			<tbody>
				<?php 
					$tot_op = 0;
					$tot_pr = 0;
					$tot_di = 0;
					$tot_prp = 0;
					$tot_prt = 0;
					$tot_stock = 0;
					foreach ($records as $val) :
						if ($val->source == 1) {
							$tot_prt += $val->quantity;
						}
						if ($val->source == 2) {
							$tot_prp += $val->quantity;
						} 
						if ($val->source == 3) {
							$tot_di += $val->quantity;
						}
						if ($val->source == 4) {
							$tot_pr += $val->quantity;
						}
						if ($val->source == 5) {
							$tot_op += $val->quantity;
						}
						$tot_stock = ($tot_op + $tot_pr + $tot_prp) - ($tot_prt + $tot_di);
				?>
				<tr>
					<td><?php echo $val->created_date; ?></td>
					<td><?php echo $val->source_name; ?></td>
					<td><?php echo $val->from_to_name; ?></td>
					<td><?php echo $val->stock_type; ?></td>
					<td><?php echo $val->quantity; ?></td>
					<td><?php echo $val->from_to_by; ?></td>
					<td><?php echo $val->created_name; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<th>Opening Balance:&nbsp;<?php echo $tot_op; ?></th>
					<th>Purchase Received:&nbsp;<?php echo $tot_pr; ?></th>
					<th>Department Issue:&nbsp;<?php echo $tot_di; ?></th>
					<th>Product Replace:&nbsp;<?php echo $tot_prp; ?></th>
					<th>Product Return:&nbsp;<?php echo $tot_prt; ?></th>
					<th><b>Current Stock:</b>&nbsp;<?php echo $tot_stock; ?></th>
					<th>Total Stock(Tk):&nbsp;<?php echo ($tot_stock * $product['purchase_price']); ?></th>
				</tr>
			</tfoot>
			<?php endif; ?>
		</table>

    <?php echo ($records) ? $this->pagination->create_links() : ""; ?>

</div>