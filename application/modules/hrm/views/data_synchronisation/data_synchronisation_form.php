<style>
.glowing-border {
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
    border-radius: 10px;
}

.glowing-border:focus { 
   -webkit-box-shadow: 0px 0px 7px #4195fc;
    -moz-box-shadow: 0px 0px 7px #4195fc;
    box-shadow: 0px 0px 7px #4195fc; 
	
}
</style>

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

/* if (isset($reader_model_details)){
$reader_model_details = (array) $reader_model_details;
print_r($reader_model_details);
} */

?>
	<div class="row box box-primary">
		<?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
			<fieldset class="box-body">
				<div class="row">
					<div class="container">
						<div class="col-sm-3 col-md-3 col-lg-3"></div>
						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
							   <label for="data_sync_reader_model" class=""><?php  echo lang('data_sync_reader_model'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6 ">
								<select class="form-control" name="data_sync_reader_model" id="data_sync_reader_model" required="" tabindex="1">
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
									
									<?php if(isset($reader_model_details)){
										foreach($reader_model_details as $reader_model_detail)
										{
											
										
											echo "<option value='".$reader_model_detail->READER_MODEL_ID."'>".$reader_model_detail->READER_MODEL_NAME."</option>";
										}
										
										
									} 
									
									?>
									
									
									
									
								</select>
								<span class='help-inline'><?php echo form_error('data_sync_reader_model'); ?></span>
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
							   <label for="dload_form" class=""><?php  echo lang('dload_form'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
								<input type='text' name='dload_form' class="form-control datepickerCommon glowing-border" id='dload_form'   class="form-control "  placeholder="<?php echo lang('dload_form_placeholder')?>" required="" tabindex="2"/>
								<span class='help-inline'><?php echo form_error('dload_form'); ?></span>
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
							   <label for="dload_to" class=""><?php  echo lang('dload_to'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6 col-md-6 col-lg-6">
								<input type='text' name='dload_to' class="form-control datepickerCommon glowing-border" id='dload_to'  class="form-control "  placeholder="<?php echo lang('dload_to_placeholder')?>" required="" tabindex="3"/>
								<span class='help-inline'><?php echo form_error('dload_to'); ?></span>
							</div>
						   
						</div> 
						<div class="col-sm-3  col-md-3 col-lg-3"></div>
					   
					</div>  
				</div>
				
				
				
				 
				<div class="col-md-12"> 
					<div class="col-md-12"> 
						<div class="col-md-12 box-footer pager">
							<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('data_sync_button'); ?>" tabindex="4"/>
						</div>
					</div>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
    