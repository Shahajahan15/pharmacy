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

$can_delete = true; //$this->auth->has_permission('Store.dept.requisition.Delete');
$can_edit = false; //$this->auth->has_permission('Store.dept.requisition.Edit');

$has_records = true; //isset($records) && is_array($records) && count($records);
?>

<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Req. Pharmacy Name</th>				
                    <th>Category Name</th>
                    <th>Product Name</th>
                    <th>Req. Qnty.</th>
                    <th>Issue Qnty.</th>
                    <th>Req. Date</th>
                    <th>Issue. Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                    ?>
                        <tr>
                        <td><?php echo ($record->requisition_pharmacy_name) ? $record->requisition_pharmacy_name : "Main Pharmacy"; ?></td>
                        <td><?php e($record->category_name); ?></td>
                        <?php if ($can_edit) : ?>
                                <td><?php echo anchor(SITE_AREA . '/department_requisition/store/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->product_name); ?></td>
                            <?php else : ?>
								<td><?php e($record->product_name); ?></td>
                            <?php endif; ?>
                            <td><?php e($record->req_qnty); ?></td>
                            <td><?php e($record->issue_qnty); ?></td>
                            <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->requisition_date)))?></td>
                             <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->issue_date)))?></td>
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