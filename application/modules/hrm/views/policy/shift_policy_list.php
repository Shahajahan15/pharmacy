<div id="shiftPolicyInfoInnerHTML">	
		<div class="row">	
			<fieldset>
			<legend><?php echo lang("shift_policy_list"); ?> </legend>
<div class="col-md-12">				          
	<table class="table table-striped">
		<tbody>
		<?php
		echo  "
						
						<tr class='active'>						
							<th>".lang("SHIFT_NAME")."</th>
							<th>".lang("SHIFT_TYPE")."</th>
							<th>".lang("SHIFT_STARTS")."</th>
							<th>".lang("SHIFT_ENDS")."</th>
							<th>".lang("LATE_MARKING_STARTS")."</th>
							<th>".lang("EXIT_BUFFER_TIME")."</th>
							<th>".lang("LUNCH_BREAK_STARTS")."</th>
							<th>".lang("LUNCH_BREAK_ENDS")."</th>							
							<th>".lang("EXTRA_BREAK_STARTS")."</th>
							<th>".lang("EXTRA_BREAK_ENDS")."</th>
							<th>".lang("EARLY_OUT_STARTS")."</th>							
							<th>".lang("ENTRY_RESTRICTION_STARTS")."</th>
							<th>".lang("STATUS")."</th>
							<th>".lang("emp_action")."</th>
							
						</tr>";		
						$status				= $this->config->item('status');
						$shift_types		= $this->config->item('shift_types');
					if ($records) :													
				foreach($records as $recorded): $shiftPolicyRecord = (object) $recorded;														
						$shift_policy_id = $shiftPolicyRecord->SHIFT_POLICY_ID;											
						echo "			
						<tr>							
							<td>".$shiftPolicyRecord->SHIFT_NAME."</td>							
							<td>".$shift_types[$shiftPolicyRecord->SHIFT_TYPE]."</td>								
							<td>".$shiftPolicyRecord->SHIFT_STARTS."</td>
							<td>".$shiftPolicyRecord->SHIFT_ENDS."</td>								
							<td>".$shiftPolicyRecord->LATE_MARKING_STARTS."</td>
							<td>".$shiftPolicyRecord->EXIT_BUFFER_TIME."</td>								
							<td>".$shiftPolicyRecord->LUNCH_BREAK_STARTS."</td>
							<td>".$shiftPolicyRecord->LUNCH_BREAK_ENDS."</td>								
							<td>".$shiftPolicyRecord->EXTRA_BREAK_STARTS."</td>
							<td>".$shiftPolicyRecord->EXTRA_BREAK_ENDS."</td>								
							<td>".$shiftPolicyRecord->EARLY_OUT_STARTS."</td>
							<td>".$shiftPolicyRecord->ENTRY_RESTRICTION_STARTS."</td>	
							<td>".$status[$shiftPolicyRecord->STATUS]."</td>								
							<td>
								<span onclick='editShiftPolicyInfo($shift_policy_id)' class='btn btn-xs btn-primary glyphicon glyphicon-edit'> </span>	
								<span onclick='deleteShiftPolicyInfo($shift_policy_id)' class='btn btn-xs btn-danger glyphicon glyphicon-trash'> </span>		
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