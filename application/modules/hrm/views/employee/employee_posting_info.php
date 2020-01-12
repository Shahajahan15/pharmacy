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
if (isset($posting_details)){
	 $posting_details = (array) $posting_details;
}

?>
<style>
.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
<?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
<fieldset class="box-body">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading" align="center">
				<h3 class="panel-title">Employee Posting Informations</h3>
			</div>
			
			<div class="panel-body">
			<div class="col-sm-4 col-md-4 col-lg-4 ">
			</div>
		
			<div class="col-sm-4 col-md-4 col-lg-4">
				
				<!--------------POSITION-------------->					
				<div class="form-group <?php echo form_error('POSITION_ID') ? 'error' : ''; ?>">
					<label><?php echo lang('emp_position_select').lang('bf_form_label_required');?></label>
					<select name="emp_position_select" id="emp_position_select" class="form-control" required="" onchange="getDistrictList(this.value)" tabindex="1">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
						foreach($deignation_list as $designation)
						{
							echo "<option value='".$designation->DESIGNATION_ID."'";
								if(isset($posting_details['POSITION_ID']))
								{
									if($posting_details['POSITION_ID'] == $designation->DESIGNATION_ID)
									{ 
										echo "selected ";
									}
								}
							echo ">".$designation->DESIGNATION_NAME."</option>";
						}
						?>
					 </select>
					 <span class='help-inline'><?php echo form_error('POSITION_ID'); ?></span>
				</div>

				<!--------------BRANCH_NAME-------------->					
				<div class="form-group <?php echo form_error('BRANCH_NAME') ? 'error' : ''; ?>">
					<label><?php echo lang('emp_branch_select').lang('bf_form_label_required');?></label>
					<select name="emp_branch_select" id="emp_branch_select" class="form-control" required="" onchange="getDistrictList(this.value)" tabindex="2">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php
						foreach($branchName as $branchNames)
						{							
						
							echo "<option value='".$branchNames->BRANCH_ID."'";	
							
							if(isset($posting_details['BRANCH_NAME']) )
							{							
								if($posting_details['BRANCH_NAME'] == $branchNames->BRANCH_ID)
								{ 
									echo "selected ";
								}
							}						
							echo ">".$branchNames->BRANCH_NAME."</option>";
						}
						?>
					 </select>
					 <span class='help-inline'><?php echo form_error('BRANCH_NAME'); ?></span>
				</div>


				<!--------------BRANCH_CATEGORY-------------->					
				<div class="form-group <?php echo form_error('BRANCH_CATEGORY') ? 'error' : ''; ?>">
					<label><?php echo lang('emp_branch_category_select').lang('bf_form_label_required');?></label>
					<select name="emp_branch_category_select" id="emp_branch_category_select" class="form-control" required="" onchange="getDistrictList(this.value)" tabindex="3">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php
						foreach($branch_category as $key=>$val)
						{													
							echo "<option value='".$key."'";	
							
							if(isset($posting_details['BRANCH_CATEGORY']) )
							{								
								if($posting_details['BRANCH_CATEGORY']==$key){ echo "selected";}					
							}						
							echo ">".$val."</option>";
						}
						?>
					 </select>
					 <span class='help-inline'><?php echo form_error('BRANCH_CATEGORY'); ?></span>
				</div>			


				<!--------------JOB_RESPONSIBILITY-------------->	
				<div class="form-group <?php echo form_error('JOB_RESPONSIBILITY') ? 'error' : ''; ?>">
					<label><?php echo lang('emp_job_responsibility').' '. lang('ENGLISH');?></label>
					<input class="form-control" id='emp_job_responsibility'  name='emp_job_responsibility' type='text'  maxlength="100" value="<?php echo set_value('JOB_RESPONSIBILITY', isset($posting_details['JOB_RESPONSIBILITY']) ? $posting_details['JOB_RESPONSIBILITY'] : ''); ?>"  required="" tabindex="4"/>
					<span class='help-inline'><?php echo form_error('JOB_RESPONSIBILITY'); ?></span>
				</div>
								
				<!--------------JOB RESPONSIBILITY IN BENGALI -------------->	
				<div class="form-group <?php echo form_error('emp_job_responsibility_bengali') ? 'error' : ''; ?>">
					<label><?php echo lang('emp_job_responsibility').' ' . lang('BENGALI');?></label>
					<input class="form-control" id='emp_job_responsibility_bengali'  name='emp_job_responsibility_bengali' type='text'  maxlength="100" value="<?php echo set_value('emp_job_responsibility_bengali', isset($posting_details['EMP_JOB_RESPONSIBILITY_BENGALI']) ? $posting_details['EMP_JOB_RESPONSIBILITY_BENGALI'] : ''); ?>" tabindex="4"/>
					<span class='help-inline'><?php echo form_error('emp_job_responsibility_bengali'); ?></span>
				</div>				
					
				<!--------------POSTED_AS_BRANCH_MANAGER-------------->						
				<div class="form-group <?php echo form_error('POSTED_AS_BRANCH_MANAGER') ? 'error' : ''; ?>">  
					<label><?php echo lang('emp_as_branch_manager').lang('bf_form_label_required');?></label><br>
					<input type="radio" name="POSTED_AS_BRANCH_MANAGER" value="1" <?php if(isset($posting_details['POSTED_AS_BRANCH_MANAGER']) && $posting_details['POSTED_AS_BRANCH_MANAGER'] == 1) {echo "checked";}?>> <label><?php echo lang('YES');?></label>
					<input type="radio" name="POSTED_AS_BRANCH_MANAGER" value="2" <?php if(isset($posting_details['POSTED_AS_BRANCH_MANAGER']) && $posting_details['POSTED_AS_BRANCH_MANAGER'] == 2) {echo "checked";}?> > <label><?php echo lang('NO');?></label>
					<span class='help-inline'><?php echo form_error('POSTED_AS_BRANCH_MANAGER'); ?></span>
				</div>
				
			</div>
			<!-- panel body end -->
			</div>	<!-- for panel end -->
		</div>
	</div>
		<div class="col-md-12"> 
			<div class="col-md-10 box-footer pager">
				
				<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-default"'); ?>
				
				<?php echo lang('bf_or'); ?>
				
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save').' '.lang('bf_action_next'); ?>"/>
				
			</div>
		</div>
</fieldset>
<?php echo form_close(); ?>
</div>