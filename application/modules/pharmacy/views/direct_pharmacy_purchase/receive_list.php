<?php
/**
 * Created by PhpStorm.
 * User: Robin
 * Date: 5/7/2019
 * Time: 12:15 PM
 */
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
                    <th>SL:</th>
                    <th>Date</th>
                    <th>Supplier Name</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                <?php $sl = 0;
                foreach ($records as $record) { ?>
                    <tr>
                        <td><?php echo $sl += 1; ?></td>
                        <td><?php echo date('d-m-Y', strtotime(str_replace('/', '-', $record->supply_date_from))) ?></td>
                        <td><?php echo $record->supplier_name; ?></td>
                        <td><?php echo $record->product_name; ?></td>
                        <td><?php echo $record->qnty; ?></td>
                        <td><?php echo $record->unit_price; ?></td>
                        <td><?php echo $record->total_price; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo $this->pagination->create_links(); ?>

</div>

