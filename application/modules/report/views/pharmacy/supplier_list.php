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
if (isset($records)) {
    $records= (array) $records;
}
$id = isset($records['id']) ? $records['id'] : '';
?>

<?php
$num_columns = 8;
$has_records = isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Company name</th>
                    <th>Supplier Code</th>
                    <th>Contact Person</th>
                    <th>Mobile</th>                                         
                </tr>
            </thead>

            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                        $record=(array)$record;
                    ?>
                        <tr>
                            <td><a style="cursor:pointer;" class="supplier-from-list" id="<?php echo $record['id'];?>"><?php echo $record['supplier_name'];?></td>
                            <td><?php echo $record['company_name'];?></td>
                            <td><?php echo $record['supplier_code']; ?></td>
                            <td><?php echo $record['contact_person']; ?></td>
                            <td><?php echo $record['contact_no1']; ?></td>
                   
                          
                     
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