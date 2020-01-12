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
$can_delete = false;
$can_edit = false;
$has_records = isset($records) && is_array($records) && count($records);
?>

<?php echo form_open($this->uri->uri_string()); ?>

<?php echo form_close(); ?>
<div id='search_result'>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <center>
            <h3>Opening Balance Add into <b><?php echo $pharmacy->name;?></b></h3>
            <input type="hidden" name="pharmacy_id" value="<?php echo $pharmacy->id; ?>">
        </center>
        <table class="table table-striped">
            <thead>
                <tr>
<?php if ($can_delete && $has_records) : ?>
                        <th width="2%" class="column-check"><input class="check-all" type="checkbox" /></th>
                    <?php endif; ?>
                    <th>SL</th>
                  
                    <th>Product Name</th>
                    <th>Category Name</th>
                    <th>Company Name</th>
                    <th width="15%">Opening Quantity</th>
                    <th width="3%">Submit</th>
                </tr>
            </thead>

            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                    ?>
                        <tr>
                            <td><?php echo $sl+=1 ?></td>
                            <td><?php e($record->product_name); ?></td> 
                            <td><?php e($record->category_name); ?></td>  
                             <td><?php e($record->company_name); ?></td>                           
                           
                            <td>
                                <input type="hidden" name="product_id[]" class="product_id form-control" value="<?php echo $record->id; ?>">
                                <input type="text" name="qnty[]" class="qnty form-control real-number">
                                <p class="qnty-message" style="color:red"></p>
                            </td>                            
                            <td>
                                <button type="button" class="btn btn-xs submit-op-balance">Submit</button>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_records_not_found'); ?>
                        </td>
                    </tr>
        <?php endif; ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-success pull-right op-submit-all">Submit All</button>


    </div>
</div>
<?php  echo $this->pagination->create_links(); ?>
</div>
