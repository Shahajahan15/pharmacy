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
    $records = (array)$records;
}
$id = isset($records['id']) ? $records['id'] : '';

?>

<?php
$num_columns = 8;
$can_delete = true; //$this->auth->has_permission('Store.Product_purchase.Delete');
$can_edit = true; //$this->auth->has_permission('Store.Product_purchase.Edit');
$has_records = isset($records) && is_array($records) && count($records);
?>
<div id='search_result'>
    <div class="admin-box">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <table class="table report-table">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Order No</th>
                    <th>Received No</th>
                    <th>Supplier Name</th>
                    <th>Supplier Mobile Number</th>
                    <th>Received Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $sl = 0;
                foreach ($records as $record) { ?>
                    <tr>
                        <td><?php echo $sl += 1; ?></td>
                        <td><?php echo $record->purchase_order_no; ?></td>
                        <td><?php echo $record->bill_no; ?></td>
                        <td><?php echo $record->supplier_name; ?></td>
                        <td><?php echo $record->contact_no1; ?></td>
                        <td><?php echo date('d-m-Y', strtotime(str_replace('/', '-',
                                $record->received_date))) ?></td>
                        <td>
                            <button type="button" class="btn btn-xs btn-info receive_details"
                                    href="<?php echo site_url() . '/admin/direct_pharmacy_purchase/pharmacy/details/' . $record->id; ?>">
                                Details Product
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo $this->pagination->create_links(); ?>

</div>

