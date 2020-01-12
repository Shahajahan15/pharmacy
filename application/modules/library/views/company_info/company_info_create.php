

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

if (isset($company_details))
{
	$company_details = (array) $company_details;
}
$id = isset($company_details['id']) ? $company_details['id'] : '';
?>

<div class="row box box-primary">

    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>

    <fieldset class="box-body">
    <legend>Company Add</legend>
      
        
        <div class="col-sm-4 col-md-4 col-lg-3">
				<div class="form-group <?php echo form_error('branch_name') ? 'error' : ''; ?>">                      
					<label class="control-label"><?php echo lang('library_company_name').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" id='package_id' type='text' name='library_company_name' maxlength="100" value="<?php echo set_value('library_company_name', isset($company_details['company_name']) ? $company_details['company_name'] : ''); ?>"  required="" />
					<span class='help-inline'><?php echo form_error('library_company_name'); ?></span>                        
                </div>
            </div>	
        <div class="col-sm-4 col-md-4 col-lg-3">
                <div class="form-group <?php echo form_error('account_no') ? 'error' : ''; ?>">                      
                    <label class="control-label"><?php echo lang('library_company_address').lang('bf_form_label_required');?></label>
                    <input type='text' class="form-control" id='library_branch_account_no' type='text' name='library_company_address' maxlength="100" value="<?php echo set_value('library_company_address', isset($company_details['address']) ? $company_details['address'] : ''); ?>"  required="" />
                    <span class='help-inline'><?php echo form_error('library_company_address'); ?></span>                        
                </div>
            </div>  
	
		<div class="col-sm-4 col-md-4 col-lg-3">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($company_details['status'])){if($company_details['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($company_details['status'])){if($company_details['status'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('status'); ?></span>
					</div>
				</div>
				</fieldset>
                    
                <fieldset>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="footer pager">
                	<input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"/>
                	<button type="reset" class="btn btn-warning btn-sm">Reset</button>
					
                </div>
            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>













