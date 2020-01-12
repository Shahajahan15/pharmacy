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
            <table class="table table-striped m_ph_payment">
                <thead>
                <tr>
                    <th>Customer Type</th>
                    <th>Customer Name</th>
                    <th>Pay Bill</th>
                    <th>Return Bill</th>
                    <th>Net Payment</th>
                    <th>Overall Discount</th>
                    <th>Total Due</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($has_records) :
                    foreach ($records as $record) :
                        // $net_bill = ($record->bill - $record->return_bill);
                        $net_payment = ($record->payment - $record->return_amount);
                        //$due = ($net_bill - $net_payment);
                        $due = $record->due;

                        $admission_id = 0;
                        $patient_id = 0;
                        $patient_id = 0;
                        $customer_id = 0;
                        $employee_id = 0;
                        if ($record->customer_type == 1) {
                            $admission_id = $record->client_id;
                        } elseif ($record->customer_type == 2) {
                            $patient_id = $record->client_id;
                        } elseif ($record->customer_type == 3) {
                            $customer_id = $record->client_id;
                        } elseif ($record->customer_type == 4 || $record->customer_type == 5) {
                            $employee_id = $record->client_id;
                        }
                        ?>
                        <tr>
                            <td><?php echo $record->customer_type_name; ?></td>
                            <td><?php e($record->client_name); ?></td>
                            <td class="tot_bill"><?php e($record->bill); ?></td>
                            <td class="tot_return"><?php e($record->return_bill); ?></td>
                            <td class="tot_paid"><?php e($net_payment); ?></td>
                            <td class="overall_discount"><?php e($record->overall_discount); ?></td>
                            <td class="tot_due"><?php e($due); ?></td>
                            <td class="status"><?php

                                if ($due <= 0) {
                                    ?>
                                    <span class="label label-success">Paid</span>
                                <?php } else { ?>
                                    <span class="label label-danger">Due</span>
                                <?php } ?>
                            </td>
                            <td class="full_paid">
                                <?php if ($due <= 0) : ?>
                                    <span>Full Paid</span>
                                <?php else : ?>
                                    <a customer_id="<?php echo $customer_id; ?>" patient_id="<?php echo $patient_id; ?>"
                                       customer_type="<?php echo $record->customer_type; ?>"
                                       admission_id="<?php echo $admission_id; ?>"
                                       employee_id="<?php echo $employee_id; ?>"
                                       class="btn btn-success btn-xs cbtn-mini main_pharmacy_bill">Paid</a>
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
    </div><?php echo $this->pagination->create_links(); ?>
</div>
<!--<script>
    	$(document).ready(function(){
    		<?php if (isset($print)) : ?>
    		print_view(<?php echo $print; ?>);
    		<?php endif; ?>
    	});
    	
    </script>-->

<script type="text/javascript">

    $(document).ready(function () {

        <?php if(isset($print)){?>

        var jsonObj =<?php echo json_encode($print) ?>;
        print_view(jsonObj);
        <?php unset($print); } ?>
    })


</script>



