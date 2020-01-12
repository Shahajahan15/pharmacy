<?php
	$border_color='#dedede';
?>
<section id="attendance_form">
	
<fieldset class="print-body">
	<center>
		<div>			
				<p style="font-size: 18px;font-weight: bold ">Salary Sheet</p>
				<p><?php echo $month;?></p>			
		</div>
		
		<table class="table" border="1" style="border-collapse: collapse;border-color:<?php echo $border_color;?>">
		<center>
			<thead>
				<tr class="active" style="border-color:<?php echo $border_color;?>">
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">SL</th>
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Photo</th>
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Employee Name</th>
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Designation</th>
					<th colspan="3" style="text-align:center;border-color:<?php echo $border_color;?>">Attendence</th>					
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Total Day</th>
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Gross Salary</th>
					<th colspan="3" style="text-align: center;border-color:<?php echo $border_color;?>">Deduction Amount</th>
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Total Deduction</th>
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Payable Salary</th>
					<th rowspan="2" style="border-color:<?php echo $border_color;?>">Payslip</th>
				</tr>
				<tr class="active">
					<th style="border-color:<?php echo $border_color;?>">Prsent</th>					
					<th style="border-color:<?php echo $border_color;?>">Absense</th>
					<th style="border-color:<?php echo $border_color;?>">Leave</th>

					<th style="border-color:<?php echo $border_color;?>">Advance Salary</th>
					<th style="border-color:<?php echo $border_color;?>">Loan</th>
					<th style="border-color:<?php echo $border_color;?>">Absence</th>
				</tr>

			</thead>
		</center>
			<?php if(count($records)){?>
		 	<tbody>
				<?php $sl=0; foreach($records as $record){?>
					<tr class="info">
						<td style="border-color:<?php echo $border_color;?>"><?php echo $sl+=1;?></td>
						<td style="border-color:<?php echo $border_color;?>">
                            <img src="<?php echo $img = ($record->photo == '')? base_url('assets/images/profile/default.png') : base_url('assets/images/employee_img/' . $record->photo); ?>" class="img-circle"  width="30" height="30">
						</td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->employee_name;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->designation_name;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->tot_present;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->tot_absenses;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->tot_leave;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $total_day;?></td>

						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->total_gross_amount;?></td>

						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->advance_deduction;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->loan_deduction;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->absense_deduction;?></td>

						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->total_deduction;?></td>
						<td style="border-color:<?php echo $border_color;?>"><?php echo $record->payable_salary;?></td>

						<td><p style="border-color:<?php echo $border_color;?>" emp_id="<?php echo $record->emp_id;?>" month="<?php echo $record->month?>" class="pay-slip-print btn btn-primary btn-xs">Payslip</p></td>
					</tr>
				<?php }?>				
			</tbody>
			<?php }else{?>
				<tr class="danger">
					<th colspan="14">No Attendence Taken in this month</th>
				</tr>
			<?php }?>
		</table>
	</center>
</fieldset>

