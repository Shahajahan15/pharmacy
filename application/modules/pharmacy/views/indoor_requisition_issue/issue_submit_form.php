

<?php echo form_open(site_url().'/admin/indoor_requisition_issue/pharmacy/show_list', 'role="form", class="nform-horizontal"'); ?>


<table class="table table-hover">
    <thead>
        <tr class="active">
            <th style="width: 155px;">P. Name</th>
            <th>R. Qty.</th>
            <th>Al.Is. Qty.</th>

            <th>Pe.Is. Qty.</th>

            <th>Re. Stock</th>
            <th>Is. Stock</th>
            <th>Is. Qty.</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($records as $record){ $record=(object)$record; ?>
        <tr class="warning">
            <td>
                <?php echo $record->category_name.'&nbsp; >> &nbsp;'.$record->product_name; ?>
                <?php //if($record->current_main_stock > 0){?>
                <input type="hidden" name="product_id[]" value="<?php echo $record->product_id; ?>"/>
                <input type="hidden" name="id[]" value="<?php echo $record->id; ?>">
                <input type="hidden" name="master_id[]" value="<?php echo $record->master_id; ?>">
                <input type="hidden" name="requ_qnty[]" value="<?php echo $record->req_qnty; ?>">
                <?php //} ?>
            </td>            
            <td><?php echo round($record->req_qnty); ?></td>

            <td><?php echo round($record->issue_qnty); ?></td>

            <td class="issue_need"><?php echo $pending_qty = round($record->req_qnty-$record->issue_qnty); ?></td>

            <td><?php echo round($record->req_stock); ?></td>

            <td class="main_stock"><?php echo round($record->issue_stock); ?></td>
            <td><input type="text" required="" name="issue_qnty[]" <?php echo ($record->issue_stock > 0)?'':'placeholder="Insufficient Stock" readonly="" value="0"'?> class="form-control issue_qnty_for_dept" <?php echo $record->product_id; ?> /></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<center>
    <button type="submit" onclick="return confirmMassege();" name="save" class="btn btn-primary btn-sm">Submit</button>
    <button type="reset" class="btn btn-warning btn-sm">Reset</button>
</center>




<?php echo form_close(); ?>