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

$can_delete = false; //$this->auth->has_permission('Store.dept.requisition.Delete');
$can_edit = false; //$this->auth->has_permission('Store.dept.requisition.Edit');

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
                    <th width="2%" class="column-check"><input class="check-all" type="checkbox" /></th>
            <?php endif; ?>		
                    <th>SL</th>
                    <th>Requisition No</th>
                    <th>Requisition Date</th>
                    <th>Product Name</th>
                    <th>Stock</th>
                    <th>Prv. Approve.Qnty.</th>
                    <th>Prv.Price(/unit)</th>
                    <th>Req. Qnty.</th>
                    <th>Aprv. Qnty.</th>
                    <th>T.R.Price</th>
                    <th><input class="approve-all" type="checkbox" /></th>
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
            $total_last_purchase = 0;
            $total_last_purchase_price = 0;
            if ($has_records) :
                foreach ($records as $record) :
                $total_last_purchase += 0;
                //$total_last_purchase_price += $record->last_purchase_price_unit;
                $total_last_purchase_price += ($record->req_qnty * $record->last_purchase_price_unit);
                    ?>
                        <tr>
                        <?php if ($can_delete) : ?>
                                <td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
                            <?php endif; ?>
                            <td><?php echo $sl+=1;?></td>
                                <?php if ($can_edit) : ?>
                                <td><?php echo anchor(SITE_AREA . '/dept_requisition_issue/store/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->requisition_no); ?></td>
                            <?php else : ?>
								<td><?php e($record->requisition_no); ?></td>
                            <?php endif; ?>
                             <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->requisition_date)))?></td>
                            
                            <td><?php echo ($record->category_name.' >> '.$record->product_name); ?></td>
                            <td><?php echo isset($record->quantity_level) ? $record->quantity_level : 0; ?></td>
                            <td><?php echo $record->last_approved_qnty; ?></td>
                           
                            <td class="last_price"><?php e ($record->last_purchase_price_unit); ?></td>
                            <td><?php e($record->req_qnty); ?></td>
                            <td><input type="text" value="<?php e(round($record->req_qnty)); ?>" name="approve_qnty[]" class="approve_nty" /></td>
                            <td class="total_price"><?php e($record->req_qnty * $record->last_purchase_price_unit); ?></td>
                           <!-- <a class="btn btn-info btn-xs glyphicon glyphicon-th products_issue" mst_id="<?php echo $record->id; ?>"></a>-->
                           <td class="column-check"><input type="checkbox" name="approve[]" value="<?php echo $record->id; ?>" class="pru_requi_approve" /></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                    <tr>
                        	<td colspan="8">&nbsp;</td>
                        	
                            <td id="approve_total_qnty"></td>
                        	<td id="approve_total_price"></td>                        	
                        	<td><button type="submit" class="btn btn-primary btn-sm app_done" disabled="">Done</button></td>
                    </tr>
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

