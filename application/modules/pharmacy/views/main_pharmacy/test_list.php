<?php if ($type != 2): ?>
<tr class="success">
	<td>
    <input type="hidden" name="package_id[]" class="package_id" value="0">
	<?php echo $row->product_name; ?><input type="hidden" class="product_id" name="product_id[]" value="<?php echo $row->product_id; ?>"> </td>

	<td><?php echo $row->unit_price; ?><input type="hidden" class="tot_price" name="sale_price[]" value="<?php echo $row->unit_price; ?>"> </td>

	<td><?php echo $row->quantity_level; ?><input type="hidden" name="test_type[]" class="test_type" value="2"> <input type="hidden" name="stock[]" class="stock" value="<?php echo $row->quantity_level; ?>"></td>

	<td>0<input type="hidden" name="nd_percent[]" value="0"></td>

	<td>
	0
	<input type="hidden" class="nd_amount" name="nd_amount[]" value="0">
	<input type="hidden" class="n_discount_id" name="n_discount_id[]" value="0">
	<input type="hidden" name="n_discount_type[]" value="0">
	</td>

	<td>0
	<input type="hidden" class="sd_percent" name="sd_percent[]" value="0"> 
	<input type="hidden" class="s_discount_id" name="s_discount_id[]" value="0"> 
	<input type="hidden" class="s_discount_type" name="s_discount_type[]" value="0"> 
	</td>

	<td>0<input type="hidden" class="sd_amount" name="sd_amount[]" value="0"> </td>

	<td><span class="tot_discount_text">0</span>
	<input type="hidden" class="tot_discount" name="td_amount[]" value="0"> 
	</td>

	<td>
	<input type="text" class="form-control s_qnty decimalmask" name="qnty[]" style="width:50px;" value="1" required/> 
	</td>

	<td><span class="sub_total_text"><?php echo $row->unit_price; ?></span><input type="hidden" name="sub_total[]" class="sub_total" value="<?php echo $row->unit_price; ?>"> </td>

	<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
</tr>
<?php else: ?>
<?php if ($package_info):
	foreach ($package_info as $key => $row) : 
if ($row->quantity_level >=1):
		?>
<tr class="success">
	<td>
    <input type="hidden" name="package_id[]" class="package_id" value="<?php echo $row->id; ?>">
	<?php echo $row->product_name; ?><input type="hidden" class="product_id" name="product_id[]" value="<?php echo $row->product_id; ?>"> </td>

	<td><?php echo $row->unit_price; ?><input type="hidden" class="tot_price" name="sale_price[]" value="<?php echo $row->unit_price; ?>"> </td>

	<td><?php echo $row->quantity_level; ?><input type="hidden" name="test_type[]" class="test_type" value="2"> <input type="hidden" name="stock[]" class="stock" value="<?php echo $row->quantity_level; ?>"></td>

	<td>0<input type="hidden" name="nd_percent[]" value="0"></td>

	<td>
	0
	<input type="hidden" class="nd_amount" name="nd_amount[]" value="0">
	<input type="hidden" class="n_discount_id" name="n_discount_id[]" value="0">
	<input type="hidden" name="n_discount_type[]" value="0">
	</td>

	<td>0
	<input type="hidden" class="sd_percent" name="sd_percent[]" value="0"> 
	<input type="hidden" class="s_discount_id" name="s_discount_id[]" value="0"> 
	<input type="hidden" class="s_discount_type" name="s_discount_type[]" value="0"> 
	</td>

	<td>0<input type="hidden" class="sd_amount" name="sd_amount[]" value="0"> </td>

	<td><span class="tot_discount_text">0</span>
	<input type="hidden" class="tot_discount" name="td_amount[]" value="0"> 
	</td>

	<td>
	<input type="text" class="form-control s_qnty decimalmask" name="qnty[]" style="width:50px;" value="1" required/> 
	</td>

	<td><span class="sub_total_text"><?php echo $row->unit_price; ?></span><input type="hidden" name="sub_total[]" class="sub_total" value="<?php echo $row->unit_price; ?>"> </td>

	<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
	
<?php endif; ?>