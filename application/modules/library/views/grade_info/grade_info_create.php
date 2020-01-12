
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

if (isset($grade_details))
{
	$grade_details = (array) $grade_details;
}
$id = isset($grade_details['id']) ? $grade_details['id'] : '';

?>


<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        
            <div class="col-sm-4 col-md-4 col-lg-4 col-md-offset-4">     
                                        
				<!--GRADE_NAME-->
				<div class="form-group <?php echo form_error('GRADE_NAME') ? 'error' : ''; ?>">   
					<div id="checkName" style="color:#F00; font-size:14px;"></div>
					<label><?php echo lang('library_grade_name').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" name='library_grade_name' id='library_grade_name'  maxlength="50" value="<?php echo set_value('library_grade_name', isset($grade_details['GRADE_NAME']) ? $grade_details['GRADE_NAME'] : ''); ?>" onblur="gradeCheck()"/>
					<span class='help-inline'><?php echo form_error('GRADE_NAME'); ?></span>
                      
                </div>		
				
				
					
				
				<!--DESCRIPTION-->
				<div class="form-group <?php echo form_error('DESCRIPTION') ? 'error' : ''; ?>">                       
                     <label><?php echo lang('library_grade_description');?></label>
                    <input type='text' class="form-control" name='library_grade_description' id='library_grade_description'  maxlength="150" value="<?php echo set_value('library_grade_description', isset($grade_details['DESCRIPTION']) ? $grade_details['DESCRIPTION'] : ''); ?>"/>
                    <span class='help-inline'><?php echo form_error('DESCRIPTION'); ?></span>
                     
                </div>
				
				<!-- status -->
					<div class="form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">					
						<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($grade_details['STATUS'])){if($grade_details['STATUS'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($grade_details['STATUS'])){if($grade_details['STATUS'] == 0){ echo "selected";}}?>>Inactive</option>
							
						 </select>
						<span class='help-inline'><?php echo form_error('STATUS'); ?></span>
					</div>
				
                    
                <div class="pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/grade_info/library/show_gradelist', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








