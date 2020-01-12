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

if (isset($records))
{
	$records = (array) $records;
}
$id = isset($records['id']) ? $records['id'] : '';

?>

<?php
$num_columns	= 8;
$can_delete		= true; //$this->auth->has_permission('pharmacy.Product_purchase.Delete');
$can_edit		= true; //$this->auth->has_permission('pharmacy.Product_purchase.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div id='search_result'>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
	
	
		<table class="table table-striped">
			<thead>
                <tr>           				
                	<th>SL</th>	          
                	<th>Product Name</th>	
                	<th>Approve Date</th>
                	<th>Approve Quantity</th>
                	<th>Last Purchase price</th>
                	<th>Total price</th>
                 </tr>

			</thead>

			<tbody>
				<?php 
				
					foreach($records as $record){
						$record=(object)$record; 
				 ?>
				<tr>
					<td><?php echo $sl+=1; ?></td>
					<td><?php echo $record->category_name ."&nbsp; >> &nbsp;". $record->product_name; ?></td>
					<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->requisition_approve_date)))?></td>
					<td><?php echo $record->approve_qnty; ?></td>
					<td><?php echo $record->purchase_price; ?></td>
					<td><?php echo $record->approve_qnty*$record->purchase_price; ?></td>
				</tr>
				<?php } ?>
				
			</tbody>
		</table>
	<?php echo form_close();

	?>
    </div>
</div>
<?php  echo $this->pagination->create_links(); ?>

</div>