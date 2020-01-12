
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

if (isset($record))
{
	$record = (array) $record;
}
$id = isset($record['id']) ? $record['id'] : '';

?>


<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>

        <fieldset class="box-body">   
			<!--name-->
			<div class="col-sm-4 col-md-3 col-lg-3">
				<div class="form-group <?php echo form_error('leave_type') ? 'error' : ''; ?>">   
					
					<label class="control-label">Name<?php echo lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" name='leave_type' required="" id='leave_type' required="" maxlength="50" value="<?php echo set_value('leave_type', isset($record['leave_type']) ? $record['leave_type'] : ''); ?>"/>
					<span class='help-inline'><?php echo form_error('leave_type'); ?></span>                      
                </div>
            </div>


            <!--Nums of day leave-->
			<div class="col-sm-4 col-md-3 col-lg-3">
				<div class="form-group <?php echo form_error('total_leave_days') ? 'error' : ''; ?>">   
					
					<label class="control-label">Total Day<?php echo lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" name='total_leave_days' id='total_leave_days' required="" maxlength="50" value="<?php echo set_value('total_leave_days', isset($record['total_leave_days']) ? $record['total_leave_days'] : ''); ?>"/>
					<span class='help-inline'><?php echo form_error('total_leave_days'); ?></span>                      
                </div>
            </div>
				
			
				<!--DESCRIPTION-->
			<div class="col-sm-4 col-md-3 col-lg-3">
				<div class="form-group <?php echo form_error('description') ? 'error' : ''; ?>">                       
                     <label class="control-label">Description</label>
                    <input type='text' class="form-control" name='description' id='description'  maxlength="150" value="<?php echo set_value('description', isset($record['description']) ? $record['description'] : ''); ?>"/>
                    <span class='help-inline'><?php echo form_error('description'); ?></span>
                </div>
            </div>
				
				<!-- status -->
				<div class="col-sm-4 col-md-3 col-lg-3">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="status" id="status" required="">
							<option value="1" <?php if(isset($record['status'])){if($record['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($record['status'])){if($record['status'] == 0){ echo "selected";}}?>>Inactive</option>
							
						 </select>
						<span class='help-inline'><?php echo form_error('status'); ?></span>
					</div>
				</div>     

        </fieldset>
        <fieldset>
        		<div class=" pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <input type="submit" name="reset" class="btn btn-primary" value="Reset"  />
                	
                </div>  
        </fieldset>

    <?php echo form_close(); ?>

</div>








