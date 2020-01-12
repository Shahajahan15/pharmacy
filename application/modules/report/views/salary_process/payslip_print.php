<!DOCTYPE html>
<html>
<head>
	<title>Print page</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
</head>
<body>
<section id="head-information">
	<center>
<?php echo report_header() ?>
</section>
<br>
<br>
<section id="Format">
	<center><p class="heading">Payslip</p></center>
</section>
<br>

<section id="Body">

	<p>Employee Information</p>
	<div class="box-border">
		<table>
			<tr>
				<td width="30%">Employee Name</td>
				<td width="2%">:</td>
				<td width=""><?php echo $record->employee_name; ?></td>

				<td width="20%">Employee Code</td>
				<td width="2%">:</td>
				<td><?php echo $record->emp_code; ?></td>
			</tr>			
			<tr>
				<td width="30%">Father's Name</td>
				<td>:</td>
				<td width=""><?php echo $record->employee_father_name; ?></td>

				<td width="20%">Month</td>
				<td width="2%">:</td>
				<td><?php echo $month; ?></td>
			</tr>
			<tr>
				<td width="30%">Employee Mobile</td>
				<td width="2%">:</td>
				<td><?php echo $record->mobile; ?></td>
			</tr>
			<tr>
				<td width="30%">Employee Department</td>
				<td width="2%">:</td>
				<td><?php echo $record->department_name; ?></td>
			</tr>
		</table>		
	</div>

	<b>Attendence Information</b>	
	<table class="table h-center v-center" border="1" style="colapse:colapse">
		<thead>
			<tr>
				<th class="h-center v-center">Total Working Day</th>
				<th class="h-center v-center">Total Present</th>
				<th class="h-center v-center">Total Absence</th>
				<th class="h-center v-center">Total Leave</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $total_day; ?></td>
				<td><?php echo $record->tot_present; ?></td>
				<td><?php echo $record->tot_absenses; ?></td>
				<td><?php echo $record->tot_leave; ?></td>
			</tr>
		</tbody>			
	</table>
	

	<b>Deduction Information</b>	
	<table class="table h-center v-center" border="1" style="colapse:colapse">
		<thead>
			<tr>
				<th class="h-center v-center">Advance Salary</th>
				<th class="h-center v-center">Loan</th>
				<th class="h-center v-center">Absence</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $record->advance_deduction; ?></td>
				<td><?php echo $record->loan_deduction; ?></td>
				<td><?php echo $record->absense_deduction; ?></td>
			</tr>
		</tbody>			
	</table>

	<b>Total Information</b>	
	<table class="table h-center v-center" border="1" style="colapse:colapse">
		<thead>
			<tr>
				<th class="h-center v-center">Total Salary</th>
				<th class="h-center v-center">Total Deduction</th>
				<th class="h-center v-center">Payable Salary</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $record->total_gross_amount; ?></td>
				<td><?php echo $record->total_deduction; ?></td>
				<td><?php echo $record->payable_salary; ?></td>
			</tr>
		</tbody>			
	</table>
	<p class="box-border">
		<b>Payable Salary (in word) :</b>
		<?php echo inWords($record->payable_salary);?>
	</p>
</section>




<script type="text/javascript">
	window.print();
	window.close();
</script>
</body>
</html>