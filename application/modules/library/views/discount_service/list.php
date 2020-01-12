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

if (isset($records)){
	$record = (object) $records;
}

?>



<?php
$num_columns	= 6;
$can_delete		= true; //$this->auth->has_permission('Lib.DiscountService.Delete');
$can_edit		= true; //$this->auth->has_permission('Lib.DiscountService.Edit');
$has_records	= isset($records);
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
					<th>SL</th>
					<th>Service Name</th>
					<th>Department Name</th>
					<th>Discount Applicable</th>
					<th>Status</th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<?php echo lang('bf_with_selected'); ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger confirm" value="<?php echo lang('bf_action_delete'); ?>"/>
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($records) : 
					$sl=0;
					foreach ($records as $record) :
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
					<?php endif;?>
					<td><?php echo $sl+=1; ?></td>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/discount_service_setup/library/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->service_name); ?></td>
				<?php else : ?>
					<td><?php e($record->service_name); ?></td>
				<?php endif; ?>
					<td><?php echo $record->department_name; ?></td>
					<td><?php echo ($record->has_discount==1)?'<p class="btn btn-xs btn-success disabled">Yes</p>':'<p class="btn btn-xs btn-info disabled">No</p>' ?></td>
					<td><?php echo ($record->status==1)?'Active':'Inactive' ?></td>
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