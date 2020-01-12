
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

if (isset($branch_details))
{
	$branch_details = (array) $branch_details;
}
	$id = isset($branch_details['BRANCH_ID']) ? $branch_details['BRANCH_ID'] : '';

?>


<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
        <fieldset class="box-body">        
            <div class="col-sm-12 col-md-12 col-lg-12"> 
			
				<div class="col-sm-3 col-md-3 col-lg-3"> 
					<!------------Company Name----------->
					<div class="form-group <?php echo form_error('COMPANY_ID') ? 'error' : ''; ?>">
						<label><?php echo lang('library_branch_company').lang('bf_form_label_required');?></label>									
						<select class="form-control" name="library_branch_company" id="library_branch_company" tabindex="1" required="">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
							<?php 
								
									foreach($company as $company_list)
									{									
										echo "<option value='".$company_list->id."'";	

										if(isset($branch_details['COMPANY_ID']))
										{							
											if($branch_details['COMPANY_ID'] == $company_list->id){ echo "selected";}
										}
										
										echo ">".$company_list->company_name."</option>";
									}
								
							?>							
						 </select>
						<span class='help-inline'><?php echo form_error('COMPANY_ID'); ?></span>
					</div>					
							
					<!------------Address English----------->
					<div class="form-group <?php echo form_error('BRANCH_ADDRESS') ? 'error' : ''; ?>">				
						<label><?php echo lang('library_branch_address').lang('bf_form_label_required');?></label>
						<input type='text' class="form-control" name='library_branch_address' id='library_branch_address'  maxlength="150" 
						value="<?php echo set_value('library_branch_address', isset($branch_details['BRANCH_ADDRESS']) ? $branch_details['BRANCH_ADDRESS'] : ''); ?>"  required=""/>
						<span class='help-inline'><?php echo form_error('BRANCH_ADDRESS'); ?></span>
					
					</div>
				</div>					
					
					
				<div class="col-sm-3 col-md-3 col-lg-3"> 	
					<!------------branch category----------->
					<div class="form-group <?php echo form_error('BRANCH_CATEGORY') ? 'error' : ''; ?>">					
						<label><?php echo lang('library_branch_category').lang('bf_form_label_required');?></label>
						<select class="form-control" name="library_branch_category" id="library_branch_category" required="">
							<option value=""><?php echo lang('bf_msg_selete_one');?></option>
							<?php 								
								foreach($branch_category as $key=>$val)
								{									
									echo "<option value='".$key."'";	

									if(isset($branch_details['BRANCH_CATEGORY']))
									{							
										if($branch_details['BRANCH_CATEGORY'] == $key){ echo "selected";}
									}									
									echo ">".$val."</option>";
								}								
							?>							
						 </select>
						<span class='help-inline'><?php echo form_error('BRANCH_CATEGORY'); ?></span>
					</div>
					
					<!------------Address Bangla----------->
					<div class="form-group <?php echo form_error('BRANCH_ADDRESS_BANGLA') ? 'error' : ''; ?>">					
						<label><?php echo lang('library_branch_address_bangla');?></label>
						<input type='text' class="form-control bn_language" name='library_branch_address_bangla' id='library_branch_address_bangla'  maxlength="150" value="<?php echo set_value('library_branch_address_bangla', isset($branch_details['BRANCH_ADDRESS_BANGLA']) ? $branch_details['BRANCH_ADDRESS_BANGLA'] : ''); ?>" />
						<span class='help-inline'><?php echo form_error('BRANCH_ADDRESS_BANGLA'); ?></span>				
					</div>				
				</div>
				
				
				<div class="col-sm-3 col-md-3 col-lg-3"> 
					<!------------Branch Name English----------->				
					<div class="form-group <?php echo form_error('BRANCH_NAME') ? 'error' : ''; ?>">
						<div id="checkBranchName" style="color:#F00; font-size:14px;"></div>
						<label><?php echo lang('library_branch_branch').lang('bf_form_label_required');?></label>								
						<input type='text' class="form-control" name='library_branch_branch'  id='library_branch_branch' maxlength="150" value="<?php echo set_value('library_branch_branch', isset($branch_details['BRANCH_NAME']) ? $branch_details['BRANCH_NAME'] : ''); ?>"  required="" onblur="branchCheck()"/>
						<span class='help-inline'><?php echo form_error('BRANCH_NAME'); ?></span>					
					</div>
					
					<!------------Description----------->
					<div class="form-group <?php echo form_error('DESCRIPTION') ? 'error' : ''; ?>">					
						<label><?php echo lang('library_branch_description');?></label>
						<input type='text' class="form-control" name='library_branch_description' id='library_branch_description'  maxlength="150" value="<?php echo set_value('library_branch_description', isset($branch_details['DESCRIPTION']) ? $branch_details['DESCRIPTION'] : ''); ?>" />
						<span class='help-inline'><?php echo form_error('DESCRIPTION'); ?></span>				
					</div>					
				</div>	
					
				
				<div class="col-sm-3 col-md-3 col-lg-3"> 
					<!------------Branch Name bangla----------->				
					<div class="form-group <?php echo form_error('BRANCH_NAME_BANGLA') ? 'error' : ''; ?>">
						<div id="checkBranchName" style="color:#F00; font-size:14px;"></div>					
						<label><?php echo lang('library_branch_branch_bangla');?></label>
						<input type='text' class="form-control bn_language" name='library_branch_branch_bangla'  id='library_branch_branch_bangla' maxlength="150" value="<?php echo set_value('library_branch_branch_bangla', isset($branch_details['BRANCH_NAME_BANGLA']) ? $branch_details['BRANCH_NAME_BANGLA'] : ''); ?>"   onblur="branchCheck()"/>
						<span class='help-inline'><?php echo form_error('BRANCH_NAME_BANGLA'); ?></span>					
					</div>
					
					<!-- status -->
					<div class="form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">					
						<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="library_branch_status" id="library_branch_status" required="">
							<option value="1" <?php if(isset($branch_details['STATUS'])){if($branch_details['STATUS'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($branch_details['STATUS'])){if($branch_details['STATUS'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('STATUS'); ?></span>
					</div>				
				</div>			
			</div>
			
			
			<div class="col-sm-12 col-md-12 col-lg-12"> 
						
				<div class="box-footer pager">
					<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
					&nbsp;
					<?php echo anchor(SITE_AREA .'/branch_info/library/branch_create', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>
					
				</div>
			</div>	
        </fieldset>

    <?php echo form_close(); ?>

</div>








