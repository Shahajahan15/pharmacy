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
$can_delete = $this->auth->has_permission('Pharmacy.Product.Delete');
$can_edit = $this->auth->has_permission('Pharmacy.Product.Edit');
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
                    <th>SL</th>
                    <th><?php echo lang('pharmacy_company_name'); ?></th>
                    <th><?php echo lang('pharmacy_category_name'); ?></th>
                    <th><?php echo lang('pharmacy_product_name'); ?></th>
                    <th><?php echo lang('pharmacy_purchase_price'); ?></th>
                    <th><?php echo lang('pharmacy_sale_price'); ?></th>
                    <th><?php echo lang('pharmacy_product_status'); ?></th>
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
                            <td><?php echo $sl+=1 ?></td>
                                <?php if ($can_edit) : ?>
                                <td><?php echo anchor(SITE_AREA . '/product/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->company_name,'class="bf-edit-action"'); ?></td>
                            <?php else : ?>
								<td><?php e($record->company_name); ?></td>
                            <?php endif; ?>
                            <td><?php e($record->category_name); ?></td>
                            <td><?php e($record->product_name); ?></td>
                            <td><?php e($record->purchase_price); ?></td>
                            <td><?php e($record->sale_price); ?></td>
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
<?php echo form_close(); ?>
    </div>
</div>
<?php  echo $this->pagination->create_links(); ?>
