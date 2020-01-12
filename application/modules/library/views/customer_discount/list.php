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

if (isset($records))
{
	$records = (array) $records;
}
$id = isset($records['id']) ? $records['id'] : '';

?>

<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('Lib.Branch.Delete');
$can_edit		= $this->auth->has_permission('Lib.Branch.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th width="5%" class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>					

					<th><?php echo lang('library_discount_parcent'); ?></th>					
					<th><?php echo lang('library_customer_type'); ?></th>					
					<th><?php echo lang('library_discount_for'); ?></th>
					<th><?php echo lang('date_start');?></th>
					<th><?php echo lang('date_start');?></th>
					<th><?php echo lang('bf_status'); ?></th>					

				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<?php echo lang('bf_with_selected'); ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('bf_msg_delete_confirm'))); ?>')" />
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $record) :
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record['id']; ?>" /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/customer_discount_setup/library/edit/' . $record['id'], '<span class="glyphicon glyphicon-pencil"></span>' .  $record['discount_parcent']); ?></td>
				<?php else : ?>
					
				<?php endif; ?>				
								<td>
								<?php 
								if($record['customer_type']==1){ e("Normal");}
								elseif($record['customer_type']==2){e("Employee");} 
								elseif($record['customer_type']==3){e("Doctor");} 
								elseif($record['customer_type']==4){e("Overall");} 

								?>
									
								</td>
                               <td>
								<?php 
								if($record['discount_for']==1){ e("Canteen");}
								elseif($record['discount_for']==2){e("Pharmacy");}
								elseif($record['discount_for']==3){e("Diagnosis");}  

								?>
									
								</td>
								<td><?php echo $record['start_date'];?></td>
								<td><?php echo $record['start_date'];?></td>

					

					<td>
					<?php 
					if($record['status']==1){ e("Active");
					}
					else{e("In Active");} ?>
						
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