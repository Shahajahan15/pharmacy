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
								<td colspan="2"><b>Discount Money Receipt</b></td>
							</tr>
							<tr>
								<td>Receipt No</td>
								<td><?php e($mr_no);?></td>								
							</tr>
							<tr>
								<td>Date </td>
								<td><?php echo date('d M, Y',strtotime($result->created_at)); ?></td>
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
						
						<th><?php e("Patient Code");?></th>
                                                <td><?php e($patient_info['patient_id']);?></td>
                                                
						<th><?php e("Patient Name");?></th>
                                                <td><?php echo $patient_info['patient_name']; ?></td>
                                        </tr>
                                        <tr>
						<th><?php e("Sex");?></th>
                                                 <td><?php if($patient_info['sex'] == 1){echo "Male";} elseif($patient_info['sex'] == 1){echo "Female";}else {echo "Common";}  ?></td>
						<th><?php e("Date Of Birth");?></th>
                                                 <td><?php echo $patient_info['birthday']; ?></td>
						
					</tr>
                                        <tr>
							 <th>Contact no</th>
							  <td><?php echo $patient_info['contact_no']; ?></td>

					</tr>
                                         
				</thead>
			</table>
			<br>

			<!-- <div class="border">
				<p style="text-align: left;margin:0;padding: 3px;"><b>Total Paid(In word) : <?php echo inwords($ticket_details["ticket_fee"]) ?> Only</b></p>
			</div> -->

		   

			<table class="table" style="margin-top: 45px">
				<tr>
					<td>
						<span class="border-top">Cash Received By <?php echo $current_user; ?></span>
					</td>
					<!-- <td>
					<img src="<?php echo $bar_code_path; ?>" width="200">
					</td> -->
				</tr>
			</table>               
		</div>
</div>


<script type="text/javascript">
	window.print();
	window.close();
</script>