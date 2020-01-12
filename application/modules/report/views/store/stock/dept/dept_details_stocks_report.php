<?php

$sourceType = function($source_id) {
	$type = '';
	if ($source_id == 1) {
		$type = 'Issue to Employee';
	} elseif ($source_id == 2) {
		$type = 'Requisition Received';
	}
	return $type;
};

?>

<div class="box">

    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Department Stock Details Report(<?php echo $product['product_name'] ?>)</h3>

        <?php if (isset($department) && !empty($department)) : ?>
        <div>
            <strong>Department Name :</strong> <span><?php echo $department['department_name'] ?></span>
        </div>
        <?php endif; ?>
        <div>
            <strong>Current Stock:</strong> <span><?php echo $product['quantity'] ?></span>
        </div>

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

    <br/>
		<table class="table table-striped table-bordered report-table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Source Name</th>
					<th>Store/Employee Name</th>
					<th>Stock Type</th>
					<th>Qnty</th>
					<th>Created By</t
				</tr>
			</thead>
			<?php if (!empty($records)) : ?>
			<tbody>
			    
				<?php 
					$tot_requi = 0;
					$tot_issue = 0;
					$tot_op = 0;
					$tot_stock = 0;
					foreach ($records as $record) : 
					if ($record->source == 1) {
						$tot_issue += $record->quantity;
					}
					if ($record->source == 2) {
						$tot_requi += $record->quantity;
					} 
					if ($record->source == 3) {
						$tot_op += $record->quantity;
					}
					$tot_stock = ($tot_op + $tot_requi) - $tot_issue;
				?>
				<tr>
					<td><?php echo $record->created_date; ?></td>
					<td><?php echo $record->source_name; ?></td>
					<td><?php echo $record->from_to_name; ?></td>
					<td><?php echo $record->stock_type; ?></td>
					<td><?php echo $record->quantity; ?></td>
					<td><?php echo $record->created_name; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<th>Total</th>
					<th>Requisition Received:&nbsp;<?php echo $tot_issue; ?></th>
					<th>Employee Issue:&nbsp;<?php echo $tot_requi; ?></th>
					<th>Opening Balance:&nbsp;<?php echo $tot_op; ?></th>
					<th><b>Current Stock:</b>&nbsp;<?php echo $tot_stock; ?></th>
					<th>Total Stock(Tk):&nbsp;<?php echo ($tot_stock * $product['purchase_price']); ?></th>
				</tr>
			</tfoot>
			<?php endif; ?>
		</table>

    <?php echo $this->pagination->create_links(); ?>

</div>