



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
								<td><?php echo $records[0]['purchase_order_no']; ?></td>								
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
				<p><b>Purchase Order</b></p>
			</center>
			<hr>

			<div class="border" style="padding: 3px;">
				<table>
					<tr>
						<td width="20%">To</td>
						<td></td>
						<td width="100%"></td>
					</tr>
					<tr>
						<td width="20%">Supplier Name</td>
						<td>:</td>
						<td width="100%"><?php echo $records[0]['supplier_name']; ?></td>
					</tr>
					<tr>
						<td width="20%">Contact No</td>
						<td>:</td>
			<td width="100%">
			<?php echo $records[0]['contact_no1']; ?>
			
			</td>
					</tr>
				</table>
			</div>

			<br>

						
			<table class="table">
				<thead>
					<tr class="border">
						<th>SL</th>
						<th>Product Name</th>
						<th>Unit price</th>
						<th>Quantity</th>
						<th>Total Price</th>
					</tr>
				</thead>
				<tbody>
					<?php $sl=1;$total_price=0;   foreach($records as $record){
						$total_price=$record['order_unit_price']*$record['order_qnty']+$total_price;

						?>
						<tr>
							  <td><?php echo $sl++; ?></td>
							  <td><?php echo $record['product_name']?></td> 
							  <td><?php echo $record['order_unit_price']; ?></td>
							  <td><?php echo $record['order_qnty']; ?></td>
							  <td><?php echo $record['order_unit_price']*$record['order_qnty']; ?></td>
						</tr>
					<?php }?>
					<tr class="border-top">
					    <th colspan="3"></th>
						<th>Total Price</th>
						<th><?php echo $total_price?></th>
					</tr>
					

					
					
				</tbody>
			</table>
			<br>

			<div class="border">
				<p style="text-align: left;margin:0;padding: 3px;"><b>Total Paid(In word) : <?php echo inWords($total_price); ?> Only</b></p>
			</div>

		   

			<table class="table" style="margin-top: 45px">
				<tr>
					<td>
						<span class="border-top">Cash Received By <?php echo $records[0]['username']; ?></span>
					</td>
				</tr>
			</table>               
		</div>
				
		 
</div>


<script type="text/javascript">
	window.print();
	window.close();
</script>