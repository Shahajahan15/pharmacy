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

if (isset($employee_insurance_details))
{
	$employee_insurance_details = (array) $employee_insurance_details;
}
$ID = isset($employee_insurance_details['HOLIDAY_ID']) ? $employee_insurance_details['HOLIDAY_ID'] : '';

?>

<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('HRM.Employee_Define_Holiday.Delete');
$can_edit		= $this->auth->has_permission('HRM.Employee_Define_Holiday.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>

<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>					
					<?php if ($can_delete && $has_records) : ?>
					<th width="1%" class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>                 
					<th width="20%"><?php echo lang('HOLIDAY_ID'); ?></th>
					<th width="30%"><?php echo lang('HOLIDAY_DATE'); ?></th>
					<th width="30%"><?php echo lang('HOLIDAY_NAME'); ?></th>
					<th width="20%"><?php echo lang('TYPE'); ?></th>					
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
					<td class="column-check">
						<input type="checkbox" name="checked[]" value="<?php echo $record->HOLIDAY_ID; ?>" />
					</td>
						<?php endif;?>
						
					<?php if ($can_edit) : ?>
					<td>
						<?php echo anchor(SITE_AREA . '/employee_define_holiday/hrm/emp_holiday_define/' . $record->HOLIDAY_ID, '<span class="glyphicon glyphicon-pencil"></span>' .$record->HOLIDAY_ID); ?>
					</td>
					<?php else : ?>
					<?php endif; ?> 
					<td><?php e($record->HOLIDAY_DATE); ?></td>
					<td><?php e($record->HOLIDAY_NAME); ?></td>							
					<td>
						<?php 
						if($record->HOLIDAY_TYPE==1)
						{ 
							e("Holiday");
						}
						elseif($record->HOLIDAY_TYPE==2)
						{
							e("Compensatory");
						} 
						elseif($record->HOLIDAY_TYPE==3)
						{
							e("Festival");
						}
						?>
					</td>
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