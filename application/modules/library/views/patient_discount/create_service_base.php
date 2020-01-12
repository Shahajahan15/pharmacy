<?php
//print_r($record);die();
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
<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal jj"'); ?>
<fieldset>
	<legend>Add Patient Discount</legend>		
		

		<!-- Patient code-->
		<div class="col-md-12 col-lg-12 col-sm-12">
			<div class="col-sm-12 col-md-6 col-lg-4 col-lg-offset-4">
				<div class="form-group <?php echo form_error('patient_code') ? 'error' : ''; ?>">
					<label class="control-label">Patient Code</label>
					<input type="text" id='patient_code' autocomplete="off" class="form-control autocomplete_search" required="" value="<?php if(isset($record)){ echo $record->patient_id; }elseif(isset($patient_id)){ echo $patient_id; }?>" autocomplete="off" <?php if($this->uri->segment(5)){echo 'disabled=""'; }?>>
					<div class="autocomplete_box" target-url="patient_discount_setup/library/getPatient"></div>
					
					<span class='help-inline'><?php echo form_error('patient_code'); ?></span>
				</div>
			</div>
		</div>

		<!-- Patient ID-->
		<div class="col-sm-12 col-md-6 col-lg-4">
			<div class="form-group <?php echo form_error('patient_id') ? 'error' : ''; ?>">
				<label class="control-label">Patient Name</label>
				
					<input type="hidden" name='patient_id' id='patient_id' value="<?php if(isset($record)){ echo $record->id; }elseif(isset($id)){ echo $id; }?>" class="form-control">
					<input type="text" id='patient_name' disabled="" class="form-control" required="" value="<?php if(isset($record)){ echo $record->patient_name; }elseif(isset($patient_name)){ echo $patient_name; } ?>">

					<span class='help-inline'><?php echo form_error('patient_id'); ?></span>
			</div>
		</div>

		<!--Service id-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('service_id') ? 'error' : ''; ?>">	
				<label class="control-label">Service Name</label>
				<select name='service_id' class="form-control service_id" required="">
					<option value="">Select Service</option>
						<?php if($service_lists): foreach($service_lists as $list){?>
						<option value="<?php echo $list->id; ?>" <?php if(isset($record)){echo ($record->service_id==$list->id)?'selected':'';}?> ><?php echo $list->service_name; ?></option>

					<?php } endif;?>
				</select>
				<span class='help-inline'><?php echo form_error('service_id'); ?></span>
			</div>
		</div>		

		<!-- discount_start_date-->
		<div class="col-sm-12 col-md-6 col-lg-2">
			<div class="form-group <?php echo form_error('discount_start_date') ? 'error' : ''; ?>">
				<label class="control-label">Discount Start Date</label>				
					<input type="text" name='discount_start_date' id='discount_start_date' class="form-control datepickerCommon" required="" value="<?php if(isset($record)){echo date('d/m/Y',strtotime($record->discount_start_date)); }?>">
					<span class='help-inline'><?php echo form_error('discount_start_date'); ?></span>
			</div>
		</div>

		<!-- 	discount_end_date-->
		<div class="col-sm-12 col-md-6 col-lg-2">
			<div class="form-group <?php echo form_error('discount_end_date') ? 'error' : ''; ?>">
				<label class="control-label">Discount End Date</label>				
					<input type="text" onchange="myButtonEnable()" name='discount_end_date' id='discount_end_date' class="form-control datepickerCommon" required="" value="<?php if(isset($record)){echo date('d/m/Y',strtotime($record->discount_end_date)); } ?>">
					<span class='help-inline'><?php echo form_error('discount_end_date'); ?></span>
			</div>
		</div>

		<!--Discount Type-->
		<div class="col-sm-12 col-md-6 col-lg-2">
			<div class="form-group <?php echo form_error('discount_type') ? 'error' : ''; ?>">
				<label>Discount Type</label>
				<select name='discount_type' class="form-control discount_type" required="">
					<option value="1" <?php if(isset($record)){echo ($record->discount_type==1)?'selected':'';}?> >Percentage</option>
					<option value="0" <?php if(isset($record)){echo ($record->discount_type=='0')?'selected':'';}?> >Amount</option>
				</select>
				<span class='help-inline'><?php echo form_error('discount_type'); ?></span>
			</div>
		</div>

		<!--Discount-->
		<div class="col-sm-12 col-md-6 col-lg-3">
			<div class="form-group <?php echo form_error('discount_type') ? 'error' : ''; ?>">
				<label>Discount</label>
				<div class="form-group <?php echo form_error('discount') ? 'error' : ''; ?>">		
						<input type="number" name='discount' class="form-control discount" required="" value="<?php if(isset($record)){echo $record->discount; } ?>">
						<span class='help-inline'><?php echo form_error('discount'); ?></span>
				</div>
				<span class='help-inline'><?php echo form_error('discount'); ?></span>
			</div>
		</div>
</fieldset>



<fieldset>
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="pager">
			<!-- <input type="submit" name="save" class="btn btn-primary" value="<?php //echo lang('bf_action_save'); ?>" /> -->
			<button name="save" id="mybtn" class="mybtn btn-primary" onclick="doubleClickNotSubmit(this)" type="submit" value="Submit" disabled="disabled">Submit</button>
			<button type="reset" class="btn btn-warning">Reset</button>
		</div> 
	</div>
</fieldset>

<?php echo form_close(); ?>

</div>


