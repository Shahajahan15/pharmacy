	<?php

	if(!extract($records))
		extract($records);

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

	if (isset($emp_transfer_details))
	{
		$emp_transfer_details = (array) $emp_transfer_details;
	}

	?>

	<?php
	$num_columns	= 13;
	$can_create		= $this->auth->has_permission('HRM.Policy_Tracker.Create');
	$has_records	= isset($records) && is_array($records) && count($records);
	//$src_emp = (array) $src_emp;
	?>

	<style>
		.box-c{width: auto;margin-bottom: 0px;}
		.new-row{margin-left: 5px;margin-right: 5px;}
	</style>

	<div class="box box-primary box-c">
		<?php //echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal", id="employee-policy-tagging-form"'); ?>
		<fieldset>
			<legend>Policy Tagging</legend>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<?php echo $this->load->view('policy_tagging/policy_tagging', $_REQUEST, TRUE); ?>
			</div>
		</fieldset>	

		<table class="table table-striped report-table showtable table-responsive">
			<thead>
				<tr>
					<?php if ($can_create && $has_records) : ?>
						<th width="" class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>  
					<th>#</th>               
					<th>Emp. Code</th>
					<th>Emp. Name</th>	
					<th>Emp. Type</th>	
					<th>Emp. Department</th>
					<th>Leave</th>
					<th>Medical</th>
					<th>Absent</th>
					<th>Shift</th>
					<th>Roster</th>
					<th>Maternity</th>
					<th>Bonus</th>
					<th>Overtime</th>
					<th>P. Fund</th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
				<tfoot>
					<?php if ($can_create) : ?>
						<tr>
							<td colspan="<?php echo $num_columns; ?>">
								<?php echo lang('bf_with_selected'); ?>
								<input type="submit" name="save" id="Save" class="btn btn-danger" value="Add" onclick="return confirm('Are You Confirm!')" />
							</td>
						</tr>
					<?php endif; ?>
				</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $key => $record) :
						$record = (object) $record;
					?>
					<tr>
						<?php if ($can_create) : ?>
							<td class="column-check"><input type="checkbox" name="checked[]" class="empCheckid" value="<?php echo $record->EMP_ID; ?>" />
							</td>
						<?php endif;?>
						<td><?php echo $key+1;?></td>
						<td>
							<?php echo $record->EMP_CODE; ?>
						</td>					              
						<td><?php echo "<b>".$record->designation_name."</b>"." ".$record->EMP_NAME; ?></td>


						<td><?php e($record->emp_type);  ?></td>
						<td><?php e($record->department_name);  ?></td>
						<td class="leave"><?php echo policyCheck(1, $record->policy_types); ?></td>
						<td class="medical"><?php echo policyCheck(2, $record->policy_types); ?></td>
						<td class="absent"><?php echo policyCheck(3, $record->policy_types); ?></td>
						<td class="shift"><?php echo policyCheck(4, $record->policy_types); ?></td>
						<td class="roster"><?php echo policyCheck(7, $record->policy_types); ?></td>
						<td class="maternity"><?php echo policyCheck(5, $record->policy_types); ?></td>
						<td class="bonus"><?php echo policyCheck(6, $record->policy_types); ?></td>
						<td class="overtime"><?php echo policyCheck(8, $record->policy_types); ?></td>
						<td class="provident_fund "><?php echo policyCheck(9, $record->policy_types); ?></td>
					</tr>
					<?php
					endforeach;
					else:
						?>
					<tr>
						<td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_no_records_found'); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php //echo form_close(); ?>
		<?php echo $this->pagination->create_links(); ?>
	</div>

