<?php
//extract($sendData);
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
if (isset($requision_records)) {
    $records = (array) $requision_records;
}
$id = isset($records['id']) ? $records['id'] : '';



?>
<?php
$num_columns = 8;
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
       
        <table class="table table-striped  ">
            <thead>
                <tr>				
                   <th width=""><?php echo lang('store_purchase_details');?></th>				
                </tr>
            </thead>
			
			<thead>
				<tr>				
					<th width=""><?php echo lang('store_purchase_product_name');?></th>
					<th width=""><?php echo lang('store_purchase_indent_qty');?></th>					
					<th width=""><?php echo lang('product_unit_price');?></th>
					<th width=""><?php echo lang('product_total_price');?></th>					
				</tr>
			</thead>
			
			<thead id="indentResultDetails">
				
				
				
			</thead>
			
			
            </tbody>
        </table>
    </div>
</div>