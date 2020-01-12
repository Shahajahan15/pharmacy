
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

if (isset($lib_base_head_details))
{
	$lib_base_head_details = (array) $lib_base_head_details;
}
	$BASE_HEAD_ID = isset($lib_base_head_details['BASE_HEAD_ID']) ? $lib_base_head_details['BASE_HEAD_ID'] : '';

?>
<!-- col-md-offset-4 -------- use this class for set div center---- --> 
<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">        
			<div class="col-sm-12 col-md-12 col-lg-12">   
				<div class="col-sm-4 col-md-4 col-lg-4"> <!-----left-->
					<!------------BASE_SYSTEM_HEAD_NAME----------->
					<div class="form-group <?php echo form_error('BASE_SYSTEM_HEAD') ? 'error' : ''; ?>">                   
						<label><?php echo lang('lib_base_system_head').lang('bf_form_label_required');?></label>              
						<input type='text' class="form-control" name='lib_base_system_head' id='lib_base_system_head'  maxlength="50" value="<?php echo set_value('lib_base_system_head', isset($lib_base_head_details['BASE_SYSTEM_HEAD']) ? $lib_base_head_details['BASE_SYSTEM_HEAD'] : ''); ?>" placeholder="<?php echo lang('lib_base_system_head')?>" required="" tabindex="1"/>
						<span class='help-inline'><?php echo form_error('BASE_SYSTEM_HEAD'); ?></span>                   
					</div>
					
					
					<!------------BASE_HEAD LOCAL LANGUAGE_NAME----------->
					<div class="form-group <?php echo form_error('BASE_HEAD_LOCAL_LANG') ? 'error' : ''; ?>">                    
						<label><?php echo lang('lib_base_head_local_language');?></label> 
						<input type='text' class="form-control" name='lib_base_head_local_language' id='lib_base_head_local_language'  maxlength="50" value="<?php echo set_value('lib_base_head_local_language', isset($lib_base_head_details['BASE_HEAD_LOCAL_LANG']) ? $lib_base_head_details['BASE_HEAD_LOCAL_LANG'] : ''); ?>" placeholder="<?php echo lang('lib_base_head_local_language')?>"  tabindex="4"/>
						<span class='help-inline'><?php echo form_error('BASE_HEAD_LOCAL_LANG'); ?></span>                   
					</div>
					
					
						<!-----------BASE HEAD-TYPE----------->
					<div class="form-group <?php echo form_error('BASE_HEAD_TYPE') ? 'error' : ''; ?>">					
						<label><?php echo lang('lib_base_head_type'). lang('bf_form_label_required');?></label> 										
							<select class="form-control" name="lib_base_head_type" id="lib_base_head_type" required="" tabindex="5">
								<option value=""><?php echo lang('bf_msg_selete_one');?></option>
								<?php 
									
									foreach($head_type as $key=>$val){
									
									echo "<option value='".$key."'";
									
									if(isset($lib_base_head_details['BASE_HEAD_TYPE'])){
										if(trim($lib_base_head_details['BASE_HEAD_TYPE'])==$key){echo "selected";}
									}
									echo ">".$val."</option>";
									}
									
								?>	
							 </select>
						 <span class='help-inline'><?php echo form_error('BASE_HEAD_TYPE'); ?></span>					
					</div>
					
					
				
				</div>
				
				
				<div class="col-sm-4 col-md-4 col-lg-4">  <!-----center-->
					<!------------BASE_HEAD_CUSTOM NAME----------->
					<div class="form-group <?php echo form_error('BASE_HEAD_CUSTOM_NAME') ? 'error' : ''; ?>">
						<label><?php echo lang('lib_base_head_custom_name');?></label>     
						<input type='text' class="form-control" name='lib_base_head_custom_name' id='lib_base_head_custom_name'  maxlength="50" value="<?php echo set_value('lib_base_head_custom_name', isset($lib_base_head_details['BASE_HEAD_CUSTOM_NAME']) ? $lib_base_head_details['BASE_HEAD_CUSTOM_NAME'] : ''); ?>" placeholder="<?php echo lang('lib_base_head_custom_name')?>" tabindex="2"/>
						<span class='help-inline'><?php echo form_error('BASE_HEAD_CUSTOM_NAME'); ?></span>                   
					</div>	
					
					<div class="form-group <?php echo form_error('IS_SALARY_HEAD') ? 'error' : ''; ?>">				
						<label><?php echo lang('lib_base_is_salary_head');?></label>
						<select name="lib_base_is_salary_head" id="lib_base_is_salary_head" class="form-control" required="">	
							<option value="0"><?php echo lang('bf_msg_selete_one'); ?></option>
							<option value="1" <?php if(isset($lib_base_head_details['IS_SALARY_HEAD'])){if($lib_base_head_details['IS_SALARY_HEAD'] == 1){ echo "selected";}}?> >Yes</option>
							<option value="0" <?php if(isset($lib_base_head_details['IS_SALARY_HEAD'])){if($lib_base_head_details['IS_SALARY_HEAD'] == 0){ echo "selected";}}?>>No</option>
						</select>
						<span class='help-inline'><?php echo form_error('IS_SALARY_HEAD'); ?></span>					
					</div> 
					
					<!------------DESCRIPTION----------->
					<div class="form-group <?php echo form_error('DESCRIPTION') ? 'error' : ''; ?>">					
						<label><?php echo lang('lib_base_head_description');?></label>
						<input type='text' class="form-control" name='lib_base_head_description' id='lib_base_head_description'  maxlength="100" value="<?php echo set_value('lib_base_head_description', isset($lib_base_head_details['DESCRIPTION']) ? $lib_base_head_details['DESCRIPTION'] : ''); ?>" placeholder="<?php echo lang('lib_base_head_description')?>" tabindex="6"/>
						<span class='help-inline'><?php echo form_error('DESCRIPTION'); ?></span>				
					</div>
				
				
				</div>
				
				<div class="col-sm-4 col-md-4 col-lg-4">  <!-----right-->
					<!------------BASE_HEAD ABBREBIATION_NAME----------->
					<div class="form-group <?php echo form_error('BASE_HEAD_ABBREBIATION') ? 'error' : ''; ?>">                   
						<label><?php echo lang('lib_base_head_abrebiation');?></label>  
						<input type='text' class="form-control" name='lib_base_head_abrebiation' id='lib_base_head_abrebiation'  maxlength="50" value="<?php echo set_value('lib_base_head_abrebiation', isset($lib_base_head_details['BASE_HEAD_ABBREBIATION']) ? $lib_base_head_details['BASE_HEAD_ABBREBIATION'] : ''); ?>" placeholder="<?php echo lang('lib_base_head_abrebiation')?>" tabindex="3"/>
						<span class='help-inline'><?php echo form_error('BASE_HEAD_ABBREBIATION'); ?></span>                   
					</div>
					
					
					<div class="form-group <?php echo form_error('IS_BASE_HEAD') ? 'error' : ''; ?>">				
						<label><?php echo lang('lib_base_is_base_head');?></label>
						<select name="lib_base_is_base_head" id="lib_base_is_base_head" class="form-control">		
							<option value="0"><?php echo lang('bf_msg_selete_one'); ?></option>
							<option value="1" <?php if(isset($lib_base_head_details['IS_BASE_HEAD'])){if($lib_base_head_details['IS_BASE_HEAD'] == 1){ echo "selected";}}?> >Yes</option>
							<option value="0" <?php if(isset($lib_base_head_details['IS_BASE_HEAD'])){if($lib_base_head_details['IS_BASE_HEAD'] == 0){ echo "selected";}}?>>No</option>
						</select>
						<span class='help-inline'><?php echo form_error('IS_BASE_HEAD'); ?></span>					
					</div> 	
					
					<div class="form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">				
						<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select name="bf_status" id="bf_status" class="form-control" required="">					
							<option value="1" <?php if(isset($lib_base_head_details['STATUS'])){if($lib_base_head_details['STATUS'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($lib_base_head_details['STATUS'])){if($lib_base_head_details['STATUS'] == 0){ echo "selected";}}?>>Inactive</option>
						</select>
						<span class='help-inline'><?php echo form_error('STATUS'); ?></span>					
					</div>
				
				</div>
			</div>	
			
			
			<div class="col-sm-12 col-md-12 col-lg-12"> 
                <div class="box-footer pager">
					<?php echo anchor(SITE_AREA .'/base_head_info/library/base_head_create', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>
					&nbsp;
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />    
				</div>
            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








