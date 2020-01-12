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
if (isset($emp_training_details)){
	 $emp_training_details = (array) $emp_training_details;
	// print_r($emp_training_details);
}
$EMP_TRAINING_ID = isset($emp_training_details['EMP_TRAINING_ID']) ? $emp_training_details['EMP_TRAINING_ID'] : '';
?>
<style>
.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", id="training_info_form" class="form-horizontal"'); ?>

    <fieldset class="box-body">
	<div class="col-sm-12 col-md-12 col-lg-12">
	
		<div class="panel panel-primary">
			<div class="panel-heading" align="center">
				<h3 class="panel-title">Employee Training Info</h3>
			</div>
					
		<div class="panel-body">		
			
			<div class="col-sm-3 col-md-3 col-lg-3 ">
				<!-- to element centre -->
			</div>
			
			<div class="col-sm-6 col-md-6 col-lg-6">	
				
				<!-- ----------- Training Type ------------------- -->  	
				<div class="form-group <?php echo form_error('TRAINING') ? 'error' : ''; ?>">
					<label><?php echo lang('TRAINING');?></label>
					<select name="TRAINING" id="TRAINING" class="form-control" required="" tabindex="25">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
						
							foreach($trainingType as $v_trainingType)
							{
								echo "<option value='".$v_trainingType->TRAINING_TYPE_ID."'";
								
								echo ">".$v_trainingType->TRAINING_TYPE_NAME."</option>";
							}
						
						?>
					</select>
					<span class='help-inline'><?php echo form_error('TRAINING'); ?></span>
				</div>
				
				<!-- ----------- Training / Conducted By ------------- --> 
                <div class="form-group <?php echo form_error('CONDUCTED_BY') ? 'error' : ''; ?>">
                    <label><?php echo lang('CONDUCTED_BY').' '.lang('ENGLISH');?></label>
                    <input type='text' name='CONDUCTED_BY' value="" class="form-control" id='CONDUCTED_BY' maxlength="100" tabindex="2"/>
                    <span class='help-inline'><?php echo form_error('CONDUCTED_BY'); ?></span>
                </div>
				
				<!-- ----------- Training / Conducted By In Bengali ------------- --> 
                <div class="form-group <?php echo form_error('CONDUCTED_BY_BENGALI') ? 'error' : ''; ?>">
                    <label><?php echo lang('CONDUCTED_BY').' '.lang('BENGALI');?></label>
                    <input type='text' name='CONDUCTED_BY_BENGALI' value="" class="form-control" id='CONDUCTED_BY_BENGALI' maxlength="100" tabindex="3"/>
                    <span class='help-inline'><?php echo form_error('CONDUCTED_BY_BENGALI'); ?></span>
                </div>
				
				<!-- ----------- Training / COMPLETION DATE --------------- -->
				<div class="<?php echo form_error('COMPLETION_DATE') ? 'error' : ''; ?>">
					<div class='form-group'>
						<label><?php echo lang('COMPLETION_DATE').lang('bf_form_label_required');?></label>
						<input type="text" name="COMPLETION_DATE" value="" id="COMPLETION_DATE" class="form-control datepickerCommon" title="<?php e(lang('COMPLETION_DATE'));?>" required="" tabindex="4"/>
					</div>
				</div>
				
				<!-- ----------- Job Experience/ CERTIFICATE FLAG (1 = Yes, 0 = No)--------------- -->
				<div class="form-group <?php echo form_error('CERTIFICATE_FLAG') ? 'error' : ''; ?>">  
					<label><?php echo lang('CERTIFICATE_FLAG');?></label>
					&nbsp; &nbsp; &nbsp;
					<input type="checkbox" name="CERTIFICATE_FLAG" id="CERTIFICATE_FLAG" value="1" tabindex="5"> 					
                    <span class='help-inline'><?php echo form_error('CERTIFICATE_FLAG'); ?></span>
                </div>
				
				<input type='hidden' name='EMP_TRAINING_ID' value=""  id='EMP_TRAINING_ID' />
				<span onclick="showTraining(<?php echo  $employeeId = (int)$this->uri->segment(5);?>)" class="btn btn-primary-add">View </span>
				&nbsp; &nbsp; &nbsp;
				<span onclick="addTraining(<?php echo  $employeeId = (int)$this->uri->segment(5);?>)" id="add_employee_training" class="btn btn-primary-add">Add </span>	
			
			</div> 
			
			<div class="col-sm-3  col-md-3 col-lg-3">
				<!-- to element center -->
			</div>
			
		
			<div class="col-md-12">
				<div>
				<table class="table table-striped">
					<tbody id="employeeTrainingInnerHTML">
						<!--Ajax data goes here if responds is successful -->
					</tbody>							
				</table>
					
				</div>
            </div> 
			
		</div>  <!-- panel body end -->
		</div>	<!-- for panel end -->
		
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
</div>