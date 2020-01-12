<?php echo form_open(site_url().'/admin/product_return/pharmacy/return_request_list', 'role="form", class="nform-horizontal"'); ?>
<table class="table">
	<thead>
		<tr class="active">
			<th>Supplier Name</th>
			<th>Product name</th>
			<th>Requist Quantity</th>
			<th>Approved Quanity</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<tr class="success">
			<td>
			<?php echo $record->supplier_name; ?>
			<input type="hidden" name="return_id" value="<?php echo $record->id; ?>">
			<input type="hidden" name="receive_dtls_id" value="<?php echo $record->receive_dtls_id; ?>">
				
			</td>
			<td><?php echo $record->product_name; ?></td>
			<td><?php echo $record->return_requst_qnty; ?></td>
			<td><input type="text" name="approved_qnty" class="form-control on-focus-selected" value="<?php echo $record->return_requst_qnty; ?>"></td>
			<td>
		<!-- 	<input type="submit" name="save" class="" value="Approved"> -->
		<button name="save" class="btn btn-primary btn-sm" onclick="doubleClickNotSubmit(this)" type="submit" value="Approved">Approved</button>
			</td>
		</tr>
	</tbody>
</table>
<?php echo form_close(); ?>