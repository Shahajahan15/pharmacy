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
	<legend>Patient Overall Discount</legend>
	
		<table class="table">
			<thead>
				<tr class="active">
					<th>SL</th>
					<th>Patient Name</th>
					<th>Admission Days</th>
					<th>Total Bill</th>
					<th>Total Paid</th>
					<th>Due</th>
					<th width="15%">Discount Amount</th>
					<th>Submit</th>
				</tr>
			</thead>
			<tbody>
				<?php $sl=0; foreach($admission_patients as $patient):?>
					<?php if(isset($admission_patient_bill[$patient->id]) && $patient->over_all_discount==0):?>
					<tr>
						<td><?php echo $sl+=1; ?></td>
						<td><?php echo $patient->patient_name; ?></td>
						<td><?php echo $admission_patient_bill[$patient->id][2]; ?></td>
						<td><?php echo $admission_patient_bill[$patient->id][0]; ?></td>
						<td><?php echo $admission_patient_bill[$patient->id][1]; ?></td>

						<td class="due-amount"><?php echo $admission_patient_bill[$patient->id][0]-$admission_patient_bill[$patient->id][1]; ?></td>
						<td>
							<input type="text" class="form-control over_all_discount" name="over_all_discount" value="<?php echo $patient->over_all_discount;?>">
							<input type="hidden" name="admission_id" value="<?php echo $patient->id;?>">
						</td>
						<td>
							<a href="<?php echo site_url().'/admin/patient_overall_discount/library/save'?>" class="btn btn-xs btn-success overall_discount_submit">Submit</a>
						</td>

					</tr>
				<?php endif;?>
				<?php endforeach;?>
			</tbody>
		</table>
	
</fieldset>
<?php echo form_close(); ?>
</div>
