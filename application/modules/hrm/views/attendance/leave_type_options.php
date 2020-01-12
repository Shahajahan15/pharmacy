<select required="" id='leave_type'  class="form-control leave_type" name="leave_type[<?php echo $employee_id;?>]">
	<option value="">Select</option>
    <?php foreach($leave_types as $type){?>
        <option value="<?php echo $type->id; ?>"><?php echo $type->leave_type;?> (<?php echo $type->remaining_leave; ?>) </option>
    <?php }?>
</select>