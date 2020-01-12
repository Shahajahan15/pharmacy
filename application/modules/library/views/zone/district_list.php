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

if (isset($zone_district))
{
	$zone_district = (array) $zone_district;
}
$id = isset($zone_district['id']) ? $zone_district['id'] : '';

?>


<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('Lib.Zone.District.Delete');
$can_edit		= $this->auth->has_permission('Lib.Zone.District.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th  class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>					
					<th><?php echo lang('library_zone_district_name'); ?></th>
                    <th ><?php echo lang('library_zone_division_name'); ?></th>						 
                    <th><?php echo lang('library_zone_district_status'); ?></th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<?php echo lang('bf_with_selected'); ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('library_delete_confirm'))); ?>')" />
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
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->district_id; ?>" /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/district/library/edit/' . $record->district_id, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->district_name); ?></td>
				<?php else : ?>
					<td><?php e($record->district_name); ?></td>
				<?php endif; ?>		
					<td><?php e($record->division_name) ?></td>
					  
					  <td><?php if($record->district_status==1){ e("Active");}else{e("In Active");} ?></td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>"><?php echo lang('zone_no_records_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
</div>