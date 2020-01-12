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
$num_columns = 12;
$can_delete = ""; // $this->auth->has_permission('Others.Service.Bill.Delete');
$can_edit = ""; // $this->auth->has_permission('Others.Service.Bill.Edit');
$can_print = $this->auth->has_permission('Pharmacy.MoneyReceive.Reprint');
$has_records = isset($records) && is_array($records) && count($records);
?>

<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped bill_payment">
            <thead>
            <tr style="background-color: #b7cfe4;padding: 10px;">
                <th>SL. No</th>
                <th>Sale No</th>
                <th>Customer Name</th>
                <th>Customer Type</th>
                <th>Contact Number</th>
                <th>Cash Recieved By</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Less Discount</th>
                <th>Due Amount</th>
                <th>Reprint</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                $i = 1;
                foreach ($records as $record) :
                    ?>
                    <tr>
                        <td><?php e($i++); ?></td>
                        <td><?php e($record->sale_no); ?></td>
                        <td><?php e($record->customer_name); ?></td>
                        <td><?php
                            if ($record->customer_type == 1):
                                echo "Admission Patient";
                            elseif ($record->customer_type == 2) :
                                echo "Patient";
                            elseif ($record->customer_type == 3) :
                                echo "Customer";
                            elseif ($record->customer_type == 4) :
                                echo "Employee";
                            elseif ($record->customer_type == 5) :
                                echo "Doctor";
                            elseif ($record->customer_type == 6) :
                                echo "Hospital";
                            endif; ?>
                        </td>
                        <td><?php
                            if ($record->customer_contct_no != "") {
                                e($record->customer_contct_no);
                            } else {
                                echo "0";
                            } ?>
                        </td>
                        <td><?php e($record->display_name); ?></td>
                        <td><?php echo $record->created_date; ?></td>
                        <td><?php echo round($record->tot_bill); ?></td>
                        <td><?php echo round($record->tot_paid); ?></td>
                        <td><?php echo round($record->tot_less_discount); ?></td>
                        <td><?php echo round(round($record->tot_bill) - ($record->tot_paid + $record->tot_less_discount)); ?></td>
                        <td>
                            <a class="btn btn-danger btn-xs"
                               href="<?php echo site_url() . '/admin/sale_reprint_list/pharmacy/sale_print/' . $record->id; ?>"
                               target="_blank">Reprint
                            </a>
                        </td>
                    </tr>
                <?php
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="<?php echo $num_columns; ?>"
                        class="text-center text-capitalize bg-gray"><?php echo "No Record Found"; ?>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
    </div>
    <?php
    echo $this->pagination->create_links();
    ?>
</div>


