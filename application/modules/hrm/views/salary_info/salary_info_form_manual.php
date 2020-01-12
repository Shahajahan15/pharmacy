<style>
    .requisition-list{
        margin-bottom: 15px;
    }    
	.padding-left-div{margin-left:23px; }
</style>

<?php
if (isset($result))
{
	$details = (array) $result;	
}
?>

<?php if(isset($targetDetailsRecords)){
	foreach($targetDetailsRecords as $record){ ?>
			
			<span>     
				<div class="container text-center">
					
					<!-- salary head start -->
					<div class="col-sm-3 col-md-3 col-lg-3">
					   
						<input type="hidden" id="master_id" name="master_id" value="<?php e(isset($record->MST_ID) ? $record->MST_ID : '');?>" >
						<input type="hidden" id="details_id" name="details_id" value="<?php e(isset($record->DETAILS_ID) ? $record->DETAILS_ID : '');?>" > 
						
						<div class="form-group  <?php echo form_error('hrm_salary_head') ? 'error' : ''; ?>">					
							<select class="form-control" name="manual_salary_head[]"  required="" tabindex="4">
								<option value=""><?php echo lang('bf_msg_selete_one');?></option>
								<?php 
										
									foreach($salary_heads as $salary_head)
									{									
										echo "<option value='".$salary_head->SALARY_HEAD_ID."'";	

										if(isset($record->SALARY_HEAD))
										{							
											if($record->SALARY_HEAD == $salary_head->SALARY_HEAD_ID)
											{ 
												echo "selected";
											}
										}
										
										echo ">".$salary_head->SALARY_HEAD_NAME."</option>";
									}
								
								?>
							</select>
							
							<span class='help-inline'><?php echo form_error('hrm_salary_head'); ?></span>
							
						</div>
						
					</div>
					<!-- salary head end -->
					
					<!-- Amount Type start -->
					<div class="col-sm-2 col-md-2 col-lg-2 text-center  padding-left-div">
						<div class="form-group <?php echo form_error('hrm_amount_type') ? 'error' : ''; ?> ">
							<div class='control'>						
								<select class="form-control" name="amount_type[]" onchange="getAmountType(this)" class="option_change" required="" tabindex="4">							
									<option value="1" <?php if (@$record->AMOUNT_TYPE == 1) {echo "selected";} ?>> Fixed </option>
									<option value="2"<?php if(@$record->AMOUNT_TYPE == 2) {echo "selected";} ?>> Percentage </option>                       
								</select>
								<span class='help-inline'><?php echo form_error('hrm_amount_type'); ?></span>									
							</div>
						</div>                
					</div>	
					<!-- Amount Type end -->
				   
					<!-- Fixed value start -->
					<div class="col-sm-2 col-md-2 col-lg-2 text-center  padding-left-div">
						<div class="form-group <?php echo form_error('hrm_fixed_value') ? 'error' : ''; ?> ">
							<div class='control'>
								<input type='text' name='fixed_value[]' value="<?php echo set_value('hrm_fixed_value', isset($record->FIXED_VALUE) ? $record->FIXED_VALUE : ''); ?>" id='fixed_value' class="form-control fixedValue" onkeyup="calculativeFixedValue()" placeholder="<?php echo lang('hrm_fixed_value')?>" required="" tabindex="6"/>
								<span class='help-inline'><?php echo form_error('hrm_fixed_value'); ?></span>
							</div>
						</div>                
					</div>	
					<!-- Fixed value end -->

					<!-- Percentage value start -->
					<div class="col-sm-2 col-md-1 col-lg-1 text-center  padding-left-div">
						<div class="form-group <?php echo form_error('hrm_percentage_value') ? 'error' : ''; ?> ">
							<div class='control'>
								<input type='text' name='percentage_value[]' value="<?php echo set_value('hrm_percentage_value', isset($record->PERCENTAGE_VALUE) ? $record->PERCENTAGE_VALUE : ''); ?>" id='percentage_value' class="form-control" disabled onkeyup="calculativePercentageValue()" placeholder="<?php echo lang('hrm_percent_sign')?>" required="" tabindex="7"/>
								<span class='help-inline'><?php echo form_error('hrm_percentage_value'); ?></span>
							</div>
						</div>                
					</div>
					<!-- Percentage value end -->
					
					
					<div class="col-sm-2 col-md-1 col-lg-1 text-center  padding-left-div">
						<div class="form-group <?php echo form_error('calculative_value') ? 'error' : ''; ?> ">
							<div class='control'>
								<input type='text' name='calculative_value[]' value="<?php echo set_value('calculative_value', isset($record->CALCULATIVE_VALUE) ? $record->CALCULATIVE_VALUE : ''); ?>"  class="form-control sum"  placeholder="<?php echo lang('hrm_calculative_value')?>" readonly tabindex="8"/>
								<span class='help-inline'><?php echo form_error('calculative_value'); ?></span>
							</div>
						</div>                
					</div>
				   
				</div> <!-- end container -->
			</span>	
			
<?php	}
} else { ?>	
		<span>     
			<div class="container text-center">
				
				<!-- salary head start -->
				<div class="col-sm-2 col-md-3 col-lg-3">
				   
					<input type="hidden" id="master_id" name="master_id" value="<?php e(isset($record->MST_ID) ? $record->MST_ID : '');?>" >
					<input type="hidden" id="details_id" name="details_id" value="<?php e(isset($record->DETAILS_ID) ? $record->DETAILS_ID : '');?>" >
					
					<div class="form-group  <?php echo form_error('hrm_salary_head') ? 'error' : ''; ?>">					
						<select class="form-control" name="manual_salary_head[]"  required="" tabindex="4">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
							<?php 
									
								foreach($salary_heads as $salary_head)
								{									
									echo "<option value='".$salary_head->SALARY_HEAD_ID."'";	

									if(isset($record->SALARY_HEAD))
									{							
										if($record->SALARY_HEAD == $salary_head->SALARY_HEAD_ID)
										{
											echo "selected";
										}
									}
									
									echo ">".$salary_head->SALARY_HEAD_NAME."</option>";
								}
							
							?>
						</select>
						<span class='help-inline'><?php echo form_error('hrm_salary_head'); ?></span>
					</div>
					
				</div>
				<!-- salary head end -->
				
				<!-- Amount Type start -->
				<div class="col-sm-2 col-md-2 col-lg-2 text-center  padding-left-div">
					<div class="form-group <?php echo form_error('hrm_amount_type') ? 'error' : ''; ?> ">
						<div class='control'>						
							<select class="form-control" name="amount_type[]" onchange="getAmountType(this)" class="option_change" required="" tabindex="4">							
								<option value="1" <?php if (@$record->AMOUNT_TYPE == 1) {echo "selected";} ?>> Fixed </option>
								<option value="2"<?php if(@$record->AMOUNT_TYPE == 2) {echo "selected";} ?>> Percentage </option>                       
							</select>
							<span class='help-inline'><?php echo form_error('hrm_amount_type'); ?></span>									
						</div>
					</div>                
				</div>	
				<!-- Amount Type end -->
			   
				<!-- Fixed value start -->
				<div class="col-sm-2 col-md-2 col-lg-2 text-center  padding-left-div">
					<div class="form-group <?php echo form_error('hrm_fixed_value') ? 'error' : ''; ?> ">
						<div class='control'>
							<input type='text' name='fixed_value[]' value="<?php echo set_value('hrm_fixed_value', isset($record->FIXED_VALUE) ? $record->FIXED_VALUE : ''); ?>" id='fixed_value' class="form-control fixedValue" onkeyup="calculativeFixedValue()" placeholder="<?php echo lang('hrm_fixed_value')?>" required="" tabindex="6"/>
							<span class='help-inline'><?php echo form_error('hrm_fixed_value'); ?></span>
						</div>
					</div>                
				</div>	
				<!-- Fixed value end -->

				<!-- Percentage value start -->
				<div class="col-sm-2 col-md-1 col-lg-1 text-center  padding-left-div">
					<div class="form-group <?php echo form_error('hrm_percentage_value') ? 'error' : ''; ?> ">
						<div class='control'>
							<input type='text' name='percentage_value[]' value="<?php echo set_value('hrm_percentage_value', isset($record->PERCENTAGE_VALUE) ? $record->PERCENTAGE_VALUE : ''); ?>" id='percentage_value' class="form-control" disabled onkeyup="calculativePercentageValue()" placeholder="<?php echo lang('hrm_percent_sign')?>" required="" tabindex="7"/>
							<span class='help-inline'><?php echo form_error('hrm_percentage_value'); ?></span>
						</div>
					</div>                
				</div>
				<!-- Percentage value end -->
				
				
				<div class="col-sm-2 col-md-1 col-lg-1 text-center  padding-left-div">
					<div class="form-group <?php echo form_error('calculative_value') ? 'error' : ''; ?> ">
						<div class='control'>
							<input type='text' name='calculative_value[]' value="<?php echo set_value('calculative_value', isset($record->CALCULATIVE_VALUE) ? $record->CALCULATIVE_VALUE : ''); ?>"  class="form-control sum"  placeholder="<?php echo lang('hrm_calculative_value')?>" readonly tabindex="8"/>
							<span class='help-inline'><?php echo form_error('calculative_value'); ?></span>
						</div>
					</div>                
				</div>
			   
				<div class="col-sm-1 col-md-1 col-lg-1">               
					<?php if(isset($removeRow) && $removeRow){?> 
							<a name="clicktoadd" class="btn btn-danger glyphicon glyphicon-minus btn-xs" onclick="removeAbsentTr(this); grossTotal();" href="javascript:void(0)"> </a>
					<?php } else { ?>	
							<a name="clicktoMinus" class="btn btn-primary glyphicon glyphicon-plus btn-xs" onclick="addAbsentRow(this)" href="javascript:void(0)"> </a> 										
						<?php 
					} 
					?>											
				</div>
				
			</div> <!-- end container -->
		</span>		
<?php } ?>
		