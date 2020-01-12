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
$can_delete		= $this->auth->has_permission('pharmacy.Product_purchase.Delete');
$can_edit		= $this->auth->has_permission('pharmacy.Product_purchase.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>	
		<table class="table table-striped">
			<thead>
				<tr>									
					<th width="15%"><?php echo lang('store_purchase_product_name'); ?></th>	                               
					<th width="15%"><?php echo lang('store_purchase_indent_qty'); ?></th>
					<th width="15%"><?php echo lang('product_unit_price'); ?></th>								
					<th width="20%"><?php echo lang('product_total_price'); ?></th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php
                               
				if ($has_records) :
					$sub_total = 0;
					foreach ($records as $record) : 
					$record = (object) $record;
					
				?>
				<tr>				
				
                    <td><?php e($record->STORE_PRODUCT_NAME); ?></td>
					<td><?php e($record->PURCHASE_ORDER_INDENT_QTY); ?></td>	
					<td><?php e($record->PURCHASE_ORDER_UNIT_PRICE); ?></td>
					<td><?php e($record->PURCHASE_ORDER_TOTAL_PRICE); ?></td>
					
				</tr>
				<?php
					
					$sub_total+=$record->PURCHASE_ORDER_TOTAL_PRICE;
				
					endforeach;
					
					?>
					<tr>
					<td colspan="3" align="right">Total Taka : </td>	 
					<td ><?php echo $sub_total; ?></td>	 
				</tr>
				
				<?php	
				else:
				?>
				
				<tr>
					<td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
</div>