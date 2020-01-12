<?php if(isset($SendData))extract($SendData); ?>

	<!-- Start Search -->
	
	<div class="panel panel-primary ">
		<div class="panel-heading" role="tab" id="headingOne">
			<div class="panel-title center">
				<span class="glyphicon glyphicon-plus"></span>
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				Search Panel
				</a>
			</div>
		</div>
		<div id="" class=" " role="tabpanel" aria-labelledby="headingOne">
			<div class="panel-body">							
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="col-sm-6 col-md-3 col-lg-3  ">
							<div class="form-group">
								<label for="employee_id" class=""><?php echo lang('employee_emp_id');?></label> 
								<input type='text' name='employee_id' class="form-control " id='employee_id'     placeholder="<?php echo lang('employee_emp_id')?>"  tabindex="1"/>
								<span class='help-inline'><?php echo form_error('employee_id'); ?></span>
								
							</div>
							</div>
						<div class="col-sm-6 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="employee_name" class=""><?php echo lang('hrm_employee_name');?></label> 
								<select class="form-control chosenCommon chosen-single " name="employee_name" id="employee_name"  tabindex="2" >
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
									<?php 
										 if(isset($employee_details))
										{											
											foreach($employee_details as  $employee_detail ): 
									?> 
											<option value="<?php echo $employee_detail->EMP_ID; ?>" 
											<?php if(isset($movement_details)){
												if($movement_details->EMP_ID ==  $employee_detail->EMP_ID ){
													
													echo 'selected';
												}
											} ?>
											
											><?php echo $employee_detail->EMP_NAME; ?></option>
									<?php 
											endforeach;
										} 
									?>							
								</select>
								<span class='help-inline'><?php echo form_error('employee_name'); ?></span>
								
							</div>
						</div>
						
						<div class="col-sm-6 col-md-3 col-lg-3  ">
							<div class="form-group">
								<label for="employee_designation"  ><?php echo lang('hrm_employee_designation');?></label>
								<select class="form-control  " name="employee_designation" id="employee_designation"  tabindex="3">
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
										<?php 
										 if(isset($designation_details))
										{											
											foreach($designation_details as  $designation_detail ): 
									?>
											<option value="<?php echo $designation_detail->	DESIGNATION_ID; ?>"><?php echo $designation_detail->DESIGNATION_NAME; ?></option>
									<?php 
											endforeach;
										} 
									?>							
								</select>
								<span class='help-inline'><?php echo form_error('employee_designation'); ?></span>
							</div>
						</div>
						<div class="col-sm-6 col-md-3 col-lg-3  ">
							<div class="form-group">
								<label for="employee_department" ><?php echo lang('employee_emp_department');?></label>	   
								<select class="form-control  " name="employee_department" id="employee_department"  tabindex="4" >
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
										<?php 
										if(isset($department_details))
										{
											foreach($department_details as  $department_detail): 
									?>
											<option value="<?php echo $department_detail->dept_id; ?>"><?php echo $department_detail->department_name; ?></option>                                                       
									<?php
											endforeach;
										}
									?>						
								</select>
								<span class='help-inline'><?php echo form_error('employee_department'); ?></span>
							</div>	
						</div>
						
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="form-group text-center ">								
								<button type="button" name="" id="searchButton" class="btn  btn-primary btn-md"  tabindex="5"><span class="glyphicon glyphicon-search"> </span>Search</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row searchPanelDiv table-responsive">
					
					<legend> Employee Info </legend>
					<table class="table ">
						<thead>
							<tr>
								<th></th>											
								<th>Name</th>						
								<th>Designation </th>	
								<th>Division</th>						
								<th>Department </th>	
							</tr>
						</thead>
								
						<tbody id="show_employee_search_result">						
									<td colspan="5">No Information about Employee </td>	
						</tbody>

						<tfoot>						
							<tr>
								<td colspan="5" id="infoMessage">
									
								</td>
							</tr>					
						</tfoot>
					</table>
					
				
				</div>
			</div>
		</div>
	</div>
	 		  
	
	<!-- End Search -->
