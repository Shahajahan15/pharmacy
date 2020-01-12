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

if (isset($store_details)) {
    $store_details = (array) $store_details;
}
$id = isset($store_details['id']) ? $store_details['id'] : '';
?>

<?php
$num_columns = 8;
$can_delete = $this->auth->has_permission('pharmacy.Company.Delete');
$can_edit = $this->auth->has_permission('pharmacy.Company.Edit');
$has_records = isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped">
            <thead>
                <tr>
<?php if ($can_delete && $has_records) : ?>
                        <th width="2%" class="column-check"><input class="check-all" type="checkbox" /></th>
                    <?php endif; ?>					
                    <th width="20%"><?php echo lang('pharmacy_company_name'); ?></th>
                    <th width="20%"><?php echo lang('pharmacy_company_ceo_name'); ?></th>
                    <th width="20%"><?php echo lang('pharmacy_company_code'); ?></th>
                    <th width="20%"><?php echo lang('pharmacy_company_location'); ?></th>					
                    <th width="20%"><?php echo lang('pharmacy_company_country'); ?></th>
                    <th width="20%"><?php echo lang('pharmacy_company_status'); ?></th>
                </tr>
            </thead>
<?php if ($has_records) : ?>
                <tfoot>
                <?php if ($can_delete) : ?>
                        <tr>
                            <td colspan="<?php echo $num_columns; ?>">
        <?php echo lang('bf_with_selected'); ?>
                                <input type="submit" name="delete" id="delete-me" class="btn btn-danger bf-delete-action" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('bf_msg_delete_confirm'))); ?>')" />
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
                                <td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
                            <?php endif; ?>

                                <?php if ($can_edit) : ?>
                                <td><?php echo anchor(SITE_AREA . '/company/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->company_name,'class="bf-edit-action"'); ?></td>
                            <?php else : ?>

                            <?php endif; ?>

                            <td><?php e($record->company_ceo_name); ?></td>
                            <td><?php e($record->company_code); ?></td>
                            <td><?php e($record->company_location); ?></td>
                            <td><?php echo ($record->company_country == 1) ? "Bangladesh" : ""; ?></td>
                            <td><?php if ($record->status == 1) {
                        e("Active");
                    } else {
                        e("In Active");
                    } ?></td>
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
     <?php echo $this->pagination->create_links(); ?>
</div>