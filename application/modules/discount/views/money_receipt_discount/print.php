
<?php
if (isset($ticket_details)){
	$ticket_details = (array) $ticket_details;
}

?>











<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap3.3.7.css'); ?>">
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>


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
	.border-top{
		border-top: 1px solid #eee;
	}
	.border{
		border: 1px solid black;
	}
	.h-center{
		text-align: center;
	}
	.v-align{
		vertical-align:middle !important;
	}

</style>
<div class=" box box-primary" >
		<div class="col-md-8 col-md-offset-2" id="em_print_id">
			<table class="">
				<tr class="">
					<td>
						<img src="<?php echo base_url($hospital->logo); ?>" width="200">
					</td>
					<td width="2%"></td>
					<td>
						<b><?php echo $hospital->name; ?></b>
						<p style="margin:0"><?php echo $hospital->address; ?></p>
						<p style="margin:0"><?php echo $hospital->mobile; ?></p>
					</td>
					<td width="20%">
						
					</td>

					<td width="25%">
						<table border="1" width="100%" style="margin-top:10px;">
							<tr class="v-align h-center">
								<td colspan="2"><b>Money Receipt</b></td>
							</tr>
							<tr>
								<td>Receipt No</td>
								<td><?php e($ticket_details["receipt_no"]);?></td>								
							</tr>
							<tr>
								<td>Date </td>
								<td><?php echo date('d M, Y'); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<br>
			<center>
				<p><b>Emergency Ticket Bill(Client Copy)</b></p>
			</center>
			<hr>

		

			<br>

						
			<table class="table">
				<thead>
					<tr>
						
						<th><?php e(lang("patient_mst_patient_id"));?></th>
                                                <td><?php e($ticket_details["patient_code"]);?></td>
                                                
						<th><?php e(lang("patient_mst_patient_name"));?></th>
                                                <td><?php e($ticket_details["patient_name"]); ?></td>
                                        </tr>
                                        <tr>
						<th><?php e(lang("patient_mst_sex"));?></th>
                                                 <td><?php e($sex[$ticket_details["sex"]]);  ?></td>
						<th><?php e(lang("patient_mst_age"));?></th>
                                                 <td><?php e(dob_convert_age($ticket_details["birthday"])); ?></td>
						
					</tr>
                                        <tr>
							
							   
							  
							 
							 <th>Contact no</th>
							  <td><?php e($ticket_details["contact_no"]); ?></td>
                                                          <th><?php e(lang("odp_doctor_id"));?></th>
                                                          <td><?php e($ticket_details["doctor_name"]); ?></td>

					</tr
                                             <tr>
							
							   
							  
							 
							 <th>Ticket Fee</th>
							  <td><?php e($ticket_details["ticket_fee"]); ?></td>
                                                          <th>Is Admitted?</th>
                                                          <td><?php if($ticket_details["admission_status"]==1){echo "Yes";}else{echo "No";} ?></td>

					</tr>
				</thead>
				<!--<tbody>
					
						
					<tr class="border-top">
					    <th colspan="3"></th>
						<th><?php e(lang("odp_doctor_id"));?></th>
						<th><?php e($ticket_details["doctor_name"]); ?></th>
					</tr>
					<tr>
					    <th colspan="3"></th>
						<th>Ticket Fee</th>
						<th> <?php e($ticket_details["ticket_fee"]); ?></th>
					</tr>
				</tbody> -->
			</table>
			<br>

			<div class="border">
				<p style="text-align: left;margin:0;padding: 3px;"><b>Total Paid(In word) : <?php echo inwords($ticket_details["ticket_fee"]) ?> Only</b></p>
			</div>

		   

			<table class="table" style="margin-top: 45px">
				<tr>
					<td>
						<span class="border-top">Cash Received By <?php echo $current_user; ?></span>
					</td>
					<td>
					<img src="<?php echo $bar_code_path; ?>" width="200">
					</td>
				</tr>
			</table>               
		</div>
</div>


<script type="text/javascript">
	window.print();
	window.close();
</script>