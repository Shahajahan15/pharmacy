<?php
$validation_errors = validation_errors();
if ($validation_errors) :
?>
<div class="alert alert-block alert-error fade in">
	<a class="close" permanentAddress-dismiss="alert">&times;</a>
	<h4 class="alert-heading">Please fix the following errors:</h4>
	<?php echo $validation_errors; ?>
</div>
	<?php
		endif;
		if (isset($permanentAddress)){
		$permanentAddress = (array) $permanentAddress;
		}
		$id = isset($permanentAddress['id']) ? $permanentAddress['id'] : '';
		
		if (isset($presentAddress)){
		$presentAddress = (array) $presentAddress;
		}
		
		
	?>
<!--<style>
.padding-left-div{margin-left:23px; }
</style>-->

<div class="box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

  <div class="row">
	<!--<div class="col-sm-12 col-md-12 col-lg-12">-->
		<fieldset>
			<legend>Employee Contact Info</legend>
			
			<input type="hidden" name="empId" id="empId" value="<?php if(isset($employeeId)){echo $employeeId;}?>" required="" />
			
				<div class="col-sm-6 col-md-6 col-lg-6" style="margin-left: 32px;">
					<h4><strong>Permanent Address</strong></h4>
					
					<!-- - Division- --> 
					<div class="form-group <?php echo form_error('permanent_division') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_permanent_division').lang('bf_form_label_required');?></label>
						<select class="form-control" name="employee_permanent_division" id="employee_permanent_division" required="" onchange="getDistrictList(this.value)" tabindex="1">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
							<?php
								foreach($division as $divisions)
								{	
									echo "<option value='".$divisions->division_id."'";	
								
									if(isset($permanentAddress['permanentDivisionId']))
									{							
										if($permanentAddress['permanentDivisionId']==$divisions->division_id){ echo "selected ";}
									}						
									echo ">".$divisions->division_name."</option>";
								}
							?>
						 </select>
						 <span class='help-inline'><?php echo form_error('permanent_division'); ?></span>
					</div>
					
					<!--- district --> 
					<div class="form-group <?php echo form_error('permanent_district') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_permanent_district').lang('bf_form_label_required');?></label>
						<select class="form-control" name="employee_permanent_district" id="employee_permanent_district" required="" onchange="getPoliceStation(this.value)" tabindex="2">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
							<?php 													
								foreach($district as $row)
								{
									if (!empty($permanentAddress['permanentDivisionId']) && $permanentAddress['permanentDivisionId'] != $row->division_no) {
										continue;
									}

									echo "<option value='".$row->district_id."'";
									if(isset($permanentAddress['permanentDistrictId']))
									{							
										if($permanentAddress['permanentDistrictId']==$row->district_id){ echo "selected ";}			
									}
									echo ">".$row->district_name."</option>";
								}											
							?> 							
						 </select>
						 <span class='help-inline'><?php echo form_error('permanent_district'); ?></span>
					</div>
					
					<!--  Police Station  --> 
					<div class="form-group <?php echo form_error('permanent_police_station') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_permanent_police_station').lang('bf_form_label_required');?></label>
						<select class="form-control" name="employee_permanent_police_station" id="employee_permanent_police_station"  required="" onchange="getPermanentPostOffice(this.value)" tabindex="3">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						   <?php 
							
								foreach($policeStation as $row)
								{
									if (!empty($permanentAddress['permanentDistrictId']) && $permanentAddress['permanentDistrictId'] != $row->district_no) {
										continue;
									}

									echo "<option value='".$row->area_id."'";
									
									if(isset($permanentAddress['permanentPoliceStationId']))
									{							
										if($permanentAddress['permanentPoliceStationId']==$row->area_id){ echo " selected ";}						
									}
									echo ">".$row->area_name."</option>";
								}
											
							?>    							
						 </select>
						 <span class='help-inline'><?php echo form_error('permanent_police_station'); ?></span>
					</div>
					
					<!--  Post Office  --> 				
					<div class="form-group <?php echo form_error('permanent_post_office') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_permanent_post_office').lang('bf_form_label_required');?></label>
						<div class="input-group">
							<select class="form-control" name="employee_permanent_post_office" id="employee_permanent_post_office" required="" tabindex="4">
						  		<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						  		<?php 													
								foreach($postOffice as $postOffices)
								{
									if (!empty($permanentAddress['permanentPoliceStationId']) && $permanentAddress['permanentPoliceStationId'] != $postOffices->area_no) {
										continue;
									}

									echo "<option value='".$postOffices->trt_id."'";
									if(isset($permanentAddress['perPostId']))
									{							
										if($permanentAddress['perPostId']==$postOffices->trt_id){ echo "selected ";}				
									}
									echo ">".$postOffices->trt_name."</option>";
								}												
							?>    							
							</select>
							<div style="border: 1px solid gray" class="input-group-addon add-new-post-office btn">
                                <i class="fa fa-plus"></i>
                            </div>
						</div>
						<span class='help-inline'><?php echo form_error('permanent_post_office'); ?></span>
					</div>
					
					<!--  Village--> 
					<div class="form-group <?php echo form_error('permanent_village') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_permanent_village').lang('bf_form_label_required');?></label>
						<input type='text' class="form-control" name='employee_permanent_village'  id='employee_permanent_village'  maxlength="100" value="<?php echo set_value('permanent_village', isset($permanentAddress['permanentCityVillage']) ? $permanentAddress['permanentCityVillage'] : ''); ?>"  required="" tabindex="5"/>
						<span class='help-inline'><?php echo form_error('permanent_village'); ?></span>
					</div>
					
					<!--  Email Address --> 
					<div class="form-group <?php echo form_error('EMAIL') ? 'error' : ''; ?>">
						<div id="checkEmail" style="color:#F00; font-size:14px;"></div>
						<label><?php echo lang('employee_email_address');?></label>
						<input type='text' class="form-control" name='employee_email_address' id='employee_email_address'  maxlength="100" value="<?php echo set_value('EMAIL', isset($permanentAddress['email']) ? $permanentAddress['email'] : ''); ?>" tabindex="7" onblur="emailCheck()"  />
						<span class='help-inline'><?php echo form_error('EMAIL'); ?></span>
					</div>
					
					<!--  Telephone No --> 
					<div class="form-group <?php echo form_error('TELEPHONE') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_telephone_no');?></label>
						<input type='text' class="form-control"  name='employee_telephone_no' id='employee_telephone_no'  maxlength="15" value="<?php echo set_value('TELEPHONE', isset($permanentAddress['telephone']) ? $permanentAddress['telephone'] : ''); ?>" tabindex="8"/>
						<span class='help-inline'><?php echo form_error('TELEPHONE'); ?></span>
					</div>				
				</div>
			<!-- End Left Side -->
			
			
			<!-- Start Right Side -->
			<!--mailing address -->
				<div class="col-sm-5 col-md-5 col-lg-5 padding-left-div">
					<div class="row">
						<div class="col-md-9">
							<h4><strong>Mailing Address Same</strong></h4>
						</div>
					
						<div class="col-md-3"><input class="form-control" id='sameas'  name='sameas' type='checkbox' onclick="sameAsMailingAddress()"  value="1" /></div>
					</div>
					<!--  Division  --> 
					<div class="form-group <?php echo form_error('mailing_division') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_mailing_division').lang('bf_form_label_required');?></label>
						<select class="form-control" name="employee_mailing_division" id="employee_mailing_division" required="" onchange="getMailingDistrictList(this.value)" tabindex="9">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
							
							<?php 
							foreach($division as $divisions)
							{	
								echo "<option value='".$divisions->division_id."'";	
								if(isset($presentAddress['presentDivisionId']))
								{	
								if($presentAddress['presentDivisionId']==$divisions->division_id)
								{ echo "selected ";}
								}						
								echo ">".$divisions->division_name."</option>";
							}
							?>
						</select>
						 
						 <span class='help-inline'><?php echo form_error('mailing_division'); ?></span>
					</div>
						
					<!--  District  --> 
				   <div class="form-group <?php echo form_error('mailing_district') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_mailing_district').lang('bf_form_label_required');?></label>
						<select name="employee_mailing_district" id="employee_mailing_district" class="form-control" required="" onchange="getMailingPoliceStation(this.value)" tabindex="10">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
							<?php 													
								foreach($district as $districts)
								{
									if (!empty($presentAddress['presentDivisionId']) && $presentAddress['presentDivisionId'] != $districts->division_no) {
										continue;
									}

									echo "<option value='".$districts->district_id."'";
									if(isset($presentAddress['presentDistrictId']))
									{							
										if($presentAddress['presentDistrictId']==$districts->district_id){ echo "selected ";}
									}
									echo ">".$districts->district_name."</option>";
								}											
							?> 							
						 </select>
						 <span class='help-inline'><?php echo form_error('mailing_district'); ?></span>
					</div>
					
					<!--  Police Station  --> 
					<div id="police_station" class="form-group <?php echo form_error('mailing_police_station') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_mailing_police_station').lang('bf_form_label_required');?></label>
						<select class="form-control" name="employee_mailing_police_station" id="employee_mailing_police_station"  required="" onchange="getMailingPostOffice(this.value)" tabindex="11">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						   <?php 													
								foreach($policeStation as $policeStations)
								{
									if (!empty($presentAddress['presentDistrictId']) && $presentAddress['presentDistrictId'] != $policeStations->district_no) {
										continue;
									}

									echo "<option value='".$policeStations->area_id."'";
									if(isset($presentAddress['presentPoliceStationId']))
									{							
										if($presentAddress['presentPoliceStationId']==$policeStations->area_id){ echo "selected ";}						
									}
									echo ">".$policeStations->area_name."</option>";
								}											
							?>
						 </select>
						 <span class='help-inline'><?php echo form_error('mailing_police_station'); ?></span>
					</div>
						
					

					<!--  Post Office  --> 				
					<div class="form-group <?php echo form_error('mailing_post_office') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_mailing_post_office').lang('bf_form_label_required');?></label>
						<div class="input-group">
							<select class="form-control" name="employee_mailing_post_office" id="employee_mailing_post_office" required="" tabindex="12">
						     	<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						  <?php 													
								foreach($postOffice as $postOffices)
								{		
									if (!empty($presentAddress['presentPoliceStationId']) && $presentAddress['presentPoliceStationId'] != $postOffices->area_no) {
										continue;
									}

									echo "<option value='".$postOffices->trt_id."'";
									if(isset($presentAddress['prePostId']))
									{							
										if($presentAddress['prePostId']==$postOffices->trt_id){ echo "selected ";}				
									}
									echo ">".$postOffices->trt_name."</option>";
								}												
							?> 						
							</select>
							<div style="border: 1px solid gray" class="input-group-addon add-new-mailing-post-office btn">
                                <i class="fa fa-plus"></i>
                            </div>
						</div>
						<span class='help-inline'><?php echo form_error('permanent_post_office'); ?></span>
					</div>













						
					<!--  Village  --> 
					<div class="form-group <?php echo form_error('mailing_village') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_mailing_village').lang('bf_form_label_required');?></label>
						<input type='text' class="form-control" id='employee_mailing_village'  name='employee_mailing_village'   maxlength="100" value="<?php echo set_value('mailing_village', isset($presentAddress['presentCityVillage']) ? $presentAddress['presentCityVillage'] : ''); ?>" required="" tabindex="13"/>
						<span class='help-inline'><?php echo form_error('mailing_village'); ?></span>
					</div>
											
					<!--  Mobile No --> 
					<div class="form-group <?php echo form_error('MOBILE') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_mobile_no').lang('bf_form_label_required');?></label>
						<input type='text' class="form-control"  name='employee_mobile_no' id='employee_mobile_no' maxlength="15" value="<?php echo set_value('MOBILE', isset($permanentAddress['mobile']) ? $permanentAddress['mobile'] : ''); ?>"  required="" tabindex="15"/>
						<span class='help-inline'><?php echo form_error('MOBILE'); ?></span>
					</div>
					
					<!--  Alternative Nos --> 
					<div class="form-group <?php echo form_error('ALTERNATIVE_MOBILE') ? 'error' : ''; ?>">
						<label><?php echo lang('employee_alternative_mobile_no');?></label>
						<input type='text' class="form-control" name='employee_alternative_mobile_no' id='employee_alternative_mobile_no'     maxlength="15" value="<?php echo set_value('ALTERNATIVE_MOBILE', isset($permanentAddress['alternative_mobile']) ? $permanentAddress['alternative_mobile'] : ''); ?>" tabindex="16"/>
						<span class='help-inline'><?php echo form_error('ALTERNATIVE_MOBILE'); ?></span>
					</div>						
				</div>
				<!-- End Right Side -->
			<!--</div>--></fieldset>
	<!--</div>-->
	
        <div class="row">	
		<fieldset>			
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 
						
						<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-default"'); ?>
						
						<?php echo lang('bf_or'); ?>
						
						<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save').' '.lang('bf_action_next'); ?>"/>
	
					</div>
				</fieldset>
			
        </div>
     </div>
    <?php echo form_close(); ?>
	

</div>