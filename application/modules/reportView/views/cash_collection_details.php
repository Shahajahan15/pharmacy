<?php
extract($sendData); 
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

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('Report.Collection.Delete');
$can_edit		= $this->auth->has_permission('Report.Collection.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

function getTotalDays($admissiondate){
	$total_days 	= 0;
	$currentdate 	= date('Y-m-d H:i:s');
	$diff 			= (strtotime($currentdate) - strtotime($admissiondate)) / (60 * 60 * 24);
	
	$total_days 	= ceil($diff);
	if($total_days == 0){
		$total_days = 1;
	}
	return $total_days;
}


?>


<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	
		<table class="table table-striped">
			<thead>
				<th><h3>Personal Information</h3></th>
			</thead>
			
			<tbody>
			
				<tr>
					<td><?php echo lang('admitted_patient_id'); ?> :</td>
					<td><?php //e($records->patient_id); ?></td>
					<td><?php echo lang('admitted_patient_details_name'); ?> :</td>
					<td><?php //e($records->patient_name); ?></td>
				</tr>
				
				<tr>
					<table class="table table-striped">
						<thead>
							<th><h3>Personal Information</h3></th>
						</thead>
						
						<tbody>
						
							<tr>
								<td><?php echo lang('admitted_patient_id'); ?> :</td>
								<td><?php //e($records->patient_id); ?></td>
								<td><?php echo lang('admitted_patient_details_name'); ?> :</td>
								<td><?php //e($records->patient_name); ?></td>
							</tr>
							
							<tr>
								<td><?php echo lang('admitted_patient_father_name'); ?> :</td>
								<td><?php //e($records->father_name); ?></td>
								<td><?php echo lang('admitted_patient_mother_name'); ?> :</td>
								<td><?php //e($records->mother_name); ?></td>
							</tr>
							<tr>
								<td><?php echo lang('admitted_patient_father_name'); ?> :</td>
								<td><?php //e($records->father_name); ?></td>
								<td><?php echo lang('admitted_patient_mother_name'); ?> :</td>
								<td><?php //e($records->mother_name); ?></td>
							</tr>
							<tr>
								<td><?php echo lang('admitted_patient_father_name'); ?> :</td>
								<td><?php //e($records->father_name); ?></td>
								<td><?php echo lang('admitted_patient_mother_name'); ?> :</td>
								<td><?php //e($records->mother_name); ?></td>
							</tr>
							<tr>
								<td><?php echo lang('admitted_patient_father_name'); ?> :</td>
								<td><?php //e($records->father_name); ?></td>
								<td><?php echo lang('admitted_patient_mother_name'); ?> :</td>
								<td><?php //e($records->mother_name); ?></td>
							</tr>
							
							
						</tbody>
					</table>
				</tr>
				
				
				
			</tbody>
		</table>
		
		
		
		
		<div class="col-md-12"> 
            <div class="col-md-10 box-footer pager">
                <input type="button" name="print" class="btn btn-primary" value="<?php echo lang('admitted_patient_details_print'); ?>" onclick="javascript:window.print();"  />           
            </div>
      </div>
	
    </div>
</div>