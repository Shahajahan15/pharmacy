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

if (isset($subject_details))
{
	$subject_details = (array) $subject_details;	
}
?>

<div class="row box box-primary">
<?php echo form_open($this->uri->uri_string(), 'role="form", class=""'); ?>
<fieldset class="box-body">
	<div class="col-sm-12 col-md-6 col-lg-6 col-md-offset-3">  
		
		<!-- Subject English-->
		<div class="form-group <?php echo form_error('subject') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_subject').' '. lang('ENGLISH').lang('bf_form_label_required'), 'subject', array('class' => 'control-label') ); ?>
			<div class='control'>
				<span id="checkDuplicateSubjectName" style="color:#F00; font-size:14px;"></span>
				<input type='text' name='subject' id='subject' value="<?php echo set_value('subject', isset($subject_details['SUBJECT']) ? $subject_details['SUBJECT'] : '');  ?>"  class="form-control"  maxlength="100"  tabindex="1" required  onkeyup="getSubjectName(this)" >
				<span class='help-inline'><?php echo form_error('subject'); ?></span>
			</div>
		</div>
		
		<!-- Subject Bengali -->
		<div class="form-group <?php echo form_error('subject_bengali') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_subject').' ' .lang('BENGALI'), 'subject_bengali', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='subject_bengali' id='subject_bengali' value="<?php echo set_value('subject_bengali', isset($subject_details['SUBJECT_BENGALI']) ? $subject_details['SUBJECT_BENGALI'] : '');  ?>" class="form-control bn_language"  maxlength="100"  tabindex="2" >
				<span class='help-inline'><?php echo form_error('subject_bengali'); ?></span>
			</div>
		</div>
				
		<!-- Code -->	
		<div class="form-group <?php echo form_error('code') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_code'), 'code', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='code' id='code' value="<?php echo set_value('code', isset($subject_details['CODE']) ? $subject_details['CODE'] : ''); ?>" class="form-control"  maxlength="100" tabindex="3">
				<span class='help-inline'><?php echo form_error('code'); ?></span>
			</div>
		</div>
									
		<!------------ Remarks English ----------->
		<div class="form-group <?php echo form_error('remarks') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_remarks').' '.lang('ENGLISH'), 'remarks', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='remarks' id='remarks' value="<?php echo set_value('remarks', isset($subject_details['REMARKS']) ? $subject_details['REMARKS'] : ''); ?>" class="form-control"  maxlength="150" tabindex="4"/>
				<span class='help-inline'><?php echo form_error('remarks'); ?></span>
			</div>
		</div>
		
		<!------------ Remarks Bengali ----------->
		<div class="form-group <?php echo form_error('remarks_bengali') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_remarks').' '. lang('BENGALI'), 'remarks_bengali', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='remarks_bengali' id='remarks_bengali' value="<?php echo set_value('remarks_bengali', isset($subject_details['REMARKS_BENGALI']) ? $subject_details['REMARKS_BENGALI'] : ''); ?>" class="form-control bn_language"  maxlength="150" tabindex="5"/>
				<span class='help-inline'><?php echo form_error('remarks_bengali'); ?></span>
			</div>
		</div>
		
		<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_subject_status'). lang('bf_form_label_required'), 'status', array('class' => 'control-label') ); ?>
			<div class='control'>
				<select name="status" class="form-control" required tabindex="8">
					<option value=""><?php echo lang('bf_msg_selete_one')?></option> 
					<option value="1" <?php  if(isset($subject_details['STATUS'])){if($subject_details['STATUS']==1){echo "selected";}} ?>>Active </option>	
					<option value="0" <?php  if(isset($subject_details['STATUS'])){if($subject_details['STATUS']==0){echo "selected";}} ?>>In Active </option>					
				</select>
				<span class='help-inline'><?php echo form_error('status'); ?></span>
			</div>
		</div>
		
		<div class="box-footer pager">
			<?php echo anchor(SITE_AREA .'/subject_create/library/create', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>					
			&nbsp;			
			<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
		</div>               

	</div>
</fieldset>

<?php echo form_close(); ?>

</div>
