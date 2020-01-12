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

if (isset($library_designation))
{
	$library_designation = (array) $library_designation;
}
$id = isset($library_ldesignation['id']) ? $library_designation['id'] : '';

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
                                        
         
				<div class="form-group <?php echo form_error('designation_name') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_initial_designation_name'). lang('bf_form_label_required'), 'library_initial_designation_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_designation_designation_name' type='text' name='lib_designation_designation_name' maxlength="285" value="<?php echo set_value('lib_designation_designation_name', isset($library_designation['designation_name']) ? $library_designation['designation_name'] : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('designation_name'); ?></span>
                        </div>
                </div>

                <div class="form-group <?php echo form_error('designation_duration') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_initial_designation_duration'). lang('bf_form_label_required'), 'library_initial_designation_duration', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_designation_designation_duration' type='text' name='lib_designation_designation_duration' maxlength="285" value="<?php echo set_value('lib_designation_designation_duration', isset($library_designation['designation_duration']) ? $library_designation['designation_duration'] : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('designation_duration'); ?></span>
                        </div>
                </div> 

				<?php // Change the values in this array to populate your dropdown as required
					$options = array(
						'1' => 'Active',
						'0' => 'In Active',
					);

					echo form_dropdown('lib_designation_status', $options, set_value('lib_designation_status', isset($library_designation['status']) ? $library_designation['status'] : '1'), lang('library_initial_designation_status'), "class='form-control'", '', "class='control-label col-sm-4'");
                ?>
				
			   <div class="box-footer pager">
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('save'); ?>"  />
				&nbsp;
				<?php echo anchor(SITE_AREA .'/designation/library/show_list', lang("cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>
