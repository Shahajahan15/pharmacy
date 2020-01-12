
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

        <fieldset>
            <legend>Other Service Add</legend>
        	<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">     
            <div class="<?php echo form_error('library_service_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('library_service_name') . lang('bf_form_label_required'), '', array('class' => 'control-label ')); ?>
                    <div class='control'>
                    	<select name="service_id" id="service_id" class="form-control" required="">
                    		<option value=""><?php echo lang('select_a_one'); ?></option>
                    		<?php foreach ($service_details as $row) : ?>
                    			<option value="<?php echo $row->id; ?>"<?php if(isset($records)){echo (($records['service_id'] == $row->id)? "selected" : "");} ?>><?php echo $row->service_name; ?></option>
                    		<?php endforeach; ?>
                    	</select>
                        <span class='help-inline'><?php echo form_error('library_service_name'); ?></span>
                    </div>
                </div>
            </div>		
            </div> 
        
            <div class="col-sm-4 col-md-4 col-lg-3">
				<div class="form-group <?php echo form_error('otherservice_name') ? 'error' : ''; ?>">                      
					<label class="control-label"><?php echo lang('library_otherservice_name').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" id='library_otherservice_name' type='text' name='library_otherservice_name' maxlength="100" value="<?php echo set_value('library_otherservice_name', isset($records['otherservice_name']) ? $records['otherservice_name'] : ''); ?>"  required="" />
					<span class='help-inline'><?php echo form_error('library_otherservice_name'); ?></span>                        
                </div>
            </div>	
            <div class="col-sm-4 col-md-4 col-lg-3">
                <div class="form-group <?php echo form_error('other_service_price') ? 'error' : ''; ?>">                      
                    <label class="control-label"><?php echo lang('other_service_price').lang('bf_form_label_required');?></label>
                    <input type='text' class="form-control" id='other_service_price' type='text' name='other_service_price' maxlength="100" value="<?php echo set_value('other_service_price', isset($records['other_service_price']) ? $records['other_service_price'] : ''); ?>"  required="" />
                    <span class='help-inline'><?php echo form_error('other_service_price'); ?></span>                        
                </div>
            </div>  
            
	 <div class="col-sm-4 col-md-4 col-lg-3">
                <div class="form-group <?php echo form_error('Description') ? 'error' : ''; ?>">                      
                    <label class="control-label"><?php echo lang('Description').lang('bf_form_label_required');?></label>
                    <input type='text' class="form-control" id='Description' type='text' name='Description' maxlength="100" value="<?php echo set_value('Description', isset($records['description']) ? $records['description'] : ''); ?>"  required="" />
                    <span class='help-inline'><?php echo form_error('Description'); ?></span>                        
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








