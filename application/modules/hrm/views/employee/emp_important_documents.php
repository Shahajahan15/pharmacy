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
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

    <fieldset class="box-body">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="panel panel-primary">
					<div class="panel-heading" align="center">
                        <h3 class="panel-title">Important Documentation</h3>
                    </div>
					
		<div class="panel-body">		
			<div class="col-sm-2 col-md-2 col-lg-2">
			</div>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<!-- ----------- Documentation/ NID_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('NID_STATUS') ? 'error' : ''; ?>">  
					
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('NID_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">
						<input type="radio" name="NID_STATUS" value="1" checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="NID_STATUS" value="0"> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('NID_STATUS'); ?></span>
					</div>
					
                </div>
				
				<!-- ----------- Documentation/ PV_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('PV_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('PV_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">
						<input type="radio" name="PV_STATUS" value="1" checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="PV_STATUS" value="2"> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('PV_STATUS'); ?></span>
					</div>
                </div>
				
				<!-- ----------- Documentation/ JAS_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('JAS_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('JAS_STATUS');?></label>
					</div>
				
					<div class="col-sm-3 col-md-3 col-lg-3">	
						<input type="radio" name="JAS_STATUS" value="1" checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="JAS_STATUS" value="2"> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('JAS_STATUS'); ?></span>
					</div>	
                </div>
				
				<!-- ----------- Documentation/ NDA_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('NDA_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('NDA_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">
						<input type="radio" name="NDA_STATUS" value="1" checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="NDA_STATUS" value="2"> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('NDA_STATUS'); ?></span>
					</div>	
                </div>
				
				<!-- ----------- Documentation/ DL_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('DL_STATUS') ? 'error' : ''; ?>"> 
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('DL_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">	
						<input type="radio" name="DL_STATUS" value="1" checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="DL_STATUS" value="2"> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('DL_STATUS'); ?></span>
					</div>
                </div>
				
				<!-- ----------- Documentation/ PASSPORT_STATUS  ---------------- -->               
				<div class="form-group <?php echo form_error('PASSPORT_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('PASSPORT_STATUS');?></label>
					</div>
				
				<div class="col-sm-3 col-md-3 col-lg-3">	
					<input type="radio" name="PASSPORT_STATUS" value="1" checked> <label><?php echo lang('YES');?></label>
					<input type="radio" name="PASSPORT_STATUS" value="2"> <label><?php echo lang('NO');?></label>
                    <span class='help-inline'><?php echo form_error('PASSPORT_STATUS'); ?></span>
				</div>
				
                </div>
				
			</div> 
			<div class="col-sm-2  col-md-2 col-lg-2">
			</div>
			
		</div>  <!-- panel body end -->
		</div>	<!-- for panel end -->
		
        <div class="col-md-12"> 
					<div class="col-md-10 box-footer pager">
						<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save').lang('bf_action_next'); ?>"/>
						<?php echo lang('bf_or'); ?>
						<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
					</div>
				</div>
      </fieldset>

    <?php echo form_close(); ?>
	

</div>