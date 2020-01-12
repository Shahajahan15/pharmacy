
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

if (isset($insurance_type_details))
{
	$insurance_type_details = (array) $insurance_type_details;
}
//$id = isset($insurance_type_details['EMP_INSURANCE_ID']) ? $insurance_type_details['EMP_INSURANCE_ID'] : '';

?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
		
			<div class="col-sm-2 col-md-2 col-lg-2"> 
			</div>
			
            <div class="col-sm-8 col-md-8 col-lg-8">     
                 								
				<!------------- Insurance Type Name -------------> 
				<div class="form-group <?php echo form_error('INSURANCE_TYPE_NAME') ? 'error' : ''; ?>">
					<?php echo form_label(lang('INSURANCE_TYPE_NAME'). lang('bf_form_label_required'), 'INSURANCE_TYPE_NAME', array('class' => 'control-label col-sm-4') ); ?>					             
					<input type='text' name='INSURANCE_TYPE_NAME' value="<?php echo set_value('INSURANCE_TYPE_NAME', isset($insurance_type_details['INSURANCE_TYPE_NAME']) ? $insurance_type_details['INSURANCE_TYPE_NAME'] : ''); ?>" required maxlength="100" placeholder="<?php echo lang('INSURANCE_TYPE_NAME')?>" id='INSURANCE_TYPE_NAME' class="form-control" tabindex="1"/>					
                    <span class='help-inline'><?php echo form_error('INSURANCE_TYPE_NAME'); ?></span>
				</div>
				
				<!------------- Insurance Type Code No -------------> 
				<div class="form-group <?php echo form_error('INSURANCE_TYPE_CODE') ? 'error' : ''; ?>">
					<?php echo form_label(lang('INSURANCE_TYPE_CODE'). lang('bf_form_label_required'), 'INSURANCE_TYPE_CODE', array('class' => 'control-label col-sm-4') ); ?>						                   
					<input type='text' name='INSURANCE_TYPE_CODE' value="<?php echo set_value('INSURANCE_TYPE_CODE', isset($insurance_type_details['INSURANCE_TYPE_CODE']) ? $insurance_type_details['INSURANCE_TYPE_CODE'] : ''); ?>" required maxlength="11" placeholder="<?php echo lang('INSURANCE_TYPE_CODE')?>" id='INSURANCE_TYPE_CODE' class="form-control" tabindex="2"/>					
                    <span class='help-inline'><?php echo form_error('INSURANCE_TYPE_CODE'); ?></span>
				</div>
				
				
				<?php // Change the values in this array to populate your dropdown as required
						$options = array(
							'1' => 'Active',
							'0' => 'In Active',
						);

						echo form_dropdown('bf_status', $options, set_value('bf_status', isset($insurance_type_details['STATUS']) ? $insurance_type_details['STATUS'] : '1'), lang('bf_status'), "class='form-control'", '', "class='control-label col-sm-4'");
				?>
				
				
				<div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/insurance_type/library/show_list', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>               	
                </div> 

            </div>
			
        </fieldset>

    <?php echo form_close(); ?>

</div>

<div class="col-sm-2 col-md-2 col-lg-2"> 
</div>






