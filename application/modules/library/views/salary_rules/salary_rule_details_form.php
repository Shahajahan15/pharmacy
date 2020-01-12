<!-- ----------- Requisition Details --------- -->

<style> 
	.padding-left-div{margin-left:23px; }
</style>

<div class="row">
	<div class="container">
				  
		<div class="col-sm-6 col-md-3 col-lg-3 text-center">
			
		</div>                              
		
		<div class="col-sm-6 col-md-2 col-lg-2 text-center">
			
		</div>
		
		<div class="col-sm-6 col-md-2 col-lg-3 text-center">
			
		</div>
		
		<div class="col-sm-6 col-md-1 col-lg-1 text-left">
			
		</div> 

	</div>
</div>

<?php
if (isset($result))
{
	$details = (array) $result;	
?>
<span>     
	<div class="row">
		<!-- salary head start -->
		<div class="col-sm-3 col-md-3 col-lg-3 padding-left-div">				   
			<div class="form-group  <?php echo form_error('salary_head') ? 'error' : ''; ?>">	
				<?php echo form_label(lang('hrm_salary_rule_salary_head'), 'salary_head'); ?>
				<div class='control'>
				<input type="hidden" name="details_id[]" id="details_id" value="<?php echo isset($details['DTLS_ID'])? $details['DTLS_ID'] : ''; ?>"/>				
					<select class="form-control" name="salary_head[]" id="salary_head" required="">						
						<?php 			
							
							foreach($salary_heads as $salary_head)
							{									
								echo "<option value='".$salary_head->BASE_HEAD_ID."'";	
								
								if(isset($details['SALARY_HEAD_ID']))
								{							
									if($details['SALARY_HEAD_ID'] == $salary_head->BASE_HEAD_ID){ echo "selected";}
								}
								
								
								echo ">".$salary_head->BASE_SYSTEM_HEAD."</option>";
							}									
						?>
					</select>
				</div>
				<span class='help-inline'><?php echo form_error('salary_head'); ?></span>
			</div>						
		</div>
		<!-- salary head end -->
		
		<!-- Amount Type start -->
		<div class="col-sm-2 col-md-2 col-lg-2 text-center  padding-left-div">
			<div class="form-group <?php echo form_error('amount_type') ? 'error' : ''; ?> ">
			<?php echo form_label(lang('hrm_salary_rule_amount_type'), 'amount_type'); ?>
				<div class='control'>						
					<select class="form-control" name="amount_type[]" id="amount_type" class="option_change" required="" onchange="getAmountType(this)">							
						<option value="1"<?php if(@$details['AMOUNT_TYPE'] == 1) {echo "selected";} ?>> Percentage </option> 
						<option value="2" <?php if (@$details['AMOUNT_TYPE'] == 2) {echo "selected";} ?>> Fixed </option>					                      
					</select>
					<span class='help-inline'><?php echo form_error('amount_type'); ?></span>									
				</div>
			</div>                
		</div>	
		<!-- Amount Type end -->
		
		<!------------ Percentage amount ----------->
		<div class="col-sm-2 col-md-2 col-lg-2 padding-left-div"> 				
			<div class="form-group <?php echo form_error('percentage_amount') ? 'error' : ''; ?>">
				<?php echo form_label(lang('hrm_salary_rule_percentage'), 'description'); ?>
				<div class='control'>
					<input type='text' class="form-control" name='percentage_amount[]' id='percentage_amount'  maxlength="100" value="<?php echo set_value('percentage_amount',isset($details['PERCENTAGE'])? $details['PERCENTAGE'] : ''); ?>" placeholder="%"/>
					<span class='help-inline'><?php echo form_error('percentage_amount'); ?></span>
				</div>
			</div>
		</div>	
		
		<!------------ Fixed amount ----------->
		<div class="col-sm-2 col-md-2 col-lg-2 padding-left-div"> 				
			<div class="form-group <?php echo form_error('fixed_amount') ? 'error' : ''; ?>">
				<?php echo form_label(lang('hrm_salary_rule_fixed'), 'fixed_amount'); ?>
				<div class='control'>
					<input type='text' class="form-control" name='fixed_amount[]' id='fixed_amount' value="<?php echo set_value('fixed_amount',isset($details['FIXED'])? $details['FIXED'] : ''); ?>" placeholder="<?php echo lang('hrm_salary_rule_fixed')?>" disabled />
					<span class='help-inline'><?php echo form_error('fixed_amount'); ?></span>
				</div>
			</div>
		</div>	
		
		<div class="col-sm-1 col-md-1 col-lg-1">  
			<div class="form-group">
				<label style="margin-top:26px;"> &nbsp; </label>
				<?php if(isset($removeRow) && $removeRow){?> 
						<a name="clicktoadd" class="btn btn-danger glyphicon glyphicon-minus btn-xs" onclick="removeRow(this);" href="javascript:void(0)"> </a>
				<?php } else { ?>	
						<a name="clicktoMinus" class="btn btn-primary glyphicon glyphicon-plus btn-xs" onclick="addRow(this)" href="javascript:void(0)" name="plus_btn[]" disabled> </a> 										
					<?php 
				} 
				?>
			</div>			
		</div>
		
	</div>		
</span>		
<?php
} else { ?>
	<span>     
		<div class="row">
			<!-- salary head start -->
			<div class="col-sm-3 col-md-3 col-lg-3 padding-left-div">				   
				<div class="form-group  <?php echo form_error('salary_head') ? 'error' : ''; ?>">	
					<?php echo form_label(lang('hrm_salary_rule_salary_head'), 'salary_head'); ?>
					<div class='control'>
						<input type="hidden" name="details_id[]" id="details_id" value="<?php echo isset($details['DTLS_ID'])? $details['DTLS_ID'] : ''; ?>"/>
						<select class="form-control" name="salary_head[]" required="">						
							<?php 											
								foreach($salary_heads as $salary_head)
								{									
									echo "<option value='".$salary_head->BASE_HEAD_ID."'";	
									/*
									if(isset($record->SALARY_HEAD))
									{							
										if($record->SALARY_HEAD == $salary_head->BASE_HEAD_ID){ echo "selected";}
									}
									*/
									
									echo ">".$salary_head->BASE_SYSTEM_HEAD."</option>";
								}									
							?>
						</select>
					</div>
					<span class='help-inline'><?php echo form_error('salary_head'); ?></span>
				</div>						
			</div>
			<!-- salary head end -->
			
			<!-- Amount Type start -->
			<div class="col-sm-2 col-md-2 col-lg-2 text-center  padding-left-div">
				<div class="form-group <?php echo form_error('amount_type') ? 'error' : ''; ?> ">
				<?php echo form_label(lang('hrm_salary_rule_amount_type'), 'amount_type'); ?>
					<div class='control'>						
						<select class="form-control" name="amount_type[]" id="amount_type" class="option_change" required="" onchange="getAmountType(this)">							
							<option value="1"<?php if(@$record->AMOUNT_TYPE == 1) {echo "selected";} ?>> Percentage </option> 
							<option value="2" <?php if (@$record->AMOUNT_TYPE == 2) {echo "selected";} ?>> Fixed </option>
												  
						</select>
						<span class='help-inline'><?php echo form_error('amount_type'); ?></span>									
					</div>
				</div>                
			</div>	
			<!-- Amount Type end -->
			
			<!------------ Percentage amount ----------->
			<div class="col-sm-2 col-md-2 col-lg-2 padding-left-div"> 				
				<div class="form-group <?php echo form_error('percentage_amount') ? 'error' : ''; ?>">
					<?php echo form_label(lang('hrm_salary_rule_percentage'), 'description'); ?>
					<div class='control'>
						<input type='text' class="form-control" name='percentage_amount[]' id='percentage_amount'  maxlength="100" value="<?php echo set_value('percentage_amount',isset($details['PERCENTAGE'])? $details['PERCENTAGE'] : ''); ?>" placeholder="%"/>
						<span class='help-inline'><?php echo form_error('percentage_amount'); ?></span>
					</div>
				</div>
			</div>	
			
			<!------------ Fixed amount ----------->
			<div class="col-sm-2 col-md-2 col-lg-2 padding-left-div"> 				
				<div class="form-group <?php echo form_error('fixed_amount') ? 'error' : ''; ?>">
					<?php echo form_label(lang('hrm_salary_rule_fixed'), 'fixed_amount'); ?>
					<div class='control'>
						<input type='text' class="form-control" name='fixed_amount[]' id='fixed_amount' value="<?php echo set_value('fixed_amount',isset($details['FIXED'])? $details['FIXED'] : ''); ?>" placeholder="<?php echo lang('hrm_salary_rule_fixed')?>" disabled />
						<span class='help-inline'><?php echo form_error('fixed_amount'); ?></span>
					</div>
				</div>
			</div>	
			
			<div class="col-sm-1 col-md-1 col-lg-1">  
				<div class="form-group">
					<label style="margin-top:26px;"> &nbsp; </label>
					<?php if(isset($removeRow) && $removeRow){?> 
							<a name="clicktoadd" class="btn btn-danger glyphicon glyphicon-minus btn-xs" onclick="removeRow(this);" href="javascript:void(0)"> </a>
					<?php } else { ?>	
							<a name="clicktoMinus" class="btn btn-primary glyphicon glyphicon-plus btn-xs" onclick="addRow(this)" href="javascript:void(0)"> </a> 										
						<?php 
					} 
					?>
				</div>			
			</div>
			
		</div>		
	</span>	
<?php }
?>	



