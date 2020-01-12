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


if (isset($employee_weekend_details)){
	$employee_weekend_details = (array) $employee_weekend_details;
	 
	@$sunday 	= $employee_weekend_details['SU_ID'];
	@$monday 	= $employee_weekend_details['MO_ID'];
	@$tuesday 	= $employee_weekend_details['TU_ID'];
	@$wednesday = $employee_weekend_details['WE_ID'];	
	@$thursday 	= $employee_weekend_details['TH_ID'];
	@$friday 	= $employee_weekend_details['FR_ID'];
	@$saturday 	= $employee_weekend_details['SA_ID'];
	
	 //print_r($employee_weekend_details);
}
$EMP_WEEKEND_ID = isset($employee_weekend_details['EMP_WEEKEND_ID']) ? $employee_weekend_details['EMP_WEEKEND_ID'] : '';


								
?>
<!--<style>
.padding-left-div{margin-left:23px; }
</style>-->

<div class="box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
	<div class="row">
    <fieldset>
    	<legend>Define Employee Weekend</legend>
			

			
			<div class="col-md-4 col-md-offset-4">
				
				<!-- ----------- Weekend Define ---------------- -->               
				<div class="form-group <?php echo form_error('hrm_ls_weekend') ? 'error' : ''; ?> col-sm-4 col-md-4 col-lg-4">  										
					
					<?php 
						foreach($days as $key => $val){	
							
							echo "<input type='checkbox' name='".$val."' value='".$key."' ";							
							if(
								$sunday  == $key || $monday == $key || $tuesday  == $key  || 
								$wednesday == $key || $thursday == $key || $friday == $key || $saturday == $key
							){ echo "checked";} 
													
							echo ">";
							
							echo " $val  &nbsp; &nbsp; <br> ";
						}
					?>
					
                    <span class='help-inline'><?php echo form_error('hrm_ls_weekend'); ?></span>
					
                </div>	
				
				<div class="col-md-4 padding-left-div">
				</div>
						
			</div>			 
	</fieldset>
	 <div class="row">	
		<fieldset>			
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 
					<input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save').lang('bf_action_next'); ?>"/>
					<?php echo lang('bf_or'); ?>
					<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-warning btn-sm"'); ?>
				</div>
			</fieldset>
        </div>
        
	</div>
    <?php echo form_close(); ?>

</div>