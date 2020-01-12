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

<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped store_payment">
            <thead>
                <tr>
                <th>SL</th>		
                <th>Sale No</th>
                <th>Return Amount</th>
                <th>Charge Amount</th>
                <th>Less Discount</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
                <th>Date</th>	
                </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                    ?>
                        <tr>
                            <td><?php echo $sl+=1;?></td>
                            <td><?php e($record->sale_no); ?></td>
                            <td><?php e($record->tot_return_amount); ?></td>
                            <td><?php e($record->tot_charge_amount); ?></td>
                            <td><?php e($record->tot_less_discount); ?></td>
                            <td><?php e($record->tot_paid_return_amount); ?></td>
                            <td><?php e($record->tot_return_due); ?></td>
                            <td><?php e(date('d-m-Y',strtotime($record->created_date))); ?></td>                       
                         </tr>
                        <?php
                    endforeach;
                    ?>
                <?php
                else:
                    ?>
                    <tr>
                        <td colspan="4"><?php echo "No Records Found"; ?></td>
                    </tr>
        <?php endif; ?>
            </tbody>
        </table>
<?php echo form_close(); ?>
    </div>
</div>

<?php  echo $this->pagination->create_links(); ?>

<script type="text/javascript">

$(document).ready(function(){

	<?php if(isset($print)){?>

	var jsonObj=<?php echo json_encode($print) ?>;
	print_view(jsonObj);
	<?php unset($print); } ?>
})

	
</script>

