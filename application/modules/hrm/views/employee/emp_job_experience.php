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
.padding-left-div{margin-left:23px; }
</style>

<?php echo form_open($this->uri->uri_string(), 'role="form", id="job_experience_form" class="form-horizontal"'); ?>
	<fieldset class="box box-primary box-body">
	<div class="row">
		 
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">			
			<!-- -----------Job Experience/ Organization Name ------------------- --> 
			<div class="form-group <?php echo form_error('ORGANIZATION') ? 'error' : ''; ?>">
				<label><?php echo lang('ORGANIZATION').' '.lang('ENGLISH').lang('bf_form_label_required');?></label>
				<input type='text' name='ORGANIZATION' value="" id='ORGANIZATION' class="form-control" maxlength="150"  required tabindex="1"/>
				<span class='help-inline'><?php echo form_error('ORGANIZATION'); ?></span>
			</div>		
		</div>
				
		<!-- Optional: clear the XS cols if their content doesn't match in height -->
		<div class="clearfix visible-xs-block"></div>
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ ORGANIZATION_ADDRESS ---------------- -->               
			<div class="form-group <?php echo form_error('ORGANIZATION_ADDRESS') ? 'error' : ''; ?>">
				<label><?php echo lang('ORGANIZATION_ADDRESS').' '. lang('ENGLISH');?></label>
					<textarea  name='ORGANIZATION_ADDRESS' id='ORGANIZATION_ADDRESS' class="form-control" rows="1" cols="50" tabindex="5"  maxlength="150" style="max-height:28px;"></textarea>
				<span class='help-inline'><?php echo form_error('ORGANIZATION_ADDRESS'); ?></span>
			</div>
		</div>
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ YEAR START --------------- -->
			<div class="<?php echo form_error('YEAR_START') ? 'error' : ''; ?>">
				<div class='form-group'>
					<label><?php echo lang('YEAR_START').lang('bf_form_label_required');?></label>
					<input type="text" name="YEAR_START" value="" id="YEAR_START" class="form-control datepickerCommon" title="<?php e(lang('YEAR_START'));?>" required tabindex="9"/>
				</div>
			</div>
		</div>

	</div>  
	   
		
	<div class="row">
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">		
			<!-- -----------Job Experience/ Organization Name In Bengali ------------------- --> 
			<div class="form-group <?php echo form_error('ORGANIZATION_BENGALI') ? 'error' : ''; ?>">
				<label><?php echo lang('ORGANIZATION').' '.lang('BENGALI');?></label>
				<input type='text' name='ORGANIZATION_BENGALI' value="" id='ORGANIZATION_BENGALI' class="form-control" maxlength="150" tabindex="2"/>
				<span class='help-inline'><?php echo form_error('ORGANIZATION_BENGALI'); ?></span>
			</div>
		</div>
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ ORGANIZATION_ADDRESS IN BENGALI ---------------- -->               
			<div class="form-group <?php echo form_error('ORGANIZATION_ADDRESS_BENGALI') ? 'error' : ''; ?>">
				<label><?php echo lang('ORGANIZATION_ADDRESS').' '. lang('BENGALI');?></label>
					<textarea  name='ORGANIZATION_ADDRESS_BENGALI' id='ORGANIZATION_ADDRESS_BENGALI' class="form-control" rows="1" cols="50" tabindex="6"  maxlength="150" style="max-height:28px;"></textarea>
				<span class='help-inline'><?php echo form_error('ORGANIZATION_ADDRESS_BENGALI'); ?></span>
			</div>
		</div>
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ YEAR END --------------- -->
			<div class="<?php echo form_error('YEAR_END') ? 'error' : ''; ?>">
				<div class='form-group'>
					<label><?php echo lang('YEAR_END').lang('bf_form_label_required');?></label>
					<input type="text" name="YEAR_END" value="" id="YEAR_END" class="form-control datepickerCommon" title="<?php e(lang('YEAR_END'));?>" required tabindex="10"/>
					<span class='help-inline'><?php echo form_error('YEAR_END'); ?></span>
				</div>
			</div>	
		</div>
		
		<!-- Optional: clear the XS cols if their content doesn't match in height -->
		<div class="clearfix visible-xs-block"></div>
				
	</div>  			
					
					
	<div class="row">
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ Position ------------- -->          				
			<div class="form-group <?php echo form_error('POSITION') ? 'error' : ''; ?>">
				<label><?php echo lang('POSITION').lang('bf_form_label_required');?></label>
				<select class="form-control" name="POSITION" id="POSITION" required="" tabindex="2">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					
					<?php 
					foreach($deignation_list as $designation)
					{	
						echo "<option value='".$designation->DESIGNATION_ID."'";	
											
						echo ">".$designation->DESIGNATION_NAME."</option>";
					}
					?>
				</select>
				 
				 <span class='help-inline'><?php echo form_error('POSITION'); ?></span>
			</div>
		</div>
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ CONTACT PERSON ENGLISH ------------ -->               
			<div class="form-group <?php echo form_error('CONTACT_PERSON') ? 'error' : ''; ?>">
				<label><?php echo lang('CONTACT_PERSON').' '.lang('ENGLISH') ;?></label>
				<input type='text' name='CONTACT_PERSON' value="" id='CONTACT_PERSON' class="form-control" maxlength="50"  required tabindex="7"/>
				<span class='help-inline'><?php echo form_error('CONTACT_PERSON'); ?></span>
			</div>
		</div>
				  			
		<!-- Optional: clear the XS cols if their content doesn't match in height -->
		<div class="clearfix visible-xs-block"></div>
	  			
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">			
			<!-- ----------- Job Experience/ REASON FOR LEAVING ---------------- -->               
			<div class="form-group <?php echo form_error('REASON_FOR_LEAVING') ? 'error' : ''; ?>">
				<label><?php echo lang('REASON_FOR_LEAVING').' '. lang('ENGLISH').lang('bf_form_label_required');?></label>
				<textarea  name='REASON_FOR_LEAVING' id='REASON_FOR_LEAVING' class="form-control" rows="1" cols="50"  required="" tabindex="11"  maxlength="50" style="max-height:28px;"></textarea>
				<span class='help-inline'><?php echo form_error('REASON_FOR_LEAVING'); ?></span>
			</div>		
		</div>
		
	</div>				
					
									
	<div class="row">
	
		<!-- Optional: clear the XS cols if their content doesn't match in height -->
		<div class="clearfix visible-xs-block"></div>
	  
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ CONTACT Number ------------ -->               
			<div class="form-group <?php echo form_error('CONTACT_NUMBER') ? 'error' : ''; ?>">
				<label><?php echo lang('CONTACT_NUMBER');?></label>
				<input type='text' name='CONTACT_NUMBER' value="" id='CONTACT_NUMBER' class="form-control" maxlength="15" tabindex="4"/>
				<span class='help-inline'><?php echo form_error('CONTACT_NUMBER'); ?></span>
			</div>						
		</div>
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- Job Experience/ CONTACT PERSON IN BENGALI ------------ -->               
			<div class="form-group <?php echo form_error('CONTACT_PERSON_BENGALI') ? 'error' : ''; ?>">
				<label><?php echo lang('CONTACT_PERSON') .' '. lang('BENGALI') ;?></label>
				<input type='text' name='CONTACT_PERSON_BENGALI' value="" id='CONTACT_PERSON_BENGALI' class="form-control" maxlength="50" tabindex="8"/>
				<span class='help-inline'><?php echo form_error('CONTACT_PERSON_BENGALI'); ?></span>
			</div>
		</div>
		
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">		
			<!-- ----------- Job Experience/ Reason For Leaving In Bengali ---------------- -->               
			<div class="form-group <?php echo form_error('REASON_FOR_LEAVING_BENGALI') ? 'error' : ''; ?>">
				<label><?php echo lang('REASON_FOR_LEAVING').' '.lang('BENGALI');?></label>
				<textarea  name='REASON_FOR_LEAVING_BENGALI' id='REASON_FOR_LEAVING_BENGALI' class="form-control" rows="1" cols="50"  tabindex="12"  maxlength="50" style="max-height:28px;"></textarea>
				<span class='help-inline'><?php echo form_error('REASON_FOR_LEAVING_BENGALI'); ?></span>
			</div>
		</div>
		
	</div>				

	<div class="row">
		<div class="col-md-8 col-md-offset-4">
			<div class="col-xs-6 col-sm-4">
			<!-- hidden value pass -->
			<input type='hidden' name='EMP_JOB_EXP_ID' value=""  id='EMP_JOB_EXP_ID' />			
			<span onclick="showJobexperience(<?php echo  $employeeId = (int)$this->uri->segment(5);?>)"  class="btn btn-primary-add">View </span>
			&nbsp; &nbsp; &nbsp;
			<span onclick="addJobexperience(<?php echo  $employeeId = (int)$this->uri->segment(5);?>)" id="add_employee_jobexperience" class="btn btn-primary-add">ADD </span>
		</div>
		</div>
	</div>


					
	<div class="row">					
		<div class="col-md-12">
			<div>
				<table class="table table-striped">
					<tbody id="employeeJobExperienceInnerHTML">
						
					</tbody>							
				</table>					
			</div>
		</div>
	</div>	
			   
	<div class="row">					
		<div class="col-md-12"> 
			<div class="col-md-10 box-footer pager">
				<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-default"'); ?>
				
				<?php echo lang('bf_or'); ?>
				
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save').' '.lang('bf_action_next'); ?>"/>
				
			</div>
		</div>
	</div>
	
</fieldset>   
<?php echo form_close(); ?>
	

