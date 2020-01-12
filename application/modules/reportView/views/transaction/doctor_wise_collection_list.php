<?php
extract($sendData);
$num_columns	= 10;
$can_delete		= $this->auth->has_permission('Collection.Details.Delete');
$can_edit		= $this->auth->has_permission('Collection.Details.Edit');
$has_doctorwise_records	= isset($doctor) && is_array($doctor) && count($doctor);
//$src_name 			= (array) $src_name;
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
					<input type="text" class="form-control" name="collection_id" id="collection_id" value="<?php echo set_value('collection_id', isset($src_name['collection_id']) ? $src_name['collection_id'] : '');?>" placeholder="<?php echo lang("mr_no");?>" title="<?php echo lang("mr_no");?>">				
				  </div>
				  
				  <div class="col-md-2">					
					<input type="text" class="form-control" name="diagnosis_patient_id" id="diagnosis_patient_id" value="<?php echo set_value('diagnosis_patient_id', isset($src_name['patient_id']) ? $src_name['patient_id'] : '');?>" placeholder="<?php echo lang("transaction_patient_id");?>" title="<?php echo lang("transaction_patient_id");?>">				
				  </div>
				  <!--
				  <div class="col-md-2">
					<input type="text" class="form-control" name="diagnosis_patient_name" id="diagnosis_patient_name" value="<?php// echo set_value('diagnosis_patient_name', isset($src_name['patient_name']) ? $src_name['patient_name'] : '');?>" placeholder="<?php e(lang('path_diagnosis_patient_name'));?>" title="<?php e(lang('path_diagnosis_patient_name'));?>">
				  </div>-->
				 
				  <div class='col-md-2'>                          
						<select name="transaction_source_name" id="transaction_source_name" class="form-control" required="">
							<?php if(empty($src_name['source_name'])){?>
								<option selected value=""><?php echo lang('transaction_source_name')?></option>
							<?php }
								foreach($source_name as $key => $val){	                              
								echo "<option value='".$key."'";									
								if(isset($src_name['source_name']) ){									
								if($src_name['source_name']==$key){ echo "selected";}									
								}								
								echo ">".$val."</option>";
								}
							?>
						</select>
						<span class='help-inline'><?php echo form_error('source_name'); ?></span>
				  </div>
				  
				   <div class="col-md-2">
					<input type="text" class="form-control datepickerCommon" name="collection_date_start" id="collection_date_start" value="<?php echo set_value('collection_date', isset($src_name['collection_date_start']) ? $src_name['collection_date_start'] : '');?>" placeholder="<?php e(lang('bf_date'));?>" title="<?php e(lang('bf_date'));?>">
				  </div>
				  <div class="col-md-1">
					<?php echo lang("date_to"); ?>
				  </div>
				  				  
				  <div class="col-md-2">
					<input type="text" class="form-control datepickerCommon" name="collection_date_end" id="collection_date_end" value="<?php echo set_value('collection_date', isset($src_name['collection_date_end']) ? $src_name['collection_date_end'] : '');?>" placeholder="<?php e(lang('bf_date'));?>" title="<?php e(lang('bf_date'));?>">
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
				<caption><h4><?php echo lang("transaction_total_doctorwise");?></h4></caption>
				<tr class ='active strong'> 
					
					<th width="4%"><?php echo lang("doctor_collection_serial");?></th>
					<th width="30%"><?php echo lang("doctor_name");?></th>
					<th width="14%"><?php echo lang("doctor_type");?></th>
					<th width="14%"><?php echo lang("doctor_male");?></th>
					<th width="14%"><?php echo lang("doctor_female");?></th>
					<th width="14%"><?php echo lang("doctor_male_pluse_female");?></th>
					<th width="10%"><?php echo lang("doctor_collection_amount");?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				
				$slNo = 1; 
				$total_doctorwise_amount=0;
				if ($has_doctorwise_records) :
					foreach ($doctor as $doctorList) :
					$doctorwise_records = (object) $doctorList;
					
					$total_doctorwise_amount += ($doctorwise_records->consultantFee + $doctorwise_records->ticketFee);
				?>
				

				<tr>
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($slNo++);
					?>
                    </td>
					
					<td><?php e($doctorwise_records->first_name) ?></td>
					<td><?php e($doctorwise_records->ticketFee) ?></td>
					<td><?php e($doctorwise_records->consultantFee) ?></td>
					<td><?php //e($doctorwise_records->collection_source) ?></td>
					<td><?php //e($doctorwise_records->remarks) ?></td>
					<td><?php //e($doctorwise_records->amount) ?></td>
				</tr>

				
				<?php
					endforeach;
				?>
				<tr>
					<td colspan="4"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					<td></td>
					<td></td>
					<td><?php echo e($total_doctorwise_amount).' '.lang("bf_currency");?></td>
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