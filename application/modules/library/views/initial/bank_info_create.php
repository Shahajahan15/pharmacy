
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





<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>

        <fieldset class="box-body">
         <legend><?php echo isset($bank_details)?'Update '.$bank_details['bank_name']:'New Bank Add' ?></legend>
            <div class="col-sm-8 col-md-4 col-lg-4">
				<div class="form-group <?php echo form_error('bank_name') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_bank_name'). lang('bf_form_label_required'), 'library_bank_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input type='text' class="form-control" name='library_bank_name' id='library_bank_name'  maxlength="100" value="<?php echo set_value('library_bank_name', isset($bank_details['bank_name']) ? $bank_details['bank_name'] : ''); ?>" placeholder="<?php echo lang('library_bank_name')?>" required="" tabindex="1"/>
                            <span class='help-inline'><?php echo form_error('bank_name'); ?></span>
                        </div>
                </div>
            </div>	

				<div class="col-sm-8 col-md-4 col-lg-4">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($bank_details['status'])){if($bank_details['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($bank_details['status'])){if($bank_details['status'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('status'); ?></span>
					</div>
				</div>
</fieldset>
<fieldset>
 
				
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="pager">
                	<input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo isset($bank_details)?'Update':lang('bf_action_save'); ?>"/>
                	<button type="reset" class="btn btn-warning btn-sm">Reset</button>
					
                </div>
            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>






























