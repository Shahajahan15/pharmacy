<div class="col-md-12" id="bonusPolicyInfoInnerHTML">				          
	<table class="table table-striped">
		<tbody id="bonusPolicyInfoInnerHTML">
		<?php
			
		echo  "<tr class='active strong'>
					<td colspan='9' align='center'>".lang("bonus_policy_list")."</td>
				</tr>						
				<tr>						
					<th>".lang("BONUS_NAME")."</th>
					<th>".lang("DESCRIPTION")."</th>
					<th>".lang("CONFIRMATION_STATUS")."</th>
					<th>".lang("WORKING_DAYS")."</th>
					<th>".lang("BASE_HEAD")."</th>
					<th>".lang("AMOUNT_TYPE")."</th>
					<th>".lang("AMOUNT")."</th>
					<th>".lang("STATUS")."</th>					
					<th>".lang("bf_actions")."</th>
				</tr>";		
					
				if ($records):
				
				foreach($records as $recorded): $bonusPolicyInfoRecord = (object) $recorded;			
																	
				$BONUS_POLICY_DETAILS_ID  	= $bonusPolicyInfoRecord->BONUS_POLICY_DETAILS_ID;
				$BONUS_POLICY_MST_ID  		= $bonusPolicyInfoRecord->BONUS_POLICY_MST_ID;	
				$bonus_policy_status  		= $bonusPolicyInfoRecord->STATUS;
				
				if($bonus_policy_status == 1)					
				{
					$bonus_policy_status = 'Active';
				}else
				{
					$bonus_policy_status = 'Inactive';
					
				}	
						$confirmation_status 		= $this->config->item('confirmation_status');
						$amount_type				= $this->config->item('amount_type');	
											
						echo "			
						<tr>
							
							<td>".$bonusPolicyInfoRecord->NAME."</td>
							<td>".$bonusPolicyInfoRecord->DESCRIPTION."</td>
							<td>".$confirmation_status[$bonusPolicyInfoRecord->CONFIRMATION_STATUS]."</td>						
							<td>".$bonusPolicyInfoRecord->WORKING_DAYS_MORE_THAN."</td>
							<td>".$bonusPolicyInfoRecord->BASE_SYSTEM_HEAD."</td>	
							<td>".$amount_type[$bonusPolicyInfoRecord->AMOUNT_TYPE]."</td>
							<td>".$bonusPolicyInfoRecord->AMOUNT."</td>							
							<td>".$bonus_policy_status."</td>
							<td>									
								<button onclick='editBonusInfo($BONUS_POLICY_MST_ID)' class='btn btn-sm btn-primary glyphicon glyphicon-edit'></button>
								<span onclick='deleteBonusInfo($BONUS_POLICY_DETAILS_ID)' class='btn btn-sm btn-danger glyphicon glyphicon-trash'> </span>				
								
							</td>
						</tr>";				
			endforeach;	
			endif;
		?>
		<p id="demo"></p>
		</tbody>							
	</table>								
</div> 					