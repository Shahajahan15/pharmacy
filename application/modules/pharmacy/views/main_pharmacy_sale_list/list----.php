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
                    <th>Date</th>
                    <th>Sale No</th>
                    <th>Total Bill</th>
                    <th>Total Payment</th>
                    <th>Total Due</th>
                    <th>Total Normal Discount</th>
                    <!-- <th>Total Service Discount tot_service_discount</th> -->
                    <th>Total Less Discount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $record) { ?>
                    <tr>
                        <td><?php echo date('d/m/Y h:m:i a', strtotime($record->created_date)); ?></td>
                        <td><?php echo $record->sale_no; ?></td>
                        <td><?php echo $record->tot_bill; ?></td>
                        <td><?php echo $record->tot_paid; ?></td>
                        <td><?php echo $record->tot_due; ?></td>
                        <td><?php echo $record->tot_normal_discount; ?></td>
                        <td><?php echo $record->tot_less_discount; ?></td>
                        <td><a class="btn btn-danger btn-xs reprint"
                               href="<?php echo site_url() . '/admin/main_pharmacy_sale/pharmacy/sale_reprint/' . $record->id; ?>">Reprint</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>
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



