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
<div id='search_result'>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped store_payment">
            <thead>
                <tr>	
                    <th>SL</th>		
                    <th>Bill No</th>
                    <th>Supplier Name</th>
                    <th>Total Bill</th>
                    <th>Total Paid</th>
                    <th>Payment</th>
                    
                </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                    ?>
                        <tr>
                            <td><?php echo $sl+=1;?></td>
                            <td><?php e($record->bill_no); ?></td>
                            <td><?php e($record->supplier_name); ?></td>
                            <td><?php e($record->total_price); ?></td>
                            <td><?php e($record->paid_price); ?></td>
                           <td>
                           	<?php if ($record->status == 0) :
                           		echo 'Pending';
                           	?>
                           	<?php else : ?>
                           		<a id="<?php echo $record->id; ?>" index="<?php echo $record->id; ?>" class="btn btn-success btn-xs cbtn-mini payment" >Payment</a>
                           	<?php endif; ?>
                           </td>
                          
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
</div>

