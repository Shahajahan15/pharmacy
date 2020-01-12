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

$id 		= isset($employeeId) ? $employeeId : '';
$tab_active = isset($tab_active) ? $tab_active : "#emp_personal_info";
$tab_url	= isset($tab_url) ? $tab_url : "employee/hrm/employee_personal_info/";
?>


<div class="box box-primary row">
	<div class="col-md-12 col-lg-12">
		<!-- Custom Tabs -->
		<div class="nav-tabs-custom">
		
			<ul class="nav nav-pills nav-wizard employeeCreateForm">
			
				<li class="active">
				<a href="#emp_personal_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employee_personal_info/'.$id?>">Personal Info</a>
					
				</li>
				
				 <li class="disabled">
					 <a class="tab-disabled" href="#employee_contact_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employee_contact_info/'.$id?>">Contact Info</a>
					 
				 </li>							 
				 
				<li class="disabled">
					<a class="tab-disabled" href="#employee_family_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employee_family_info/'.$id?>">Family Info</a>
				</li>
				 
				<li class="disabled">
					<a class="tab-disabled" href="#emp_curriculam_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/emp_curriculam_info/'.$id?>">Curriculum Info</a>
				</li>							
				
				<!--<li class="disabled">								
					<a class="tab-disabled" href="#employee_job_experience_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employee_job_experience_info/'.$id?>">Job Experience</a>								
				</li>	-->		  
				  
				  
				<!--<li class="disabled">
					<a class="tab-disabled" href="#employee_training_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employee_training_info/'.$id?>">Training Info</a>
				</li>-->		

				
				<li class="disabled">
					<a class="tab-disabled" href="#emp_important_documentation" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/emp_important_documentation/'.$id?>">Important Documentation</a>
				</li>
				
				<!--<li class="disabled">
					<a class="tab-disabled" href="#employee_posting_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employee_posting_info/'.$id?>">Posting</a>
				</li>-->
				
				<li class="disabled">
					<a class="tab-disabled" href="#employee_bank_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employee_bank_info/'.$id?>">Bank Info</a>
				</li>
				
				<li class="disabled">
					<a class="tab-disabled" href="#emp_weekend_define" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/emp_weekend_define/'.$id?>">Weekend</a>
				</li>
				
				<li class="disabled">
					<a class="tab-disabled" href="#emp_policy_tagging" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/emp_policy_tagging/'.$id?>">Policy Tagging</a>
				</li>
				
				
				<!--<li class="disabled">
					<a class="tab-disabled" href="#emp_appointment_info" data-toggle="tab" data-url="<?php echo site_url().'/admin/employee/hrm/employeeAppointmentInfo/'.$id?>">Appointment Info</a>
				</li>-->			
				
				
				<!--<?php
					$transferUrl ='/admin/employee_transfer/hrm/transfer_create?employee_id='.$id;
				?>
				<li class="disabled"><a class="" href="<?php echo site_url().$transferUrl?>" onclick="window.location.href=$(this).attr('href')">Transfer</a></li>-->
				
				
				<?php
					$credentialUrl = (int)$user_id ? '/admin/settings/users/edit/'.(int)$user_id : '/admin/settings/users/create?employee_id='.$id;
				?>
				
				<li class="disabled"><a class="" href="<?php echo site_url().$credentialUrl?>" onclick="window.location.href=$(this).attr('href')">Credential</a></li>
				
			</ul>
			
			<div class="tab-content">
				<!-- /.tab-pane -->
				<div class="tab-pane active" id="emp_personal_info"></div>							
				<div class="tab-pane" id="employee_contact_info"></div>
				<div class="tab-pane" id="employee_family_info"></div>
				<div class="tab-pane" id="emp_curriculam_info"></div>
				<div class="tab-pane" id="employee_job_experience_info"></div>							
				<!--<div class="tab-pane" id="employee_training_info"></div> 	-->					
				<div class="tab-pane" id="emp_important_documentation"></div>						
				<div class="tab-pane" id="employee_posting_info"></div>							
				<div class="tab-pane" id="employee_bank_info"></div>
				<div class="tab-pane" id="emp_weekend_define"></div>							
				<div class="tab-pane" id="emp_policy_tagging"></div>
				<!--<div class="tab-pane" id="emp_appointment_info"></div>	-->							
				<div class="tab-pane" id="emp_userInfo"></div>							
				<!-- /.tab-pane -->
			</div><!-- /.tab-content -->
			<!-- END CUSTOM TABS -->						
				</div><!-- nav-tabs-custom -->
	</div><!-- /.col -->
	   
           
<input class="form-control" id='tab_active'  name='tab_active' type='hidden'  maxlength="30" value="<?php echo set_value('tab_active', isset($tab_active) ? $tab_active : 'emp_personal_info'); ?>"/>

<input class="form-control" id='tab_url'  name='tab_url' type='hidden'  maxlength="30" value="<?php echo set_value('tab_url', isset($tab_url) ? $tab_url : 'employee/hrm/employee_personal_info/'); ?>"/>

<input class="form-control" id='id'  name='id' type='hidden'  maxlength="30" value="<?php echo set_value('id', isset($id) ? $id : '0'); ?>"/>
                    
</div>