
<?php
if (isset($result))
{
	$details = (array) $result;	
}

?>
<script>
.addContainer {
    height:100px;
}

.addContainer h3{
    position:absolute;
    bottom:0;
    left:0;
}


</script>

<span class="row">						
	<div class="col-sm-12 col-md-12 col-lg-12">			
		<div class="col-sm-3 col-md-3 col-lg-3">							
			<!------------- Transfer Letter No -------------> 
			<div class="form-group <?php echo form_error('TRANSFER_LETTER_NO') ? 'error' : ''; ?>">
				<div id="checkLetterNo" style="color:#F00; font-size:14px;"></div>
				<label><?php echo lang('TRANSFER_LETTER_NO').lang('bf_form_label_required');?></label>
				<input type='text' name='TRANSFER_LETTER_NO[]' value="<?php if(isset($emp_transfer_details->TRANSFER_LETTER_NO)) echo $emp_transfer_details->TRANSFER_LETTER_NO; ?>"  id='TRANSFER_LETTER_NO' class="form-control" required="" />						
				<span class='help-inline'><?php echo form_error('TRANSFER_LETTER_NO'); ?></span>
			</div>	
		</div>	
			
								
			
		<div class="col-sm-3 col-md-3 col-lg-3">		
			<!-- ----------- Joining Date From--------------- -->
			<div class="<?php echo form_error('JOINNING_DATE_FROM') ? 'error' : ''; ?>">
				<div class='form-group'>
					<label><?php echo lang('JOINNING_DATE_FROM').lang('bf_form_label_required');?></label>
					<input type="text" name="JOINNING_DATE_FROM[]" value="<?php if(isset($emp_transfer_details->JOINNING_DATE_FROM)) echo $emp_transfer_details->JOINNING_DATE_FROM; ?>"  id="JOINNING_DATE_FROM" class="form-control datepickerCommon" title="<?php e(lang('JOINNING_DATE_FROM'));?>" required="" />
				</div>
			</div>	
			
		</div>
		
			
		<div class="col-sm-3 col-md-3 col-lg-3">		
			
			<!-- -----------joining date to --------------- -->
			<div class="<?php echo form_error('JOINNING_DATE_TO') ? 'error' : ''; ?>">
				<div class='form-group'>
					<label><?php echo lang('JOINNING_DATE_TO').lang('bf_form_label_required');?></label>
					<input type="text" name="JOINNING_DATE_TO[]" value="<?php if(isset($emp_transfer_details->JOINNING_DATE_TO)) echo $emp_transfer_details->JOINNING_DATE_TO; ?>" id="JOINNING_DATE_TO" class="form-control datepickerCommon" title="<?php e(lang('JOINNING_DATE_TO'));?>" required="" />
				</div>
			</div>						
		</div>
		
		
		<div class="col-sm-3 col-md-3 col-lg-3">	
			<!------------- Transfer reason -------------> 
			<div class="form-group <?php echo form_error('TRANSFER_REASON') ? 'error' : ''; ?>">
				<label><?php echo lang('REASON_FOR_TRANSFER').lang('bf_form_label_required');?></label>
				<select class="form-control" name="TRANSFER_REASON[]" id="TRANSFER_REASON" required="" >
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
							foreach($transferReason as  $key => $val)
							{									
								echo "<option value='".$key."'";
								if(isset($emp_transfer_details->TRANSFER_REASON))
								{
									if(trim($emp_transfer_details->TRANSFER_REASON)== $key){echo "selected";}
								}
								echo ">".$val."</option>";
							}
						
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('TRANSFER_REASON'); ?></span>
			</div>	
		</div>		
		
	</div>
	
	<div class="col-sm-12 col-md-12 col-lg-12">					
		<div class="col-sm-3 col-md-3 col-lg-3">				
			<!------------- Transfer to work station -------------> 
			<div class="form-group <?php echo form_error('BEFORE_BRANCH_ID') ? 'error' : ''; ?>">
				<label><?php echo lang('BEFORE_BRANCH_ID').lang('bf_form_label_required');?></label>
				<select class="form-control" name="BEFORE_BRANCH_ID[]" id="BEFORE_BRANCH_ID"  required="">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
							foreach($work_station_list as $branch)
							{								
								echo "<option value='".$branch->BRANCH_ID."'";
								
								if(isset($emp_transfer_details->BEFORE_BRANCH_ID))
								{
									if(trim($emp_transfer_details->BEFORE_BRANCH_ID)== $branch->BRANCH_ID){echo "selected";}
								}
								echo ">".$branch->BRANCH_NAME."</option>";
							}
						
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('BEFORE_BRANCH_ID'); ?></span>
			</div>
		</div>			
			
		<div class="col-sm-3 col-md-3 col-lg-3">				
			<!------------- Transfer to work station -------------> 
			<div class="form-group <?php echo form_error('TRANSFER_BRANCH_ID') ? 'error' : ''; ?>">
				<label><?php echo lang('TRANSFER_BRANCH_ID').lang('bf_form_label_required');?></label>
				<select class="form-control" name="TRANSFER_BRANCH_ID[]" id="TRANSFER_BRANCH_ID" required="" >
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
							foreach($work_station_list as $branch)
							{								
								echo "<option value='".$branch->BRANCH_ID."'";
								
								if(isset($emp_transfer_details->TRANSFER_BRANCH_ID))
								{
									if(trim($emp_transfer_details->TRANSFER_BRANCH_ID)== $branch->BRANCH_ID){echo "selected";}
								}
								echo ">".$branch->BRANCH_NAME."</option>";
							}
						
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('TRANSFER_BRANCH_ID'); ?></span>
			</div>
		</div>		

		<div class="col-sm-3 col-md-3 col-lg-3">	
				<!------------- Transfer reason -------------> 
				<div class="form-group <?php echo form_error('BEFORE_DEPARTMENT_ID') ? 'error' : ''; ?>">
					<label><?php echo lang('BEFORE_DEPARTMENT_ID').lang('bf_form_label_required');?></label>
					<select class="form-control" name="BEFORE_DEPARTMENT_ID[]" id="BEFORE_DEPARTMENT_ID" required="" >
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
							foreach($department_list as $row)
								{
								
									echo "<option value='".$row->dept_id."'";
									
									if(isset($emp_transfer_details->BEFORE_DEPARTMENT_ID))
									{
										if(trim($emp_transfer_details->BEFORE_DEPARTMENT_ID)== $row->dept_id){echo "selected";}
									}
								
									echo ">".$row->department_name."</option>";
								}
							
						?>							
					 </select>
					 <span class='help-inline'><?php echo form_error('BEFORE_DEPARTMENT_ID'); ?></span>
				</div>	
			</div>
			
			<div class="col-sm-3 col-md-3 col-lg-3">	
				<!------------- Transfer reason -------------> 
				<div class="form-group <?php echo form_error('TRANSFER_DEPARTMENT_ID') ? 'error' : ''; ?>">
					<label><?php echo lang('TRANSFER_DEPARTMENT_ID').lang('bf_form_label_required');?></label>
					<select class="form-control" name="TRANSFER_DEPARTMENT_ID[]" id="TRANSFER_DEPARTMENT_ID" required="" >
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
							foreach($department_list as $row)
								{
								
									echo "<option value='".$row->dept_id."'";
									
									if(isset($emp_transfer_details->TRANSFER_DEPARTMENT_ID))
									{
										if(trim($emp_transfer_details->TRANSFER_DEPARTMENT_ID)== $row->dept_id){echo "selected";}
									}
								
									echo ">".$row->department_name."</option>";
								}
							
						?>							
					 </select>
					 <span class='help-inline'><?php echo form_error('TRANSFER_DEPARTMENT_ID'); ?></span>
				</div>	
			</div>		
			
	</div>	
			

	<div class="col-sm-12 col-md-12 col-lg-12">		
		<div class="col-sm-3 col-md-3 col-lg-3">				
			<!-------------present Designation -------------> 
			<div class="form-group <?php echo form_error('BEFORE_DESIGNATION_ID') ? 'error' : ''; ?>">
				<label><?php echo lang('BEFORE_DESIGNATION_ID').lang('bf_form_label_required');?></label>
					<select class="form-control" name="BEFORE_DESIGNATION_ID[]" id="BEFORE_DESIGNATION_ID" required="">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 						
								foreach($designation_list as $beforeRow)
								{							
									echo "<option value='".$beforeRow->DESIGNATION_ID."'";
									
									if(isset($emp_transfer_details->BEFORE_DESIGNATION_ID))
									{
										if(trim($emp_transfer_details->BEFORE_DESIGNATION_ID)== $beforeRow->DESIGNATION_ID){echo "selected";}
									}
								
									echo ">".$beforeRow->DESIGNATION_NAME."</option>";
								}
							
						?>							
					</select>
					<span class='help-inline'><?php echo form_error('BEFORE_DESIGNATION_ID'); ?></span>		
			</div>	
		</div>
		
		
		<div class="col-sm-3 col-md-3 col-lg-3">		
			<!------------- Transfer designation -------------> 
			<div class="form-group <?php echo form_error('TRANSFER_DESIGNATION_ID') ? 'error' : ''; ?>">
				<label><?php echo lang('TRANSFER_DESIGNATION').lang('bf_form_label_required');?></label>
				<select class="form-control" name="TRANSFER_DESIGNATION_ID[]" id="TRANSFER_DESIGNATION_ID" required="">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
							foreach($designation_list as $row)
							{
							
								echo "<option value='".$row->DESIGNATION_ID."'";
								
								if(isset($emp_transfer_details->TRANSFER_DESIGNATION_ID))
								{
									if(trim($emp_transfer_details->TRANSFER_DESIGNATION_ID)== $row->DESIGNATION_ID){echo "selected";}
								}
							
								echo ">".$row->DESIGNATION_NAME."</option>";
							}
						
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('TRANSFER_DESIGNATION_ID'); ?></span>
			</div>		
		</div>	
		
		

		<div class="col-sm-3 col-md-3 col-lg-3">		
			<!-- ----------- Remarks--------------- -->               
			<div class="form-group <?php echo form_error('TRANSFER_REMARKS') ? 'error' : ''; ?>">
				<label><?php echo lang('TRANSFER_REMARKS');?></label>
					<input type="text" name="TRANSFER_REMARKS[]" value="<?php if(isset($emp_transfer_details->TRANSFER_REMARKS)) echo $emp_transfer_details->TRANSFER_REMARKS; ?>" id="TRANSFER_REMARKS" class="form-control" title="<?php e(lang('TRANSFER_REMARKS'));?>" />
				<span class='help-inline'></span>
			</div>											
		</div>	
		
		<div class="col-md-1 addContainer">	
			<h3>	
			<?php if(isset($removeRow) && $removeRow){?> 
				<a name="clicktoadd" class="btn btn-danger glyphicon glyphicon-minus" onclick="removeTransferTr(this)" href="javascript:void(0)"> </a>
			<?php } else { ?>	
				<a name="clicktoadd" class="btn btn-primary glyphicon glyphicon-plus" onclick="addTransferRow(this)" href="javascript:void(0)"> </a>
			<?php } ?>	
		</h3>
		</div>	
	</div>	

</span>

