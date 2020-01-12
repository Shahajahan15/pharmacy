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
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>SL</th>			
                    <th>Bill No</th>
                    <th>Supplier Name</th>
                    <th>Received Qnty</th>
                    <th>Total Price</th>
                    <th><input class="approve-all" type="checkbox" /></th>
                </tr>
            </thead>
            <tbody>
            <?php
            $tot_qnty = 0;
            $tot_price = 0;
            $sub_total= 0;
            if ($has_records) :
                foreach ($records as $record) :
                $tot_qnty +=$record->qnty;
                $tot_price +=$record->price;
                    ?>
                        <tr>
                            <td><?php echo $sl+=1;?></td>
                            <td><?php e($record->bill_no); ?></td>
                            <td><?php e($record->supplier_name); ?></td>
                            <td class="qnty"><?php echo $record->qnty; ?></td>
                            <td class="price"><?php echo $record->price; ?></td>
                           <td><input type="checkbox" name="approve[]" value="<?php echo $record->id; ?>" class="pp_approve" /></td>
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
            <tfoot>
            	<tr>
            		<td>&nbsp;</td>
            		<td>&nbsp;</td>
                    <td>&nbsp;</td>
            		<td id="tot_qnty"><?php echo $tot_qnty; ?></td>
            		<td id="tot_price"><?php echo $tot_price; ?></td>
            		<td><button type="submit" class="btn btn-primary btn-sm app_done">Done</button></td>
            	</tr>
            </tfoot>
        </table>
<?php echo form_close(); ?>
    </div>
</div>
<?php  echo $this->pagination->create_links(); ?>
</div>
