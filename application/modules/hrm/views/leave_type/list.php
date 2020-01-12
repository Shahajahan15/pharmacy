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

<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('Lib.LeaveType.Delete');
$can_edit		= $this->auth->has_permission('Lib.LeaveType.Edit');
$has_records	= isset($records) && $records;
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th width="5%"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>					
				
					<th width="35%">Leave Name</th>
					<th width="35%">Total Leave Days</th>
					<th width="50%">Description</th>
					<th width="10%">Status</th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<?php echo lang('bf_with_selected'); ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('bf_msg_delete_confirm'))); ?>')" />
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $record) :
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
					<?php endif;?>
					
					<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/leave_type_setup/hrm/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->leave_type); ?></td>
					<?php else : ?>
						<td><?php e($record->leave_type); ?></td>
					<?php endif; ?>
					
					<td><?php e($record->total_leave_days); ?></td>
					<td><?php e($record->description); ?></td>
				
					<td><?php if($record->status==1){ e("Active");}else{e("In Active");} ?></td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
</div>