
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
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        	<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">     
            <div class="<?php echo form_error('pharmacy_group_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_group_name') . lang('bf_form_label_required'), '', array('class' => 'control-label ')); ?>
                    <div class='control'>
                    	<select name="group_id" id="group_id" class="form-control" required="">
                    		<option value=""><?php echo lang('select_a_one'); ?></option>
                    		<?php foreach ($group_details as $row) : ?>
                    			<option value="<?php echo $row->id; ?>" <?php if(isset($records)){echo (($records['group_id'] == $row->id)? "selected" : "");} ?>><?php echo $row->group_name; ?></option>
                    		<?php endforeach; ?>
                    	</select>
                        <span class='help-inline'><?php echo form_error('pharmacy_group_name'); ?></span>
                    </div>
                </div>
            </div>		
            </div> 
        
            <div class="col-sm-4 col-md-4 col-lg-3">
				<div class="form-group <?php echo form_error('generic_name') ? 'error' : ''; ?>">                      
					<label class="control-label"><?php echo lang('pharmacy_generic_name').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" id='pharmacy_generic_name' type='text' name='pharmacy_generic_name' maxlength="100" value="<?php echo set_value('pharmacy_generic_name', isset($records['generic_name']) ? $records['generic_name'] : ''); ?>"  required="" />
					<span class='help-inline'><?php echo form_error('pharmacy_generic_name'); ?></span>                        
                </div>
            </div>	
	
				<div class="col-sm-4 col-md-4 col-lg-3">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($group_details['status'])){if($group_details['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($group_details['status'])){if($group_details['status'] == 0){ echo "selected";}}?>>Inactive</option>											
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








