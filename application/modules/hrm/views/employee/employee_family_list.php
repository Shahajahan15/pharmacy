<div class="col-md-12" id="employeeFamilyInfoInnerHTML">				          
	<table class="table table-striped">
		<tbody id="employeeFamilyInfoInnerHTML">
		<?php
		echo  "<tr class='active strong'>
							<td colspan='7' align='center'>".lang("EMPLOYMENT_HISTORY")."</td>
						</tr>
						
						<tr>						
							<th>".lang("NAME")."</th>
							<th>".lang("RELATION")."</th>
							<th>".lang("BIRTH_DATE")."</th>
							<th>".lang("AGE")."</th>
							<th>".lang("OCCPATION")."</th>
							<th>".lang("CONTACT_NO")."</th>
							<th>".lang("emp_action")."</th>
						</tr>";		
																		
				foreach($records as $recorded): $familyInfoRecord = (object) $recorded;														
																	
				$EMP_FAMILY_ID  = $familyInfoRecord->EMP_FAMILY_ID;
								
						$relationtypes = $this->config->item('relation');
						echo "			
						<tr>
							
							<td>".$familyInfoRecord->NAME."</td>								
							<td>".$relationtypes[$familyInfoRecord->RELATION]."</td>
							<td>".$familyInfoRecord->BIRTH_DATE."</td>						
							<td>".$familyInfoRecord->AGE."</td>						
							<td>".$familyInfoRecord->OCCPATION."</td>
							<td>".$familyInfoRecord->CONTACT_NO."</td>
							<td>
								<span onclick='editFamilyInfo($EMP_FAMILY_ID,$empId)' class='btn btn-sm btn-primary glyphicon glyphicon-edit'> </span>	
								<span onclick='deleteFamilyInfo($EMP_FAMILY_ID,$empId)' class='btn btn-sm btn-danger glyphicon glyphicon-trash'> </span>				
							</td>
						</tr>";				
			endforeach;				
		?>
		</tbody>							
	</table>								
</div> 					