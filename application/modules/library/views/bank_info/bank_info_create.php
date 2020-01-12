
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

if (isset($bank_details))
{
	$bank_details = (array) $bank_details;
}
$id = isset($bank_details['id']) ? $bank_details['id'] : '';

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
                                        
							<!--BANK_NAME-->
				<div class="form-group <?php echo form_error('BANK_NAME') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_bank_name'). lang('bf_form_label_required'), 'library_bank_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input type='text' class="form-control" name='library_bank_name' id='library_bank_name'  maxlength="100" value="<?php echo set_value('library_bank_name', isset($bank_details['BANK_NAME']) ? $bank_details['BANK_NAME'] : ''); ?>" placeholder="<?php echo lang('library_bank_name')?>" required="" tabindex="1"/>
                            <span class='help-inline'><?php echo form_error('BANK_NAME'); ?></span>
                        </div>
                </div>
				
							<!--BRANCH_NAME-->
				
				<div class="form-group <?php echo form_error('BRANCH_NAME') ? 'error' : ''; ?>">
					<?php echo form_label(lang('library_bank_branch'). lang('bf_form_label_required'), 'library_bank_branch', array('class' => 'control-label col-sm-4') ); ?>
					<div class='control'>
						<input type='text' class="form-control" name='library_bank_branch' id='library_bank_branch' maxlength="50" value="<?php echo set_value('library_bank_branch', isset($bank_details['BRANCH_NAME']) ? $bank_details['BRANCH_NAME'] : ''); ?>" placeholder="<?php echo lang('library_bank_branch')?>" required="" tabindex="3"/>
						<span class='help-inline'><?php echo form_error('BRANCH_NAME'); ?></span>
					</div>
				</div>
				
						<!--BRANCH_CATEGORY-->
				<div class="form-group <?php echo form_error('BRANCH_CATEGORY') ? 'error' : ''; ?>">
					<?php echo form_label(lang('library_bank_branch_category'). lang('bf_form_label_required'), 'library_bank_branch_category', array('class' => 'control-label col-sm-4') ); ?>
					<div class='control'>
						<input type='text' class="form-control" name='library_bank_branch_category' id='library_bank_branch_category'   maxlength="50" value="<?php echo set_value('library_bank_branch_category', isset($bank_details['BRANCH_CATEGORY']) ? $bank_details['BRANCH_CATEGORY'] : ''); ?>" placeholder="<?php echo lang('library_bank_branch_category')?>"required="" tabindex="4"/>
						<span class='help-inline'><?php echo form_error('BRANCH_CATEGORY'); ?></span>
					</div>
				</div>
				
								<!--ADDRESS-->
				<div class="form-group <?php echo form_error('ADDRESS') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_bank_address'). lang('bf_form_label_required'), 'library_bank_address', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input type='text' class="form-control" name='library_bank_address' id='library_bank_address'   maxlength="200" value="<?php echo set_value('library_bank_address', isset($bank_details['ADDRESS']) ? $bank_details['ADDRESS'] : ''); ?>" placeholder="<?php echo lang('library_bank_address')?>" required="" tabindex="5"/>
                            <span class='help-inline'><?php echo form_error('ADDRESS'); ?></span>
                        </div>
                </div>
				
					<?php // Change the values in this array to populate your dropdown as required
					$options = array(
						'1' => 'Active',
						'0' => 'In Active',
					);

					echo form_dropdown('bf_status', $options, set_value('bf_status', isset($bank_details['STATUS']) ? $bank_details['STATUS'] : '1'), lang('bf_status'), "class='form-control'", '', "class='control-label col-sm-4'");
				?>
                    
                <div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo isset($bank_details)?'Update':lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/bank_info/library/show_banklist', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








