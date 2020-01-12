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
							
							<div class="form-group col-sm-6  col-md-6 col-lg-6">
								<label for="date_from" class=""><?php  echo lang('date_from'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6  col-md-6 col-lg-6 ">
								<input type='text' name='date_from' class="form-control datepickerCommon glowing-border" id='date_from'   class="form-control "  placeholder="<?php echo lang('date_from_placeholder')?>" required="" tabindex="1"/>
								<span class='help-inline'><?php echo form_error('date_from'); ?></span>
							</div>
							
							<div class="form-group col-sm-6  col-md-6 col-lg-6">
								<label for="date_to" class=""><?php  echo lang('date_to'); ?></label> 
							</div>
							
							<div class="form-group col-sm-6  col-md-6 col-lg-6 ">
								<input type='text' name='date_to' class="form-control datepickerCommon glowing-border" id='date_to'  class="form-control "  placeholder="<?php echo lang('date_to_placeholder')?>" required="" tabindex="2"/>
								<span class='help-inline'><?php echo form_error('date_to'); ?></span>
							</div>
						   
						</div> 
						<div class="col-sm-3  col-md-3 col-lg-3"></div>
					   
					</div>  
				</div>
				
				
				
				 
				<div class="col-md-12"> 
					<div class="col-md-12"> 
						<div class="col-md-12 box-footer pager">
							<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('strt_proces_button'); ?>" tabindex="3"/>
							
						</div>
					</div>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
    