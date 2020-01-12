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
<script type="text/javascript">
	//document.getElementById("mybtn").disabled = true;
	function myButtonEnable()
	{
		document.getElementById("mybtn").disabled = false;
	}
</script>

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
	.mybtn
	{
		padding-top: 7px;
		padding-bottom: 7px;
		padding-left: 15px;
		padding-right: 15px;
		border-radius: 3px;
		margin-right: 3px;
	}
</style>

<div class="box box-primary">
<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
<fieldset>
	<legend>Add Patient Discount</legend>

		<!-- Patient code-->
		<div class="col-md-12 col-lg-12 col-sm-12">
			<div class="col-sm-12 col-md-6 col-lg-4 col-lg-offset-4">
				<div class="form-group <?php echo form_error('patient_code') ? 'error' : ''; ?>">
					<label class="control-label">Patient Code</label>
					<input type="text" id='patient_code' value="<?php if(isset($record)){ echo $record->patient_id; }elseif(isset($patient_id)){ echo $patient_id; }?>" autocomplete="off" class="form-control autocomplete_search" required="" <?php if($this->uri->segment(5)){echo 'disabled=""'; }?>>
					<div class="autocomplete_box" target-url="patient_discount_setup/library/getPatient"></div>
					
					<span class='help-inline'><?php echo form_error('patient_code'); ?></span>
				</div>
			</div>
		</div>		
		

		<!-- Patient ID-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('patient_id') ? 'error' : ''; ?>">
				<label class="control-label">Patient Name</label>
				
					<input type="hidden" name='patient_id' id='patient_id' value="<?php if(isset($record)){ echo $record->id; }elseif(isset($id)){ echo $id; }?>" class="form-control" required="">
					<input type="text" id='patient_name' disabled="" value="<?php if(isset($record)){ echo $record->patient_name; }elseif(isset($patient_name)){ echo $patient_name; } ?>" class="form-control" required="">

					<span class='help-inline'><?php echo form_error('patient_id'); ?></span>
			</div>
		</div>

		

		<!-- discount_start_date-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('discount_start_date') ? 'error' : ''; ?>">
				<label class="control-label">Discount Start Date</label>				
					<input type="text" name='discount_start_date' id='discount_start_date' class="form-control datepickerCommon" required="" value="<?php echo isset($record->discount_start_date)?$record->discount_start_date:'';?>">
					<span class='help-inline'><?php echo form_error('discount_start_date'); ?></span>
			</div>
		</div>

		<!-- 	discount_end_date-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('discount_end_date') ? 'error' : ''; ?>">
				<label class="control-label">Discount End Date</label>				
					<input type="text" onchange="myButtonEnable()" name='discount_end_date' id='discount_end_date' class="form-control datepickerCommon" required="" value="<?php echo isset($record->discount_end_date)?$record->discount_end_date:'';?>">
					<span class='help-inline'><?php echo form_error('discount_end_date'); ?></span>
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
				<select class="form-control sub_service_id chosenCommon" >
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
			<input type="submit"  name="save" id="mybtn" class="mybtn btn-primary" value="<?php echo lang('bf_action_save'); ?>" disabled/>
			<button type="reset" class="btn btn-warning">Reset</button>
		</div> 
	</div>
</fieldset>

<?php echo form_close(); ?>

</div>
