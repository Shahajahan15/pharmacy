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
	<legend>Discount Set</legend>	


		<!-- Patient Type id-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('patient_type_id') ? 'error' : ''; ?>">
				<label class="control-label">Patient Type</label>
					<select name="patient_type_id" id="patient_type_id" class="form-control" required="">
					<option value="">Select Type</option>
					<?php foreach($types as $type){?>
						<option value="<?php echo $type->id; ?>" <?php if(isset($record)){echo ($record->patient_type_id==$type->id)?'selected':'d';}?>><?php echo $type->type_name;?></option>
					<?php }?>
					</select>
					<span class='help-inline'><?php echo form_error('patient_type_id'); ?></span>
			</div>
		</div>

		<!-- Patient Sub Type id-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('patient_sub_type_id') ? 'error' : ''; ?>">
				<label class="control-label">Patient sub Type</label>
					<select name="patient_sub_type_id" id="patient_sub_type_id" class="form-control">
					<option value="">Select Sub Type</option>
					<span id="sub_type_dropdown">
					<?php foreach($sub_types as $subtype){?>
						<option value="<?php echo $subtype->id; ?>" <?php if(isset($record)){echo ($record->patient_sub_type_id==$subtype->id)?'selected':'d';}?>><?php echo $subtype->sub_type_name;?></option>
					<?php }?>
					</span>
					</select>
					<span class='help-inline'><?php echo form_error('patient_sub_type_id'); ?></span>
			</div>
		</div>

		<!-- Service ID-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('service_id') ? 'error' : ''; ?>">
				<label class="control-label">Service</label>
					<select name="service_id" id="service_id" class="form-control" required="" <?php echo isset($record)?'readonly=""':''?>>
					
					<option value="">Select service</option>
					<?php foreach($services as $service){?>
						<option value="<?php echo $service->id; ?>" <?php if(isset($record)){echo ($record->service_id==$service->id)?'selected':'d';}?>><?php echo $service->service_name;?></option>
					<?php }?>
					</select>
					<span class='help-inline'><?php echo form_error('service_id'); ?></span>
			</div>
		</div>

		<?php if(isset($record)){?>
			<!-- Discount  -->
			<div class="col-sm-12 col-md-6 col-lg-3">
				<div class="form-group <?php echo form_error('sub_service_name') ? 'error' : ''; ?>">
					<label class="control-label">Sub Service</label>				
						<input type="text" id='' class="form-control" readonly="" value="<?php echo isset($record->sub_service_name) ? $record->sub_service_name :  ''?>">
						<span class='help-inline'><?php echo form_error('sub_service_name'); ?></span>
				</div>
			</div>
		<?php }?>


		<!-- Discount  -->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('discount') ? 'error' : ''; ?>">
				<label class="control-label">Discount(%)</label>				
					<input type="text" name='discount' id='discount' class="form-control decimal" required="" value="<?php echo isset($record->discount) ? $record->discount :  ''?>">
					<span class='help-inline'><?php echo form_error('discount'); ?></span>
			</div>
		</div>


		<!-- Date Start-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('date_start') ? 'error' : ''; ?>">
				<label class="control-label">Date Start</label>				
					<input type="text" name='date_start' id='date_start' required="" class="form-control datepickerCommon" value="<?php echo isset($record->date_start) ? date('d/m/Y',strtotime($record->date_start)) :  ''?>">
					<span class='help-inline'><?php echo form_error('date_start'); ?></span>
			</div>
		</div>

		<!-- Date Start-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('date_end') ? 'error' : ''; ?>">
				<label class="control-label">Date End</label>				
					<input type="text" name='date_end' id='date_end' class="form-control datepickerCommon" value="<?php echo isset($record->date_end) ? date('d/m/Y',strtotime($record->date_end)) :  ''?>">
					<span class='help-inline'><?php echo form_error('date_end'); ?></span>
			</div>
		</div>
		

		

		<!-- 	status-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">
				<label class="control-label">Status</label>
				<select name="status" class="form-control" required="">
					<option value="1" <?php if(isset($record->status)){echo ($record->status==1)?'selected':'';}?>>Active</option>
					<option value="0" <?php if(isset($record->status)){echo ($record->status==0)?'selected':'';}?>>Inactive</option>
				</select>		
					
					<span class='help-inline'><?php echo form_error('status'); ?></span>
			</div>
		</div>
				<!-- 	Is campaign-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">
				<label class="control-label">Is Campaign?</label>
				<select name="campaign" class="form-control" required="">
					<option value="0" <?php if(isset($record->is_campaign)){echo ($record->is_campaign==0)?'selected':'';}?>>Not Campaign</option>
					<option value="1" <?php if(isset($record->is_campaign)){echo ($record->is_campaign==1)?'selected':'';}?>>Campaign</option>
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
