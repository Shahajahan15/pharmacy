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
	.box-headline{
		padding: 7px;
		font-size: 18px;
		background-color: whitesmoke;	
		border: 1px solid black;

	}
	
</style>
<div class="box box-primary" >
		<div class="col-md-8 col-md-offset-2" id="em_print_id">

			<center>
				<img src="<?php echo base_url($hospital->logo); ?>" width="200"><br>
				<b><?php echo $hospital->name; ?></b>
				<p style="margin:0"><?php echo $hospital->address; ?></p>
				<p style="margin:0"><?php echo $hospital->mobile; ?></p>
			</center>

			<br>
			<center>
				<p class="box-headline"><b>Issue to Indoor pharmacy</b></p>
			</center>
			<br>
			<br>

			<table class="table">
				<thead>
					<tr class="border">
						<th>Serial</th>
						<th>Product Name</th>
						<th>Issue Qntity</th>
					</tr>
				</thead>
					<tbody>
						<?php
							$sl=0; 
							foreach($records as $record){
							
							?>
						 <tr>
						 	<td><?php echo $sl+=1; ?></td>
						 	<td><?php echo $record['product_name']; ?></td>
						 	<td><?php echo $record['quantity']; ?></td>
						 </tr>
						<?php } ?>
					</tbody>
									
			</table>

			<div class="pull-right" style="border-top: 1px solid black;margin-top: 50px">Order Issue by <?php echo $record['EMP_NAME'][0]; ?></div>

			<div class="pull-left" style="border-top: 1px solid black;margin-top: 50px">Order Received by </div>


			             
		</div>
				
		 
</div>


<script type="text/javascript">
	window.print();
	window.close();
</script>















