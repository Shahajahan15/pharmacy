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

?>

<?php

$can_delete = false; //$this->auth->has_permission('Pharmacy.Purcase.Requisition.Delete');
$can_edit = false;//$this->auth->has_permission('Pharmacy.Purcase.Requisition.Edit');

$has_records = true; //isset($records) && is_array($records) && count($records);
?>
<div id='search_result'>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped">
            <thead>
                <tr>
<?php if ($can_delete && $has_records) : ?>
                    <th class="column-check"><input class="check-all" type="checkbox" /></th>
            <?php endif; ?>	
                    <th>SL</th>				
                    <th>Requisition No</th>
                    <th>Product Name</th>
                    <th>Requisition quantity</th>
                    <th>Approve quantity</th>
                    <th>Issue quantity</th>
                    <th>Requisition Date</th>
                    <th>Status</th>
                </tr>
            </thead>
<?php if ($has_records) : ?>
                <tfoot>
                <?php if ($can_delete) : ?>
                        <tr>
                            <td colspan="">
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
                                <td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
                            <?php endif; ?>
                            <td><?php echo $sl+=1;?></td>
                                <?php if ($can_edit) : ?>
                                <td><?php echo anchor(SITE_AREA . '/department_requisition/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->requisition_no); ?></td>
                            <?php else : ?>
								<td><?php e($record->requisition_no); ?></td>
                            <?php endif; ?>
                            <td><?php e($record->category_name."  >>  ".$record->product_name); ?></td>
                            <td><?php e($record->req_qnty); ?></td>
                            <td><?php e($record->approve_qnty); ?></td>
                            <td><?php e($record->issue_qnty); ?></td>
                            <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->requisition_date)))?></td>
                            <td>
                            <?php echo ($record->status == 1) ? "pending" : "issue"; ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                    </tr>
        <?php endif; ?>
            </tbody>
        </table>
<?php echo form_close(); ?>
    </div>
</div>
<?php  echo $this->pagination->create_links(); ?>
</div>