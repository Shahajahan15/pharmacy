<?php if(!extract($records))
extract($records);?>
				<div class="panel-body policyTrackingPanel">
					<div class="row ">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="1"    />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5 dropdownCheck">
								   <label><?php echo lang('hrm_leave_policy');?></label>
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6" >
									<div class="form-group <?php echo form_error('leave_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="leave_policy" id="leave_policy" disabled=""  >
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											<?php
												if ($leave_policy_details) :
												foreach($leave_policy_details as $leave_policy_detail)
												{								
													echo "<option value='".$leave_policy_detail->LEAVE_POLICY_MST_ID."'>".$leave_policy_detail->LEAVE_POLICY_NAME."</option>";
												} 
												endif;
											?>							
										</select>
										<span class='help-inline'><?php echo form_error('leave_policy'); ?></span>
									</div>
								</div>						
							</div>				
				
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="2"  />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5  dropdownCheck">
									<label><?php echo lang('hrm_medical_policy')?></label>
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="form-group <?php echo form_error('medical_policy') ? 'error' : ''; ?>">

										<select class="form-control a" name="medical_policy" id="medical_policy"  disabled="">
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
												<?php
													if ($medical_policy_details) :
													foreach($medical_policy_details as $medical_policy_detail)
													{								
														echo "<option value='".$medical_policy_detail->MEDICAL_POLICY_MASTER_ID."'>".$medical_policy_detail->NAME."</option>";
													} 
													endif;
												?>						
										</select>
										<span class='help-inline'><?php echo form_error('medical_policy'); ?></span>
									</div>
								</div>						
							</div>
							
							
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="3" />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5  dropdownCheck">
									<label><?php echo lang('hrm_absent_policy');?></label> 
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="form-group <?php echo form_error('absent_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="absent_policy" id="absent_policy"  disabled="">
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											<?php
												if ($absent_policy_details) :
												foreach($absent_policy_details as $absent_policy_detail)
												{								
													echo "<option value='".$absent_policy_detail->ABSENT_POLICY_MST_ID."'>".$absent_policy_detail->ABSENT_POLICY_NAME."</option>";
												} 
												endif;
											?>							
										</select>
										<span class='help-inline'><?php echo form_error('absent_policy'); ?></span>
									</div>
								</div>						
							</div>	
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="4" />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5 dropdownCheck">
								  <label><?php echo lang('hrm_shifting_policy');?></label> 
								</div>
								<div class="col-sm-6 col-md-6 col-lg-6">
								   <div class="form-group <?php echo form_error('shifting_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="shifting_policy" id="shifting_policy" disabled="" >
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											<?php
											     if ($shift_policy_details) :
												foreach($shift_policy_details as $shift_policy_detail)
												{								
													echo "<option value='".$shift_policy_detail->SHIFT_POLICY_ID."'>".$shift_policy_detail->SHIFT_NAME."</option>";
												} 
												endif;
											?>							
										</select>
										<span class='help-inline'><?php echo form_error('shifting_policy'); ?></span>
									</div> 
								</div>
							</div>
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="9" />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5 dropdownCheck">
								  <label><?php echo lang('hrm_provident_fund_policy');?></label> 
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6">
								   <div class="form-group <?php echo form_error('provident_fund_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="provident_fund_policy" id="provident_fund_policy" disabled="" >
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											<?php /*
												foreach($shift_policy_details as $shift_policy_detail)
												{								
													echo "<option value='".$shift_policy_detail->SHIFT_POLICY_ID."'>".$shift_policy_detail->SHIFT_NAME."</option>";
												} */
											?>							
										</select>
										<span class='help-inline'><?php echo form_error('provident_fund_policy'); ?></span>
									</div> 
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4"></div>
							</div>	
						</div>
						
						
						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="8" />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5 dropdownCheck">
								  <label><?php echo lang('hrm_overtime_policy');?></label> 
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6">
								   <div class="form-group <?php echo form_error('overtime_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="overtime_policy" id="overtime_policy" disabled="" >
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											<?php /*
												foreach($shift_policy_details as $shift_policy_detail)
												{								
													echo "<option value='".$shift_policy_detail->SHIFT_POLICY_ID."'>".$shift_policy_detail->SHIFT_NAME."</option>";
												} */
											?>							
										</select>
										<span class='help-inline'><?php echo form_error('overtime_policy'); ?></span>
									</div> 
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4"></div>
							</div>
							
							
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="5" />
								</div>
								<div class="col-sm-5 col-md-5 col-lg-5 dropdownCheck">
								   <label><?php echo lang('hrm_maternity_policy');?></label> 
								</div>
							
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="form-group <?php echo form_error('maternity_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="maternity_policy" id="maternity_policy" disabled="" >
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											<?php
												if ($maternity_policy_details) :
												foreach($maternity_policy_details as $maternity_policy_detail)
												{								
													echo "<option value='".$maternity_policy_detail->MATERNITY_LEAVE_ID."'>".$maternity_policy_detail->MATERNITY_POLICY_NAME."</option>";
												} 
												endif;
											?>							
										</select>
										<span class='help-inline'><?php echo form_error('maternity_policy'); ?></span>
									</div>
								</div>						
							</div>
						
						
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="6" />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5 dropdownCheck">
									<label class=""><?php echo lang('hrm_bonus_policy');?></label> 
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="form-group <?php echo form_error('bonus_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="bonus_policy" id="bonus_policy"  disabled="">
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											   <?php
											   		if ($bonus_policy_details) :									 
													foreach($bonus_policy_details  as $bonus_policy_detail)
													{								
														echo "<option value='".$bonus_policy_detail->BONUS_POLICY_MST_ID."'>".$bonus_policy_detail->NAME."</option>";
													} 
													endif;
												?>								
										</select>
										<span class='help-inline'><?php echo form_error('bonus_policy'); ?></span>
									</div> 
								</div>						
							</div>
							<div class="row policySelector">
								<div class="col-sm-1 col-md-1 col-lg-1">
									<input class="check_policy" type="checkbox" name="policyChecked[]" value="7" />
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5 dropdownCheck">
									<label class=""><?php echo lang('hrm_roster_policy');?></label> 
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="form-group <?php echo form_error('roster_policy') ? 'error' : ''; ?>">
										<select class="form-control a" name="roster_policy" id="roster_policy"  disabled="">
											<option value=""><?php echo lang('bf_msg_selete_one');?></option>
											   <?php	
											   		if ($roster_policy_details)	:							 
													foreach($roster_policy_details  as $roster_policy_detail)
													{								
														echo "<option value='".$roster_policy_detail->ROSTER_POLICY_ID."'>".$roster_policy_detail->ROSTER_POLICY_NAME."</option>";
													} 
													endif;
												?>								
										</select>
										<span class='help-inline'><?php echo form_error('roster_policy'); ?></span>
									</div> 
								</div>						
							</div>
						</div>
					</div>				
				</div>  <!-- panel body end -->