<style type="text/css">
	.table tr{
		padding: 0;
		margin:0;
	}
</style>

<?php echo form_open(site_url().'/admin/product_return_replace/pharmacy/replace_add', 'role="form", class="nform-horizontal"'); ?>
<table class="table">
		<thead>
			<tr class="active">
				<th>Supplier Name</th>
				<th>Supplier Code</th>
				<th>Received Date</th>
				<th>Product Name</th>
				<th>Product Received Qnty</th>
				<th>Return Confirm Qnty</th>
				<th>Already Replace Qnty</th>
			</tr>
		</thead>
		<tbody>
				<tr class="info">
					<td class="supplier_name"><?php echo $record->supplier_name;?></td>
					<td><?php echo $record->supplier_code;?></td>
					<td><?php echo $record->received_date;?></td>
					<td class="product_name"><?php echo $record->product_name;?></td>
					<td><?php echo $record->received_qnty;?></td>
					<td class=""><?php echo $record->return_approved_qnty;?></td>
					<td class=""><?php echo isset($record->replace_qnty)?$record->replace_qnty:'0';?></td>
					
				</tr>
		</tbody>
	</table>

	<center>
		<div>
			<strong style="font-size: 18px;background-color:#f9a4a4;padding: 5px;margin-bottom: 3px;">Receivable Replace Quantity =<span class="receivable_replace_qnty"> <?php echo isset($record->replace_qnty)?$record->return_approved_qnty-$record->replace_qnty:$record->return_approved_qnty;?></span></strong>

			<input type="hidden" name="return_products_id" value="<?php echo $record->id; ?>">
		</div>
		<div class="form-group" style="background-color:#d9edf7">
			<label>
				Replace Quantity
				<input type="text" name="replace_qntity" class="form-control replace_qntity" required="">
			</label>
		</div>
		<div>
		
			<button name="save" class="btn btn-primary btn-sm" onclick="doubleClickNotSubmit(this)" type="submit" value="Submit">Replace</button>
			<button type="reset" class="btn btn-warning btn-sm">Reset</button>
		</div>
	</center>

<?php echo form_close(); ?>