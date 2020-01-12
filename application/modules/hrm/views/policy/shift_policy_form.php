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
	if (isset($shift_details)){
		$shift_details = (array) $shift_details;
		// print_r($shift_details);
	}
	$SHIFT_ID = isset($shift_details['SHIFT_ID']) ? $shift_details['SHIFT_ID'] : '';
	?>
	<style>
		.box-c{width: auto;margin-bottom: 0px;}
		.new-row{margin-left: 5px;margin-right: 5px;}
		.input-group-addon{padding: 5px 12px;}
	</style>

	<div class="row box box-primary box-c">

		<?php echo form_open($this->uri->uri_string(), 'id="shiftPolicyFrm", role="form", class="nform-horizontal", onsubmit=""'); ?>
		
		<fieldset>
			<legend>Shift Policy</legend>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class=row>
					<div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4">
						<!-- Policy / Shift Name --> 
						<div class="form-group <?php echo form_error('SHIFT_NAME') ? 'error' : ''; ?>">
							<?php echo form_label(lang('SHIFT_NAME'). lang('bf_form_label_required'), 'SHIFT_NAME', array('class' => 'control-label') ); ?>
							<input type='text' name='SHIFT_NAME' value="<?php echo set_value('SHIFT_NAME', isset($shift_details['SHIFT_NAME']) ? $shift_details['SHIFT_NAME'] : ''); ?>" placeholder="<?php echo lang('SHIFT_NAME')?>" id='SHIFT_NAME' class="form-control" maxlength="100" required tabindex="1"/>
							<span class='help-inline'><?php echo form_error('SHIFT_NAME'); ?></span>
						</div>
					</div>
				</div>
				<div class="row new-row">
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Shift Type  --> 
						<div class="form-group <?php echo form_error('SHIFT_TYPE') ? 'error' : ''; ?>">
							<?php echo form_label(lang('SHIFT_TYPE'). lang('bf_form_label_required'), 'SHIFT_TYPE', array('class' => 'control-label') ); ?>
							<select class="form-control" name="SHIFT_TYPE" id="SHIFT_TYPE" required="" tabindex="2">
								<option value=""><?php echo lang('bf_msg_selete_one');?></option>

								<?php 
								foreach($shift_types as $key => $val)
								{
									echo "<option value='".$key."'";

									echo ">".$val."</option>";
								}
								?>			
							</select>
							<span class='help-inline'><?php echo form_error('SHIFT_TYPE'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Shift Starts  -->
						<div class="form-group <?php echo form_error('SHIFT_STARTS') ? 'error' : ''; ?>">	
							<?php echo form_label(lang('SHIFT_STARTS'). lang('bf_form_label_required'), 'SHIFT_STARTS', array('class' => 'control-label') ); ?>					
							<div class="input-group bootstrap-timepicker">
								<input type="text" name="SHIFT_STARTS" value="<?php echo set_value('SHIFT_STARTS', isset($shift_details['SHIFT_STARTS']) ? $shift_details['SHIFT_STARTS'] : '');?>" id="SHIFT_STARTS" class="form-control timepickerCommon" placeholder="<?php echo lang('hms')?>" title="<?php e(lang('SHIFT_STARTS'));?>" required="" tabindex="3"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('SHIFT_STARTS'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Shift Ends -->               
						<div class="form-group <?php echo form_error('SHIFT_ENDS') ? 'error' : ''; ?>">
							<?php echo form_label(lang('SHIFT_ENDS'). lang('bf_form_label_required'), 'SHIFT_ENDS', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='SHIFT_ENDS' value="<?php echo set_value('SHIFT_ENDS', isset($shift_details['SHIFT_ENDS']) ? $shift_details['SHIFT_ENDS'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='SHIFT_ENDS' class="form-control timepickerCommon" required tabindex="4" />
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('SHIFT_ENDS'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Late Marking Starts --> 
						<div class="form-group <?php echo form_error('LATE_MARKING_STARTS') ? 'error' : ''; ?>">	
							<?php echo form_label(lang('LATE_MARKING_STARTS'). lang('bf_form_label_required'), 'SHIFT_STARTS', array('class' => 'control-label') ); ?>					
							<div class="input-group bootstrap-timepicker timepicker">
								<input type="text" name="LATE_MARKING_STARTS" value="<?php echo set_value('LATE_MARKING_STARTS', isset($shift_details['LATE_MARKING_STARTS']) ? $shift_details['LATE_MARKING_STARTS'] : '');?>" id="LATE_MARKING_STARTS" class="form-control timepickerCommon" placeholder="<?php echo lang('hms')?>" title="<?php e(lang('LATE_MARKING_STARTS'));?>" required="" tabindex="5"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('LATE_MARKING_STARTS'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!--  Policy / Entry Restriction Start - Start    -->               
						<div class="form-group <?php echo form_error('ENTRY_RESTRICTION_STARTS') ? 'error' : ''; ?>">
							<?php echo form_label(lang('ENTRY_RESTRICTION_STARTS'). lang('bf_form_label_required'), 'ENTRY_RESTRICTION_STARTS', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='ENTRY_RESTRICTION_STARTS' value="<?php echo set_value('ENTRY_RESTRICTION_STARTS', isset($shift_details['ENTRY_RESTRICTION_STARTS']) ? $shift_details['ENTRY_RESTRICTION_STARTS'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='ENTRY_RESTRICTION_STARTS' class="form-control timepickerCommon" required tabindex="6"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('ENTRY_RESTRICTION_STARTS'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Lunch Break Starts  -->               
						<div class="form-group <?php echo form_error('LUNCH_BREAK_STARTS') ? 'error' : ''; ?>">
							<?php echo form_label(lang('LUNCH_BREAK_STARTS'), 'LUNCH_BREAK_STARTS', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='LUNCH_BREAK_STARTS' value="<?php echo set_value('LUNCH_BREAK_STARTS', isset($shift_details['LUNCH_BREAK_STARTS']) ? $shift_details['LUNCH_BREAK_STARTS'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='LUNCH_BREAK_STARTS' class="form-control timepickerCommon" tabindex="7"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('LUNCH_BREAK_STARTS'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!--  Policy / Lunch Break Ends - Start  -->               
						<div class="form-group <?php echo form_error('LUNCH_BREAK_ENDS') ? 'error' : ''; ?>">                 
							<?php echo form_label(lang('LUNCH_BREAK_ENDS'), 'LUNCH_BREAK_ENDS', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='LUNCH_BREAK_ENDS' value="<?php echo set_value('LUNCH_BREAK_ENDS', isset($shift_details['LUNCH_BREAK_ENDS']) ? $shift_details['LUNCH_BREAK_ENDS'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='LUNCH_BREAK_ENDS' class="form-control timepickerCommon" tabindex="8"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('LUNCH_BREAK_ENDS'); ?></span>
						</div>	
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Extra Break Starts -->               
						<div class="form-group <?php echo form_error('EXTRA_BREAK_STARTS') ? 'error' : ''; ?>">
							<?php echo form_label(lang('EXTRA_BREAK_STARTS'), 'EXTRA_BREAK_STARTS', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='EXTRA_BREAK_STARTS' value="<?php echo set_value('EXTRA_BREAK_STARTS', isset($shift_details['EXTRA_BREAK_STARTS']) ? $shift_details['EXTRA_BREAK_STARTS'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='EXTRA_BREAK_STARTS' class="form-control timepickerCommon" tabindex="9"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('EXTRA_BREAK_STARTS'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Extra Break Ends - Start  -->               
						<div class="form-group <?php echo form_error('EXTRA_BREAK_ENDS') ? 'error' : ''; ?>">
							<?php echo form_label(lang('EXTRA_BREAK_ENDS'), 'EXTRA_BREAK_ENDS', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='EXTRA_BREAK_ENDS' value="<?php echo set_value('EXTRA_BREAK_ENDS', isset($shift_details['EXTRA_BREAK_ENDS']) ? $shift_details['EXTRA_BREAK_ENDS'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='EXTRA_BREAK_ENDS' class="form-control timepickerCommon" tabindex="10"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('EXTRA_BREAK_ENDS'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Exit Buffer Time -->               
						<div class="form-group <?php echo form_error('EXIT_BUFFER_TIME') ? 'error' : ''; ?>">                 
							<?php echo form_label(lang('EXIT_BUFFER_TIME'). lang('bf_form_label_required'), 'EXIT_BUFFER_TIME', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='EXIT_BUFFER_TIME' value="<?php echo set_value('EXIT_BUFFER_TIME', isset($shift_details['EXIT_BUFFER_TIME']) ? $shift_details['EXIT_BUFFER_TIME'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='EXIT_BUFFER_TIME' class="form-control timepickerCommon" required tabindex="11"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('EXIT_BUFFER_TIME'); ?></span>
						</div>
					</div>
					<div class="col-sm-3 col-md-2 col-lg-2">
						<!-- Policy / Early out Starts -->               
						<div class="form-group <?php echo form_error('EARLY_OUT_STARTS') ? 'error' : ''; ?>">
							<?php echo form_label(lang('EARLY_OUT_STARTS'). lang('bf_form_label_required'), 'EARLY_OUT_STARTS', array('class' => 'control-label') ); ?>
							<div class="input-group bootstrap-timepicker timepicker">
								<input type='text' name='EARLY_OUT_STARTS' value="<?php echo set_value('EARLY_OUT_STARTS', isset($shift_details['EARLY_OUT_STARTS']) ? $shift_details['EARLY_OUT_STARTS'] : ''); ?>" placeholder="<?php echo lang('hms')?>" id='EARLY_OUT_STARTS' class="form-control timepickerCommon" required tabindex="12"/>
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<span class='help-inline'><?php echo form_error('EARLY_OUT_STARTS'); ?></span>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<!-- Policy / Shift policy Description --> 
						<div class="form-group <?php echo form_error('DESCRIPTION') ? 'error' : ''; ?>">
							<?php echo form_label(lang('DESCRIPTION'), 'DESCRIPTION', array('class' => 'control-label') ); ?>			
							<input type='text' name='DESCRIPTION' value="<?php echo set_value('DESCRIPTION', isset($shift_details['DESCRIPTION']) ? $shift_details['DESCRIPTION'] : ''); ?>" placeholder="<?php echo lang('DESCRIPTION')?>" id='DESCRIPTION' class="form-control" maxlength="100"  tabindex="13"/>							
							<span class='help-inline'><?php echo form_error('DESCRIPTION'); ?></span>
						</div>
					</div>
					<div class="col-sm-2 col-md-2 col-lg-2">
						<!-- Policy / Shift Type --> 
						<div class="form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">
							<?php echo form_label(lang('STATUS'). lang('bf_form_label_required'), 'STATUS', array('class' => 'control-label') ); ?>
							<select class="form-control" name="STATUS" id="STATUS" required="" tabindex="14">

								<?php 
								foreach($status as $key => $val)
								{
									echo "<option value='".$key."'";

									echo ">".$val."</option>";
								}
								?>

							</select>
							<span class='help-inline'><?php echo form_error('STATUS'); ?></span>
						</div>
					</div>
				</div>

			</fieldset>
			<fieldset>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">	
					<input type='hidden' name='SHIFT_POLICY_ID_TARGET' value="" id='SHIFT_POLICY_ID_TARGET' />		
					<a name="reset" class="btn btn-warning btn-xs" onclick="resetShift()">Reset</a>														
					<a href="javascript:void(0)" onclick="addShiftPolicy()" class="btn btn-primary btn-xs btn-mlm ">Save</a>
				</div>
			</fieldset>

			<?php echo form_close(); ?>

		</div>