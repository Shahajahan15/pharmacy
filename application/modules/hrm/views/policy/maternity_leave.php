
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

?>

<style>
	.box-c{width: auto;margin-bottom: 0px;}
	.new-row{margin-left: 5px;margin-right: 5px;}
</style>

<div class="row box box-primary box-c">
	<?php echo form_open($this->uri->uri_string(), 'id="maternityInFoFrm", role="form", class="form-horizontal", onsubmit=""' ); ?>

	<fieldset>  
		<legend>Maternity Policy</legend>      
		<div class="col-sm-12 col-md-12 col-lg-12">	
			<div class="row">
				<div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4"> 
					<!--  Policy Name     -->
					<div class="form-group <?php echo form_error('MATERNITY_POLICY_NAME') ? 'error' : ''; ?>">
						<label><?php echo lang('maternity_policy_name'). lang('bf_form_label_required');?></label>
						<div class='control'>
							<input type='text' class="form-control" name='maternity_policy_name' id='maternity_policy_name'  maxlength="50" value="<?php echo set_value('maternity_policy_name', isset($lib_base_head_details['MATERNITY_POLICY_NAME']) ? $lib_base_head_details['MATERNITY_POLICY_NAME'] : ''); ?>" placeholder="<?php echo lang('maternity_policy_name')?>" required="" tabindex="1"/>
							<span class='help-inline'><?php echo form_error('MATERNITY_POLICY_NAME'); ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row new-row">
				<div class="col-sm-3 col-md-3 col-lg-3">
					<!--     Minimum Service Length  (Days)    -->
					<div class="form-group <?php echo form_error('MIN_SERVICE_LENGTH') ? 'error' : ''; ?>">
						<label><?php echo lang('maternity_mini_service');?></label>
						<div class='control'>
							<input type='text' class="form-control" name='maternity_mini_service' id='maternity_mini_service'  maxlength="50" value="<?php echo set_value('maternity_mini_service', isset($lib_base_head_details['MIN_SERVICE_LENGTH']) ? $lib_base_head_details['MIN_SERVICE_LENGTH'] : ''); ?>" placeholder="<?php echo lang('maternity_mini_service')?>" tabindex="3"/>
							<span class='help-inline'><?php echo form_error('MIN_SERVICE_LENGTH'); ?></span>
						</div>
					</div>	
				</div>
				<div class="col-sm-3 col-md-3 col-lg-3">
					<!--  Leave After Delivery     -->
					<div class="form-group <?php echo form_error('LEAVE_AFTER_DELIVERY') ? 'error' : ''; ?>">
						<label><?php echo lang('maternity_leave_after');?></label>
						<div class='control'>
							<input type='text' class="form-control" name='maternity_leave_after' id='maternity_leave_after'  maxlength="50" value="<?php echo set_value('maternity_leave_after', isset($lib_base_head_details['LEAVE_AFTER_DELIVERY']) ? $lib_base_head_details['LEAVE_AFTER_DELIVERY'] : ''); ?>" placeholder="<?php echo lang('maternity_leave_after')?>"  tabindex="5"/>
							<span class='help-inline'><?php echo form_error('LEAVE_AFTER_DELIVERY'); ?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4 col-lg-4">
					<!--Payment Disbursement-->
					<div class="form-group <?php echo form_error('PAYMENT_DISBURSEMENT') ? 'error' : ''; ?>">
						<label><?php echo lang('maternity_payment_disburse');?></label>
						<div class='control'>					
							<select class="form-control" name="maternity_payment_disburse" id="maternity_payment_disburse" tabindex="7">
								<option value=""><?php echo lang('bf_msg_selete_one');?></option>
								<?php 

								foreach($parameter_disburse as $key=>$val){

									echo "<option value='".$key."'";

									if(isset($lib_base_head_details['PAYMENT_DISBURSEMENT'])){
										if(trim($lib_base_head_details['PAYMENT_DISBURSEMENT'])==$key){echo "selected";}
									}
									echo ">".$val."</option>";									}

									?>	
								</select>
								<span class='help-inline'><?php echo form_error('PAYMENT_DISBURSEMENT'); ?></span>					
							</div>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!--Max Limit (Days)-->
						<div class="form-group <?php echo form_error('MAX_DAY_LIMIT') ? 'error' : ''; ?>">
							<label><?php echo lang('maternity_max_day_limit'). lang('bf_form_label_required');?></label>
							<div class='control'>
								<input type='text' class="form-control" name='maternity_max_day_limit' id='maternity_max_day_limit'  maxlength="50" value="<?php echo set_value('maternity_max_day_limit', isset($lib_base_head_details['MAX_DAY_LIMIT']) ? $lib_base_head_details['MAX_DAY_LIMIT'] : ''); ?>" placeholder="<?php echo lang('maternity_max_day_limit')?>" required="" tabindex="2"/>
								<span class='help-inline'><?php echo form_error('MAX_DAY_LIMIT'); ?></span>
							</div>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!--Leave Before Delivery -->
						<div class="form-group <?php echo form_error('LEAVE_BEFORE_DELIVERY') ? 'error' : ''; ?>">
							<label><?php echo lang('maternity_leave_before');?></label>
							<div class='control'>
								<input type='text' class="form-control" name='maternity_leave_before' id='maternity_leave_before'  maxlength="50" value="<?php echo set_value('maternity_leave_before', isset($lib_base_head_details['LEAVE_BEFORE_DELIVERY']) ? $lib_base_head_details['LEAVE_BEFORE_DELIVERY'] : ''); ?>" placeholder="<?php echo lang('maternity_leave_before')?>" tabindex="4"/>
								<span class='help-inline'><?php echo form_error('LEAVE_BEFORE_DELIVERY'); ?></span>
							</div>
						</div>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<!--Payment Calculation-->
						<div class="form-group <?php echo form_error('PAYMENT_CALCULATION') ? 'error' : ''; ?>">
							<label><?php echo lang('maternity_payment_calculation');?></label>
							<div class='control'>					
								<select class="form-control" name="maternity_payment_calculation" id="maternity_payment_calculation"  tabindex="6">
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
									<?php 

									foreach($parameter_calculation as $key=>$val){
										
										echo "<option value='".$key."'";
										
										if(isset($lib_base_head_details['PAYMENT_CALCULATION'])){
											if(trim($lib_base_head_details['PAYMENT_CALCULATION'])==$key){echo "selected";}
										}
										echo ">".$val."</option>";
									}

									?>	
								</select>
								<span class='help-inline'><?php echo form_error('PAYMENT_CALCULATION'); ?></span>					
							</div>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!--Statust-->
						<div class="form-group <?php echo form_error('MATERNITY_STATUS') ? 'error' : ''; ?>">
							<label>Status<?php echo lang('bf_form_label_required');?></label>								
							<div class='control'>					
								<select class="form-control" name="maternity_policy_status" id="maternity_policy_status" tabindex="8">
									<option value="1">Active</option>
									<option value="0">Inactive</option>		
								</select>
								<span class='help-inline'><?php echo form_error('MATERNITY_STATUS'); ?></span>					
							</div>
						</div>
					</div>
				</div>	
			</div>
		</fieldset>
		<fieldset>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
				<input type='hidden' name='MATERNITY_LEAVE_ID' value=""  id='MATERNITY_LEAVE_ID' />	
				<a name="reset" class="btn btn-warning btn-xs" onclick="resetMaternity()">Reset</a>	 
				<a name="add_maternity_leave_policy_info" href="javascript:void(0)" onclick="addmMaternityInfo()" class="btn btn-primary btn-xs mlm">Add</a>	
			</div>
		</fieldset>

		<?php echo form_close(); ?>

	</div>









