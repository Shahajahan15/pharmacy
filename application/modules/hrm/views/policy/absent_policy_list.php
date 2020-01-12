<div id="absentPolicyInfoInnerHTML">
	<div class="row">	
	<fieldset>			
	<legend><?php echo lang("maternity_policy_view"); ?> </legend>	
<div class="col-md-12">          
	<table class="table table-striped report-table">
		<tbody id="absentPolicyInfoInnerHTML">
		<?php
			
		echo  "						
				<tr class='active'>						
					<th>".lang("absent_policy_name")."</th>
					<th>".lang("absent_deduction_head")."</th>
					<th>".lang("absent_parameter_type")."</th>
					<th>".lang("absent_persentage")."</th>
					<th>".lang("absent_base_head")."</th>
					<th>".lang("absent_amount")."</th>
					<th>".lang("absent_policy_status")."</th>					
					<th>".lang("bf_actions")."</th>
				</tr>";		
					
				if ($records):
				
				foreach($records as $recorded): $absentInfoRecord = (object) $recorded;			
																	
				$ABSENT_POLICY_DTLS_ID  	= $absentInfoRecord->ABSENT_POLICY_DTLS_ID;
				$ABSENT_POLICY_MST_ID  		= $absentInfoRecord->ABSENT_POLICY_MST_ID;	
				$absentStatus  				= $absentInfoRecord->ABSENT_POLICY_STATUS;
				
				if($absentStatus == 1)					
				{
					$status = 'Active';
				}else
				{
					$status = 'Inactive';
					
				}	
						$parameter_type			= $this->config->item('parameter_type');					
						
						
						echo "			
						<tr>
							
							<td>".$absentInfoRecord->ABSENT_POLICY_NAME."</td>
							<td>".$absentInfoRecord->BASE_SYSTEM_HEAD."</td>
							<td>".$parameter_type[$absentInfoRecord->ABSENT_PARAMETER_TYPE]."</td>						
							<td>".$absentInfoRecord->ABSENT_PERSENT_FORMULA."</td>
							<td>".$absentInfoRecord->BASE_SYSTEM_HEAD."</td>
							<td>".$absentInfoRecord->ABSENT_AMOUNT."</td>							
							<td>".$status."</td>
							<td>
								<span onclick='editAbsentInfo($ABSENT_POLICY_MST_ID)' class='btn btn-xs btn-primary glyphicon glyphicon-edit'> </span>	
								<span onclick='deleteAbsentInfo($ABSENT_POLICY_DTLS_ID)' class='btn btn-xs btn-danger glyphicon glyphicon-trash'> </span>				
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