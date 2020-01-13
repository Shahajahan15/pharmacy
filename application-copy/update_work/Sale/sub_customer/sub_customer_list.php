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
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
    <?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped">
            <thead>
                <tr>           
                    <th width="20%"><?php echo lang('pharmacy_customer_name'); ?></th>
                    <th width="20%"><?php echo lang('pharmacy_sub_customer_name'); ?></th>
                    <th width="20%"><?php echo lang('sub_customer_phone'); ?></th>
                    <th width="20%"><?php echo lang('pharmacy_company_status'); ?></th> 
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($records as $record) :
                    ?>
                        <tr>
                              <td><?php echo anchor(SITE_AREA . '/sub_customer/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->customer_name,'class="bf-edit-action"'); ?></td>
                            <td><?php e($record->sub_customer_name); ?></td>
                            <td><?php e($record->sub_customer_phone); ?></td>
                            <td><?php if ($record->status == 1) {
                        e("Active");
                    } else {
                        e("In Active");
                    } ?></td>
                        </tr>
                        <?php
                    endforeach;
                // else:
                    ?>
            </tbody>
        </table>
<?php echo form_close(); ?>
    </div>
     <?php echo $this->pagination->create_links(); ?>
</div>