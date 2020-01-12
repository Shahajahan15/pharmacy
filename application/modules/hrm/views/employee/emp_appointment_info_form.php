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

if (isset($appointment_details))
{
	$appointment_details = (array) $appointment_details;
	//echo "<pre>";
	//print_r($appointment_details);
	//die();
}

/** 
* User given date convert to normal view date formate exe- dd/mm/2016
* $givenDate it will get given date.
*/	
function dateConvertForView($givenDate) 
{
	$viewDate = '';
	if($date = explode('-',$givenDate))
	{
		$viewDate  = $date[2].'/'.$date[1].'/'.$date[0];
	}	
	return $viewDate;
}
?>

<div class="row box box-primary">
	<?php echo form_open($this->uri->uri_string(), 'role="form", class=""'); ?>
	<fieldset class="box-body">

	<div class="col-sm-12 col-md-6 col-lg-6"> 
		
		<!-- Employee Visual Id -->
		<div class="form-group <?php echo form_error('emp_visual_id') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_visual_id'), 'emp_visual_id', array('class' => 'control-label') ); ?>
			<div class='control'>				
				<input type='text' name='emp_visual_id' value="<?php echo set_value('emp_visual_id', isset($appointment_details['EMP_VISUAL_ID']) ? $appointment_details['EMP_VISUAL_ID'] : ''); ?>" id='emp_visual_id' class="form-control"  maxlength="100">
				<span class='help-inline'><?php echo form_error('emp_visual_id'); ?></span>
			</div>
		</div>
		
		<!-- File No -->
		<div class="form-group <?php echo form_error('file_no') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_file_no').' '.lang('ENGLISH').'-'.lang('BENGALI'), 'file_no', array('class' => 'control-label') ); ?>
			<div class='control'>
				<span id="checkDuplicateSubjectName" style="color:#F00; font-size:14px;"></span>
				<input type='text' name='file_no' value="<?php echo set_value('file_no', isset($appointment_details['FILE_NO']) ? $appointment_details['FILE_NO'] : ''); ?>" id='file_no' class="form-control"  maxlength="100">
				<span class='help-inline'><?php echo form_error('file_no'); ?></span>
			</div>
		</div>

			<!-- Circular No -->
		<div class="form-group <?php echo form_error('circular_no') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_circular_no'), 'circular_no', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='circular_no' value="<?php echo set_value('circular_no', isset($appointment_details['CIRCULAR_NO']) ? $appointment_details['CIRCULAR_NO'] : ''); ?>" id='circular_no'  class="form-control"  maxlength="100">
				<span class='help-inline'><?php echo form_error('circular_no'); ?></span>
			</div>
		</div>
				
		<!------------ Written Exam Date ----------->
		<div class="form-group <?php echo form_error('written_exam_date') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_written_exam_date'), 'written_exam_date', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='written_exam_date' id='written_exam_date' value="<?php echo set_value('written_exam_date', isset($appointment_details['WRITTEN_EXAM_DATE']) ? dateConvertForView($appointment_details['WRITTEN_EXAM_DATE']) : ''); ?>" class="form-control datepickerCommon"  maxlength="150"/>
				<span class='help-inline'><?php echo form_error('written_exam_date'); ?></span>
			</div>
		</div>

		<!-- Appointment Letter No -->
		<div class="form-group <?php echo form_error('appointment_letter_no') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_appointment_letter_no'), 'appointment_letter_no', array('class' => 'control-label') ); ?>
			<div class='control'>			
				<input type='text' name='appointment_letter_no' value="<?php echo set_value('appointment_letter_no', isset($appointment_details['APPOINTMENT_LETTER_NO']) ? $appointment_details['APPOINTMENT_LETTER_NO'] : ''); ?>" id='appointment_letter_no' class="form-control"  maxlength="100">
				<span class='help-inline'><?php echo form_error('appointment_letter_no'); ?></span>
			</div>
		</div>

		<!------------ Second Reference Person Name ----------->
		<div class="form-group <?php echo form_error('second_reference_person_name') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_reference_person_name'), 'second_reference_person_name', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='second_reference_person_name' id='second_reference_person_name' value="<?php echo set_value('second_reference_person_name', isset($appointment_details['SECOND_REFERENCE_PERSON_NAME']) ? $appointment_details['SECOND_REFERENCE_PERSON_NAME'] : ''); ?>" class="form-control"  maxlength="100"/>
				<span class='help-inline'><?php echo form_error('second_reference_person_name'); ?></span>
			</div>
		</div>
	</div>

	<div class="col-sm-12 col-md-6 col-lg-6"> 
		<!------------ Application No ----------->
		<div class="form-group <?php echo form_error('application_no') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_application_no'), 'application_no', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='application_no' id='application_no' value="<?php echo set_value('application_no', isset($appointment_details['APPLICATION_NO']) ? $appointment_details['APPLICATION_NO'] : ''); ?>" class="form-control"  maxlength="100"/>
				<span class='help-inline'><?php echo form_error('application_no'); ?></span>
			</div>
		</div>

		<!-- Circular Date -->	
		<div class="form-group <?php echo form_error('circular_date') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_circular_date'), 'circular_date', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type="text" name="circular_date" value="<?php echo set_value('circular_date', isset($appointment_details['CIRCULAR_DATE']) ? dateConvertForView($appointment_details['CIRCULAR_DATE']) : '');?>" id="circular_date"   class="form-control datepickerCommon"/>
				<span class='help-inline'><?php echo form_error('circular_date'); ?></span>
			</div>
		</div>
				
		<!------------ Viva Exam Date ----------->
		<div class="form-group <?php echo form_error('viva_exam_date') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_viva_exam_date'), 'viva_exam_date', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='viva_exam_date' id='viva_exam_date' value="<?php echo set_value('viva_exam_date', isset($appointment_details['VIVA_EXAM_DATE']) ? dateConvertForView($appointment_details['VIVA_EXAM_DATE']) : ''); ?>" class="form-control datepickerCommon"  maxlength="150"/>
				<span class='help-inline'><?php echo form_error('viva_exam_date'); ?></span>
			</div>
		</div>
		
		<!------------ First Reference Person Name ----------->
		<div class="form-group <?php echo form_error('first_reference_person_name') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_reference_person_name'), 'first_reference_person_name', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='first_reference_person_name' id='first_reference_person_name' value="<?php echo set_value('first_reference_person_name', isset($appointment_details['FIRST_REFERENCE_PERSON_NAME']) ? $appointment_details['FIRST_REFERENCE_PERSON_NAME'] : ''); ?>" class="form-control"  maxlength="100"/>
				<span class='help-inline'><?php echo form_error('first_reference_person_name'); ?></span>
			</div>
		</div>

		<!------------ First Reference Person Description ----------->
		<div class="form-group <?php echo form_error('first_reference_person_description') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_reference_person_description'), 'first_reference_person_description', array('class' => 'control-label') ); ?>
			<div class='control'>
				<textarea name='first_reference_person_description' id='first_reference_person_description'  class="form-control"  maxlength="200" style="max-height:28px;">  					
				<?php isset($appointment_details['FIRST_REFERENCE_PERSON_DESCRIPTION'])? e($appointment_details['FIRST_REFERENCE_PERSON_DESCRIPTION']):'' ;?>
				</textarea>
				<span class='help-inline'><?php echo form_error('first_reference_person_description'); ?></span>
			</div>
		</div>

		<!------------ Second Reference Person Description ----------->
		<div class="form-group <?php echo form_error('second_reference_person_description') ? 'error' : ''; ?>">
			<?php echo form_label(lang('emp_reference_person_description'), 'second_reference_person_description', array('class' => 'control-label') ); ?>
			<div class='control'>
				<textarea name='second_reference_person_description' id='second_reference_person_description'  class="form-control"  maxlength="200" style="max-height:28px;">  	
				<?php isset($appointment_details['SECOND_REFERENCE_PERSON_DESCRIPTION'])? e(trim($appointment_details['SECOND_REFERENCE_PERSON_DESCRIPTION'])):'' ;?>
				</textarea>
				<span class='help-inline'><?php echo form_error('second_reference_person_description'); ?></span>
			</div>
		</div>
	</div>

	<div class="box-footer pager">
		<?php echo anchor(SITE_AREA .'/employee/hrm/employeeAppointmentInfo', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>					
		&nbsp;			
		<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
	</div> 
	
	</fieldset>
	<?php echo form_close(); ?>
</div>
