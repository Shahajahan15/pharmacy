
<div id="employeeLeavePolicyInnerHTML">	
<div class="row">
<fieldset>
<legend><?php echo lang("maternity_policy_view"); ?> </legend>	
<div class="col-md-12">
		          
	<table class="table table-striped report-table">
		<tbody id="employeeLeavePolicyInnerHTML">
		<?php
			
		echo  "						
				<tr class='active'>						
					<th>".lang("leave_policy_name")."</th>
					<th>".lang("leave_type")."</th>		
					<th>".lang("leave_limit_type")."</th>
					<th>".lang("leave_max_limit")."</th>									
					<th>".lang("leave_of_day_count")."</th>	
					<th>Status</th>				
					<th>".lang("bf_actions")."</th>
				</tr>";		
				if($records):
					
				foreach($records as $recorded): $leaveInfoRecord = (object) $recorded;			
																	
				$LEAVE_POLICY_DTLS_ID  		= $leaveInfoRecord->LEAVE_POLICY_DTLS_ID;
				$LEAVE_POLICY_MST_ID  		= $leaveInfoRecord->LEAVE_POLICY_MST_ID;	
				$leaveStatus  				= $leaveInfoRecord->LEAVE_POLICY_STATUS;
				$leaveForward  				= $leaveInfoRecord->LEAVE_POLICY_STATUS;
				
				if($leaveForward == 1)					
				{
					$forward = 'Yes';
				}else
				{
					$forward = 'No';
					
				}

				
				if($leaveStatus == 1)					
				{
					$status = 'Active';
				}else
				{
					$status = 'Inactive';
					
				}


				
					$leave_type 			= $this->config->item('leave_type');
					$limit_type 			= $this->config->item('limit_type');
					$formula				= $this->config->item('formula');
					$leave_calculation 		= $this->config->item('leave_calculation');
					$leave_criteria 		= $this->config->item('leave_criteria');
					$fructional_leave		= $this->config->item('fructional_leave');
					$leave_avail 			= $this->config->item('leave_avail');
					$offday_leave 			= $this->config->item('offday_leave');	
					$leave_policy_limit = ($leaveInfoRecord->LEAVE_POLICY_LIMIT) ? $limit_type[$leaveInfoRecord->LEAVE_POLICY_LIMIT] : "";
					$offday_leave_count = 	($leaveInfoRecord->OFFDAY_LEAVE_COUNT) ? $offday_leave[$leaveInfoRecord->OFFDAY_LEAVE_COUNT] : "";	
						
						
						echo "			
						<tr>
							
							<td>".$leaveInfoRecord->LEAVE_POLICY_NAME."</td>							
							<td>".$leave_type[$leaveInfoRecord->LEAVE_POLICY_TYPE]."</td>
							<td>".$leave_policy_limit."</td>	
							<td>".$leaveInfoRecord->LEAVE_POLICY_MAX_LIMIT."</td>	
							<td>".$offday_leave_count."</td>		
							<td>".$status."</td>
							<td>
								<span onclick='editLeaveInfo($LEAVE_POLICY_MST_ID)' class='btn btn-xs btn-primary glyphicon glyphicon-edit'> </span>	
								<span onclick='deleteLeaveInfo($LEAVE_POLICY_DTLS_ID)' class='btn btn-xs btn-danger glyphicon glyphicon-trash'> </span>				
							</td>
						</tr>";				
			endforeach;	

			endif;
		?>
		</tbody>							
	</table>	
	</div>
	</div>	
	</fieldset>							
</div> 	

			