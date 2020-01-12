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

$has_records = isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped store_payment">
            <thead>
                <tr>			
                    <th>SL</th>
                    <th>Product</th>
                    <th>Discount</th>
                    <th>From</th>
                    <th>To</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if ($has_records) :
                $sl=1;
                foreach ($records as $record) :
                    ?>
                        <tr>
                            <td><?php echo $sl++; ?></td>                            
                            <td><?php echo ($record->discount_type==1)?'All <a class="btn btn-xs btn-info discount_without" id="'.$record->id.'" style="cursor:pointer">without Some Product</a>':$record->product_name ?></td>
                            <td><?php e($record->discount_parcent); ?></td>
                            <td><?php e(date('d M,Y',strtotime($record->discount_from))); ?></td>
                            <td><?php e(date('d M,Y',strtotime($record->discount_to))); ?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                <?php
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


