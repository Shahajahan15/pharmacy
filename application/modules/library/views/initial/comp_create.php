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

if (isset($library_comp))
{
	$library_comp = (array) $library_comp;
}
$id = isset($library_comp['id']) ? $library_comp['id'] : '';

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
                                        
         
				<div class="form-group <?php echo form_error('comp_name') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_initial_project_name'). lang('bf_form_label_required'), 'library_initial_comp_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_comp_comp_name' type='text' name='lib_comp_comp_name' maxlength="285" value="<?php echo set_value('lib_comp_comp_name', isset($library_comp['comp_name']) ? $library_comp['comp_name'] : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('comp_name'); ?></span>
                        </div>
                </div>
				
								
				<?php // Change the values in this array to populate your dropdown as required
					$options = array(
						'1' => 'Active',
						'0' => 'In Active',
					);

					echo form_dropdown('lib_comp_status', $options, set_value('lib_comp_status', isset($library_comp['comp_status']) ? $library_comp['comp_status'] : '1'), lang('library_initial_project_status'), "class='form-control'", '', "class='control-label col-sm-4'");
				?>
                    
                <div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/initial/comp_list', lang("cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>
