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



?>

<?php
$num_columns	= 8;
?>
<div class="box box-primary">
	<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
	<fieldset>
		<label>Order Information</label>
		
	

			<table class="table">
				<thead>
					<tr class="active">				
						<th>Order No</th>
						<th>Supplier Name</th>
						<th>Supplier Mobile Number</th>
						<th>Supply Date From</th>
						<th>Supply Date to</th>
					</tr>
				</thead>
				 <tbody>
				 <?php if(isset($record)){?>
			
					<tr class="success">
						<td><?php echo $record->purchase_order_no;?></td>
						<td><?php echo $record->SUPPLIER_NAME;?></td>
						<td><?php echo $record->SUPPLIER_CONTACT_PHONENO;?></td>
						<td><?php echo $record->supply_date_from;?></td>
						<td><?php echo $record->supply_date_to;?></td>					
					</tr>
				<?php }else{ ?>
					<tr>
						<td colspan="5" class="danger">No Order Available</td>
					</tr>
				<?php }?>
			
				</tbody>
			</table>

	</fieldset>

	<fieldset>
		<legend>Products Receive</legend>
		<table class="table">
			<thead>
				<tr class="active">
					<th>SL</th>
					<th>Product Name</th>
					<th>Order Quntity</th>
					<th>Already Received</th>
					<th>Receivable Qnty</th>
					<th>Order Unit Price</th>
					<th>Order Total Price</th>
					<th>Receive Qnty</th>					
					<th>Receive Free Qnty</th>
					<th>Receive Total Qnty</th>
					<th>Receive Unit Price</th>
					<th>Receive Total Price</th>
				</tr>
			</thead>
			<tbody>
				<?php $sl=0; foreach($products as $product){?>
				<tr class="info">
					<td><?php echo $sl+=1; ?></td>
					<td>
					<?php echo $record->category_name."&nbsp; >> &nbsp;".$product->product_name; ?>
					<input type="hidden" name="product_id[]" value="<?php echo $product->product_id; ?>">
					<input type="hidden" name="order_dtls_id[]" value="<?php echo $product->id; ?>">

						
					</td>
					<td class=""><?php echo $product->order_qnty; ?></td>

					<td class=""><?php echo $product->receive_qnty; ?></td>
					<td class="receivable_order_qnty"><?php echo $product->order_qnty-$product->receive_qnty; ?></td>
					<td><?php echo $product->order_unit_price; ?></td>
					<td><?php echo $product->total_order_price; ?></td>
					<td><input type="text" name="receive_qnty[]" class="form-control receive_qnty" value="<?php echo  $product->order_qnty-$product->receive_qnty; ?>" required=""></td>					
					<td><input type="text" name="receive_free_qnty[]" class="form-control receive_free_qnty" value="0" required=""></td>


					<td><input type="text" name="" class="form-control receive_total_qnty" readonly="" value="<?php echo $product->order_qnty; ?>"></td>


					<td><input type="text" name="receive_unit_price[]" class="form-control receive_unit_price" readonly="" value="<?php echo $product->order_unit_price; ?>"></td>
					<td><input type="text" name="" class="form-control receive_total_price" value="<?php echo $product->total_order_price; ?>" readonly=""></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</fieldset>

	<fieldset>
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm" value="Submit"  />
                <input type="reset" name="" class="btn btn-warning btn-sm" value="Reset" />                
            </div>
     </fieldset>

	<?php echo form_close(); ?>
</div>

<script type="text/javascript">

$(document).ready(function(){

	<?php if(isset($print_page)){?>

	var jsonObj=<?php echo json_encode($print_page) ?>;	
	print_view(jsonObj);
	<?php unset($print_page); } ?>
})

	
</script>