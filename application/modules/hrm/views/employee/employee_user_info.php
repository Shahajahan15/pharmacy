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

//foreach($userDetails as $userDetail): 
if(isset($userDetails)){	
	$userDetail = $userDetails;
}

?>


<style>
.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
    <fieldset class="box-body">
	
	<div class="col-sm-12 col-md-12 col-lg-12">
		<!-- Left -->
		<div class="col-sm-4 col-md-4 col-lg-4">
			<!-- ----------- email ---------------- --> 
			<div class="form-group <?php echo form_error('email') ? 'error' : ''; ?>">
				<label><?php echo lang('bf_email');?></label>
				<input type='text' name='email' id='email' class="form-control"  value="<?php echo set_value('email', isset($userDetail) ? $userDetail->email : ''); ?>"/>		
				<span class='help-inline'><?php echo form_error('email'); ?></span>
			</div>
			
			<input type='hidden' name='userId' id='userId' class="form-control"  value="<?php if(isset($userDetail)){echo $userDetail->id;} ?>"/>	
			
			<!-------------Display Name-------------> 
			<div class="form-group <?php echo form_error('display_name') ? 'error' : ''; ?>">
				<label><?php echo lang('bf_display_name');?></label>
				<input type='text' name='display_name' id='display_name' class="form-control"  value="<?php echo set_value('display_name', isset($userDetail) ? $userDetail->display_name : ''); ?>"/>		
				<span class='help-inline'><?php echo form_error('display_name'); ?></span>
			</div>	
			
			<!----------user login name-------------> 
			<div class="form-group <?php echo form_error('username') ? 'error' : ''; ?>">
				<label><?php echo lang('bf_username');?></label>
				<input type='text' name='username' id='username' class="form-control"  value="<?php echo set_value('username', isset($userDetail) ? $userDetail->username : ''); ?>"/>	
				<span class='help-inline'><?php echo form_error('username'); ?></span>
			</div>
			
		</div>
		
		
		<!-- Center -->
		<div class="col-sm-4 col-md-4 col-lg-4">
		
			<!------------password------------> 
			<div class="form-group <?php echo form_error('password') ? 'error' : ''; ?>">
				<label><?php echo lang('bf_password');?></label>
				<input type='password' name='password' id='password' class="form-control" />		
				<span class='help-inline'><?php echo form_error('password'); ?></span>
			</div>
			
			
			<!-------------password_confirm------------> 
			<div class="form-group <?php echo form_error('pass_confirm') ? 'error' : ''; ?>">
				<label><?php echo lang('bf_password_confirm');?></label>
				<input type='password' name='pass_confirm' id='pass_confirm' class="form-control"/>		
				<span class='help-inline'><?php echo form_error('pass_confirm'); ?></span>
			</div>
			
					
			<!------------- department name-------------> 
			<div class="form-group <?php echo form_error('department_id') ? 'error' : ''; ?>">
				<label><?php echo lang('us_department');?></label>
				<select class="form-control" name="department_id" id="department_id">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 					
						foreach($departmentName as $departmentNames)
						{								
							echo "<option value='".$departmentNames->dept_id."'";
							
							if(isset($userDetail->department_id))
							{
								if(trim($userDetail->department_id)== $departmentNames->dept_id){echo "selected";}
							}
							echo ">".$departmentNames->department_name."</option>";
						}			
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('department_id'); ?></span>
			</div>
		</div>
		
		
		
		<!-- Right -->
		<div class="col-sm-4 col-md-4 col-lg-4">
			<!------------lab name ------------> 
			<div class="form-group <?php echo form_error('lab_room_id') ? 'error' : ''; ?>">
				<label><?php echo lang('us_lab_room');?></label>
				<select class="form-control" name="lab_room_id" id="lab_room_id" tabindex="4">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
						foreach($labName as $labNames)
						{							
							echo "<option value='".$labNames->id."'";
							
							if(isset($userDetail->lab_room_id))
							{
								if(trim($userDetail->lab_room_id)== $labNames->id){echo "selected";}
							}
						
							echo ">".$labNames->lab_name."</option>";
						}							
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('lab_room_id'); ?></span>
			</div>
			
			
			<!------------store name ------------> 
			<div class="form-group <?php echo form_error('store_id') ? 'error' : ''; ?>">
				<label><?php echo lang('us_lab_room');?></label>
				<select class="form-control" name="store_id" id="store_id" tabindex="4">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
						foreach($storeName as $storeNames)
						{							
							echo "<option value='".$storeNames->STORE_ID."'";
							
							if(isset($userDetail->store_id))
							{
								if(trim($userDetail->store_id)== $storeNames->STORE_ID){echo "selected";}
							}
						
							echo ">".$storeNames->STORE_NAME."</option>";
						}							
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('store_id'); ?></span>
			</div>
			
			
			<!------------role name ------------> 
			<div class="form-group <?php echo form_error('role_id') ? 'error' : ''; ?>">
				<label><?php echo lang('us_role');?></label>
				<select class="form-control" name="role_id" id="role_id" tabindex="4">
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
						
						foreach($rolesName as $rolesNames)
						{							
							echo "<option value='".$rolesNames->role_id."'";
							
							if(isset($userDetail->role_id))
							{
								if(trim($userDetail->role_id)== $rolesNames->role_id){echo "selected";}
							}
						
							echo ">".$rolesNames->role_name."</option>";
						}							
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('role_id'); ?></span>
			</div>
				
		</div>	
				
	</div>	
	
		
	<div class="col-md-12"> 
		<div class="col-md-12"> 
			<div class="col-md-10 box-footer pager">
				 <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
				&nbsp;
				<?php echo anchor(SITE_AREA .'/employee/hrm/emp_userInfo', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
			</div>
		</div>
	</div>

</div>  
</fieldset>
<?php echo form_close(); ?>
</div>