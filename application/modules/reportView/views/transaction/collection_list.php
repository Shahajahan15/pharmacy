<?php
extract($sendData);
$num_columns	= 10;
$can_delete		= $this->auth->has_permission('Collection.Details.Delete');
$can_edit		= $this->auth->has_permission('Collection.Details.Edit');
$has_collection_records	= isset($collection_records) && is_array($collection_records) && count($collection_records);
$src_diagnosis 			= (array) $src_diagnosis;
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
					<input type="text" class="form-control" name="collection_id" id="collection_id" value="<?php echo set_value('collection_id', isset($src_diagnosis['collection_id']) ? $src_diagnosis['collection_id'] : '');?>" placeholder="<?php echo lang("mr_no");?>" title="<?php echo lang("mr_no");?>">				
				  </div>
				  
				  <div class="col-md-2">					
					<input type="text" class="form-control" name="diagnosis_patient_id" id="diagnosis_patient_id" value="<?php echo set_value('diagnosis_patient_id', isset($src_diagnosis['patient_id']) ? $src_diagnosis['patient_id'] : '');?>" placeholder="<?php echo lang("transaction_patient_id");?>" title="<?php echo lang("transaction_patient_id");?>">				
				  </div>
				 
				  <div class='col-md-2'>                          
						<select name="transaction_source_name" id="transaction_source_name" class="form-control" >
							<?php if(empty($src_diagnosis['source_name'])){?>
								<option selected value=""><?php echo lang('transaction_source_name')?></option>
							<?php }
								foreach($source_name as $key => $val){	                              
								echo "<option value='".$key."'";									
								if(isset($src_diagnosis['source_name']) ){									
								if($src_diagnosis['source_name']==$key){ echo "selected";}									
								}								
								echo ">".$val."</option>";
								}
							?>
						</select>
						<span class='help-inline'><?php echo form_error('source_name'); ?></span>
				  </div>
				  				  
				  <div class="col-md-2">
					<input type="text" class="form-control datepickerCommon" name="collection_date" id="collection_date" value="<?php echo set_value('collection_date', isset($src_diagnosis['collection_date']) ? $src_diagnosis['collection_date'] : '');?>" placeholder="<?php e(lang('bf_date'));?>" title="<?php e(lang('bf_date'));?>">
				  </div>
				  <div class="col-md-1">
					<?php echo lang("date_to"); ?>
				  </div>
				  <div class="col-md-2">
					<input type="text" class="form-control datepickerCommon" name="collection_date_to" id="collection_date_to" value="<?php echo set_value('collection_date_to', isset($src_diagnosis['collection_date_to']) ? $src_diagnosis['collection_date_to'] : '');?>" placeholder="<?php e(lang('bf_date'));?>" title="<?php e(lang('bf_date'));?>">
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
				<caption><h4><?php echo lang("transaction_total_collection");?></h4></caption>
				<tr>
					
					<th width="3%"><?php echo lang("transaction_collection_serial");?></th>
					<th width="7%"><?php echo lang("mr_no");?></th>
					<th width="7%"><?php echo lang("transaction_patient_id");?></th>
					<th width="12%"><?php echo lang("bf_date");?></th>
					<th width="13%"><?php echo lang("transaction_collection_type");?></th>
					<th width="12%"><?php echo lang("transaction_collection_remark");?></th>
					<th width="13%"><?php echo lang("transaction_collection_amount");?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				$collection_sl = 1; $total_collection_amount=0;
				
								
				
				if ($has_collection_records) :
					foreach ($collection_records as $collection_records) :
					$collection_records = (object) $collection_records;
					
					$total_collection_amount = ($total_collection_amount + $collection_records->amount);
				?>
				<tr>
					
					
				
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($collection_sl++);
					?>
					
                    </td>
							
					<td><?php e($collection_records->id) ?></td>
					<td><?php e($collection_records->patient_id) ?></td>
					<td><?php e($collection_records->collection_date) ?></td>
					<td><?php e($collection_records->collection_source) ?></td>
					<td><?php e($collection_records->remarks) ?></td>
					<td><?php e($collection_records->amount) ?></td>
				</tr>
				<?php
					endforeach;
				?>
				<tr>
					
					<td colspan="4"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					<td></td>
					<td></td>
					<td><?php echo e($total_collection_amount).' '.lang("bf_currency");?></td>
				
				</tr>
				<?php
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