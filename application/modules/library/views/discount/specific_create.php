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
<style type="text/css">

	.table tbody tr td{
		margin: 0;
		padding:1px;
	}
	.table thead tr th{
		margin: 0;
		padding:1px;
	}
	.plus-minus{
		width:20px;
		height: 25px;
	}
</style>

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

</fieldset>



<fieldset>
	<legend>Multiple Sub Item Discount</legend>

		<!-- Service ID-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('service_id') ? 'error' : ''; ?>">	
				<select class="form-control service_id" >
					<option value="">Select Service</option>
								<?php if($service_lists): foreach($service_lists as $list){?>
						<option value="<?php echo $list->id; ?>"><?php echo $list->service_name; ?></option>

				<?php } endif;?>
				</select>
				<span class='help-inline'><?php echo form_error('service_id'); ?></span>
			</div>
		</div>

		<!-- Sub Service ID-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('sub_service_id') ? 'error' : ''; ?>">
				<select class="form-control sub_service_id" >
					<span id="sub_service_list">

					</span>
				</select>
				<span class='help-inline'><?php echo form_error('sub_service_id'); ?></span>
			</div>
		</div>






	<table class="table">
		<thead>
		<tr class="active">
			<th>Service Name</th>
			<th>Sub Service Name</th>
			<th>Discount type</th>
			<th>Discount (per unit)</th>
			<th>Discount Unit</th>
			<th><span class="btn btn-xs btn-danger"><i class="fa fa-times" aria-hidden="true"></i></span></th>
		</tr>
		</thead>

		<tbody class="multi-input-fields">
		<?php if(isset($discounts)){?>
			<?php foreach($discounts as $discount){?>
			<tr class="success">
				<td>
					<?php echo $discount['service_name']; ?><input type="hidden" name="service_id[]" class="form-control service_id" value="<?php echo $discount['service_id']; ?>"> 
				</td>
				<td>
					<?php echo $discount['sub_service_name']; ?><input type="hidden" name="sub_service_id[]" class="form-control sub_service_id" value="<?php echo $discount['sub_service_id']; ?>"> 
				</td>
				<td>
					<select name="discount_type[]" class="form-control" required="">
						<option value="1" <?php if($discount['discount_type']==1){echo 'selected'; }?>>Percentage</option>
						<option value="0" <?php if($discount['discount_type']==0){echo 'selected'; }?>>Amount</option>
					</select> 
				</td>
				<td>
					<input type="text" name="discount[]" class="form-control" value="<?php echo $discount['discount']; ?>" required=""> 
				</td>
				<td>
					<select name="discount_unit[]" class="form-control discount_unit" required="">
						<option value="1" <?php if($discount['discount_unit']==1){echo 'selected'; }?>>Day</option>
						<option value="2" <?php if($discount['discount_unit']==2){echo 'selected'; }?>>Hour</option>
					</select> 
				</td>
				<td>
					<button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button>
				</td>
			</tr>
			<?php }?>
		<?php }?>

		
		</tbody>
	</table>
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
