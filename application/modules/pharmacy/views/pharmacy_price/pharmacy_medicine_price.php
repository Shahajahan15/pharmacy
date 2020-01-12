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
$num_columns	= 8;
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div id="search_result">
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>SL</th>
                    <th>Company Name</th> 
                    <th>Category Name</th> 
                    <th>Medicine Name</th> 
                    <th>Price</th>
                    <th>Update price</th>
                    <th>Last Update</th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<input type="submit" name="price_update" class="btn btn-danger" value="Update Price" onclick="return confirm('<?php e("Are You Sure To Update Purchase Price"); ?>')" />
					</td>
				</tr>
				
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					 foreach ($records as $record) :
				?>
				<tr>
					<td><?php echo $sl+=1;?></td>
                	<td><?php e($record->company_name); ?></td>
                	<td><?php e($record->category_name); ?></td>
                	<td><?php e($record->product_name); ?></td>
                	<td><?php e($record->sale_price); ?></td>
                	<td><input type="text" name="sale_price[]" class="fomr-control">
                		<input type="hidden" name="product_id[]" class="fomr-control" value="<?php echo $record->id;?>">
                	</td>
                	<td><?php if($record->updated_date==null){echo "";}else{echo date('d/m/Y h:i:sa',strtotime($record->updated_date));}?></td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_no_records_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
</div>

<?php
echo $this->pagination->create_links();
?>
</div>