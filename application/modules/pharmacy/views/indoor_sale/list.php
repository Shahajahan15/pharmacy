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
                    <th>Patient Name</th>
                    <th>Contact No</th>
                    <th>Admission Id</th>
                    <th>Patient Id</th>
                    <th>Purpose</th>
                    <th>Cost Paid By</th>
                    <th>Assigned Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                foreach ($records as $record) :
                    ?>
                        <tr>
                            <td><?php echo $sl+=1;?></td>
                            <td><?php e($record->patient_name); ?></td>
                            <td><?php e($record->contact_no); ?></td>
                            <td><?php e($record->admission_code); ?></td>
                            <td><?php e($record->patient_code); ?></td>

                            <td><?php echo ($record->status==2)?'<p class="btn btn-xs btn-warning disabled">Operation</p>':'<p class="btn btn-xs btn-info disabled">Normal</p>'?></td>
                            


                            <td><?php echo ($record->cost_paid_by==1)?'<p class="btn btn-xs btn-success disabled">Patient</p>':'<p class="btn btn-xs btn-warning disabled">Hospital</p>'?></td>
                            <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->assigned_date)))?></td>
                           	<!--<a id="<?php echo $record->id; ?>" index="<?php echo $record->id; ?>" class="btn btn-success btn-xs cbtn-mini payment" >Sale</a>-->
                           	<td>
                           		<a href="<?php echo site_url(). '/admin/indoor_sale/pharmacy/sale/' . $record->id; ?>" class="btn btn-success btn-xs cbtn-mini payment" >Sale</a>
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

