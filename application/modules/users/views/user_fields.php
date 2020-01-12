<?php /* /bonfire/modules/users/views/user_fields.php */

$currentMethod = $this->router->fetch_method();

$errorClass     = empty($errorClass) ? ' error' : $errorClass;
$controlClass   = empty($controlClass) ? 'span4' : $controlClass;
$registerClass  = $currentMethod == 'register' ? ' required' : '';
$editSettings   = $currentMethod == 'edit';
?>

<!--<input type="hidden" id="employee_id" name="employee_id" value="<?php echo isset($user) ? set_value('employee_id', isset($user) ? $user->employee_id : '') : $employee_id; ?>" />-->
<div class="col-md-8">
<div class="control-group<?php echo iif(form_error('email'), $errorClass); ?>">
    <label class="control-label" for="email"><?php echo lang('bf_email'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="text" id="email" name="email" value="<?php echo set_value('email', isset($user) ? $user->email : ''); ?>" />
        <span class="help-inline"><?php echo form_error('email'); ?></span>
    </div>
</div>

<div class="control-group<?php echo iif(form_error('display_name'), $errorClass); ?>">
    <label class="control-label" for="display_name"><?php echo lang('bf_display_name'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="text" id="display_name" name="display_name" value="<?php echo set_value('display_name', isset($user) ? $user->display_name : ''); ?>" />
        <span class="help-inline"><?php echo form_error('display_name'); ?></span>
    </div>
</div>

<?php if (settings_item('auth.login_type') !== 'email' OR settings_item('auth.use_usernames')) : ?>
<div class="control-group<?php echo iif(form_error('username'), $errorClass); ?>">
    <label class="control-label required" for="username"><?php echo lang('bf_username'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="text" id="username" name="username" value="<?php echo set_value('username', isset($user) ? $user->username : ''); ?>" />
        <span class="help-inline"><?php echo form_error('username'); ?></span>
    </div>
</div>
<?php endif; ?>

<div class="control-group<?php echo iif(form_error('password'), $errorClass); ?>">
    <label class="control-label required <?php echo $registerClass; ?>" for="password"><?php echo lang('bf_password'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="password" id="password" name="password" value="" />
        <span class="help-inline"><?php echo form_error('password'); ?></span>
        <p class="help-block"><?php if (isset($password_hints) ) { echo $password_hints; } ?></p>
    </div>
</div>

<div class="control-group<?php echo iif(form_error('pass_confirm'), $errorClass); ?>">
    <label class="control-label required <?php echo $registerClass; ?>" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="password" id="pass_confirm" name="pass_confirm" value="" />
        <span class="help-inline"><?php echo form_error('pass_confirm'); ?></span>
    </div>
</div>

<?php if ($editSettings) : ?>
<div class="control-group<?php echo iif(form_error('force_password_reset'), $errorClass); ?>">
    <div class="controls">
        <label class="checkbox" for="force_password_reset">
            <input type="checkbox" id="force_password_reset" name="force_password_reset" value="1" <?php echo set_checkbox('force_password_reset', empty($user->force_password_reset)); ?> />
            <?php echo lang('us_force_password_reset'); ?>
        </label>
    </div>
</div>

<?php endif;?>

<div class="control-group<?php echo iif(form_error('department_id'), $errorClass); ?>">
    <label class="control-label" for="department_id"><?php echo lang('us_department'); ?><span class="required">*</span></label>
    <div class="controls">
        <select name="department_id" class="form-control" id="department_id" >
            <option value=""><?php echo lang('us_select').lang('us_department'); ?></option>
            <?php
            if($department){
                foreach($department as $row){
                    echo "<option value='".$row->dept_id."'";
                    if (isset($employee_info)) {
						if( isset($employee_info) && $employee_info->EMP_DEPARTMENT == $row->dept_id){echo "selected ";}
					} else {
						if( isset($user) && $user->department_id == $row->dept_id){echo "selected ";}
					}
                    
                    echo ">".$row->department_name."</option>";
                }
            }
            ?>
        </select>
        <span class="help-inline"><?php echo form_error('department_id'); ?></span>
    </div>
</div>

<div class="control-group<?php echo iif(form_error('employee_id'), $errorClass); ?>">
    <label class="control-label" for="department_id">Employee Name</label>
    <div class="controls">
        <select name="employee_id" class="form-control" id="employee_idd" required="" >
            <option value="0"><?php echo lang('us_select')."Employee" ?></option>
            <?php
            if($employee){
                foreach($employee as $row){
                    echo "<option value='".$row->id."'";
                    if (isset($employee_info)) {
						if( isset($employee_info) && $employee_info->EMP_ID == $row->id){echo "selected ";}
					} else {
						if( isset($user) && $user->employee_id == $row->id){echo "selected ";}
					}
                    echo ">".$row->emp_name."</option>";
                }
            }
            ?>
        </select>
        <span class="help-inline"><?php echo form_error('department_id'); ?></span>
    </div>
</div>

<div class="control-group<?php echo iif(form_error('type'), $errorClass); ?>">
    <label class="control-label" for="type">Type</label>
    <div class="controls">
        <select name="type" class="form-control" id="type" >
            <option value="0"><?php echo lang('us_select')."Type"; ?></option>
            <option value="1" <?php if( isset($user) && $user->type == 1){echo "selected ";} ?>>Sample</option>
            <option value="2" <?php if( isset($user) && $user->type == 2){echo "selected ";} ?>>Lab Room</option>
            <option value="3" <?php if( isset($user) && $user->type == 3){echo "selected ";} ?>>Main Store</option>
            <option value="4" <?php if( isset($user) && $user->type == 4){echo "selected ";} ?>>Pharmacy</option>
        </select>
        <span class="help-inline"><?php echo form_error('type'); ?></span>
    </div>
</div>
<div id="type_name_id">
	<div class="control-group<?php echo iif(form_error('type_id'), $errorClass); ?>">
		 <label class="control-label" for="type_id">Type Name</label>
		 <div class="controls">
	        <select name="type_id" class="form-control" id="type_id" >
	        	<?php
	        		if ($select_name) :
	        	 ?>
	        	 <option value="0">Select <?php echo $select_name; ?></option>
	        	 <?php else : ?>
	        		<option value="0">Select Type Name</option>
	        	<?php endif; ?>
	        	<?php if ($type_name) : 
	        		foreach ($type_name as $row) :
	        	?>
	        	<option value="<?php echo $row->id; ?>" <?php if( isset($select_type) && $select_type == $row->id){echo "selected ";} ?>><?php echo $row->name; ?></option>
	        	<?php endforeach; endif; ?>
	        </select>
	        <span class="help-inline"><?php echo form_error('type_id'); ?></span>
    </div>
	</div>
</div>
</div>

<!-- nasir 02-02-16  add mill dropdown--> 
<?php /*
<div class="control-group<?php echo iif(form_error('branch_id'), $errorClass); ?>">
    <label class="control-label" for="branch_id"><?php echo lang('us_branch_name'); ?></label>
    <div class="controls">
        <select name="branch_id" id="branch_id" >
            <option value="0"><?php echo lang('us_select'); ?></option>
            <?php  // mill name is branch name
            if($branch_name){
                foreach($branch_name as $row){
                    echo "<option value='".$row->BRANCH_ID."'";
                    if( isset($user) && $user->branch_id == $row->BRANCH_ID){echo "selected ";}
                    echo ">".$row->BRANCH_NAME."</option>";
                }
            }
            ?>
        </select>
        <span class="help-inline"><?php echo form_error('branch_id'); ?></span>
    </div>
</div>


<div class="control-group<?php echo iif(form_error('image_profile'), $errorClass); ?>">
    <label class="control-label" for="image_profile">Image</label>
    <div class="controls">
        <input type="file" name="image_profile">
        <span class="help-inline"><?php echo form_error('image_profile'); ?></span>
    </div>
</div>

*/?>

<script>
	$(document).ready(function(){
		/*       get employee name by department name     */
		$("#department_id").on("change",function(){
				var ci_csrf_token = $("input[name='ci_csrf_token']").val();
				var id = $(this).val();
				var sendData = {id: id, ci_csrf_token: ci_csrf_token}
				//console.log(sendData);
				var targetUrl = siteURL +"settings/users/getEmployeeNameByDepartmentId";
				$("#employee_idd").html('<option>Loading...</option>');
				$.ajax({
		        	url: targetUrl,
		        	type: "POST",
		        	data: sendData,
		        	dataType: "json",
		        	success: function (response) {
		        		console.log(response);
		        		$("#employee_idd").html(response);
		        }, error: function (jqXHR) {
		        	console.log(jqXHR);
		            showMessages('Unknown Error!!!', 'error');
		        }
		    });
		});
		/*       get employee name by department name     */
		$("#type").on("change",function(){
				var ci_csrf_token = $("input[name='ci_csrf_token']").val();
				var type = $(this).val();
				var sendData = {type: type, ci_csrf_token: ci_csrf_token}
				//alert(id);return;
				//console.log(sendData);
				var targetUrl = siteURL +"settings/users/getDifferentIdByTypeId";
				$.ajax({
		        	url: targetUrl,
		        	type: "POST",
		        	data: sendData,
		        	dataType: "json",
		        	success: function (response) {
		        		console.log(response);
		        		$("#type_name_id").show();
		        		$("#type_id").html(response);
		        }, error: function (jqXHR) {
		        	console.log(jqXHR);
		            showMessages('Unknown Error!!!', 'error');
		        }
		    });
		});
	});
</script>