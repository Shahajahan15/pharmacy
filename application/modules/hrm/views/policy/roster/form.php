	<?php
	$validation_errors = validation_errors();
	if ($validation_errors) :
		?>
	<div class="alert alert-block alert-error fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<h4 class="alert-heading">Please fix the following errors:</h4>
		<?php 	
		echo $validation_errors;
		endif;
		?>
	</div>

	<style>
		.box-c{width: auto;margin-bottom: 0px;}
		.new-row{margin-left: 5px;margin-right: 5px;}
	</style>


	<div class="box box-primary row box-c">
		<?php echo form_open($this->uri->uri_string(), 'id="leaveInFoFrm", role="form", class="nform-horizontal", onsubmit=""'); ?>
			<fieldset>
				<legend>Leave Policy</legend>
				<div class="col-sm-12 col-md-12 col-lg-12">	
					<div class="new-row row">	
						<div class="col-sm-6 col-md-6 col-lg-6">
							<!-- Policy Status --> 					
							<div class="form-group <?php echo form_error('COMBI_POLICY_NAME') ? 'error' : ''; ?>">
								<label>Roster Shift Name<?php echo lang('bf_form_label_required');?></label>
								<select class="form-control" name="combi_policy_name" required="" id="combi_policy_name">
									<option value= "">Select a roster shift name....</option>
									<?php if ($combination_roster) : 
										foreach ($combination_roster as $key => $val) :
									?>	
										<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
									<?php endforeach; endif; ?>									
								</select>
								<span class='help-inline'><?php echo form_error('COMBI_POLICY_NAME'); ?></span>
							</div>		
						</div>			
						<div class="col-sm-4 col-md-3 col-lg-3">
							<!--  Policy Name --> 					
							<div class="<?php echo form_error('ROSTER_POLICY_NAME') ? 'error' : ''; ?>">
								<!--<div id="checkLeavePolicyName"></div>-->
								<div class='form-group'>
									<label><?php echo lang('leave_policy_name').lang('bf_form_label_required');?></label>									
									<input type="text"  class="form-control" name="roster_policy_name"  id="roster_policy_name" value="" placeholder="<?php e(lang('leave_policy_name'));?>" title="<?php e(lang('leave_policy_name'));?>" required="" />
								</div>
							</div>	
						</div>	
						<div class="col-sm-4 col-md-2 col-lg-2">
							<!--  Policy Name --> 					
							<div class="<?php echo form_error('AFTER_CHANGE_DAY') ? 'error' : ''; ?>">
								<!--<div id="checkLeavePolicyName"></div>-->
								<div class='form-group'>
									<label>After Change Day<?php echo lang('bf_form_label_required');?></label>									
									<input type="text"  class="form-control" name="after_change_day"  id="after_change_day" value="" placeholder="After Change Day.." required="" />
								</div>
							</div>	
						</div>					

						<div class="col-sm-2 col-md-1 col-lg-1">
							<!-- ----------- Policy Status ---------------- --> 					
							<div class="form-group <?php echo form_error('ROSTER_POLICY_STATUS') ? 'error' : ''; ?>">
								<label><?php echo lang('leave_policy_status').lang('bf_form_label_required');?></label>
								<select class="form-control" name="roster_policy_status" id="roster_policy_status">							
									<option value="1">Active</option>
									<option value="0">Inactive</option>										
								</select>
								<span class='help-inline'><?php echo form_error('ROSTER_POLICY_STATUS'); ?></span>
							</div>		
						</div>
					</div> 
				</div>
			</fieldset>
			<fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">

            	<input type='hidden' name='ROSTER_POLICY_MST_ID' value="" id='ROSTER_POLICY_MST_ID' />	
				<a name="reset" class="btn btn-warning btn-xs" onclick="resetRoster()">Reset</a>		
				<a name="add_roster_policy_info" href="javascript:void(0)" onclick="addRosterInfo()" class="btn btn-primary btn-xs mlm">Save</a>   
            </div>
            </fieldset>
		<?php echo form_close(); ?>

	</div>