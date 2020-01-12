<?php

$validation_errors = validation_errors();
if ($validation_errors) :

?>
<div class="alert alert-block alert-error fade in">
<a class="close" data-dismiss="alert">&times;</a>
<h4 class="alert-heading">Please fix the following errors:</h4>
<?php echo $validation_errors; ?>
</div>
<?php
endif;

?>


<div class="box box-primary">
<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
<fieldset>
	<legend>Add Employee Type</legend>		
		

		<!-- Patient Type Name-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('emp_type') ? 'error' : ''; ?>">
				<label class="control-label">Employee Type Name</label>
					<input type="text" name="emp_type" id="emp_type" class="form-control" value="<?php echo isset($record) ? $record->emp_type :  ''?>" required="">
					<span class='help-inline'><?php echo form_error('emp_type'); ?></span>
			</div>
		</div>


		<!-- 	status-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">
				<label class="control-label">Status</label>
				<select name="status" class="form-control">
					<option value="1" <?php if(isset($record->status)){echo ($record->status==1)?'selected':'';}?>>Active</option>
					<option value="0" <?php if(isset($record->status)){echo ($record->status==0)?'selected':'';}?>>Inactive</option>
				</select>		
					
					<span class='help-inline'><?php echo form_error('status'); ?></span>
			</div>
		</div>
</fieldset>



<fieldset>
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="pager">
			<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>" />
			<button type="reset" class="btn btn-warning">Reset</button>
		</div> 
	</div>
</fieldset>

<?php echo form_close(); ?>

</div>
