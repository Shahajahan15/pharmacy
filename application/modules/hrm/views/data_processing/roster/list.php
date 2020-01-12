		<?php

		if(!extract($records))
			extract($records);
		?>

		<?php
		$num_columns	= 11;
		$can_create		= $this->auth->has_permission('HRM.RosterDP.Add');
		$can_delete		= $this->auth->has_permission('HRM.RosterDP.Delete');
		$can_edit	= $this->auth->has_permission('HRM.RosterDP.Edit');
		$has_records	= isset($records) && is_array($records) && count($records);
		?>

		<style>
		.box-c{width: auto;margin-bottom: 0px;}
		.new-row{margin-left: 5px;margin-right: 5px;}
	</style>

	<div class="box box-primary box-c">
		<fieldset>
			<legend>Roster Process Add</legend>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<?php echo $this->load->view('data_processing/roster/form', $_REQUEST, TRUE); ?>
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
					<th>Emp. Department</th>
					<th>Roster Name</th>
					<th>Shift Date</th>
					<th>Shift Name</th>
					<th>Count</th>
					<th>AC. Day</th>
					<?php if ($can_edit) : ?>
					<th>Action</th>
				<?php endif; ?>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
				<tfoot>
					<?php if ($can_delete) : ?>
						<tr>
							<td colspan="<?php echo $num_columns; ?>">
								<?php echo lang('bf_with_selected'); ?>
								<input type="submit" name="delete" id="delete-me" class="btn btn-danger btn-xs" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('Are You Confirm!')" />
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
							<?php if ($can_delete) : ?>
									<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->ID; ?>" /></td>
								</td>
							<?php endif;?>
							<td><?php echo $key+1;?></td>
							<td>
								<?php echo $record->emp_code; ?>
							</td>					              
							<td><?php echo "<b>".$record->designation_name."</b>"." ".$record->emp_name; ?></td>
							<td><?php e($record->department_name); ?></td>
							<td><?php echo $record->roster_name; ?></td>
							<td><?php echo $record->DATE; ?></td>
							<td><?php echo $record->SHIFT_NAME; ?></td>
							<td><?php echo $record->COUNT_DAY; ?></td>
							<td><?php echo $record->CHANGE_DAY; ?></td>
							<?php if ($can_edit) : ?>
							<td>
								<button type="button" id="<?php echo $record->ID; ?>" class="roster-re-process btn btn-success btn-xs">Re-Process</button>
							</td>
							<?php endif; ?>
						</tr>
						<?php
					endforeach;
				else:
					?>
					<tr>
						<td colspan="<?php echo $num_columns; ?>">No Record Found</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php echo $this->pagination->create_links(); ?>
	</div>

