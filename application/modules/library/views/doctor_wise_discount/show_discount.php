
<?php if ($type == 1): ?>
<tr class="success">
	<td><?php echo $service_name; ?></td>
	<td>NILL<input type="hidden" name="sub_service_id[]" class="sub_service_id" value="0"></td>
	<td><?php echo $commossion; ?>%<input type="hidden" name="dr_discount[]" class="dr_discount" value="<?php echo $commossion; ?>"><input type="hidden" name="commossion_id[]" class="commission_id" value="<?php echo $commission_id; ?>"></td>
	<td><input type="text" name="hospital_discount[]" class="hospital_discount" value="<?php echo $h_discount; ?>"></td>
	<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
</tr>
<?php else: ?>
	<?php if ($commossion > 0): ?>
<tr class="success">
	<td><?php echo $service_name; ?></td>
	<td><?php echo $sub_service_name; ?><input type="hidden" name="sub_service_id[]" class="sub_service_id" value="<?php echo $sub_service_id; ?>"></td>
	<td><?php echo $commossion; ?>%<input type="hidden" name="dr_discount[]" class="dr_discount" value="<?php echo $commossion; ?>"><input type="hidden" name="commossion_id[]" class="commission_id" value="<?php echo $commission_id; ?>"></td>
	<td><input type="text" name="hospital_discount[]" class="hospital_discount" value="<?php echo $h_discount; ?>"></td>
	<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
</tr>
 <?php endif; ?>
<?php endif; ?>