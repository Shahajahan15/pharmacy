<?php
extract($sendData);
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
            <div class="col-sm-4 col-md-4 col-lg-4 col-md-offset-4">     
                                        
				<div class="form-group <?php echo form_error('BRANCH_ID') ? 'error' : ''; ?>">				
					<label><?php echo lang('library_branch_branch').lang('bf_form_label_required');?></label>
					<select name="library_branch_branch" id="library_branch_branch" class="form-control" required="">
						<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
						<?php foreach($branch as $branchs){				
							echo "<option value='".$branchs->BRANCH_ID."'";
							if($postDetails->BRANCH_ID==$branchs->BRANCH_ID){echo "selected ";}
							echo ">".$branchs->BRANCH_NAME."</option>";
							}
							?>
					</select>
					<span class='help-inline'><?php echo form_error('BRANCH_ID'); ?></span>					
                </div> 
				
			
				
				<div class="form-group <?php echo form_error('DEPARTMENT_ID') ? 'error' : ''; ?>">				
					<label><?php echo lang('library_branch_department');?></label>
					<select name="library_branch_department" id="library_branch_department" class="form-control">
						<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
						<?php foreach($department as $departments){				
							echo "<option value='".$departments->dept_id."'";
							if($postDetails->DEPARTMENT_ID==$departments->dept_id){echo "selected ";}
							echo ">".$departments->department_name."</option>";
							}
							?>
					</select>
					<span class='help-inline'><?php echo form_error('DEPARTMENT_ID'); ?></span>					
                </div> 	
				
				
				<div class="form-group <?php echo form_error('DESIGNATION_ID') ? 'error' : ''; ?>">				
					<label><?php echo lang('library_branch_designation').lang('bf_form_label_required');?></label>
					<select name="library_branch_designation" id="library_branch_designation" class="form-control" required="">
						<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
						<?php foreach($designation as $designations){				
							echo "<option value='".$designations->DESIGNATION_ID."'";
							if($postDetails->DESIGNATION_ID==$designations->DESIGNATION_ID){echo "selected ";}
							echo ">".$designations->DESIGNATION_NAME."</option>";
							}
							?>
					</select>
					<span class='help-inline'><?php echo form_error('DESIGNATION_ID'); ?></span>					
                </div> 
				
								
				<!--male/female -->				
				<div class="form-group <?php echo form_error('SEX') ? 'error' : ''; ?>">				
					<label><?php echo lang('library_sex').lang('bf_form_label_required');?></label>
					<select name="library_sex" id="library_sex" class="form-control" required="">
						<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
						<option value="1" <?php if(isset($postDetails->SEX)){if($postDetails->SEX == 1){ echo "selected";}}?> >Male</option>
						<option value="2" <?php if(isset($postDetails->SEX)){if($postDetails->SEX == 2){ echo "selected";}}?>>Female</option>
					</select>
					<span class='help-inline'><?php echo form_error('SEX'); ?></span>					
                </div> 			
				
					
				<div class="form-group <?php echo form_error('NUMBER_OF_POST') ? 'error' : ''; ?>">                      
					<label><?php echo lang('library_number_of_post').lang('bf_form_label_required');?></label>                       
                    <input class="form-control" id='library_number_of_post' type='text' name='library_number_of_post' maxlength="285" value="<?php echo set_value('library_number_of_post', isset($postDetails->NUMBER_OF_POST) ? $postDetails->NUMBER_OF_POST : ''); ?>"  required=""  onblur="postNumberCheck()" />
                    <span class='help-inline'><?php echo form_error('NUMBER_OF_POST'); ?></span>                   
                </div>
				
                    
                <div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/branch_wise_post/library/create_post', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>
