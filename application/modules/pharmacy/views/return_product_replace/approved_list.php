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

<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>

	<table class="table">
		<thead>
			<tr>
			
				<th>Supplier Name</th>
				<th>Supplier Code</th>
				<th>Received Date</th>
				<th>Product Name</th>
				<th>Product Received Qnty</th>
				<th>Return Confirm Qnty</th>
				<th>Already Return Qnty</th>
				<th>Return Pending Qnty</th>

				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php $sl=0; foreach($records as $record){?>
				<tr>
					
					<td class="supplier_name"><?php echo $record->supplier_name;?></td>
					<td><?php echo $record->supplier_code;?></td>
					 <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->received_date)))?></td>
                            <td>
					<td class="product_name"><?php echo $record->product_name;?></td>
					<td><?php echo $record->received_qnty;?></td>
					<td class=""><?php echo $record->return_approved_qnty;?></td>
					<td class=""><?php echo isset($record->replace_qnty)?$record->replace_qnty:0;?></td>
					<td class=""><?php echo isset($record->replace_qnty)?$record->return_approved_qnty-$record->replace_qnty:$record->return_approved_qnty;?></td>
					<td>
						<button type="button" class="btn btn-xs btn-primary confirm_return_restore" href="<?php echo site_url().'/admin/product_return_replace/pharmacy/replace_form/'.$record->id; ?>" >Restore</button>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>	
	
	
	<?php echo form_close();

	?>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){

	<?php if(isset($print_page)){?>

	var jsonObj=<?php echo json_encode($print_page) ?>;
	print_view(jsonObj);
	<?php unset($print_page); } ?>
})

	
</script>