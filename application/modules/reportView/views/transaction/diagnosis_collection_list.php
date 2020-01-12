<?php
extract($sendData);
$num_columns	= 10;
$can_delete		= $this->auth->has_permission('Collection.Details.Delete');
$can_edit		= $this->auth->has_permission('Collection.Details.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
$src_diagnosis = (array) $src_diagnosis;
?>


<div class="admin-box">
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
					<input type="text" class="form-control" name="collection_id" id="collection_id" value="<?php echo set_value('collection_id', isset($src_diagnosis['collection_id']) ? $src_diagnosis['collection_id'] : '');?>" placeholder="<?php echo lang("bf_id");?>" title="<?php echo lang("bf_id");?>">				
				  </div>
				  
				  <div class="col-md-2">					
					<input type="text" class="form-control" name="diagnosis_patient_id" id="diagnosis_patient_id" value="<?php echo set_value('diagnosis_patient_id', isset($src_diagnosis['patient_id']) ? $src_diagnosis['patient_id'] : '');?>" placeholder="<?php echo lang("path_diagnosis_patient_id");?>" title="<?php echo lang("path_diagnosis_patient_id");?>">				
				  </div>
				  
				  <div class="col-md-3">
					<input type="text" class="form-control" name="diagnosis_patient_name" id="diagnosis_patient_name" value="<?php echo set_value('diagnosis_patient_name', isset($src_diagnosis['patient_name']) ? $src_diagnosis['patient_name'] : '');?>" placeholder="<?php e(lang('path_diagnosis_patient_name'));?>" title="<?php e(lang('path_diagnosis_patient_name'));?>">
				  </div>
				  
				  <div class="col-md-2">
					<input type="text" class="form-control" name="diagnosis_contact_no" id="diagnosis_contact_no" value="<?php echo set_value('diagnosis_contact_no', isset($src_diagnosis['contact_no']) ? $src_diagnosis['contact_no'] : '');?>" placeholder="<?php e(lang('path_diagnosis_contact_no'));?>" title="<?php e(lang('path_diagnosis_contact_no'));?>">
				  </div>
				  
				  <div class="col-md-2">
					<input type="text" class="form-control datepickerCommon" name="collection_date" id="collection_date" value="<?php echo set_value('collection_date', isset($src_diagnosis['collection_date']) ? $src_diagnosis['collection_date'] : '');?>" placeholder="<?php e(lang('bf_date'));?>" title="<?php e(lang('bf_date'));?>">
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
					<th width="7%"><?php echo lang("bf_id");?></th>
					<th width="13%"><?php echo lang("path_diagnosis_id");?></th>
					<th width="12%"><?php echo lang("path_diagnosis_patient_id");?></th>
					<th width="22%"><?php echo lang("path_diagnosis_patient_name");?></th>
					<th width="12%"><?php echo lang("bf_date");?></th>
					<th width="17%"><?php echo lang("path_diagnosis_paid_by");?></th>
					<th width="13%"><?php echo lang("path_diagnosis_paid_amount");?></th>
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
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/diagnosis/pathology/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->id); ?></td>
				<?php else : ?>
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($record->id);
					?>
					
                    </td>
				<?php endif; ?>
					<td><?php e($record->source_id) ?></td>
					<td><?php e($record->patient_no) ?></td>
					<td><?php e($record->patient_name) ?></td>
					<td><?php e($record->collection_date) ?></td>
					<td><?php e($record->paid_by); ?></td>
					<td><?php e($record->amount) ?></td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
				<td colspan="<?php echo $num_columns;?> ">
				<?php echo lang("bf_msg_no_records_found")?>
                </td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php 
	echo form_close(); 
	//echo $this->pagination->create_links();
	?>
</div>