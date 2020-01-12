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
		<tr class="info">
			<th>SL</th>
			<th>Photo</th>
			<th>Code</th>
			<th>Employee Name</th>
			<th>Designation</th>
			<th>Department</th>
			<th>Advance Amount</th>
			<th>Advance Recive Date</th>
		</tr>
	</thead>
	<tbody>
		<?php $sl=0; foreach($records as $record){?>
			<tr class="">
				<td><?php echo $sl+=1; ?></td>
				<td>
					<img src="<?php echo $img = ($record->photo == "")? base_url("assets/images/profile/default.png") : base_url("assets/images/employee_img/" . $record->photo); ?>" class="img-circle"  width="40" height="40">
				</td>
				<td><?php echo $record->emp_code; ?></td>
				<td><?php echo $record->employee_name; ?></td>
				<td><?php echo $record->designation_name; ?></td>
				<td><?php echo $record->department_name; ?></td>				
				<td><?php echo $record->advance_amount; ?></td>				
				<td><?php echo date('d M,Y',strtotime($record->date)); ?></td>	
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php  echo $this->pagination->create_links(); ?>
