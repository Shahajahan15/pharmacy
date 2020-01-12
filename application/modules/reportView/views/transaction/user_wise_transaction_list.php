<?php
extract($sendData);
$num_columns	= 10;
$can_delete		= $this->auth->has_permission('Collection.Details.Delete');
$can_edit		= $this->auth->has_permission('Collection.Details.Edit');
$has_collection_records	= isset($userwise_collection_records) && is_array($userwise_collection_records) && count($userwise_collection_records);
$has_refund_records		= isset($userwise_refund_records) && is_array($userwise_refund_records) && count($userwise_refund_records);
$has_commission_records	= isset($userwise_commission_records) && is_array($userwise_commission_records) && count($userwise_commission_records);
$has_discount_records	= isset($userwise_discount_records) && is_array($userwise_discount_records) && count($userwise_discount_records);
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
						<select name="transaction_userwise" id="transaction_userwise" class="form-control" required="">
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
				
					foreach ($userwise_collection_records as $userwise_collection_records) :
					$userwise_collection_records = (object) $userwise_collection_records;
					
					$total_collection_amount = ($total_collection_amount + $userwise_collection_records->amount);
				?>
				<tr>
					
				
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($collection_sl++);
					?>
					
                    </td>
								
					<td><?php e($userwise_collection_records->id) ?></td>
					<td><?php e($userwise_collection_records->patient_id) ?></td>
					<td><?php e($userwise_collection_records->collection_date) ?></td>
					<td><?php e($userwise_collection_records->collection_source) ?></td>
					<td><?php e($userwise_collection_records->remarks) ?></td>
					<td><?php e($userwise_collection_records->amount) ?></td>
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
		<?php endif; ?>
		
		<!-- refund record -->
		<?php if ($has_refund_records) : ?>
		<table class="table table-striped">
			<thead>
				<caption><h4><?php echo lang("transaction_total_refund");?></h4></caption>
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
				
					foreach ($userwise_refund_records as $userwise_refund_records) :
					$userwise_refund_records = (object) $userwise_refund_records;
					
					$total_refund_amount = ($total_refund_amount + $userwise_refund_records->amount);
				?>
				<tr>
					
					
				
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($refund_sl++);
					?>
					
                    </td>
								
					<td><?php e($userwise_refund_records->id) ?></td>
					<td><?php e($userwise_refund_records->patient_id) ?></td>
					<td><?php e($userwise_refund_records->collection_date) ?></td>
					<td><?php e($userwise_refund_records->collection_source) ?></td>
					<td><?php e($userwise_refund_records->remarks) ?></td>
					<td><?php e($userwise_refund_records->amount) ?></td>
				</tr>
				<?php
					endforeach;
				?>
				<tr>
					
					<td colspan="4"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td style="text-decoration:overline;"><?php echo e($total_refund_amount).' '.lang("bf_currency");?></td>
				
				</tr>
				

				<tr>
				<td colspan="<?php echo $num_columns;?> ">
                </td>
				</tr>
			</tbody>
		</table>
		<?php endif; ?>
		<!-- commission record -->
		<?php if ($has_commission_records) : ?>
		<table class="table table-striped">
			<thead>
				<caption><h4><?php echo lang("transaction_total_commission");?></h4></caption>
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
				
					foreach ($userwise_commission_records as $userwise_commission_records) :
					$userwise_commission_records = (object) $userwise_commission_records;
					
					$total_commission_amount = ($total_commission_amount + $userwise_commission_records->amount);
				?>
				<tr>
					
					
				
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($commission_sl++);
					?>
					
                    </td>
								
					<td><?php e($userwise_commission_records->id) ?></td>
					<td><?php e($userwise_commission_records->patient_id) ?></td>
					<td><?php e($userwise_commission_records->collection_date) ?></td>
					<td><?php e($userwise_commission_records->collection_source) ?></td>
					<td><?php e($userwise_commission_records->remarks) ?></td>
					<td><?php e($userwise_commission_records->amount) ?></td>
				</tr>
				<?php
					endforeach;
				?>
				<tr>
					
					<td colspan="4"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td style="text-decoration:overline;"><?php echo e($total_commission_amount).' '.lang("bf_currency");?></td>
				
				</tr>
			</tbody>
		</table>
		
		<?php endif; ?>
		
		<!-- discount record -->
		<?php if ($has_discount_records) : ?>
		
		<table class="table table-striped">
			<thead>
				<caption><h4><?php echo lang("transaction_total_discount");?></h4></caption>
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
				
				
				
					foreach ($userwise_discount_records as $userwise_discount_records) :
					$userwise_discount_records = (object) $userwise_discount_records;
					
					$total_discount_amount = ($total_discount_amount + $userwise_discount_records->amount);
				?>
				<tr>
					
					
				
					<td>
                    <?php 
					//echo anchor(SITE_AREA.'/diagnosis/pathology/diagnosis_print/'.$record->id, '<span class="glyphicon glyphicon-search"></span>' . $record->id);
					e($discount_sl++);
					?>
					
                    </td>
								
					<td><?php e($userwise_discount_records->id) ?></td>
					<td><?php e($userwise_discount_records->patient_id) ?></td>
					<td><?php e($userwise_discount_records->collection_date) ?></td>
					<td><?php e($userwise_discount_records->collection_source) ?></td>
					<td><?php e($userwise_discount_records->remarks) ?></td>
					<td><?php e($userwise_discount_records->amount) ?></td>
				</tr>
				<?php
					endforeach;
				?>
				<tr>
					
					<td colspan="4"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					<td></td>
					<td></td>
					<td style="text-decoration:overline;"><?php echo e($total_discount_amount).' '.lang("bf_currency");?></td>
				
				</tr>
			</tbody>
		</table>
		
		<?php endif; ?>
		
		<table class="table table-striped">
			<thead>
				<caption><h4><?php echo lang("transaction_balance");?></h4></caption>
				
			</thead>
			
			<tbody>
				
				<tr>
					<?php
						$total_outgoing_balance = 0;
						$net_balance			= 0;
						$total_outgoing_balance = ( $total_discount_amount + $total_commission_amount + $total_refund_amount );
						$net_balance			= ( $total_collection_amount - $total_outgoing_balance );
					?>
					
					
					<td><?php echo lang("total_ingoing_balance"); ?></td>
					<td><?php echo ($total_collection_amount).' '.lang("bf_currency"); ?></td>
				</tr>
				<tr>
					<td><?php echo lang("total_outgoing_balance"); ?></td>
					<td><?php echo ($total_outgoing_balance).' '.lang("bf_currency"); ?></td>
				</tr>
				
				<tr>				
					<td><strong><?php echo lang("net_balance");?></strong></td>
					<td style="text-decoration:overline;"><?php echo e($net_balance).' '.lang("bf_currency");?></td>
				
				</tr>
				
				
			</tbody>
		</table>
	<?php
	else :
	
	?>
	<table class="table table-striped">
			<thead>
				<caption><h4><?php echo lang("todays_userwise_transaction");?></h4></caption>
				<tr>
					
					<th width="3%"><?php echo lang("transaction_collection_serial");?></th>
					<th width="18%"><?php echo lang("transaction_username");?></th>
					<th width="15%"><?php echo lang("transaction_total_collection");?></th>
					<th width="15%"><?php echo lang("transaction_total_refund");?></th>
					<th width="19%"><?php echo lang("transaction_total_commission");?></th>
					<th width="15%"><?php echo lang("transaction_total_discount");?></th>
					<th width="15%"><?php echo lang("transaction_nit_amount");?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				
					$total_amount 		= 0;
					$total_nit_amount 	= 0;
					foreach ($userList as $row) :
					$row = (object) $row;
					
					$total_nit_amount = ( $total_collection[$row->collection_by] - ($total_refund[$row->collection_by] + $total_commission[$row->collection_by] + $total_discount[$row->collection_by]));
					$total_amount	  = ($total_amount + $total_nit_amount);
				?>
				<tr>
					
					
				
					<td>
                    <?php 
					e($sl++);
					?>
					
                    </td>
								
					<td><?php e($row->first_name) ?></td>
					<td><?php e($total_collection[$row->collection_by]); ?></td>
					<td><?php e($total_refund[$row->collection_by]); ?></td>
					<td><?php e($total_commission[$row->collection_by]); ?></td>
					<td><?php e($total_discount[$row->collection_by]); ?></td>
					<td><?php e($total_nit_amount) ?></td>
				</tr>
				<?php
					endforeach;
				?>
				<tr>
					
					<td colspan="6"><strong><?php echo lang("transaction_collection_total_amount");?></strong></td>
					
					<td style="text-decoration:overline;"><?php echo e($total_amount).'  '.lang("bf_currency");?></td>
				
				</tr>
			</tbody>
		</table>
	<?php
	endif;
	echo form_close(); 
	//echo $this->pagination->create_links();
	?>
</div>