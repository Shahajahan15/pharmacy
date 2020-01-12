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

if (isset($lib_base_head_details))
{
	$lib_base_head_details = (array) $lib_base_head_details;
}
$BASE_HEAD_ID  = isset($lib_base_head_details['BASE_HEAD_ID ']) ? $lib_base_head_details['BASE_HEAD_ID '] : '';

?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<?php
$num_columns	= 4;
$can_delete		= $this->auth->has_permission('Lib.Basehead.Delete');
$can_edit		= $this->auth->has_permission('Lib.Basehead.Edit');
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
					<th width="30%"><?php echo lang('lib_base_system_head'); ?></th>
					<th width="20%"><?php echo lang('lib_base_head_custom_name'); ?></th>
					<th width="15%"><?php echo lang('lib_base_head_abrebiation'); ?></th>
					<th width="15%"><?php echo lang('lib_base_head_type'); ?></th>
					<th width="10%"><?php echo lang('bf_status'); ?></th>
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
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->BASE_HEAD_ID; ?>" /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/base_head_info/library/base_head_edit/' . $record->BASE_HEAD_ID, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->BASE_SYSTEM_HEAD); ?></td>
				<?php else : ?>
					
				<?php endif; ?>
					<td><?php e($record->BASE_HEAD_CUSTOM_NAME); ?></td>
					<td><?php e($record->BASE_HEAD_ABBREBIATION); ?></td>
					<td><?php e($head_type[$record->BASE_HEAD_TYPE]); ?></td>					
					<td><?php if($record->STATUS==1){ e("Active");}else{e("In Active");} ?></td>
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