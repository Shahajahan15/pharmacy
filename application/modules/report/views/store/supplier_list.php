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
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
                            <tr>			
                                <th width="20%">Supplier Name</th>
                                <th width="20%">Supplier Persion</th>
                                <th width="15%">Supplier Mobile</th>
                                <th width="18%">Supplier Email</th>			
                                <th width="8%">Status</th>
                            </tr>
			</thead>
			<tbody>
				<?php
                               
				if ($has_records) :
					foreach ($records as $record) : 
				?>
				<tr>
					<td><a style="cursor:pointer" title="Select Supplier" id="<?php echo $record->SUPPLIER_ID; ?>" class="supplier-from-list"><?php echo $record->SUPPLIER_NAME;?></a></td>
					<td><?php e($record->SUPPLIER_CONTACT_PERSON); ?></td>
                    <td><?php e('0'.$record->SUPPLIER_CONTACT_PHONENO); ?><br>
                        <?php e('0'.$record->SUPPLIER_CONTACT_PHONENO_1);?> 
                    </td>
					<td><?php e($record->SUPPLIER_CONTACT_EMAIL); ?></td>
					<td><?php if($record->SUPPLIER_STATUS==1){ e("Active");}else{ e("In Active"); } ?>
					</td>
				</tr>
				<?php
					endforeach;
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
<?php echo $this->pagination->create_links();?>