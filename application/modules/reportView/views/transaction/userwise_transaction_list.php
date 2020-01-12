<?php
extract($sendData);
$num_columns	= 10;
$can_delete		= $this->auth->has_permission('Collection.Details.Delete');
$can_edit		= $this->auth->has_permission('Collection.Details.Edit');
$has_userwise_records	= isset($userwise_records) && is_array($userwise_records) && count($userwise_records);
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
						<select name="transaction_source_name" id="transaction_source_name" class="form-control" required="">
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
				  <div class='col-md-3'>                          
						<select name="transaction_userwise" id="transaction_userwise" class="form-control" required="">
							<?php if(empty($username)){?>
								<option selected value=""><?php echo lang('transaction_userwise')?></option>
							<?php }
								foreach($username as $val){	                              
								echo "<option value='".$val->collection_by."'";									
								
								echo ">".$val->first_name.'-'.$val->role_name."</option>";
								}
							?>
						</select>
						<span class='help-inline'><?php //echo form_error('username'); ?></span>
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
				<caption><h4><?php echo lang("transaction_total_userwise");?></h4></caption>
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
				
				$discount_sl = 1; $total_userwise_amount=0;
				if ($has_userwise_records) :
					foreach ($userwise_records as $userwise_records) :
					$userwise_records = (object) $userwise_records;
					
					$total_userwise_amount = ($total_userwise_amount + $userwise_records->amount);
				?>
				<tr>
					
					
				
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($discount_sl++);
					?>
					
                    </td>
								
					<td><?php e($userwise_records->id) ?></td>
					<td><?php e($userwise_records->patient_id) ?></td>
					<td><?php e($userwise_records->collection_date) ?></td>
					<td><?php e($userwise_records->collection_source) ?></td>
					<td><?php e($userwise_records->remarks) ?></td>
					<td><?php e($userwise_records->amount) ?></td>
				</tr>
				<?php
					endforeach;
				?>
				<tr>
					
					<td colspan="4"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					<td></td>
					<td></td>
					<td><?php echo e($total_userwise_amount).' '.lang("bf_currency");?></td>
				
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