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
$can_delete		= true; //$this->auth->has_permission('Store.Product_purchase.Delete');
$can_edit		= true; //$this->auth->has_permission('Store.Product_purchase.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div id='search_result'>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>

	<table class="table">
		<thead>
			<tr>
				<th>SL</th>
				<th>Supplier Name</th>
				<th>Supplier Code</th>
				<th>Received Date</th>
				<th>Product Name</th>
				<th>Product Received Qnty</th>
				<th>Return Requ.Qnty</th>

				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($records as $record){?>
				<tr>
					<td><?php echo $sl+=1;?></td>
					<td class="supplier_name"><?php echo $record->supplier_name;?></td>
					<td><?php echo $record->supplier_code;?></td>
					<td><?php echo $record->received_date;?></td>
					<td class="product_name"><?php echo $record->product_name;?></td>
					<td><?php echo $record->received_qnty;?></td>
					<td class="return_requst_qnty"><?php echo $record->return_requst_qnty;?></td>
					<td>
						<button type="button" class="btn btn-xs btn-primary approved_return" href="<?php echo site_url().'/admin/product_return/pharmacy/return_request_approved/'.$record->id; ?>" >Approved</button>
					</td>
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
<script type="text/javascript">

$(document).ready(function(){

	<?php if(isset($print_page)){?>

	var jsonObj=<?php echo json_encode($print_page) ?>;
	print_view(jsonObj);
	<?php unset($print_page); } ?>
})

	
</script>