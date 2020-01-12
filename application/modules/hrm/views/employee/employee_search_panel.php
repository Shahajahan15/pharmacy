<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
			  <div class="panel-title">
				    <span class="glyphicon glyphicon-plus"></span>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Search Panel
				</a>
			  </div>
              
           
			</div>
			<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
			  <div class="panel-body">					
				  <div class="row">		  
				  
				  
				  <div class="col-md-2">					
					<input type="text" class="form-control" name="employee_emp_id" id="employee_emp_id" value="<?php echo set_value('employee_emp_id', isset($src_emp['EMP_ID ']) ? $src_emp['EMP_ID '] : '');?>" placeholder="<?php echo lang("employee_emp_id");?>" title="<?php echo lang("employee_emp_id");?>">				
				  </div>
				  
				  <div class="col-md-3">
					<input type="text" class="form-control" name="hrm_employee_name" id="hrm_employee_name" value="<?php echo set_value('hrm_employee_name', isset($src_emp['EMP_NAME']) ? $src_emp['EMP_NAME'] : '');?>" placeholder="<?php e(lang('hrm_employee_name'));?>" title="<?php e(lang('hrm_employee_name'));?>">
				  </div>
				  
				  <div class="col-md-3">
					<input type="text" class="form-control" name="employee_mobile_no" id="employee_mobile_no" value="<?php echo set_value('employee_mobile_no', isset($src_emp['MOBILE']) ? $src_emp['MOBILE'] : '');?>" placeholder="<?php e(lang('employee_mobile_no'));?>" title="<?php e(lang('employee_mobile_no'));?>">
				  </div>			 
				  
				  <div class="col-md-1">	
				  <button type="submit" name="search" id="search" class="btn btn-default pull-right"><span class="glyphicon glyphicon-search"> </span></button>
				  </div>
				  
				</div>
				
			  </div>
			</div>
		  </div>