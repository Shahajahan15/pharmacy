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

$id 		= isset($policyId) ? $policyId : '';
$tab_active = isset($tab_active) ? $tab_active : "#leavePolicy";
$tab_url	= isset($tab_url) ? $tab_url : "policy/hrm/leave_policy/";
?>


<div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-pills nav-wizard policyCreateForm">
						
                            <li class="active"><a href="#leavePolicy" data-toggle="tab" data-url="<?php echo site_url().'/admin/policy/hrm/leave_policy/';?>">Leave</a><div></div></li>
							<li class="disabled"><a class="tab-disabled" href="#absentPolicy" data-toggle="tab" data-url="<?php echo site_url().'/admin/policy/hrm/absent_policy/'.$id?>">Absent</a></li>
							<li class="disabled"><a class="tab-disabled" href="#maternityPolicy" data-toggle="tab" data-url="<?php echo site_url().'/admin/policy/hrm/maternity_policy/'.$id?>">Maternity</a></li>						
							<li class="disabled"><a class="tab-disabled" href="#policyMedical" data-toggle="tab" data-url="<?php echo site_url().'/admin/policy/hrm/medical_policy/'.$id?>">Medical</a></li>
							<li class="disabled"><a class="tab-disabled" href="#policyShift" data-toggle="tab" data-url="<?php echo site_url().'/admin/policy/hrm/shift_policy/'.$id?>">Shift</a></li>
							<li class="disabled"> <a class="tab-disabled" href="#policyBonus" data-toggle="tab" data-url="<?php echo site_url().'/admin/policy/hrm/bonus_policy/'.$id?>">Bonus</a></li>
							<li class="disabled"> <a class="tab-disabled" href="#rosterPolicy" data-toggle="tab" data-url="<?php echo site_url().'/admin/policy/hrm/roster_policy/'.$id?>">Roster</a></li>
                        </ul>
                        <div class="tab-content">
							<!-- /.tab-pane -->
                            <div class="active" id="leavePolicy"></div>							
							<div id="absentPolicy"></div>
							<div id="maternityPolicy"></div>							
							<div id="policyMedical"></div>
							<div id="policyShift"></div>
							<div id="policyBonus"></div>						
							<div id="rosterPolicy"></div>
							<!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->
                </div><!-- /.col -->
            </div> <!-- /.row -->
            <!-- END CUSTOM TABS -->

      </div>
<input class="form-control" id='tab_active'  name='tab_active' type='hidden'  maxlength="30" value="<?php echo set_value('tab_active', isset($tab_active) ? $tab_active : 'leavePolicy'); ?>"/>

<input class="form-control" id='tab_url'  name='tab_url' type='hidden'  maxlength="30" value="<?php echo set_value('tab_url', isset($tab_url) ? $tab_url : 'policy/hrm/leave_policy/'); ?>"/>

<input class="form-control" id='id'  name='id' type='hidden'  maxlength="30" value="<?php echo set_value('id', isset($id) ? $id : '0'); ?>"/>
                    
</div>