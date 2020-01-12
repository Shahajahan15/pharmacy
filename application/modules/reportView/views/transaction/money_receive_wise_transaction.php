<?php
extract($sendData);
$num_columns	= 10;
$can_delete		= $this->auth->has_permission('Collection.Details.Delete');
$can_edit		= $this->auth->has_permission('Collection.Details.Edit');

$has_discount_records	= isset($discount_records) && is_array($discount_records) && count($discount_records);
$src_diagnosis 			= (array) $src_diagnosis;
$discount_sl 			= 1; 
$total_discount_amount	= 0;
$collection_sl 			= 1; 
$total_collection_amount= 0;
$refund_sl 				= 1; 
$total_refund_amount	= 0;
$commission_sl 			= 1; 
$sl 					= 1; 
$total_commission_amount= 0;

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
				  
					   <div class='col-md-3'>                          
							<select name="transaction_source_name" id="transaction_source_name" class="form-control" >
								<?php if(empty($src_diagnosis['source_name'])){?>
									<option selected value=""><?php echo lang('transaction_source_name')?></option>
								<?php }
									?>
									<option selected value=""><?php echo lang('transaction_source_name')?></option>
									<?php
								
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
							<select name="transaction_userwise" id="transaction_userwise" class="form-control" >
								<?php if(empty($userList)){?>
									<option selected value=""><?php echo lang('transaction_userwise')?></option>
								<?php 
									}
								?>
								<option selected value=""><?php echo lang('transaction_userwise')?></option>
								<?php
									foreach($userList as $val){	                              
									echo "<option value='".$val->collection_by."'";									
									
									echo ">".$val->first_name."</option>";
									}
								?>
							</select>
							<span class='help-inline'><?php //echo form_error('username'); ?></span>
					  </div>				  				  
					  <div class="col-md-2">
						<input type="text" class="form-control datepickerCommon" name="collection_date" id="collection_date" value="<?php echo set_value('collection_date', isset($src_diagnosis['collection_date']) ? $src_diagnosis['collection_date'] : '');?>"  placeholder="<?php e(lang('bf_date'));?>" title="<?php e(lang('bf_date'));?>">
					  </div>
					  <div class="col-md-1">	
						<?php echo lang("date_to");?>
					  </div>
					  <div class="col-md-2">
						<input type="text" class="form-control datepickerCommon" name="collection_date_to" id="collection_date_to" value="<?php echo set_value('collection_date_to', isset($src_diagnosis['collection_date_to']) ? $src_diagnosis['collection_date_to'] : '');?>"  placeholder="<?php e(lang('bf_date'));?>" title="<?php e(lang('bf_date'));?>">
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
		<?php 
		if($flag > 0) :
		
		if ($has_collection_records) : ?>
		<table class="table table-striped">
			<thead>
				<caption><h4><?php echo lang("transaction_total_collection");?></h4></caption>
				<tr>
					
					<th width="7%"><?php echo lang("mr_no");?></th>
					<th width="7%"><?php echo lang("mr_date");?></th>
					<th width="10%"><?php echo lang("transaction_patient_id");?></th>
					<th width="10%"><?php echo lang("mr_type");?></th>
					<th width="20%"><?php echo lang("mr_source");?></th>
					<th width="8%"><?php echo lang("mr_bill_amount");?></th>
					<th width="8%"><?php echo lang("mr_pay_amount");?></th>
					<th width="8%"><?php echo lang("mr_discount_amount");?></th>
					<th width="8%"><?php echo lang("mr_refund_amount");?></th>
					<th width="14%"><?php echo lang("mr_collector");?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
					foreach($mr_no as $value) :
						$value = (object)$value;
						
					
				?>
				<tr>
					
				
					<td>
                   			
                    </td>
					<td><?php e($value->id) ?></td>
					
				</tr>
				<?php
					endforeach;
				?>
				<tr>			
					<td colspan="4"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					<td></td>
					<td></td>
					<td style="text-decoration:overline;"><?php echo e($total_collection_amount).' '.lang("bf_currency");?></td>
				</tr>
				<tr>
				<td colspan="<?php echo $num_columns;?> ">				
                </td>
				</tr>				
			</tbody>
		</table>
		<?php
			else:
		?>
		<div>
		<?php echo lang("no_collection_records_found")?></br>
		</div>
		<?php endif; ?>
		
		
	<?php
	else :
	
	?>
	<table class="table table-striped">
			<thead>
				<caption><h4><?php echo lang("todays_monery_receive_transaction");?></h4></caption>
				<tr>					
					<th width="7%"><?php echo lang("mr_no");?></th>
					<th width="7%"><?php echo lang("mr_date");?></th>
					<th width="10%"><?php echo lang("transaction_patient_id");?></th>
					<th width="10%"><?php echo lang("mr_type");?></th>
					<th width="20%"><?php echo lang("mr_source");?></th>
					<th width="8%"><?php echo lang("mr_bill_amount");?></th>
					<th width="8%"><?php echo lang("mr_pay_amount");?></th>
					<th width="8%"><?php echo lang("mr_discount_amount");?></th>
					<th width="8%"><?php echo lang("mr_refund_amount");?></th>
					<th width="14%"><?php echo lang("mr_collector");?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
					foreach($mr_no as $row) :
						$row = (object)$row;
						
						$collection = 0;
						$refund 	= 0;
						$commission = 0;
						$discount   = 0;
						
					
						
					if($row->transaction_type == 1){
						
						$collection = $row->amount;
					}elseif ($row->transaction_type == 2){
										
						$refund = $row->amount;
					}
						
					//collection_source
				?>
				<tr>
				
						<td><?php e($row->id) ?></td>	
						<td><?php e($row->collection_date) ?></td>
						<td><?php e($row->patient_id) ?></td>									
						<td>
							<?php 							
								switch ($row->transaction_type) 
								{
									case 1:
										echo "Collection";
										break;
									case 2:
										echo "Refund";
										break;
									case 3:
										echo "Commission";
										break;
									case 4:
										echo "Discount";
										break;
									case 5:
										echo "Payment";
										break;
									default:
										echo "Other";
										break;
								}							
							?>
						</td>		
						<td>
							<?php 
							
								switch ($row->source_name) 
								{
									case 1:
										echo "Ambulance";
										break;
									case 2:
										echo "Ticket";
										break;
									case 3:
										echo "Diagnosis";
										break;
									case 4:
										echo "Admission";
										break;
									case 5:
										echo "Discharge";
										break;
									default:
										echo "Other";
										break;
								}
							?>
						</td>		
						<td><?php e($collection) ?></td>
						<td><?php e($row->amount); ?></td>		
						<td><?php e($discount); ?></td>		
						<td><?php e($refund); ?></td>		
						<td><?php e($row->collection_by ); ?></td>		
						
					
				</tr>
				<?php
					endforeach;
				?>
				<tr>
					
					<td colspan="9"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					
					<td style="text-decoration:overline;"><?php //echo e($total_amount).'  '.lang("bf_currency");?></td>
				
				</tr>
			</tbody>
		</table>
	<?php
	endif;
	echo form_close(); 
	//echo $this->pagination->create_links();
	?>
</div>