	<?php
	if (isset($result))
	{
		$details = (array) $result;	
	}

	?>

	<style type="text/css">
		.new-row{margin-left: 5px;margin-right: 5px;}
	</style>
	<span>
	<div class="row new-row">		
		<div class="col-sm-3 col-md-2 col-lg-2"> 		

			<!-- Leave Type --> 
			<div class="form-group <?php echo form_error('LEAVE_POLICY_TYPE') ? 'error' : ''; ?>">
				<label><?php echo lang('leave_type').lang('bf_form_label_required');?></label>
				<select class="form-control" name="leave_type[]" tabindex="1" onchange='checkType()'>
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 

					foreach($leave_type as $key=>$val)
					{								
						echo "<option value='".$key."'";
						if(isset($details['LEAVE_POLICY_TYPE']))
						{
							if(trim($details['LEAVE_POLICY_TYPE'])==$key){echo "selected";}
						}	

						echo ">".$val."</option>";								

					}

					?>							
				</select>
				<span class='help-inline'><?php echo form_error('LEAVE_POLICY_TYPE'); ?></span>
			</div>
		</div>
		<div class="col-sm-3 col-md-3 col-lg-3"> 
			
			<!-- Formula --> 
			<iv class="form-group <?php echo form_error('LEAVE_POLICY_FORMULA') ? 'error' : ''; ?>">
				<label><?php echo lang('leave_formula');?></label>
				<select class="form-control" name="leave_formula[]"  tabindex="4">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 

					foreach($formula as $key => $val)
					{								
						echo "<option value='".$key."'";							
						if(isset($details['LEAVE_POLICY_FORMULA']))
						{
							if(trim($details['LEAVE_POLICY_FORMULA'])==$key){echo "selected";}
						}
						echo ">".$val."</option>";
					}

					?>							
				</select>
				<span class='help-inline'><?php echo form_error('LEAVE_POLICY_FORMULA'); ?></span>
			</div>
			
			
			
			<div class="col-sm-3 col-md-2 col-lg-2"> 
				<!-- Leave Available criteria --> 
				<div class="form-group <?php echo form_error('LEAVE_AVAIL_CRITERIA') ? 'error' : ''; ?>">
					<label><?php echo lang('leave_available_criteria');?></label>
					<select class="form-control" name="leave_available_criteria[]"  tabindex="7">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
						
						foreach($leave_criteria as $key=>$val)
						{

							echo "<option value='".$key."'";
							if(isset($details['LEAVE_AVAIL_CRITERIA']))
							{
								if(trim($details['LEAVE_AVAIL_CRITERIA'])==$key){echo "selected";}
							}
							echo ">".$val."</option>";
						}
						
						?>							
					</select>
					<span class='help-inline'><?php echo form_error('LEAVE_AVAIL_CRITERIA'); ?></span>
				</div>
			</div>			
			
			<div class="col-sm-3 col-md-2 col-lg-2"> 
				<!--  Off day Leave --> 
				<div class="form-group <?php echo form_error('OFFDAY_LEAVE_COUNT') ? 'error' : ''; ?>">
					<label><?php echo lang('leave_of_day_count');?></label>
					<select class="form-control" name="leave_of_day_count[]"  tabindex="10">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
						
						foreach($offday_leave as $key=>$val)
						{									
							echo "<option value='".$key."'";						
							if(isset($details['OFFDAY_LEAVE_COUNT']))
							{
								if(trim($details['OFFDAY_LEAVE_COUNT'])==$key){echo "selected";}
							}
							echo ">".$val."</option>";
						}
						
						?>							
					</select>
					<span class='help-inline'><?php echo form_error('OFFDAY_LEAVE_COUNT'); ?></span>
				</div>			

			</div>
			<div class="col-sm-3 col-md-2 col-lg-2"> 
				<!-- Limit Type --> 
				<div class="form-group <?php echo form_error('LEAVE_POLICY_LIMIT') ? 'error' : ''; ?>">
					<label><?php echo lang('leave_limit_type');?></label>
					<select class="form-control" name="leave_limit_type[]" tabindex="2" onchange='checkLimit()'>
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
						
						foreach($limit_type as $key => $val)
						{								
							echo "<option value='".$key."'";				
							if(isset($details['LEAVE_POLICY_LIMIT']))
							{
								if(trim($details['LEAVE_POLICY_LIMIT'])==$key){echo "selected";}
							}
							echo ">".$val."</option>";
						}							
						?>							
					</select>
					<span class='help-inline'><?php echo form_error('LEAVE_POLICY_LIMIT'); ?></span>
				</div>	
			</div>
			<div class="col-sm-2 col-md-1 col-lg-1"> 
			<!-- Carring Forward --> 
			<div class="form-group <?php echo form_error('CARRING_FORWARD') ? 'error' : ''; ?>">
					<!-- <label><?php //echo lang('leave_carring_ford_allow');?></label> -->
					<label>Carr. Forward</label>
					<div>
						<input type="checkbox"  name="leave_carring_ford_allow[]" value="1" <?php //if($details['CARRING_FORWARD']==1){ echo 'checked="checked"';} ?>  tabindex="12"> 
					</div>
			</div>
			</div>

			<!--<div class="col-sm-3 col-md-2 col-lg-2"> 
				<!-- Leave Calculation --> 
			<!--<div class="form-group <?php echo form_error('LEAVE_CALCULATION_START_FROM') ? 'error' : ''; ?>">
				<label><?php echo lang('leave_calculation_start');?></label>
				<select class="form-control" name="leave_calculation_start[]" tabindex="5">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
						foreach($leave_calculation as $key=>$val)
						{
						
							echo "<option value='".$key."'";

								if(isset($details['LEAVE_CALCULATION_START_FROM']))
									{
										if(trim($details['LEAVE_CALCULATION_START_FROM'])==$key){echo "selected";}
									}
							echo ">".$val."</option>";
						}
						
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('LEAVE_CALCULATION_START_FROM'); ?></span>
			</div>
			</div>-->
			<div class="col-sm-3 col-md-2 col-lg-2"> 
				<!-- Leave Available after --> 
			<div class="form-group <?php echo form_error('LEAVE_AVAIL_AFTER') ? 'error' : ''; ?>">
				<label><?php echo lang('leave_available_after');?></label>
				<select class="form-control" name="leave_available_after[]" tabindex="8">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
							foreach($leave_avail as $key=>$val)
							{									
								echo "<option value='".$key."'";	
								if(isset($details['LEAVE_AVAIL_AFTER']))
									{
										if(trim($details['LEAVE_AVAIL_AFTER'])==$key){echo "selected";}
									}
								echo ">".$val."</option>";
							}
						
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('LEAVE_AVAIL_AFTER'); ?></span>
			</div>
			</div>

			<div class="col-sm-3 col-md-2 col-lg-2"> 
				<!-- MAX_ACCUMULATION_LIMIT --> 
			<div class="<?php echo form_error('MAX_ACCUMULATION_LIMIT') ? 'error' : ''; ?>">
				<div class='form-group'>
					<label><?php echo lang('leave_max_accumulation_limit');?></label>
					<input type="text"  class="form-control"   name="leave_max_accumulation_limit[]" value="<?php echo set_value('MAX_ACCUMULATION_LIMIT', isset($details['MAX_ACCUMULATION_LIMIT']) ? $details['MAX_ACCUMULATION_LIMIT'] : ''); ?>" placeholder="<?php e(lang('leave_max_accumulation_limit'));?>" title="<?php e(lang('leave_max_accumulation_limit'));?>" required="" tabindex="11"/>
				</div>
			</div>
			</div>
		<div class="col-sm-3 col-md-2 col-lg-2"> 
			<!-- Max Limit  --> 
			<div class="<?php echo form_error('LEAVE_POLICY_MAX_LIMIT') ? 'error' : ''; ?>">
				<div class='form-group'>
					<label><?php echo lang('leave_max_limit');?></label>
					<input type="text"  class="form-control"   name="leave_max_limit[]" value="<?php echo set_value('LEAVE_POLICY_MAX_LIMIT', isset($details['LEAVE_POLICY_MAX_LIMIT']) ? $details['LEAVE_POLICY_MAX_LIMIT'] : ''); ?>" placeholder="<?php e(lang('leave_max_limit'));?>" title="<?php e(lang('leave_max_limit'));?>" required="" tabindex="3"/>
				</div>
			</div>
		</div>
		<div class="col-sm-3 col-md-2 col-lg-2"> 
			<!-- Consecutive Leave --> 
			<div class="<?php echo form_error('CONSECUTIVE_LEAVE') ? 'error' : ''; ?>">
				<div class='form-group'>
					<label><?php echo lang('leave_consecutiv_day');?></label>
					<input type="text"  class="form-control"   name="leave_consecutiv_day[]" value="<?php echo set_value('CONSECUTIVE_LEAVE', isset($details['CONSECUTIVE_LEAVE']) ? $details['CONSECUTIVE_LEAVE'] : ''); ?>" placeholder="<?php e(lang('leave_consecutiv_day'));?>" title="<?php e(lang('leave_consecutiv_day'));?>" required="" tabindex="6"/>
				</div>
			</div>
		</div>
		<div class="col-sm-3 col-md-2 col-lg-2"> 
			<!-- Fraction --> 
			<div class="form-group <?php echo form_error('FRACTIONAL_LEAVE') ? 'error' : ''; ?>">
				<label><?php echo lang('leave_fraction_leave');?></label>
				<select class="form-control" name="leave_fraction_leave[]" tabindex="9">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
						foreach($fructional_leave as $key=>$val)
						{								
							echo "<option value='".$key."'";
								if(isset($details['FRACTIONAL_LEAVE']))
									{
										if(trim($details['FRACTIONAL_LEAVE'])==$key){echo "selected";}
									}
						
							echo ">".$val."</option>";
						}
						
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('FRACTIONAL_LEAVE'); ?></span>
			</div>
		</div> 

		<div class="col-sm-4 col-md-4 col-lg-3 col-lg-offset-6" >	
				<div class="form-group">
					<label>&nbsp; </label>
					<div class='control'>		
						<?php if(isset($removeRow) && $removeRow){?> 
						<a name="clicktoadd" class="btn btn-danger glyphicon glyphicon-minus btn-xs" onclick="removeAbsentTr(this)" href="javascript:void(0)"> </a>
						<?php } else { ?>	
						<a name="clicktoadd" class="btn btn-primary glyphicon glyphicon-plus btn-xs" onclick="addAbsentRow(this)" href="javascript:void(0)"> </a>
						<?php } ?>	
					</div>
				</div>
		</div>


	</div>
	</span>