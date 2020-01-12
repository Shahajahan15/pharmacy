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

if (isset($library_department))
{
	$library_department = (array) $library_department;
}
$id = isset($library_department['id']) ? $library_department['id'] : '';

?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Department Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
              <div class="form-group <?php echo form_error('department_name') ? 'error' : ''; ?>"> 
					<div id="checkName" style="color:#F00; font-size:14px;"></div>
					<label><?php echo lang('library_initial_department_name').lang('bf_form_label_required');?></label>                      
					<input class="form-control" id='lib_department_department_name' type='text' name='lib_department_department_name' maxlength="285" value="<?php echo set_value('lib_department_department_name', isset($library_department['department_name']) ? $library_department['department_name'] : ''); ?>"  required=""  onblur="departmentCheck()"/>
                    <span class='help-inline'><?php echo form_error('department_name'); ?></span>                   
                </div>
				
				
                
            </div>      
        

                 <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
				<div class="form-group <?php echo form_error('department_head') ? 'error' : ''; ?>">                      
                    <label><?php echo lang('library_initial_department_head').lang('bf_form_label_required');?></label> 
                   <select name="library_initial_department_head" id="library_initial_department_head" class="form-control" required="">
							<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
							<?php foreach($employee as $employeeList){				
							echo "<option value='".$employeeList->EMP_ID."'";
							if(isset($library_department['department_head'])==$employeeList->EMP_ID){echo "selected ";}
							echo ">".$employeeList->EMP_NAME."</option>";
							}
							?>
						</select>
                    <span class='help-inline'><?php echo form_error('department_head'); ?></span>                      
                </div>
                </div>
               <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">

                  <div class="form-group <?php echo form_error('department_code') ? 'error' : ''; ?>">                       
					<label><?php echo lang('library_initial_department_code');?></label>                      
                    <input class="form-control" id='lib_department_code' type='text' name='lib_department_code' maxlength="285" value="<?php echo set_value('lib_department_code', isset($library_department['department_code']) ? $library_department['department_code'] : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('department_code'); ?></span>                        
                </div>
                </div>
               <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">

				<div class="form-group <?php echo form_error('department_phone') ? 'error' : ''; ?>">                       
					<label><?php echo lang('library_initial_department_phone').lang('bf_form_label_required');?></label>                      
                    <input class="form-control" id='lib_department_department_phone' type='text' name='lib_department_department_phone' maxlength="285" value="<?php echo set_value('lib_department_department_phone', isset($library_department['department_phone']) ? $library_department['department_phone'] : ''); ?>" required=""/>
                    <span class='help-inline'><?php echo form_error('department_phone'); ?></span>                        
                </div>
                </div>

            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
   <div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
					<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
					<select class="form-control" name="bf_status" id="bf_status" required="">
						<option value="1" <?php if(isset($library_department['status'])){if($library_department['status'] == 1){ echo "selected";}}?> >Active</option>
						<option value="0" <?php if(isset($library_department['status'])){if($library_department['status'] == 0){ echo "selected";}}?>>Inactive</option>
						
					 </select>
					<span class='help-inline'><?php echo form_error('status'); ?></span>
				</div>
				
				
                </div>
       
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <div class=" pager">
                 
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('save'); ?>"  /> 
              <button type="reset" class="btn btn-warning btn-sm">Reset</button>





                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>
















