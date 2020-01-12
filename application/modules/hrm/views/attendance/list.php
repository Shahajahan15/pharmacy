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

<table class="table">
	<thead>
		<tr>
			<th>SL</th>
			<th>Photo</th>
			<th>Code</th>
			<th>Employee Name</th>
			<th>Designation</th>
			<th>Department</th>
			<th>Date</th>
			<th>Present Status</th>
		</tr>
	</thead>
	<tbody>
		<?php $sl=0; foreach($records as $record){?>
			<tr>
				<td><?php echo $sl+=1; ?></td>
				<td>
					<img src="<?php echo $img = ($record->photo == "")? base_url("assets/images/profile/default.png") : base_url("assets/images/employee_img/" . $record->photo); ?>" class="img-circle"  width="40" height="40">
				</td>
				<td><?php echo $record->EMP_CODE; ?></td>
				<td><?php echo $record->EMP_NAME; ?></td>
				<td><?php echo $record->designation_name; ?></td>
				<td><?php echo $record->department_name; ?></td>
				<td><?php echo date('d M, Y',strtotime($record->attendance_date)); ?></td>
				<td>
					<?php echo $attendance_status[$record->present_status];?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php  echo $this->pagination->create_links(); ?>
