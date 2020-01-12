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
				<th>SL</th>
				<th>Order No</th>
				<th>Supplier Name</th>
				<th>Supplier Mobile Number</th>
				<th>Supply Date From</th>
				<th>Supply Date to</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php $sl=0; foreach($records as $record){?>
				<tr>
					<td><?php echo $sl+=1;?></td>
					<td><?php echo $record->purchase_order_no;?></td>
					<td><?php echo $record->SUPPLIER_NAME;?></td>
					<td><?php echo $record->SUPPLIER_CONTACT_PHONENO;?></td>
					<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->supply_date_from)))?></td>
					<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->supply_date_to)))?></td>
					<td>
						<a class="btn btn-xs btn-primary" href="<?php echo site_url().'/admin/purchase_order_receive/store/receive_order/'.$record->id; ?>" target="_blank" >Order Receive</a>
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