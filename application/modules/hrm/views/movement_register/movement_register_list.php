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

if (isset($movement_details))
{
	$records = (array) $movement_details;
}
$id = isset($records['MOVEMENT_REGISTER_ID']) ? $records['MOVEMENT_REGISTER_ID'] : '';

?>

<?php
$num_columns	= 7;
$can_delete		= $this->auth->has_permission('HRM.Movement_register.Delete');
$can_edit		= $this->auth->has_permission('HRM.Movement_register.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
	
       
        <?php echo form_open($this->uri->uri_string()); ?>
            <table class="table  table-responsive">
                <thead>
                    <tr>
                        <?php if ($can_delete && $has_records) : ?>
                        <th  class="column-check"><input class="check-all" type="checkbox" /></th>
                        <?php endif;?>
						<th ><?php echo lang('hrm_employee_name'); ?></th>						
                        <th><?php echo lang('from_date'); ?></th>
                        <th><?php echo lang('to_date'); ?></th>
                        <th><?php echo lang('movement_purpose'); ?></th>
                        <th><?php echo lang('destination'); ?></th>
						<th><?php echo lang('start_time'); ?></th>
						<th><?php echo lang('return_time'); ?></th>
						<th><?php echo lang('permitted_by'); ?></th>
						<th><?php echo lang('status'); ?></th>
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
                        <td class="column-check"><input type="checkbox" name="checked[]" value="<?php  echo $record->MOVEMENT_REGISTER_ID.','.$record->CREATED_DATE; ?>" id="checked" /></td>
                        <?php endif;?>

                        <?php if ($can_edit) : ?>
                        <td><?php echo anchor(SITE_AREA . '/movement_register/hrm/edit/' . $record->MOVEMENT_REGISTER_ID, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->EMP_NAME ); ?></td>
                        <?php else : ?>
                        <?php endif; ?>
						<td><?php e($record->FROM_DATE); ?></td>
                        <td><?php e($record->TO_DATE); ?></td>
                        <td><?php e($record->MOVEMENT_PURPOSE); ?></td>
                        <td><?php e($record->DESTINATION); ?></td>
                        <td><?php e($record->START_TIME); ?></td>
                        <td><?php e($record->RETURN_TIME); ?></td>
                        <td><?php e($record->permittedBy) ?></td>
						<td><?php if($record->STATUS == '1'){ echo 'Active';} else{ echo 'Inactive';} ?> </td>	
						
                        
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