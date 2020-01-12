<?php
//extract($sendData);


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
if (isset($patient_details)){
	$patient_details = (array) $patient_details;
}
//extract($sendData);
$id = isset($patient_details['id']) ? $patient_details['id'] : '';
?>
<style>
.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

    <fieldset class="box-body">
		<div class="col-sm-12 col-md-12 col-lg-12 ">
			<div class="panel panel-primary">
						<div class="panel-heading" align="center">
							<h3 class="panel-title">Employee Appointment Information</h3>
						</div>
				<div class="panel-body">
							<div class="col-md-12">
								<div class="col-md-3">
								</div>
								<div class="col-md-2" style="padding-left:50px;">
									<label><?php echo lang('employee_emp_id');?></label>
								</div>
								<div class="col-md-4" >
										
										<input type="text" class="form-control datepickerCommon" name="employee_emp_id" id="employee_emp_id" value="<?php echo set_value('collection_date', isset($src_diagnosis['collection_date']) ? $src_diagnosis['collection_date'] : '');?>" placeholder="<?php echo lang('employee_emp_id')?>" tabindex="1"/>				
									<span class='help-inline'><?php echo form_error('blood_group'); ?></span>
								</div>
								<div class="col-md-3">
								</div>
							</div>
									<!-- Start Left Side -->
						<div class="col-sm-5 col-md-5 col-lg-5 padding-left-div">
							<!-- -----------joining date ---------------- --> 
							<div class="form-group <?php echo form_error('father_name') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_join_date').lang('bf_form_label_required');?></label>
								<input class="form-control" id='employee_emp_join_date'  name='employee_emp_join_date' type='text'  maxlength="30" value="<?php //echo set_value('employee_name', isset($patient_details['father_name']) ? $patient_details['father_name'] : ''); ?>" placeholder="<?php echo lang('employee_name')?>" required="" tabindex="2"/>
								<span class='help-inline'><?php echo form_error('father_name'); ?></span>
							</div>

							<!-- ----------- Salary---------------- --> 
							<div class="form-group <?php echo form_error('mother_name') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_salary').lang('bf_form_label_required');?></label>
								<input class="form-control" id='employee_emp_salary'  name='employee_emp_salary' type='text'  maxlength="30" value="<?php //echo set_value('employee_emp_salary', isset($patient_details['mother_name']) ? $patient_details['mother_name'] : ''); ?>" placeholder="<?php echo lang('employee_emp_salary')?>" required="" tabindex="3"/>
								<span class='help-inline'><?php echo form_error('mother_name'); ?></span>
							</div>
							
							<!-- ----------- department --------------- -->
							<div class="<?php echo form_error('blood_group') ? 'error' : ''; ?>">
								<div class='form-group'>
									<label><?php echo lang('employee_emp_department').lang('bf_form_label_required');?></label>
									<select name="employee_emp_department" id="employee_emp_department" class="form-control"  required="" tabindex="4">
									   <option value="0"><?php echo lang('bf_msg_selete_one')?></option>
									   <?php 
										foreach($department_name as $key ){
											$key = (object) $key;
										echo "<option value='".$key->dept_id."'";
										
										echo ">".$key->department_name."</option>";
										}
										?>
									</select>
									<span class='help-inline'><?php echo form_error('blood_group'); ?></span>
								</div>
							</div>
							
							<!-- -----------Duty Type------------- --> 
							 <div class="form-group <?php echo form_error('marital_status') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_duty_type').lang('bf_form_label_required');?></label>
								<select name="employee_emp_duty_type" id="employee_emp_duty_type" class="form-control" required="" tabindex="5">
										<option value=""><?php echo lang('bf_msg_selete_one');?></option>
										<?php 
										foreach($duty_type as $key ){
										echo "<option value='".$key->shift_id."'";
										//if(isset($patient_details['marital_status']) ){
										//if($patient_details['marital_status']==$key){ echo "selected ";}
										
										echo ">".$key->shift_name."</option>";
										}
										?>
								 </select>
								 <span class='help-inline'><?php echo form_error('marital_status'); ?></span>
							</div>
							<!-- -----------Roster------------ --> 
							 <div class="form-group <?php echo form_error('marital_status') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_roster');?></label>
								<select name="employee_emp_roster" id="employee_emp_roster" class="form-control"  tabindex="6">
										<option value=""><?php echo lang('bf_msg_selete_one');?></option>
										<?php /*
										foreach($marital_status as $key => $val){
										echo "<option value='".$key."'";
										if(isset($patient_details['marital_status']) ){
										if($patient_details['marital_status']==$key){ echo "selected ";}
										}
										echo ">".$val."</option>";
										}*/
										?>
								 </select>
								 <span class='help-inline'><?php echo form_error('marital_status'); ?></span>
							</div>	
								
						</div>
						<!-- End Left Side -->
						
						<!-- Start Right Side -->
						<div class="col-sm-5 col-md-5 col-lg-5 padding-left-div">
							
							<!-- -----------Confirmation date---------------- --> 
							<div class="form-group <?php echo form_error('patient_height') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_confirmation_date').lang('bf_form_label_required');?></label>
								<input class="form-control" id='employee_emp_confirmation_date'  name='employee_emp_confirmation_date' type='text'  maxlength="30" value="<?php //echo set_value('employee_emp_confirmation_date', isset($patient_details['patient_height']) ? $patient_details['patient_height'] : ''); ?>" placeholder="<?php echo lang('employee_emp_confirmation_date')?>" tabindex="7"/>
								<span class='help-inline'><?php echo form_error('patient_height'); ?></span>
							</div>
							
							<!-- ----------- Designation --------------- -->
							<div class="<?php echo form_error('blood_group') ? 'error' : ''; ?>">
								<div class='form-group'>
									<label><?php echo lang('employee_emp_designation').lang('bf_form_label_required');?></label>
									<select name="employee_emp_designation" id="employee_emp_designation" class="form-control"  required="" tabindex="8">
									   <option value="0"><?php echo lang('bf_msg_selete_one')?></option>
									   <?php 
										foreach($designation as $key ){
											$key = (object) $key;
										echo "<option value='".$key->designation_id."'";
										
										echo ">".$key->designation_name."</option>";
										}
										?>
									</select>
									<span class='help-inline'><?php echo form_error('blood_group'); ?></span>
								</div>
							</div>
							
							<!-- -----------Supervisor------------- --> 
							 
							 
							 <div class="form-group <?php echo form_error('supervisor') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_supervisor').lang('bf_form_label_required');?></label>
								<input class="form-control" id='employee_emp_supervisor'  name='employee_emp_supervisor' type='text'  maxlength="30" value="<?php //echo set_value('employee_emp_supervisor', isset($patient_details['supervisor']) ? $patient_details['supervisor'] : ''); ?>" placeholder="<?php echo lang('employee_emp_supervisor')?>" tabindex="9"/>
								<span class='help-inline'><?php echo form_error('supervisor'); ?></span>
							</div>
							 
							 
							<!-- -----------Employee Type------------- --> 
							 <div class="form-group <?php echo form_error('marital_status') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_type').lang('bf_form_label_required');?></label>
								<select name="employee_emp_type" id="employee_emp_type" class="form-control" required="" tabindex="10">
										<option value=""><?php echo lang('bf_msg_selete_one');?></option>
										<?php 
										foreach($emp_type as $key => $val){
										echo "<option value='".$key."'";										
										echo ">".$val."</option>";
										}
										?>
								 </select>
								 <span class='help-inline'><?php echo form_error('marital_status'); ?></span>
							</div>
							<!-- -----------Weekend------------- --> 
							 <div class="form-group <?php echo form_error('marital_status') ? 'error' : ''; ?>">
								<label><?php echo lang('employee_emp_weekend').lang('bf_form_label_required');?></label>
								<select name="employee_emp_weekend" id="employee_emp_weekend" class="form-control" required="" tabindex="11">
										<option value=""><?php echo lang('bf_msg_selete_one');?></option>
										<?php 
										foreach($weekend_day as $key => $val){
										echo "<option value='".$key."'";										
										echo ">".$val."</option>";
										}										
										?>
								 </select>
								 <span class='help-inline'><?php echo form_error('weekend_day'); ?></span>
							</div>
							
						</div>
						<!-- End Right Side -->
				</div>
			</div>
		</div>

					<div class="col-md-12 col-sm-12 col-lg-12"> 
						<?php //if($patientId > 0){ ?>
							<div class="col-md-10 box-footer pager">
							<div class="col-md-4 col-sm-4 col-lg-4"> 
							</div>
								<div class="col-md-1 col-sm-1 col-lg-1"> 
								<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"/>
								<?php //echo lang('bf_or'); ?>
								<?php //echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
								
								<input class="form-control" id='employee_id'  name='employee_id' type='hidden'  maxlength="30" value="<?php echo set_value('employeeId', isset($employeeId) ? $employeeId : ''); ?>" />
								</div>
								
									<div class="col-md-4 col-sm-4 col-lg-4"> 
									<?php echo anchor(SITE_AREA .'/employee/hrm/show_list', lang('bf_action_finish'), 'class="btn btn-warning"'); ?>
									</div>
								
							</div>
						<?php //} ?>
					</div>		
     
    </fieldset>

    <?php echo form_close(); ?>
	

</div>