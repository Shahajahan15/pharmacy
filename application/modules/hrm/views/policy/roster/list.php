
<div id="employeeRosterPolicyInnerHTML">	
	<div class="row">
		<fieldset>
			<legend><?php echo lang("maternity_policy_view"); ?> </legend>	
			<div class="col-md-12">

				<table class="table table-striped report-table">
					<tbody id="employeeRosterPolicyInnerHTML">
						<tr class="active">						
							<th>Roster Shift Name</th>
							<th>Policy Name</th>		
							<th>After Change Day</th>	
							<th>Status</th>	
							<th>Is Use</th>				
							<th>Actions</th>
						</tr>
						<?php 
						if ($records) : 
							foreach ($records as $key => $val) :
								$is_use = ($val->IS_USE) ? "disabled" : "";
								?>
							<tr>	
								<td><?php echo getRosterShiftNameByShiftIds($val->SHIFT_POLICY_NAME); ?></td>
								<td><?php echo $val->ROSTER_POLICY_NAME; ?></td>	
								<td><?php echo $val->AFTER_CHANGE_DAY; ?></td>	
								<td><?php echo ($val->STATUS == 1) ? "Active" : "Inactive"; ?></td>	
								<td><?php echo ($val->IS_USE) ? "Yes" : "No"; ?></td>
								<td>
									<span onclick="editRosterInfo(<?php echo $val->ROSTER_POLICY_ID; ?>)" class="btn btn-xs btn-primary glyphicon glyphicon-edit" <?php echo $is_use; ?>> </span>	
									<span onclick="deleteRosterInfo(<?php echo $val->ROSTER_POLICY_ID; ?>)" class="btn btn-xs btn-danger glyphicon glyphicon-trash" <?php echo $is_use; ?>> </span>				
								</td>
							</tr>
						<?php endforeach; endif; ?>
					</tbody>							
				</table>	
			</div>
		</div>	
	</fieldset>							
</div> 	

