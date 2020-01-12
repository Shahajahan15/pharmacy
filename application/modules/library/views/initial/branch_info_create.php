
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

if (isset($records))
{
	$records = (array) $records;
}
$id = isset($records['id']) ? $records['id'] : '';

?>

<div class="row box box-primary">

    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>

    <fieldset class="box-body">
    <legend>Branch Add</legend>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">     
            <div class="<?php echo form_error('library_bank_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('library_bank_name') . lang('bf_form_label_required'), '', array('class' => 'control-label ')); ?>
                    <div class='control'>
                    	<select name="bank_id" id="bank_id" class="form-control" required="">
                    		<option value=""><?php echo lang('select_a_one'); ?></option>
                    		<?php foreach ($bank_details as $row) : ?>
                    			<option value="<?php echo $row->id; ?>"<?php if(isset($records)){echo (($records['bank_id'] == $row->id)? "selected" : "");} ?>><?php echo $row->bank_name; ?></option>
                    		<?php endforeach; ?>
                    	</select>
                        <span class='help-inline'><?php echo form_error('library_bank_name'); ?></span>
                    </div>
                </div>
            </div>		
            </div> 
        
        <div class="col-sm-4 col-md-4 col-lg-3">
				<div class="form-group <?php echo form_error('branch_name') ? 'error' : ''; ?>">                      
					<label class="control-label"><?php echo lang('library_branch_name').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" id='branch_id' type='text' name='library_branch_name' maxlength="100" value="<?php echo set_value('library_branch_name', isset($records['branch_name']) ? $records['branch_name'] : ''); ?>"  required="" />
					<span class='help-inline'><?php echo form_error('library_branch_name'); ?></span>                        
                </div>
            </div>	
        <div class="col-sm-4 col-md-4 col-lg-3">
                <div class="form-group <?php echo form_error('account_no') ? 'error' : ''; ?>">                      
                    <label class="control-label"><?php echo lang('library_branch_account_no').lang('bf_form_label_required');?></label>
                    <input type='text' class="form-control" id='library_branch_account_no' type='text' name='library_branch_account_no' maxlength="100" value="<?php echo set_value('library_branch_account_no', isset($records['account_no']) ? $records['account_no'] : ''); ?>"  required="" />
                    <span class='help-inline'><?php echo form_error('library_branch_account_no'); ?></span>                        
                </div>
            </div>  
	
		<div class="col-sm-4 col-md-4 col-lg-3">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($records['status'])){if($records['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($records['status'])){if($records['status'] == 0){ echo "selected";}}?>>Inactive</option>											
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








