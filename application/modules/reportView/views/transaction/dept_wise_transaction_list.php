<?php
extract($sendData);

?>


<div class="admin-box">
	<?php echo form_open($this->uri->uri_string()); ?>		
		<!-- Start Search -->
		
		
		<!-- End Search -->	
		<table class="table table-striped" border="1">
			<thead>
				<caption><h4><?php echo lang("dept_wise_total_transaction");?></h4></caption>
				<tr>
					<th width="9%"><?php echo lang("transaction_dept_name");?></th>
					<th width="12%"><?php echo lang("transaction_test_name");?></th>
					<th width="5%"><?php echo lang("transaction_test_quantity");?></th>
					<th width="12%"><?php echo lang("transaction_collection_amount");?></th>
					<th width="13%"><?php echo lang("transaction_refund_amount");?></th>
					<th width="12%"><?php echo lang("transaction_discount_amount");?></th>
					<th width="13%"><?php echo lang("transaction_net_amount");?></th>
				</tr>
			</thead>
			
			<tbody>
			<?php 
					foreach($subdept_name as $key ):
					
					
					$key = (object) $key;
					
					?>
				<tr>					
					<td>
					<?php 
						echo $key->subdept_name;
						
					?>
					</td>
					
					<td colspan="6">						
						<table class="table table-striped">
							<tbody>
							<?php
									
									$total_collection_amount 	= 0; 
									$total_refund_amount 		= 0; 
									$total_discount_amount 		= 0; 
									$total_net_amount 			= 0; 
									
									foreach($testdata as $key )
									{
										$total_collection_amount = $total_collection_amount + $key->test_price;
										$total_discount_amount 	 = $total_discount_amount + $key->discount;
										$total_net_amount 		 = $total_net_amount + $key->total_price;
										
									?>
								<tr>									
									<td width="12%"><?php echo $key->test_name;?></td>
									<td width="5%"><?php //echo $key->test_name;?></td>
									<td width="12%"><?php echo $key->test_price;?></td>
									<td width="13%"><?php //echo $key->test_name;?></td>
									<td width="12%"><?php echo $key->discount;?></td>
									<td width="13%"><?php echo $key->total_price;?></td>																		
								</tr>
								<?php
									}
								?>
								<tr>
									<td>&nbsp;</td>									
									<td colspan="3" style="text-decoration:overline;"><?php echo 'Department Total: '.$total_collection_amount;?></td>
									<td style="text-decoration:overline;"><?php echo $total_discount_amount;?></td>
									<td style="text-decoration:overline;"><?php echo $total_net_amount;?></td>	
								</tr>
							</tbody>
						</table>					
					</td>					
				</tr>
				<?php
					endforeach;
				?>
				<tr>					
					<td>OPD</td>					
					<td colspan="6">						
						<table class="table table-striped">
							<tbody>
							<?php
									$t_amount=0;$c=1;
									$total_collection_amount 	= 0; 
									$total_refund_amount 		= 0; 
									$total_discount_amount 		= 0; 
									$total_net_amount 			= 0; 
									
									if($total_amount != NULL) :									
										foreach($total_amount as $row) :
										
											$row = (object) $row;	
											
											$t_amount = $t_amount + $row->amount;
											
										endforeach;
										endif;
									?>
								<tr>									
									<td width="12%">Ticket</td>
									<td width="05%"><?php //echo $c++;?></td>
									<td width="05%"><?php echo $t_amount;?></td>
									<td width="12%"</td>
									
									<td width="12%"></td>
									<td width="13%"></td>																		
								</tr>
									<?php  ?>
								<tr>
									<td>&nbsp;</td>									
									<td colspan="3" style="text-decoration:overline;"><?php echo 'Department Total: '.$total_collection_amount;?></td>
									<td style="text-decoration:overline;"><?php echo $total_discount_amount;?></td>
									<td style="text-decoration:overline;"><?php echo $total_net_amount;?></td>	
								</tr>
							</tbody>
						</table>					
					</td>					
				</tr>
			</tbody>
		</table>
		
	<?php 
	echo form_close(); 
	?>
</div>