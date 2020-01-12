
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
				<tr>
					<td><?php echo $id; ?></td>
				</tr>
				
			</table>

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