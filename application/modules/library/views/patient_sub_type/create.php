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
<?php echo form_open($this->uri->uri_string(), 'role="form", class=""'); ?>
<fieldset>
	<legend>Add Patient Type</legend>	


		<!-- Patient Type id-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('patient_type_id') ? 'error' : ''; ?>">
				<label class="control-label">Patient Type Name</label>
					<select name="patient_type_id" id="patient_type_id" class="form-control" required="">
					<?php foreach($types as $type){?>
						<option value="<?php echo $type->id; ?>" <?php if(isset($record)){echo ($record->patient_type_id==$type->id)?'selected':'d';}?>><?php echo $type->type_name;?></option>
					<?php }?>
					</select>
					<span class='help-inline'><?php echo form_error('patient_type_id'); ?></span>
			</div>
		</div>	
		

		<!-- Patient Sub Type Name-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('sub_type_name') ? 'error' : ''; ?>">
				<label class="control-label">Patient Sub Type Name</label>
					<input type="text" name="sub_type_name" id="sub_type_name" class="form-control" value="<?php echo isset($record->sub_type_name) ? $record->sub_type_name :  ''?>" required="">
					<span class='help-inline'><?php echo form_error('sub_type_name'); ?></span>
			</div>
		</div>

		

		<!-- Description-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('description') ? 'error' : ''; ?>">
				<label class="control-label">Description</label>				
					<input type="text" name='description' id='description' class="form-control" value="<?php echo isset($record->description) ? $record->description :  ''?>">
					<span class='help-inline'><?php echo form_error('description'); ?></span>
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
