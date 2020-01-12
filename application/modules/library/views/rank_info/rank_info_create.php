
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

if (isset($rank_details))
{
	$rank_details = (array) $rank_details;
}
$id = isset($rank_details['id']) ? $rank_details['id'] : '';

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
                                        
							<!------------RANK_NAME----------->
				<div class="form-group <?php echo form_error('RANK_NAME') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_rank_name'). lang('bf_form_label_required'), 'library_rank_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input type='text' class="form-control" name='library_rank_name' id='library_rank_name'  maxlength="50" value="<?php echo set_value('library_rank_name', isset($rank_details['RANK_NAME']) ? $rank_details['RANK_NAME'] : ''); ?>" required="" tabindex="1"/>
                            <span class='help-inline'><?php echo form_error('RANK_NAME'); ?></span>
                        </div>
                </div>
				
					<?php // Change the values in this array to populate your dropdown as required
					$options = array(
						'1' => 'Active',
						'0' => 'In Active',
					);

					echo form_dropdown('bf_status', $options, set_value('bf_status', isset($rank_details['STATUS']) ? $rank_details['STATUS'] : '1'), lang('bf_status'), "class='form-control'", '', "class='control-label col-sm-4'");
				?>
                    
                <div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/rank_info/library/show_ranklist', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








