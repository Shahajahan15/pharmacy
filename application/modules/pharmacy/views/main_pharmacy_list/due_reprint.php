

<?php
//extract($sendData);
//print_r($records);exit();
?>

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap3.3.7.css'); ?>">
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
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
	.h-center{
		text-align: center;
	}
	.v-align{
		vertical-align:middle !important;
	}
	.panel-info, .panel{
		padding: 5px;
	}
	.border-top{
		border-top: 1px solid #eee;
	}
	.border{
		border: 1px solid black;
	}
	.paid_unpaid{
		position: relative;
	    z-index: 999999999;
	    left: -160px;
	    bottom: 149px;
	}
	.paid_unpaid img{
		width: 120px;
		height: 120px;

	}
	.uppercase{
		text-transform: uppercase;
	}
</style>
<div class=" box box-primary">
		<div class="col-md-8 col-md-offset-2" id="em_print_id">
			<table class="table">
				<tr class="head">
					<span class="pull-left">
					<?php $img = base_url($hospital->logo);?>
						<td style="width:152px"><img class="logo" src=" <?php echo $img;?>">
						</td>
						<td colspan="2" align="center">
						<h3><b><?php echo $hospital->name?></b></h3>
						<h6><?php echo $hospital->address?></h6>
						<h6><?php echo $hospital->mobile?></h6>
						<h6><?php echo $hospital->email?></h6>
						</td>	
						<td>
							<table border="1" class="table table-bordered" width="100%" style="margin-top:10px;">
								<tr class="v-align h-center">
									<td colspan="2"><b>Money Receipt</b></td>
								</tr>
								<!-- <tr>
									<td>MR. No</td>
									<td><?php ?></td>								
								</tr> -->
								<tr>
									<td>Date </td>
									<td><?php echo date('d M, Y'); ?></td>
								</tr>
							</table>
						</td>				
				</tr>
				<tr>
				<td colspan="4" class="head-info"><h4 class="panel panel-info">Pharmacy <b>Due Paid</b> Money Receipt</h4></td>
				</tr>
				
			</table>


			<div class="border" style="padding: 3px;">
				<table>
					<tr>
						<td width="20%">Customer Name</td>
						<td>:</td>
						<td class="uppercase" width="100%"><?php echo $c_info->name; ?></td>
					</tr>
<?php if($c_info->dob!=0){ ?>
					<tr>
						<td width="20%">Age</td>
						<td>:</td>
						<td width="100%"><?php echo dob_convert_age($c_info->dob); ?></td>
					</tr>
<?php } ?>
					<tr>
						<td width="20%">Mobile</td>
						<td>:</td>
						<td width="100%"><?php echo $c_info->mobile; ?></td>
					</tr>
					<tr>
						<td width="20%">Customer Type</td>
						<td>:</td>
			<td class="uppercase" width="100%">
			 <?php 
			 if($records['0']->customer_type==1){
			 	echo 'Admission';
			 }
			 elseif ($records['0']->customer_type==2) {
			 	echo 'Patient';
			 }
			 elseif ($records['0']->customer_type==3) {
			 	echo 'Customer';
			 }
			 elseif($records['0']->customer_type==4) {
			 	echo 'Employee';
			 }
			 elseif($records['0']->customer_type==5) {
			 	echo 'Doctor';
			 }
			 else{
			 	echo 'None';
			 }
			 ?>
				
			</td>
					</tr>

					<?php if($records['0']->customer_type == 1) { ?>

					<?php $patient_info = currentAdmissionPatientBedInfo($c_info->admission_id); ?>
					<tr>
						<td width="20%">Room Name</td>
						<td>:</td>
			<td width="100%">
			<?php echo $patient_info->room_name; ?>
			
			</td>
					</tr>
					<tr>
						<td width="20%">Bad Name</td>
						<td>:</td>
			<td width="100%">
			<?php echo $patient_info->bed_name; ?>
			
			</td>
					</tr>
					
					<?php } else {echo ""; } ?>
				</table>
			</div>

			<br>



		
			<table class="table" style="margin-top: 5px;">
				<thead>
					<tr class="border">
						<th>#</th>
						<th>Payment Date</th>
						<th>Sale No.</th>
						<th>Type</th>
						<th>Pay</th>
					</tr>
				</thead>
				<tbody>
					<?php $sl=0;
					$amount=0;

					foreach($records as $record){
						$amount+=$record->amount;?>
						<tr>
							  <td><?php echo $sl+=1;?></td>
							  <td><?php echo ($record->create_time); 
							   ?></td> 
							   <td><?php echo $record->sale_no; ?></td>
							  <td>
							  <?php 
							     echo 'Payment';
							  ?>
							  	
							  </td>
							  <td><?php if($record->type==1 || $record->type==2 || $record->type==3){echo $record->amount;} ?></td>

						</tr>
						
						
					
					<?php } ?>
				

				</tbody>
            <tr>
            <td colspan="3"></td>
			<td >Total paid = <?php echo $amount;?> Tk.</td>
			</tr>
			<?php  if($total_due > 0) { ?>
<tr> <td> &nbsp;</td></tr>
			<tr>
            <td colspan="3"></td>
           
			<td >Total Due Amoutn = <?php echo $total_due;?> Tk.</td>
			</tr>
			<?php  } ?>
			</table>

			
			
			<br>
	 <!--  <div class="border">
				<p style="text-align: left;margin:0;padding: 3px;"><b>Total Paid(In word) : <?php //echo inWords($record->paid_amount); ?> Only</b></p>
			</div>  -->
		<hr>
<table>
     <tr>
              <!--  <td>
                <?php  
        //$master_id = $records[0]->id;
       // $patient_code = $records[0]->patient_id;
       // $bar_code_path = base_url("barcode/diagnosis/$master_id-$patient_code.png");

                {?>
					<img src="<?php// echo $bar_code_path; ?>" width="200">
					
               <?php } ?>
		        </td> -->
	</tr>	        
</table>

       
		<table class="table">
			<tr>
				<td>
					<span class="pull-right" ">Cash Received By <?php echo $current_user;?></span>
				</td>
			</tr>
				
		</table>
        

	</div>
</div>




</div>
<script type="text/javascript">

    window.print();
    setTimeout(function() {
        window.close();
    }, 150);

</script>