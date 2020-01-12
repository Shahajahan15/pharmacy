
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

if (isset($movement_details))
{
	$movement_details = (array) $movement_details;


}



?>


<div class="box box-primary">
<?php echo form_open($this->uri->uri_string(), 'role="form" class="nform-horizontal"'); ?>
 
    	<fieldset>
                	<legend>Employee Information</legend>
                			<div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
                                    <!--   age-->
                                    <div class="<?php echo form_error('emp_id') ? 'error' : ''; ?>">
                                        <div class='form-group'>               
                                                <select class="form-control employee-auto-complete" name="emp_id" id="emp_id" required="">
                                               
                                                </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
					

                </fieldset>


			<fieldset>
			<legend>Employee Details</legend>
			<div class="col-md-12 col-sm-12 col-lg-12">
                              
                             

                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                    <div class="<?php echo form_error('emp_name') ? 'error' : ''; ?>">
                                        <div class='form-group'>
                                            <label>Employee Name</label>
                                                <input class="form-control" id='emp_name'  name='emp_name' type='text' value="<?php echo isset($record)?$record->emp_name:''; ?>" readonly="" />
                                        </div>
                                    </div>
                                </div>
                            <!--   mobile / contact -->
                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <div class="<?php echo form_error('emp_mobile') ? 'error' : ''; ?>">
                                <div class='form-group'>
                                    <label>Employee Mobile</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"> +880</i>
                                        </div>
                                        <input type="text" id='emp_mobile'  class="form-control" value="<?php echo isset($record)?$record->customer_mobile:''; ?>" readonly="" />
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!--   Designation -->
                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <div class="<?php echo form_error('designation') ? 'error' : ''; ?>">
                                <div class='form-group'>
                                    <label>Designation</label>
                                    <div class="form-group">
                                        <input type="text" id='designation'  class="form-control" value="<?php echo isset($record)?$record->designation:''; ?>" readonly="" />
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!--   Department -->
                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <div class="<?php echo form_error('emp_department') ? 'error' : ''; ?>">
                                <div class='form-group'>
                                    <label>Department</label>
                                    <div class="form-group">
                                        <input type="text" id='emp_department'  class="form-control" value="<?php echo isset($record)?$record->emp_department:''; ?>" readonly="" />
                                    </div>
                                </div>
                            </div>
                            </div>

          

            </div> 
            </fieldset>
					
			

                                   <fieldset>
                
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label class="control-label">Form Date</label>
                            <input type='text' name='from_date' value="<?php echo set_value('from_date', isset($movement_details->FROM_DATE) ? date('d/m/Y', strtotime($movement_details->FROM_DATE)) : ''); ?>" class="form-control  datepickerCommon" id='from_date'     required="" tabindex="6"/>
							<span class='help-inline'><?php echo form_error('from_date'); ?></span>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label class="control-label">To Date</label>
                            <input type='text' name='to_date' value="<?php echo set_value('to_date', isset($movement_details->TO_DATE) ? date('d/m/Y', strtotime($movement_details->TO_DATE)) : ''); ?>" class="form-control  datepickerCommon" id='to_date'     required="" tabindex="7"/>
							<span class='help-inline'><?php echo form_error('to_date'); ?></span>
                        </div>                        
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="control-label">Movement Purpose</label>
                            <input type='text' name='movement_purpose' value="<?php echo set_value('from_date', isset($movement_details->MOVEMENT_PURPOSE) ? $movement_details->MOVEMENT_PURPOSE : ''); ?>" class="form-control " id='movement_purpose'    required="" tabindex="8"/>
									<span class='help-inline'><?php echo form_error('movement_purpose'); ?></span>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label class="control-label">Destination</label>
                            <input type='text' name='destination' value="<?php echo set_value('from_date', isset($movement_details->DESTINATION) ? $movement_details->DESTINATION : ''); ?>" class="form-control  " id='destination'     required="" tabindex="9"/>
									<span class='help-inline'><?php echo form_error('destination'); ?></span>
                        </div>                        
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label class="control-label">Start Time</label>
                            <input type='text' name='start_time' value="<?php echo set_value('from_date', isset($movement_details->START_TIME) ? $movement_details->START_TIME : ''); ?>" class="form-control " id='start_time'   required="" tabindex="10"/>
									<span class='help-inline'><?php echo form_error('start_time'); ?></span>
                        </div>                        
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label class="control-label">Return Time</label>
                            <input type='text' name='return_time' value="<?php echo set_value('from_date', isset($movement_details->RETURN_TIME) ? $movement_details->RETURN_TIME : ''); ?>" class="form-control " id='return_time'     required="" tabindex="11"/>
									<span class='help-inline'><?php echo form_error('return_time'); ?></span>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label class="control-label">Permitted By</label>
                            <select class="form-control chosenCommon chosen-single" name="permitted_by" id="permitted_by" required=""   tabindex="12" >
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
									<?php 
										 if(isset($employee_details))
										{											
											foreach($employee_details as  $employee_detail ): 
									?> 
											<option value="<?php echo $employee_detail->EMP_ID; ?>" 
											<?php if(isset($movement_details)){
												if($movement_details->PERMITTED_BY ==  $employee_detail->EMP_ID ){
													
													echo 'selected';
												}
											} ?>
											
											><?php echo $employee_detail->EMP_NAME; ?></option>
									<?php 
											endforeach;
										} 
									?>							
								</select>
                        </div>                        
                    </div>
                </fieldset>


        <fieldset>
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                &nbsp;
                <input type="reset" class="btn btn-warning btn-sm" value="Reset"/>
            </div> 
        </fieldset>

<?php echo form_close(); ?>

</div>  