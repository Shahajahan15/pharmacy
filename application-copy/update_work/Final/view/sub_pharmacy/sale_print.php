
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
		border-top: 1px solid black;
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
	.paid_unpaid{
		position: relative;
	    z-index: 999999999;
	    left: -63px;
	    bottom: 149px;
	}
	.paid_unpaid img{
		width: 120px;
		height: 120px;

	}

</style>
<div class=" box box-primary" >
		<div class="col-md-8 col-md-offset-2" id="em_print_id">
			<table class="">
				<tr class="">
					<td>
                        <?php
                        if ($hospital->type==2)
                        { ?>
                            <img src="<?php echo base_url('assets/images/hospital/'.$hospital->logo); ?>" height="100" width="100" />
                        <?php }
                            else{
                        ?>
						<img src="<?php echo base_url('assets/images/hospital/'.$hospital->logo); ?>" height="100" width="100" />
                        <?php } ?>
					</td>
					<td width="2%"></td>
					<td>
						<b><?php echo $hospital->name; ?></b>
						<p style="margin:0"><?php echo $hospital->address; ?></p>
						<p style="margin:0"><?php echo $hospital->phone; ?></p>
						<p style="margin:0"><?php echo $hospital->mobile; ?></p>
						<p style="margin:0"><?php echo $hospital->email; ?></p>
					</td>
					<td width="20%">
						
					</td>

					<td width="25%">
						<table border="1" width="100%" style="margin-top:10px;">
							<tr class="v-align h-center">
								<td colspan="2"><b>Money Receipt</b></td>
							</tr>
							<tr>
								<td>sale no</td>
								<td><?php echo $records['0']->sale_no; ?></td>								
							</tr>
							<tr>
								<td>Date </td>
								<td><?php echo date('d/m/Y h:i A',strtotime($records['0']->created_date)); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<br>
			<center>
				<p><b>Sub Pharmacy Sale(Client Copy)</b></p>
			</center>
			<hr>

			<div class="border" style="padding: 3px;">
				<table>
					<tr>
						<td width="20%">Customer Name</td>
						<td>:</td>
						<td width="100%"><?php echo $c_info->name; ?></td>
					</tr>
					<tr>
						<td width="20%">Mobile</td>
						<td>:</td>
						<td width="100%"><?php echo $c_info->mobile; ?></td>
					</tr>
					<tr>
						<td width="20%">Customer Type</td>
						<td>:</td>
			<td width="100%">
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
				</table>
			</div>

			<br>

						
			<table class="table">
				<thead>
					<tr class="border">
						<th>SL</th>
						<th>Medicine Name</th>
						<th>Unit price</th>
						<th>Quantity</th>					
						<th>Discount</th>
						
						
						<th>Total Price</th>
					</tr>
				</thead>
				<tbody>
					<?php $total_discount=0; $sl=1; foreach($records as $record){
                   $discount=$record->qnty*($record->normal_discount_taka+$record->service_discount_taka);
                   $total_discount+=$discount;

						?>
						<tr>
							  <td><?php echo $sl++; ?></td>
							  <td><?php echo $record->category_name.'&nbsp; >> &nbsp;'.$record->product_name?></td> 
							  <td><?php echo $record->unit_price?></td>
							  <td><?php echo $record->qnty?></td>
							 
							  <td>
							   <?php if($discount>0){?>
							  <?php echo $discount?>
							  	 <?php }?>
							  </td>
							 
	                           <td>
	                           <?php echo ($record->qnty*$record->unit_price)-$discount;?>
	                           	
	                           </td>
						</tr>
					<?php }?>
					<tr class="border-top">
					    <th colspan="4"></th>
						<th>Total Price</th>
						<th><?php echo intval($record->tot_bill); ?></th>
					</tr>
					<tr>
						<th colspan="4"></th>
						<?php if($record->tot_less_discount>0){?>
						<th>Total Less Discount</th>
						<th>
						<?php echo intval($record->tot_less_discount);?>

						</th>
						<?php } ?>
					</tr>
					<tr>
						<th colspan="4"></th>
						<?php if($total_discount>0){?>
						<th>Total  Discount</th>
						<th>
						<?php echo intval($total_discount);?>

						</th>
						<?php } ?>
					</tr>
					<tr>
						<th colspan="4"></th>
						<th>Total Payable</th>
						<th><?php echo intval($total_payable=$record->tot_bill-$record->tot_less_discount);?></th>
						
					</tr>
					<tr>
						<th colspan="4"></th>
						<th>Total Receivable</th>
						<th><?php echo intval($total_payable=$record->tot_bill-$record->tot_less_discount);?></th>
						
					</tr>
					<tr>
						<th colspan="4"></th>
						<th>Paid Amount</th>
						<th><?php echo intval($total_paid=$record->tot_paid)?></th>
					</tr>

					<tr>
						<th colspan="4"></th>
						<th>Due</th>
						<th><?php echo intval($total_due=$total_payable-$total_paid); ?></th>
					</tr>

					
					
				</tbody>
			</table>
			<br>

			<div class="border">
				<p style="text-align: left;margin:0;padding: 3px;"><b>Total Paid(In word) : <?php echo inWords($record->tot_paid); ?> Only</b></p>
			</div>
			<?php $dp_img = ($total_due == "0") ? "paid" : "due"; ?>
		<div class="paid_unpaid"><img src="<?php echo base_url("assets/images/paid_unpaid/$dp_img.png"); ?>"></div>	
		   

			<table class="table" style="margin-top: -100px">
				<tr>
					<td>
						<span class="border-top">Cash Received By <?php echo $current_user; ?></span>
					</td>
					<td><strong><p style="font-weight: bold;font-size: 12px;padding: 5px">বিঃদ্রঃ</strong><u> ক্যাশ মেমো ছাড়া ওষুধ ফেরত নেয়া হবে না । </u></p></td>
				</tr>
			</table>	            
		</div>
				
		 
</div>


<script type="text/javascript">
	window.print();
	window.close();
</script>