
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
?>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        
            <div class="col-sm-4 col-md-4 col-lg-3">
				<div class="form-group <?php echo form_error('medicine_category_name') ? 'error' : ''; ?>">                      
					<label class="control-label"><?php echo lang('library_category_name').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" id='medicine_category_name' type='text' name='medicine_category_name' maxlength="100" value="<?php echo set_value('medicine_category_name', isset($record['medicine_category_name']) ? $record['medicine_category_name'] : ''); ?>"  required="" />
					<span class='help-inline'><?php echo form_error('medicine_category_name'); ?></span>
                </div>
            </div>	

				
				
				<div class="col-sm-4 col-md-4 col-lg-3">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($record['status'])){if($record['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($record['status'])){if($record['status'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('status'); ?></span>
					</div>
				</div>
				
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="footer pager">
                	<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"/>
                	<button type="reset" class="btn btn-warning">Reset</button>
					
                </div>
            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








