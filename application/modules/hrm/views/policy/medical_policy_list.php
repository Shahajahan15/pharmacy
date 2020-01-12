<div class="col-md-12" id="medicalPolicyInfoInnerHTML">				          
	<table class="table table-striped">
		<tbody id="medicalPolicyInfoInnerHTML">
		<?php
			
		echo  "<tr class='active strong'>
					<td colspan='7' align='center'>".lang("medical_policy_list")."</td>
				</tr>						
				<tr>						
					<th>".lang("NAME")."</th>
					<th>".lang("DESCRIPTION")."</th>
					<th>".lang("BASE_HEAD")."</th>
					<th>".lang("AMOUNT_TYPE")."</th>
					<th>".lang("AMOUNT")."</th>
					<th>".lang("STATUS")."</th>					
					<th>".lang("bf_actions")."</th>
				</tr>";		
					
				if ($records):
				
				foreach($records as $recorded): $medicalPolicyInfoRecord = (object) $recorded;			
																	
				$MEDICAL_POLICY_DETAILS_ID  	= $medicalPolicyInfoRecord->MEDICAL_POLICY_DETAILS_ID;
				$MEDICAL_POLICY_MASTER_ID  		= $medicalPolicyInfoRecord->MEDICAL_POLICY_MASTER_ID;	
				$medical_policy_status  		= $medicalPolicyInfoRecord->STATUS;
				
				if($medical_policy_status == 1)					
				{
					$medical_policy_status = 'Active';
				}else
				{
					$medical_policy_status = 'Inactive';
					
				}	
						
						$amount_type				= $this->config->item('amount_type');	
											
						echo "			
						<tr>
							
							<td>".$medicalPolicyInfoRecord->NAME."</td>
							<td>".$medicalPolicyInfoRecord->DESCRIPTION."</td>
							<td>".$medicalPolicyInfoRecord->BASE_SYSTEM_HEAD."</td>	
							<td>".$amount_type[$medicalPolicyInfoRecord->AMOUNT_TYPE]."</td>
							<td>".$medicalPolicyInfoRecord->AMOUNT."</td>							
							<td>".$medical_policy_status."</td>
							<td>									
								<button onclick='editMedicalInfo($MEDICAL_POLICY_MASTER_ID)' class='btn btn-sm btn-primary glyphicon glyphicon-edit'></button>
								<span onclick='deleteMedicalInfo($MEDICAL_POLICY_DETAILS_ID)' class='btn btn-sm btn-danger glyphicon glyphicon-trash'> </span>				
								
							</td>
						</tr>";				
			endforeach;	
			endif;
		?>
		</tbody>							
	</table>								
</div> 					