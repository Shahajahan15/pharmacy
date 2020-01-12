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

if (isset($salary_info_mst_all))
{
	$records = (array) $salary_info_mst_all;
}
$id = isset($records['HRM_EMPLOYE_SALARY_ID']) ? $records['HRM_EMPLOYE_SALARY_ID'] : '';

?>

<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('Store.supplier.Delete');
$can_edit		= $this->auth->has_permission('Store.supplier.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
        
        <?php echo form_open($this->uri->uri_string()); ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <?php if ($can_delete && $has_records) : ?>
                        <th width="2%" class="column-check"><input class="check-all" type="checkbox" /></th>
                        <?php endif;?>					
                        <th width="20%"><?php echo lang('hrm_employee_name'); ?></th>                      
                        <th width="15%"><?php echo lang('hrm_salary_amount'); ?></th>
                        <th width="15%"><?php echo lang('hrm_salary_rule'); ?></th>

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
                        <td class="column-check"><input type="checkbox" name="checked[]" value="<?php  echo $record->MST_ID; ?>" /></td>
                        <?php endif;?>

                        <?php if ($can_edit) : ?>
                        <td><?php echo anchor(SITE_AREA . '/salary_info/hrm/edit/' . $record->MST_ID, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->EMP_NAME ); ?></td>
                        <?php else : ?>
                        <?php endif; ?>                       
                        <td><?php e($record->SALARY_AMOUNT); ?> </td>
                        <td><?php e($record->SALARY_RULE); ?> </td>
                        <td><?php  ?></td>
                        <td> 
                            <a class="btn btn-primary btn-xs" href="<?php echo site_url().'/admin/salary_info/hrm/detailsList/'.$record->MST_ID ; ?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
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