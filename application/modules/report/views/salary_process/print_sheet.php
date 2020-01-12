<style type="text/css">
		.company-title,.heading{
			font-size: 24px;
			font-weight: bold;
		}
		.company-sub-title{
			font-size: 14px;			
		}
		.heading{
			text-decoration: underline;
		}
		#Body{
			margin: 0 auto;
			width: 80%;
		}
		#body > p{
			margin:0;
			padding: 0;
		}
		body{
			color:black;
		}
		.h-center{
			text-align: center;
		}
		.v-align{
			vertical-align:middle !important;
		}
		p{
			margin:0;
			padding: 0;
			font-family: 'arial';
		}
		.table thead tr th{
			margin: 0;
			padding: 0
		}
		.table tbody tr td{
			margin: 0;
			padding: 0
		}
		.box-border{
			border:1px solid gray;
			padding: 10px;
		}
		
	</style>

<section id="head-information">
	<center>
<?php echo report_header() ?>
</section>
<br>
<br>

<section id="attendance_form">	
	<center>
		<div>			
				<p style="font-size: 18px;font-weight: bold ">Salary Sheet</p>
				<p><?php echo $month;?></p>			
		</div>
		
		<table class="" border="1" style="border-collapse: collapse;">
		<center>
			<thead class="v-align h-center">
				<tr class="active">
					<th rowspan="2">SL</th>
					<th rowspan="2">Employee Name</th>
					<th rowspan="2">Employee Code</th>
					<th rowspan="2">Designation</th>
					<th rowspan="2">Gross Salary</th>
					<th colspan="3" style="text-align: center">Deduction Amount</th>
					<th rowspan="2">Total Deduction</th>
					<th rowspan="2">Payable Salary</th>

					<th width="5%" rowspan="2">Signature</th>
				</tr>
				<tr class="active">

					<th>Advance Salary</th>
					<th>Loan</th>
					<th>Absence</th>
					
				</tr>

			</thead>
		</center>
			<?php if(count($records)){?>
		 	<tbody class="v-align h-center">
				<?php $sl=0; foreach($records as $record){?>
					<tr class="info">
						<td><?php echo $sl+=1;?></td>
						<td><?php echo $record->employee_name;?></td>
						<td><?php echo $record->emp_code;?></td>
						<td><?php echo $record->designation_name;?></td>						

						<td><?php echo $record->total_gross_amount;?></td>

						<td><?php echo $record->advance_deduction;?></td>
						<td><?php echo $record->loan_deduction;?></td>
						<td><?php echo $record->absense_deduction;?></td>

						<td><?php echo $record->total_deduction;?></td>
						<td><?php echo $record->payable_salary;?></td>
						<td></td>
						
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

<script type="text/javascript">
	window.print();
	window.close();
</script>