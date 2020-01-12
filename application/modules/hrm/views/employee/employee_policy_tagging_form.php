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


if (isset($employee_policy_details)){
	$employee_policy_details = (array) $employee_policy_details;
	 
	 //print_r($employee_weekend_details);
}
$EMP_WEEKEND_ID = isset($employee_policy_details['EMP_WEEKEND_ID']) ? $employee_policy_details['EMP_WEEKEND_ID'] : '';

							
?>
<!--<style>
.padding-left-div{margin-left:23px; }
</style>-->

<div class="box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal", id="employee-policy-tagging-form"'); ?>
	<div class="row">
    <fieldset>
			<?php echo $this->load->view('policy_tagging/policy_tagging', $_REQUEST, TRUE); ?>
		 
	</fieldset>
	<div class="row">	
		<fieldset>			
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 
					<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"/>
					<?php echo lang('bf_or'); ?>
					<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-default"'); ?>
				</div>
			</fieldset>
        </div>
        
	</div>
    <?php echo form_close();?>

</div>
