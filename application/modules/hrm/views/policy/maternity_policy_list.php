	<div id="maternityPolicyInfoInnerHTML">	
		<div class="row">	
			<fieldset>			
				<legend><?php echo lang("maternity_policy_view"); ?> </legend>		
				<div class="col-md-12">             
					<table class="table table-striped">
						<tbody id="maternityPolicyInfoInnerHTML">
							<?php

							echo  "						
							<tr class='active'>						
								<th>".lang("maternity_policy_name")."</th>
								<th>".lang("maternity_max_day_limit")."</th>
								<th>".lang("maternity_mini_service")."</th>
								<th>".lang("maternity_leave_before")."</th>
								<th>".lang("maternity_leave_after")."</th>
								<th>".lang("maternity_payment_calculation")."</th>
								<th>".lang("maternity_payment_disburse")."</th>
								<th>".lang("maternity_policy_status")."</th>
								<th>".lang("bf_actions")."</th>
							</tr>";		
							if ($records):
							foreach($records as $recorded): $maternityInfoRecord = (object) $recorded;			

							$MATERNITY_LEAVE_ID  	= $maternityInfoRecord->MATERNITY_LEAVE_ID;				
							$maternityStatus  		= $maternityInfoRecord->MATERNITY_STATUS;

							if($maternityStatus == 1)					
							{
								$status = 'Active';
							}else
							{
								$status = 'Inactive';

							}	

							$parameter_calculation			= $this->config->item('parameter_calculation');
							$parameter_disburse				= $this->config->item('parameter_disburse');
							$payment_cal = ($maternityInfoRecord->PAYMENT_CALCULATION) ? $parameter_calculation[$maternityInfoRecord->PAYMENT_CALCULATION] : "";
							$payment_disb = ($maternityInfoRecord->PAYMENT_DISBURSEMENT) ? $parameter_disburse[$maternityInfoRecord->PAYMENT_DISBURSEMENT] : "";
							echo "			
							<tr>
								
								<td>".$maternityInfoRecord->MATERNITY_POLICY_NAME."</td>
								<td>".$maternityInfoRecord->MAX_DAY_LIMIT."</td>						
								<td>".$maternityInfoRecord->MIN_SERVICE_LENGTH."</td>						
								<td>".$maternityInfoRecord->LEAVE_BEFORE_DELIVERY."</td>
								<td>".$maternityInfoRecord->LEAVE_AFTER_DELIVERY."</td>
								<td>".$payment_cal."</td>
								<td>".$payment_disb."</td>
								<td>".$status."</td>
								<td>
									<span onclick='editMaternityInfo($MATERNITY_LEAVE_ID)' class='btn btn-xs btn-primary glyphicon glyphicon-edit'> </span>	
									<span onclick='deleteMaternityInfo($MATERNITY_LEAVE_ID)' class='btn btn-xs btn-danger glyphicon glyphicon-trash'> </span>				
								</td>
							</tr>";				
							endforeach;		
							endif;		
							?>
						</tbody>							
					</table>	
				</div>
			</fieldset>
		</div>							
	</div> 					