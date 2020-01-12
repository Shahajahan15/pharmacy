
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

if (isset($lib_bonus))
{
	$lib_bonus = (array) $lib_bonus;
}
$id = isset($lib_bonus['id']) ? $lib_bonus['id'] : '';

?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        
            <div class="col-sm-8 col-md-8 col-lg-8">     
                                        
							<!------------BONUS_NAME----------->
				<div class="form-group <?php echo form_error('BONUS_NAME') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_bonus_name'). lang('bf_form_label_required'), 'library_bonus_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input type='text' class="form-control" name='library_bonus_name' id='library_bonus_name'  maxlength="30" value="<?php echo set_value('library_bonus_name', isset($lib_bonus['BONUS_NAME']) ? $lib_bonus['BONUS_NAME'] : ''); ?>" placeholder="<?php echo lang('library_bonus_name')?>" required="" tabindex="1"/>
                            <span class='help-inline'><?php echo form_error('BONUS_NAME'); ?></span>
                        </div>
                </div>
				
							<!------------DESCRIPTION----------->
				<div class="form-group <?php echo form_error('DESCRIPTION') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_bonus_description'). lang('bf_form_label_required'), 'library_bonus_description', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input type='text' class="form-control" name='library_bonus_description' id='library_bonus_description'  maxlength="100" value="<?php echo set_value('library_bonus_description', isset($lib_bonus['DESCRIPTION']) ? $lib_bonus['DESCRIPTION'] : ''); ?>" placeholder="<?php echo lang('library_bonus_description')?>" required="" tabindex="2"/>
                            <span class='help-inline'><?php echo form_error('DESCRIPTION'); ?></span>
                        </div>
                </div>
				
					<?php // Change the values in this array to populate your dropdown as required
					$options = array(
						'1' => 'Active',
						'0' => 'In Active',
					);

					echo form_dropdown('bf_status', $options, set_value('bf_status', isset($lib_bonus['STATUS']) ? $lib_bonus['STATUS'] : '1'), lang('bf_status'), "class='form-control'", '', "class='control-label col-sm-4'");
				?>
                    
                <div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/bonus_info/library/show_list', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








