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
$can_delete = $this->auth->has_permission('Pharmacy.SubCategory.Delete');
$can_edit = $this->auth->has_permission('Pharmacy.SubCategory.Edit');
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
                    <th width="60%"><?php echo lang('pharmacy_sub_category_name'); ?></th>
                    <th width="60%"><?php echo lang('pharmacy_category_name'); ?></th>
                    <th width="38%"><?php echo lang('pharmacy_sub_category_status'); ?></th>
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
                                <td><?php echo anchor(SITE_AREA . '/sub_category/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->subcategory_name,'class="bf-edit-action"'); ?></td>
                            <?php else : ?>
								<td><?php e($record->subcategory_name); ?></td>
                            <?php endif; ?>
                            <td><?php echo isset($category_name[$record->category_id]) ? $category_name[$record->category_id] : ""; ?></td>
                            <td>
                            <?php echo ($record->status == 1) ? "Active" : "In Active"; ?>
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
<?php echo form_close();

echo $this->pagination->create_links();
 ?>
    </div>
</div>