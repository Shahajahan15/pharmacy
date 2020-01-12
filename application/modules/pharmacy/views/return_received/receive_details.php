

<?php echo form_open(site_url().'/admin/product_return/pharmacy/receive_list', 'role="form", class="nform-horizontal"'); ?>
<table class="table table-hover">
	<thead>
		<tr class="active">
			<th width="5%">Serial</th>
			<th width="5%">Product Name</th>
			<th width="5%">Received Quantity</th>
			<th width="5%">Return Requ</th>
			<th width="5%">Returnable Qnty</th>
			<th width="10%">Return Qnty</th>
			<th width="20%">Reason</th>
		</tr>
	</thead>
	<tbody>
	<?php $sl=0; foreach($records as $record){ ?>
		<tr class="info">
			<td><?php echo $sl+=1; ?></td>
			<td>
				<?php echo $record->product_name; ?>
				<input type="hidden" name="supplier_id" value="<?php echo $record->supplier_id; ?>">
				<input type="hidden" name="product_id[]" value="<?php echo $record->product_id; ?>">
				<input type="hidden" name="receive_master_id" value="<?php echo $record->receive_master_id; ?>">
				<input type="hidden" name="recv_dtls_id[]" value="<?php echo $record->id; ?>">
				<input type="hidden" name="order_id" value="<?php echo $record->order_id; ?>">
				
			</td>
			<td class=""><?php echo $record->received_qnty; ?></td>
			<td><?php echo $record->return_qnty_requested; ?></td>

			<td class="returnable_qnty"><?php echo $record->received_qnty-$record->return_qnty_requested; ?></td>

			<td><input type="text" name="return_qnty_requested[]" class="form-control return_qnty on-focus-selected" value="0" required=""></td>
			<td>
				<select class="form-control reason" name="reason[]">
					<option value="">Select Reason</option>
					<?php foreach($reason as $key=>$re){?>
						<option value="<?php echo $key; ?>"><?php echo $re; ?></option>
					<?php }?>
				</select>
			</td>
		</tr>		
		<?php } ?>
	</tbody>
</table>

<center>
<!-- 	<button type="submit" onclick="return confirMessage();" name="save" class="btn btn-primary">Return Submit</button> -->
<button name="save" class="btn btn-primary btn-sm" onclick="doubleClickNotSubmit(this)" type="submit" value="Submit">Return Submit</button>
	<button type="reset" name="save" class="btn btn-warning">Reset</button>
</center>
<?php echo form_close(); ?>