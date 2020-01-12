
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">

	#em_print_id{
		font-family:  "Times New Roman", Times, serif;
		text-align: center;
	}


	#em_print_id .table tr td{
		padding: 0px;
		margin: 0px;
		border: none;
		font-size: 11px;
		font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	}
	#em_print_id .table tr th{
		padding: 0px;
		margin: 0px;
		border: none;
		font-size: 11px;
		font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	}
	#em_print_id .table{
		margin: 0;
		padding: 0;
	}
	h5{
		margin: 0px;
		margin-bottom:10px;
		font-size: 11px;
		text-align: center;
	}
	h3{
		margin: 0px;
		margin-top: 10px;
		font-size: 24px;
		text-align: center;
	}
	img{
		height: 50px;
	}
	.logo{		
		width: 150px;
	}
	.head{
		text-align: right;
		margin-right: 150px;
	}
	.barcode{
		text-align: left;
	}
	.head-info{
		text-align: center;
	}
</style>
<div class=" box box-primary">
		<div class="col-md-8 col-md-offset-2" id="em_print_id">
			<table class="table">
				<tr class="head">
					
					<span class="pull-left">
						<td style="width:152px"><img class="logo" src="http://www.chicagobusiness.com/cardiac-care/images/logos_11.jpg"></td>
						<td colspan="2">
							<h3>
								<b>Hospital & Diagnosis Center</b>
							</h3>
							<h5>103,3 Mohammadpur,Dhaka,Bangladesh</h5>
						
						</td>					
						
					</span>	
					<td><img class="barcode pull-left" src="http://www.gs1.org/sites/default/files/docs/barcodes/GS1-128.png"></td>				
				</tr>
				<tr>
					<td colspan="4" class="head-info"><b> Ticket Money Recipt</b></td>
				</tr>
				
			</table>


			<hr>

		    <!--<table class="table">								
				<tr>							
					<th><?php e(lang("patient_mst_patient_id"));?></th>
					<th>: <?php e($ticket_details["patient_id"]);?></th>
					
					<span class="right">
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</span>
				</tr>
				<tr>							
					<th><?php e($ticketType[$ticket_details["ticket_type"]]." No ");?></th>
					<th>: <?php e($ticket_details["ticket_id"]);?></th>
					
					<th><?php e(lang("odp_appointment_date"));?></th>
					<th>: <?php e($ticket_details["appointment_date"]);?></th>
				</tr>
				<tr>							
					<td><?php e(lang("patient_mst_patient_name"));?></td>
					<td>: <?php e($ticket_details["patient_name"]); ?></td>

					<td><?php e(lang("patient_mst_sex"));?></td>
					<td>: <?php e($sex[$ticket_details["sex"]]);  ?></td>
				</tr>
				<tr>
					<td><?php e(lang("patient_mst_age"));?></td>
					<td>: <?php e($ticket_details["age"]); ?></td>
					<td><?php e(lang("odp_appointment_type"));?></td>
					<td>: <?php e($appointmentType[$ticket_details["appointment_type"]]); ?></td>
				</tr>	
				
				<tr>
					<td><?php e(lang("odp_doctor_id"));?></td>
					<td>: <?php e($ticket_details["doctor_name"]); ?></td>
					<td><?php e(lang("odp_ticket_fee"));?></td>
					<td>: <?php e($ticket_details["ticket_fee"]." ".lang("bf_currency")); ?></td>
				</tr>
				
				<tr>
					<td><br/><?php e(lang("odp_room_no"));?></td>
					<td valign="bottom"><br>: <?php e($ticket_details["room_no"]); ?></td>
					<td><br/><?php e(lang("signature"));?></td>
					<td><br/><sub>...........................................................</sub></td>
				</tr>
			</table>-->
			<hr> 

			<table class="table">
				<tr>
					<td>
						<span class="pull-right" ">Cash Received By Mahfuz</span>
					</td>
				</tr>
			</table>               
		</div>
				
		 
</div>


<script type="text/javascript">
	window.print();
	window.close();
</script>