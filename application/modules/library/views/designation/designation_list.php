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

if (isset($records))
{
	$record = (array) $records;
}
$DESIGNATION_ID = isset($records['DESIGNATION_ID']) ? $records['DESIGNATION_ID'] : '';

?>



<?php
$num_columns	= 6;
$can_delete		= $this->auth->has_permission('Lib.Designation.Delete');
$can_edit		= $this->auth->has_permission('Lib.Designation.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>					
					<th width="35%"><?php echo lang('library_designation_name'); ?></th>
					<th width="35%"><?php echo lang('library_designation_name_bangla'); ?></th>
					<th width="20%"><?php echo lang('library_grade_name'); ?></th>
					<th width="5%"><?php echo lang('library_designation_status'); ?></th>
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
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->DESIGNATION_ID; ?>" /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/designation/library/designation_edit/' . $record->DESIGNATION_ID, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->DESIGNATION_NAME); ?></td>
				<?php else : ?>
					<td><?php e($record->DESIGNATION_NAME); ?></td>					
				<?php endif; ?>
					<td><?php e($record->DESIGNATION_NAME_BANGLA); ?></td>
					<td><?php e($record->GRADE_NAME); ?></td>						
					<td><?php if($record->STATUS==1){ e("Active");}else{e("Inactive");} ?></td>
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