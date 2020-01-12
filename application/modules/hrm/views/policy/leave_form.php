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
	</style>


	<div class="box box-primary row box-c">
		<?php echo form_open($this->uri->uri_string(), 'id="leaveInFoFrm", role="form", class="nform-horizontal", onsubmit=""'); ?>
			<fieldset>
				<legend>Leave Policy</legend>
				<div class="col-sm-12 col-md-12 col-lg-12">	
					<div class="row">				
						<div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-2">
							<!-- ----------- Policy Name ---------------- --> 					
							<div class="<?php echo form_error('LEAVE_POLICY_NAME') ? 'error' : ''; ?>">
								<div id="checkLeavePolicyName"></div>
								<div class='form-group'>
									<label><?php echo lang('leave_policy_name').lang('bf_form_label_required');?></label>									
									<input type="text"  class="form-control"   name="leave_policy_name"  id="leave_policy_name" value="" placeholder="<?php e(lang('leave_policy_name'));?>" title="<?php e(lang('leave_policy_name'));?>" required="" onblur='leavePolicyCheck()'/>
								</div>
							</div>	
						</div>					

						<div class="col-sm-4 col-md-2 col-lg-2">
							<!-- ----------- Policy Status ---------------- --> 					
							<div class="form-group <?php echo form_error('LEAVE_POLICY_STATUS') ? 'error' : ''; ?>">
								<label><?php echo lang('leave_policy_status').lang('bf_form_label_required');?></label>
								<select class="form-control" name="leave_policy_status" id="leave_policy_status">							
									<option value="1">Active</option>
									<option value="0">Inactive</option>										
								</select>
								<span class='help-inline'><?php echo form_error('LEAVE_POLICY_STATUS'); ?></span>
							</div>		
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 detailsContainer">
							<?php echo $this->load->view('policy/leave_policy_details', $_REQUEST, true); ?>
						</div>  <!-- panel body end -->
					</div> 
				</div>
			</fieldset>
			<fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">

            	<input type='hidden' name='LEAVE_POLICY_MST_ID' value="" id='LEAVE_POLICY_MST_ID' />	
							<a name="reset" class="btn btn-warning btn-xs" onclick="resetLeave()">Reset</a>		
							<a name="add_absent_leave_policy_info" href="javascript:void(0)" onclick="addmLeaveInfo()" class="btn btn-primary btn-xs mlm">Save</a>   
            </div>
            </fieldset>
		<?php echo form_close(); ?>

	</div>