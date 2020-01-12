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
	<legend>Add New Service for Discount</legend>		
		<!-- New Service Name-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('service_name') ? 'error' : ''; ?>">
				<label class="control-label">Service Name</label>
				
					<input type='text' name='service_name' required="" id='service_name' value="<?php echo set_value('service_name', isset($record->service_name) ? $record->service_name : '');  ?>"  class="form-control" tabindex="1"  placeholder="">
					<span class='help-inline'><?php echo form_error('service_name'); ?></span>
			</div>
		</div>
		
		<!-- department list-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('service_name') ? 'error' : ''; ?>">
				<label class="control-label">Department List</label>
				<select name="department_id" id="department_id" class="form-control" required="">
					<option value="">Select a department</option>
					<?php
						foreach ($department_list as $row) :
					 ?>
					<option value="<?php echo $row->dept_id; ?>" <?php if(isset($record)){echo ($record->department_id== $row->dept_id)? 'selected':''; }?> ><?php echo $row->department_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>


		<!-- Service discount is applicable-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('service_name') ? 'error' : ''; ?>">
				<label class="control-label">Has Discount?</label>
				<select name="has_discount" id="discount_has_discount" class="form-control">
					<option value="1" <?php if(isset($record)){echo ($record->has_discount==1)? 'selected':''; }?> >Discount Applicable</option>
					<option value="0" <?php if(isset($record)){echo ($record->has_discount==0)? 'selected':''; }?> >Discount Not Applicable</option>
				</select>
			</div>
		</div>

		<!-- Service discount status-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('service_name') ? 'error' : ''; ?>">
				<label class="control-label">Status</label>
				<select name="status" id="discount_is_possible" class="form-control">
					<option value="1" <?php if(isset($record)){echo ($record->status==1)? 'selected':''; }?> >Active</option>
					<option value="0" <?php if(isset($record)){echo ($record->status==0)? 'selected':''; }?> >Deactive</option>
				</select>
			</div>
		</div>

		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="pager">
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>" />
				<button type="reset" class="btn btn-warning">Reset</button>
			</div> 
		</div>              

</fieldset>

<?php echo form_close(); ?>

</div>
