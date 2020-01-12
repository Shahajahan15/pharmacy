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
if (isset($important_documentation_status)){
	 $important_documentation_status = (array) $important_documentation_status;	 
}
?>
<!--<style>
.padding-left-div{margin-left:23px; }
</style>-->

<div class="box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
	<div class="row">
    <fieldset>
    <legend>Important Documentation</legend>
			
			<div class="col-sm-8 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
			
				<!-- ----------- Documentation/ NID_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('NID_STATUS') ? 'error' : ''; ?>">  					
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('NID_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">
						<input type="radio" name="NID_STATUS" value="1" <?php if(isset( $important_documentation_status['NID_STATUS']) &&  $important_documentation_status['NID_STATUS'] ==1) {echo "checked";}?> checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="NID_STATUS" value="2" <?php if(isset( $important_documentation_status['NID_STATUS']) &&  $important_documentation_status['NID_STATUS'] ==2) {echo "checked";}?>> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('NID_STATUS'); ?></span>
					</div>					
                </div>
				
				<!-- ----------- Documentation/ PV_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('PV_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('PV_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">
						<input type="radio" name="PV_STATUS" value="1" <?php if(isset( $important_documentation_status['PV_STATUS']) &&  $important_documentation_status['PV_STATUS'] ==1) {echo "checked";}?> checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="PV_STATUS" value="2" <?php if(isset( $important_documentation_status['PV_STATUS']) &&  $important_documentation_status['PV_STATUS'] ==2) {echo "checked";}?>> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('PV_STATUS'); ?></span>
					</div>
                </div>
				
				<!-- ----------- Documentation/ JAS_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('JAS_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('JAS_STATUS');?></label>
					</div>
				
					<div class="col-sm-3 col-md-3 col-lg-3">	
						<input type="radio" name="JAS_STATUS" value="1" <?php if(isset( $important_documentation_status['JAS_STATUS']) &&  $important_documentation_status['JAS_STATUS'] ==1) {echo "checked";}?> checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="JAS_STATUS" value="2" <?php if(isset( $important_documentation_status['JAS_STATUS']) &&  $important_documentation_status['JAS_STATUS'] ==2) {echo "checked";}?>> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('JAS_STATUS'); ?></span>
					</div>	
                </div>
				
				<!-- ----------- Documentation/ NDA_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('NDA_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('NDA_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">
						<input type="radio" name="NDA_STATUS" value="1" <?php if(isset( $important_documentation_status['NDA_STATUS']) &&  $important_documentation_status['NDA_STATUS'] ==1) {echo "checked";}?> checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="NDA_STATUS" value="2" <?php if(isset( $important_documentation_status['NDA_STATUS']) &&  $important_documentation_status['NDA_STATUS'] ==2) {echo "checked";}?>> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('NDA_STATUS'); ?></span>
					</div>	
                </div>
				
				<!-- ----------- Documentation/ DL_STATUS ---------------- -->               
				<div class="form-group <?php echo form_error('DL_STATUS') ? 'error' : ''; ?>"> 
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('DL_STATUS');?></label>
					</div>
					
					<div class="col-sm-3 col-md-3 col-lg-3">	
						<input type="radio" name="DL_STATUS" value="1" <?php if(isset( $important_documentation_status['DL_STATUS']) &&  $important_documentation_status['DL_STATUS'] ==1) {echo "checked";}?> checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="DL_STATUS" value="2" <?php if(isset( $important_documentation_status['DL_STATUS']) &&  $important_documentation_status['DL_STATUS'] ==2) {echo "checked";}?>> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('DL_STATUS'); ?></span>
					</div>
                </div>
				
				<!-- ----------- Documentation/ PASSPORT_STATUS  ---------------- -->               
				<div class="form-group <?php echo form_error('PASSPORT_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('PASSPORT_STATUS');?></label>
					</div>
				
					<div class="col-sm-3 col-md-3 col-lg-3">	
						<input type="radio" name="PASSPORT_STATUS" value="1"  <?php if(isset( $important_documentation_status['PASSPORT_STATUS']) &&  $important_documentation_status['PASSPORT_STATUS'] ==1) {echo "checked";}?> checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="PASSPORT_STATUS" value="2" <?php if(isset( $important_documentation_status['PASSPORT_STATUS']) &&  $important_documentation_status['PASSPORT_STATUS'] ==2) {echo "checked";}?>> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('PASSPORT_STATUS'); ?></span>
					</div>
                </div>
				
				<!-- ----------- Documentation/ PASSPORT_STATUS  ---------------- -->               
				<div class="form-group <?php echo form_error('EDUCATIONAL_STATUS') ? 'error' : ''; ?>">  
					<div class="col-sm-5 col-md-5 col-lg-5">
						<label><?php echo lang('EDUCATIONAL_STATUS');?></label>
					</div>
				
					<div class="col-sm-3 col-md-3 col-lg-3">	
						<input type="radio" name="EDUCATIONAL_STATUS" value="1" <?php if(isset( $important_documentation_status['EDUCATIONAL_STATUS']) &&  $important_documentation_status['EDUCATIONAL_STATUS'] ==1) {echo "checked";}?> checked> <label><?php echo lang('YES');?></label>
						<input type="radio" name="EDUCATIONAL_STATUS" value="2" <?php if(isset( $important_documentation_status['NID_STATUS']) &&  $important_documentation_status['EDUCATIONAL_STATUS'] ==2) {echo "checked";}?>> <label><?php echo lang('NO');?></label>
						<span class='help-inline'><?php echo form_error('EDUCATIONAL_STATUS'); ?></span>
					</div>
                </div>
				
			</div> 
    </fieldset>
     <div class="row">	
		<fieldset>			
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 	
	<input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save').' '. lang('bf_action_next'); ?>"/>		
	<?php echo lang('bf_or'); ?>
				<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-default btn-sm"'); ?>			
				
			</div>
			</fieldset>
		</div>
    </div>
    <?php echo form_close(); ?>	
</div>