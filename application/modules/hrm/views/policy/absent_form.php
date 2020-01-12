	<?php
	$validation_errors = validation_errors();
	if ($validation_errors) :
		?>
	<div class="alert alert-block alert-error fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<h4 class="alert-heading">Please fix the following errors:</h4>
		<?php echo $validation_errors; ?>
	</div>
	<?php
	endif;
	if (isset($employee_details))
	{
		$employee_details = (array) $employee_details;
	}
	$id = isset($employee_details['id']) ? $employee_details['id'] : '';


	?>
	<style>
		.box-c{width: auto;margin-bottom: 0px;}
	</style>

	<div class="row box box-primary box-c">
		<?php echo form_open($this->uri->uri_string(),'id="absentInFoFrm", role="form", class="form-horizontal", onsubmit=""' ); ?>
		<fieldset>
			<legend>Absent Policy</legend>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<!-- policy Name Start -->
					<div class="col-sm-3 col-md-3 col-lg-3 col-lg-offset-3">
						<div class="form-group <?php echo form_error('ABSENT_POLICY_NAME') ? 'error' : ''; ?>">
							<div id="checkAbsentPolicyName" style="color:#F00; font-size:14px;"></div>
							<label><?php echo lang('absent_policy_name').lang('bf_form_label_required');?></label>
							<div class='control'>
								<input type="text" class="form-control absent_policy_name_cls" name="absent_policy_name" id="absent_policy_name"  value=""  placeholder="<?php e(lang('absent_policy_name'));?>" title="<?php e(lang('absent_policy_name'));?>" required="" tabindex="1" onblur='absentPolicyCheck()'/>
								<span class='help-inline'><?php echo form_error('ABSENT_POLICY_NAME'); ?></span>
							</div>
						</div>
					</div>
					<!-- Deduction head Start -->
					<!--<div class="col-sm-3 col-md-2 col-lg-2">
						<div class="form-group <?php echo form_error('ABSENT_DEDUCTION_HEAD') ? 'error' : ''; ?>">
							<label><?php echo lang('absent_deduction_head').lang('bf_form_label_required');?></label>
							<select class="form-control" id="absent_deduction_head" required="" name="absent_deduction_head" tabindex="6">
								<option value=""><?php echo lang('bf_msg_selete_one');?></option>
								<?php 

								foreach($deductionHead as $deductionHeads)
								{									
									echo "<option value='".$deductionHeads->BASE_HEAD_ID."'";																				
									echo ">".$deductionHeads->BASE_SYSTEM_HEAD."</option>";
								}

								?>							
							</select>
						<span class='help-inline'><?php echo form_error('ABSENT_DEDUCTION_HEAD'); ?></span>
						</div>								
					</div>	-->					
					<!-- status Start -->
					<div class="col-sm-3 col-md-2 col-lg-2">
						<div class="form-group <?php echo form_error('ABSENT_POLICY_STATUS') ? 'error' : ''; ?>">									
							<div class='control'>
								<label><?php echo lang('absent_policy_status').lang('bf_form_label_required');?></label>
								<select class="form-control" name="absent_policy_status" id="absent_policy_status" tabindex="3">											
									<option value="1">Active</option>
									<option value="0">Inactive</option>

								</select>
							<span class='help-inline'><?php echo form_error('ABSENT_POLICY_STATUS'); ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 detailsContainer">
						<?php echo $this->load->view('policy/absent_policy_details', $_REQUEST, TRUE); ?>
					</div> <!-- form main column end -->
				</div>				
			</div> <!-- main column end -->  
		</fieldset>
		<fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
            	<input type='hidden' name='ABSENT_POLICY_MST_ID' value=""  id='ABSENT_POLICY_MST_ID' />	
				<a name="reset" class="btn btn-warning btn-xs" onclick="resetAbsent()">Reset</a>					
				<a name="add_absent_leave_policy_info" href="javascript:void(0)" onclick="addmAbsentInfo()" class="btn btn-primary btn-xs mlm">Save</a> 
            </div>
        </fieldset>
		<?php echo form_close(); ?>
	</div>