<?php
extract($sendData);

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

$num_columns	= 8;
$can_delete		= $this->auth->has_permission('HRM.Employee.Transfer.Delete');
$can_edit		= $this->auth->has_permission('HRM.Employee.Transfer.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
	
	<!-- Start Search -->
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  <div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
			  <div class="panel-title">
				    <span class="glyphicon glyphicon-plus"></span>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Search Panel
				</a>
			  </div>
              
           
			</div>
			<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
			  <div class="panel-body">					
				  <div class="row">		  
				  
				  
				  <div class="col-md-2">					
					<input type="text" class="form-control" name="TRANSFER_LETTER_NO" id="TRANSFER_LETTER_NO" value="<?php echo set_value('TRANSFER_LETTER_NO', isset($src_emp['TRANSFER_LETTER_NO ']) ? $src_emp['TRANSFER_LETTER_NO '] : '');?>" placeholder="<?php echo lang("TRANSFER_LETTER_NO");?>" title="<?php echo lang("TRANSFER_LETTER_NO");?>">				
				  </div>
				  
				  <div class="col-md-2">
					<input type="text" class="form-control" name="empid" id="empid" value="<?php echo set_value('empid', isset($src_emp['empid_or_name']) ? $src_emp['empid_or_name'] : '');?>" placeholder="<?php e(lang('empid'));?>" title="<?php e(lang('empid'));?>">
				  </div>		  
				  	
				  
				  <div class="col-md-2">
					<input type="text" id='transfer_date_start' name='transfer_date_start' class="form-control datepickerCommon" value="<?php echo set_value('transfer_date_start', isset($src_admission['admission_date']) ? $src_admission['admission_date'] : '');?>" placeholder="<?php e(lang('transfer_date'));?>" title="<?php e(lang('transfer_date'));?>"/>
									
				  </div>
				  
				   <div class="col-md-1">					
						To			
				  </div>
				  
				
				   <div class="col-md-2">
					<input type="text" id='transfer_date_end' name='transfer_date_end' class="form-control datepickerCommon" value="<?php echo set_value('transfer_date_start', isset($src_admission['admission_date']) ? $src_admission['admission_date'] : '');?>" placeholder="<?php e(lang('transfer_date'));?>" title="<?php e(lang('transfer_date'));?>"/>
									
				  </div>
				  
				  <div class="col-md-1">	
				  <button type="submit" name="search" id="search" class="btn btn-default pull-right"><span class="glyphicon glyphicon-search"> </span></button>
				  </div>
				  
				</div>
				
			  </div>
			</div>
		  </div>		  
		</div>
		<!-- End Search -->
	
		<table class="table table-striped">
			<thead>
				<tr>					
					<?php if ($can_delete && $has_records) : ?>
					<th width="1%" class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>                 
					<th width="5%"><?php echo lang('transfer_id'); ?></th>
					<th width="25%"><?php echo lang('EMP_NAME'); ?></th>	
					<th width="5%"><?php echo lang('TRANSFER_LETTER_NO'); ?></th>
					<th width="10%"><?php echo lang('TRANSFER_DESIGNATION'); ?></th>
					<th width="20%"><?php echo lang('TRANSFER_BRANCH_ID'); ?></th>
					<th width="10%"><?php echo lang('REASON_FOR_TRANSFER'); ?></th>
					<th width="10%"><?php echo lang('JOINNING_DATE_FROM'); ?></th>
					<th width="10%"><?php echo lang('JOINNING_DATE_TO'); ?></th>
							
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
					$record = (object) $record;
					
				?>

				<tr>
					<?php if ($can_delete) : ?>
					<td class="column-check">
						<input type="checkbox" name="checked[]" value="<?php echo $record->EMP_TRANSFER_ID; ?>" />
					</td>
						<?php endif;?>
						
					<?php if ($can_edit) : ?>
					<td>
						<?php echo anchor(SITE_AREA . '/employee_transfer/hrm/transfer_edit/' .$record->EMP_TRANSFER_ID, '<span class="glyphicon glyphicon-pencil"></span>' .$record->EMP_TRANSFER_ID); ?>
					</td>
					<?php else : ?>
					<?php endif; ?> 
					<td><?php e($record->EMP_NAME); ?></td>
					<td><?php e($record->TRANSFER_LETTER_NO);?></td>
					<td><?php e($record->DESIGNATION_NAME); ?></td>	
					<td><?php e($record->BRANCH_NAME); ?></td>
					<td><?php e($transferReason[$record->TRANSFER_REASON]); ?></td>	
					<td><?php e($record->JOINNING_DATE_FROM); ?></td>	
					<td><?php e($record->JOINNING_DATE_TO); ?></td>	
					
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
	<?php echo form_close();
	echo $this->pagination->create_links();
	?>
    </div>
</div>