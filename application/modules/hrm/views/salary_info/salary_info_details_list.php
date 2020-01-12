<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<table class="table table-striped">
			<thead>
				<tr>					
					<th width="5%"><?php echo lang('employee_salary_info_details_list_sl'); ?></th>
					<th width="10%"><?php echo lang('hrm_salary_head'); ?></th>
					<th width="20%"><?php echo lang('hrm_amount_type'); ?></th>					
					<th width="10%"><?php echo lang('hrm_fixed_value'); ?></th>					
					<th width="10%"><?php echo lang('hrm_percentage_value').lang('hrm_percent_sign'); ?></th>
					<th width="10%"><?php echo lang('hrm_calculative_value'); ?></th>								
				</tr>
			</thead>
		
			<tbody>	
				<?php 
					if(isset($detailsRecords))
					{
						$sl = 1;
						$total = 0;
						foreach($detailsRecords as $detailsRecord)
						{  ?>
							<tr>
								<td><?php e($sl++); ?></td>
								<td><?php e($detailsRecord->SALARY_HEAD_NAME); ?></td>
								<td><?php if($detailsRecord->AMOUNT_TYPE == 1) {e("Fixed");} else {e("Percentage");} ?></td>
								<td><?php e($detailsRecord->FIXED_VALUE); ?></td>
								<td><?php e($detailsRecord->PERCENTAGE_VALUE); ?></td>
								<td><?php e($detailsRecord->CALCULATIVE_VALUE); ?></td>								
							</tr>
				<?php 		$total += $detailsRecord->CALCULATIVE_VALUE;
						}												
					}
				?>	
				<tr>
					<td colspan="8" align="right">Total  = <?php e($total);?></td>
				</tr>
			</tbody>
			
			<tfoot>				
				<tr>					
					<td colspan="8">
						<a href="#" class="btn btn-default"> Print </a>
					</td>
				</tr>			
			</tfoot>		
		</table>	
	</div>
</div>