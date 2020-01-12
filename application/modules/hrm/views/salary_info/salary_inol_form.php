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
	.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
	<fieldset class="box-body">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading" align="center"> 
                    <h3 class="panel-title">Medical Status</h3>
                </div> <!--panel title end-->
				
				<div class="panel-body">
				
					<div class="col-sm-2 col-md-2 col-lg-2">
					</div>
					
					<div class="col-sm-5 col-md-5 col-lg-5">
						
						<!-- policy Name Start -->
						<div class="form-group <?php echo form_error('POLICY_ID') ? 'error' : ''; ?>">
							<?php echo form_label(lang('POLICY_ID').lang('bf_form_label_required'), 'POLICY_ID', array('class' => 'control-label col-sm-4') ); ?>
							<div class='control col-sm-8 col-md-8 col-lg-8'>
								<select name="POLICY_ID" id="POLICY_ID" class="form-control" required="" tabindex="1">
									<option value="1">ABC</option>
									<option value="2">XYZ</option>
									<option value="3">TYU</option>
								</select>
								<span class='help-inline'><?php echo form_error('POLICY_ID'); ?></span>
							</div>
						</div>
						<!-- policy Name End -->
						
						<!-- Medical Rule Start -->
						<div class="form-group <?php echo form_error('POLICY_ID') ? 'error' : ''; ?>">
							<?php echo form_label(lang('POLICY_ID').lang('bf_form_label_required'), 'POLICY_ID', array('class' => 'control-label col-sm-4') ); ?>
							<div class='control col-sm-8 col-md-8 col-lg-8'>
								<select name="POLICY_ID" id="POLICY_ID" class="form-control" required="" tabindex="1">
									<option value="1">ABC</option>
									<option value="2">XYZ</option>
									<option value="3">TYU</option>
								</select>
								<span class='help-inline'><?php echo form_error('POLICY_ID'); ?></span>
							</div>
						</div>
						<!-- Medical Rule End -->
						
						
						<!-- Starting Date Start -->
						<div class="form-group <?php echo form_error('START_DATE') ? 'error' : ''; ?>">
							<?php echo form_label(lang('START_DATE').lang('bf_form_label_required'), 'START_DATE', array('class' => 'control-label col-sm-4') ); ?>
							<div class='control col-sm-8 col-md-8 col-lg-8'>
								<input type="text" name="START_DATE" value="<?php echo set_value('START_DATE', isset($emp_training_details['START_DATE']) ? $emp_training_details['START_DATE'] : '');?>" id="START_DATE" class="form-control datepickerCommon"    placeholder="<?php e(lang('START_DATE'));?>" title="<?php e(lang('START_DATE'));?>" required="" tabindex="2"/>
								<span class='help-inline'><?php echo form_error('START_DATE'); ?></span>
							</div>
						</div>
						<!-- Starting Date End-->


						<!-- Ending Date Start -->
						<div class="form-group <?php echo form_error('END_DATE') ? 'error' : ''; ?>">
							<?php echo form_label(lang('END_DATE').lang('bf_form_label_required'), 'END_DATE', array('class' => 'control-label col-sm-4') ); ?>
							<div class='control col-sm-8 col-md-8 col-lg-8'>
								<input type="text" name="END_DATE" value="<?php echo set_value('END_DATE', isset($emp_training_details['END_DATE']) ? $emp_training_details['END_DATE'] : '');?>" id="END_DATE" class="form-control datepickerCommon"  placeholder="<?php e(lang('END_DATE'));?>" title="<?php e(lang('END_DATE'));?>" required="" tabindex="3"/>
								<span class='help-inline'><?php echo form_error('END_DATE'); ?></span>
							</div>
						</div>
						<!-- Ending Date End-->	
						

						<!-- Lunch Status Start -->
						<div class="form-group <?php echo form_error('LUNCH') ? 'error' : ''; ?>">
							<?php echo form_label(lang('LUNCH').lang('bf_form_label_required'), 'LUNCH', array('class' => 'control-label col-sm-4') ); ?>
							<div class='control col-sm-8 col-md-8 col-lg-8'>
								<input type="radio" name="LUNCH" value="1" checked> <label><?php echo lang('YES');?></label>
								<input type="radio" name="LUNCH" value="0"> <label><?php echo lang('NO');?></label>
								<span class='help-inline'><?php echo form_error('LUNCH'); ?></span>
							</div>
						</div>
						<!-- Lunch Status End -->
						
						
	
					</div> <!-- form main column end -->
					
					<div class="col-md-10"> 
						<div class="col-md-12 box-footer pager">
							<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save').lang('bf_action_next'); ?>"/>
							<?php echo lang('bf_or'); ?>
							<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
						</div>
					</div>
					
					<div class="col-sm-2 col-md-2 col-lg-2">
					</div>
					
				</div> <!-- panel body end -->			
			</div> <!-- panel end -->
		</div> <!-- main column end -->  
    <?php echo form_close(); ?>
	</fieldset>
</div>