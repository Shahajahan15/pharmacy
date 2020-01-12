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

if (isset($emp_transfer_details)){
$emp_transfer_details = (array) $emp_transfer_details;
//print_r($emp_transfer_details);
}

?>
	<div class="row box box-primary">
		<?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
			<fieldset class="box-body">
				<div class="row">
					<div class="container">
						<div class="col-sm-3 col-md-3 col-lg-3"></div>
						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
							   <label for="employee_name" class=""><?php  echo lang('data_sync_download_label'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6 <?php echo form_error('') ? 'error' : ''; ?>">
								<select class="form-control" name="employee_name" id="employee_name" required="" tabindex="4">
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
								</select>
								<span class='help-inline'><?php echo form_error(''); ?></span>
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
							   <label for="employee_name" class=""><?php  echo lang('dload_form_label'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
								<input type='text' name='' class="form-control datepickerCommon" id='' value=""  class="form-control "  placeholder="<?php echo lang('dload_form_placeholder')?>" required="" tabindex="6"/>
								<span class='help-inline'><?php echo form_error(''); ?></span>
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
							   <label for="employee_name" class=""><?php  echo lang('dload_to_label'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6 <?php echo form_error('') ? 'error' : ''; ?>">
								<input type='text' name='' class="form-control datepickerCommon" id='' value="" class="form-control "  placeholder="<?php echo lang('dload_to_placeholder')?>" required="" tabindex="6"/>
								<span class='help-inline'><?php echo form_error(''); ?></span>
							</div>
						   
						</div> 
						<div class="col-sm-3  col-md-3 col-lg-3"></div>
					   
					</div>  
				</div>
				
				
				
				 
				<div class="col-md-12"> 
					<div class="col-md-12"> 
						<div class="col-md-12 box-footer pager">
							<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('data_sync_button'); ?>"/>
						</div>
					</div>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
    