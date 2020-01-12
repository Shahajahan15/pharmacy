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
                    <th>Sale No</th>
                    <th>Date</th>
                    <th>Total Bill</th>
                    <th>Total Payment</th>
                    <th>Total Return</th>
                    <th>Total Due</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                    $tot_paid = isset($full_paid[$record->id]['paid']) ? $full_paid[$record->id]['paid'] : 0;
                    $tot_paid_return = isset($full_paid[$record->id]['return_paid']) ? $full_paid[$record->id]['return_paid'] : 0;
                    $tot_due = ($record->tot_bill) - ($tot_paid + $tot_paid_return);
                    ?>
                        <tr>
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
                                endif;
                             ?></td>
                            <td class="sale_no"><?php e($record->sale_no); ?></td>
                            <td><?php e(custom_date_format($record->created_date)); ?></td>
                            <td><?php e($record->tot_bill); ?></td>
                            <td class="tot_paid"><?php e($tot_paid); ?></td>
                            <td class="tot_return"><?php e($tot_paid_return); ?></td>
                            <td class="tot_due"><?php echo $tot_due; ?></td>
                            <td class="full_paid">
                            <?php if ($tot_due <= 0) : ?>
                                <span>Full Paid</span>
                            <?php else : ?>
                                <a id="<?php echo $record->id; ?>" class="btn btn-success btn-xs cbtn-mini main_pharmacy_bill">Paid</a>
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
</div><?php  echo $this->pagination->create_links(); ?>
</div>
<!--<script>
    $(document).ready(function(){
        <?php if (isset($print)) : ?>
        print_view(<?php echo $print; ?>);
        <?php endif; ?>
    });
    
</script>-->

<script type="text/javascript">

$(document).ready(function(){

    <?php if(isset($print)){?>

    var jsonObj=<?php echo json_encode($print) ?>;
    print_view(jsonObj);
    <?php unset($print); } ?>
})

    
</script>



