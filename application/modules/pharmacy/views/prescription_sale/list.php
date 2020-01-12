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
        <table class="table table-striped store_payment">
            <thead>
                <tr>			
                    <th>Patient Name</th>
                    <th>Patient Id</th>
                    <th>Contact No</th>
                    <th>Ticket No</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                    ?>
                        <tr>
                            <td><?php e($record->patient_name); ?></td>
                            <td><?php e($record->patient_code); ?></td>
                            <td><?php e($record->contact_no); ?></td>
                            <td><?php e($record->receipt_no); ?></td>
                            <td><?php e($record->doctor_name); ?></td>
                            <td><?php e($record->create_date); ?></td>
                           	<td>
                           		
                           		<?php if ($record->status != 2) : ?>
                           		<span>Print</span>
                           		<a href="<?php echo site_url(). '/admin/prescription_sale/pharmacy/sale/' . $record->id; ?>" class="btn btn-success btn-xs cbtn-mini payment" >Sale</a>
                           
                           		<?php else : ?>
                           		<a id="<?php echo $record->id; ?>" class="btn btn-info btn-xs cbtn-mini p_print" >Print</a>
                           		<span>Sale Complete</span>
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



