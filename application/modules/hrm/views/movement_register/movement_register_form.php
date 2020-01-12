<style>
.searchPanelDiv{
	max-height:250px;
	overflow-y:auto;
}
.table-responsive
{
    overflow-x: auto;
}
</style>

<?php if(isset($SendData)) extract($SendData); ?>
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

/* if (isset($movement_details))
{
	$movement_details = (array) $movement_details;
} */

?>

<div class="row box box-primary">
	<?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
	<fieldset class="box-body">
		<div class="col-sm-12 col-md-12 col-lg-12">
		
		
			
			<div class="panel-group " id="accordion" role="tablist" aria-multiselectable="true">
                <?php echo $this->load->view('emp_search_for_movement', $_REQUEST, TRUE); ?>
			</div>
			 
			 
				<div class="col-md-12">
					
						<div class="col-sm-12  col-md-8 col-lg-8">
							<div class="col-sm-12  col-md-5  col-lg-5">
								<div class="form-group ">
									<label for="from_date" class=""><?php  echo lang('from_date'); ?></label> 
									
								</div>
							</div>
							<div class="col-sm-6  col-md-7 col-lg-7 ">
								<div class="form-group ">
									<input type='text' name='from_date' value="<?php echo set_value('from_date', isset($movement_details->FROM_DATE) ? date('d/m/Y', strtotime($movement_details->FROM_DATE)) : ''); ?>" class="form-control  datepickerCommon" id='from_date'     required="" tabindex="6"/>
									<span class='help-inline'><?php echo form_error('from_date'); ?></span>
								</div>
							</div>
							<div class="col-sm-12  col-md-5  col-lg-5">
								<div class="form-group ">
									<label for="to_date" class=" "><?php  echo lang('to_date'); ?></label>
									
								</div>
							</div>
							<div class="col-sm-6  col-md-7 col-lg-7 ">
								<div class="form-group ">
									<input type='text' name='to_date' value="<?php echo set_value('to_date', isset($movement_details->TO_DATE) ? date('d/m/Y', strtotime($movement_details->TO_DATE)) : ''); ?>" class="form-control  datepickerCommon" id='to_date'     required="" tabindex="7"/>
								<span class='help-inline'><?php echo form_error('to_date'); ?></span>
								</div>
							</div>
							<div class="col-sm-12  col-md-5  col-lg-5">
								<div class="form-group ">
									<label for="movement_purpose" class=""><?php  echo lang('movement_purpose'); ?></label>
									
								</div>
							</div>
							<div class="col-sm-6  col-md-7 col-lg-7 ">
								<div class="form-group ">
									<input type='text' name='movement_purpose' value="<?php echo set_value('from_date', isset($movement_details->MOVEMENT_PURPOSE) ? $movement_details->MOVEMENT_PURPOSE : ''); ?>" class="form-control " id='movement_purpose'    required="" tabindex="8"/>
									<span class='help-inline'><?php echo form_error('movement_purpose'); ?></span>
								</div>
							</div>
							<div class="col-sm-12  col-md-5  col-lg-5">
								<div class="form-group ">
									<label for="destination" class=""><?php  echo lang('destination'); ?></label> 
									
								</div>
							</div>
							<div class="col-sm-6  col-md-7 col-lg-7 ">
								<div class="form-group ">
									<input type='text' name='destination' value="<?php echo set_value('from_date', isset($movement_details->DESTINATION) ? $movement_details->DESTINATION : ''); ?>" class="form-control  " id='destination'     required="" tabindex="9"/>
									<span class='help-inline'><?php echo form_error('destination'); ?></span>
								</div>
							</div>
							<div class="col-sm-12  col-md-5  col-lg-5">
								<div class="form-group ">
									<label for="start_time" class=""><?php  echo lang('start_time'); ?></label>  
									
								</div>
							</div>
							<div class="col-sm-6  col-md-7 col-lg-7 ">
								<div class="form-group ">
									<input type='text' name='start_time' value="<?php echo set_value('from_date', isset($movement_details->START_TIME) ? $movement_details->START_TIME : ''); ?>" class="form-control " id='start_time'   required="" tabindex="10"/>
									<span class='help-inline'><?php echo form_error('start_time'); ?></span>
								</div>
							</div>
							<div class="col-sm-12  col-md-5  col-lg-5">
								<div class="form-group ">
									<label for="return_time" class=""><?php  echo lang('return_time'); ?></label> 
									
								</div>
							</div>
							<div class="col-sm-6  col-md-7 col-lg-7 ">
								<div class="form-group ">
									<input type='text' name='return_time' value="<?php echo set_value('from_date', isset($movement_details->RETURN_TIME) ? $movement_details->RETURN_TIME : ''); ?>" class="form-control " id='return_time'     required="" tabindex="11"/>
									<span class='help-inline'><?php echo form_error('return_time'); ?></span>
								</div>
							</div>
							<div class="col-sm-12  col-md-5  col-lg-5">
								<div class="form-group ">
									<label for="permitted_by" class=""><?php  echo lang('permitted_by'); ?></label> 
									
								</div>
							</div>
							<div class="col-sm-6  col-md-7 col-lg-7 ">
								<div class="form-group ">
								
								<select class="form-control chosenCommon chosen-single " name="permitted_by" id="permitted_by" required=""   tabindex="12" >
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
						
						</div>
						<div class="col-sm-0  col-md-4  col-lg-4"></div>
					
				</div>
				
				<div class="col-md-12"> 
					<div class="col-md-12 box-footer pager">
						<?php echo anchor(SITE_AREA .'movement_register/hrm/create', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
						&nbsp; <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>" id="Save" />
					</div>
				</div> 
				
		</div>
		
    </fieldset>
	<!-- End Search -->			
	
	<?php echo form_close(); ?>
</div>

