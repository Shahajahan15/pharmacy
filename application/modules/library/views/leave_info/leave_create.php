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

	if (isset($lib_leave))
	{
		$lib_leave = (array) $lib_leave;
	}
	$POLICY_ID = isset($lib_leave['POLICY_ID']) ? $lib_leave['POLICY_ID'] : '';

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
				<div class="form-group <?php echo form_error('LEAVE_TYPE') ? 'error' : ''; ?>">
					<?php echo form_label(lang('library_leave_name'). lang('bf_form_label_required'), 'library_leave_name', array('class' => 'control-label col-sm-4') ); ?>
					<div class='control'>
						<input type='text' class="form-control" name='library_leave_name' id='library_leave_name' maxlength="30" value="<?php echo set_value('library_leave_name', isset($lib_leave['LEAVE_TYPE']) ? $lib_leave['LEAVE_TYPE'] : ''); ?>" placeholder="<?php echo lang('library_leave_name')?>" required="" tabindex="1"/>
						<span class='help-inline'><?php echo form_error('LEAVE_TYPE'); ?></span>
					</div>
                </div>
				
 
				<?php // Change the values in this array to populate your dropdown as required
					$options = array(
						'1' => 'Active',
						'0' => 'In Active',
					);
					echo form_dropdown('library_leave_status', $options, set_value('library_leave_status', isset($lib_leave['STATUS']) ? $lib_leave['STATUS'] : '1'), lang('library_leave_status'), "class='form-control'", '', "class='control-label col-sm-4'");
                ?>
				
				<div class="box-footer pager">
					<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
					&nbsp;
					<?php echo anchor(SITE_AREA .'/leave_info/library/show_list', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>
