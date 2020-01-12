
<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
    .page-break{
    page-break-before: always;
    page-break-after: always;
  }
  @page { margin: 0; }
</style>
<div class="box">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
@media print
{

.category_name{ display: none !important;}
.Company{ display: none !important;}
.val_category_name{ display: none !important;}
.val_company{ display: none !important;}



}
</style>
<div id="search_result">

    <?php echo report_header() ?>
    <div class="text-center">
    	<h3>Store Wise Stock (<?php echo isset($store_name) ? $store_name : "All"; ?>)</h3>
    	<h5> Total Stock Quantity : 
            <span style="font-weight: bold;"><?php echo $total_stocks->total_stock; ?></span>
              Total Stock(Tk) : <span style="font-weight: bold;"><?php echo $total_stocks->total_stock_tk; ?></span>
         </h5>
    	<h6>
    	<?php 
    	   if (isset($from_date) || isset($to_date)) :
    		if ($from_date && $to_date) : 
    			echo "Date from $from_date to $to_date";
    		elseif ($from_date) :
    			echo $from_date;
    		elseif ($to_date) :
    			echo $to_date;
    		endif;
    		endif;
    	?>
    	</h6>
    </div>

	<div class="admin-box">
			<table class="table table-striped table-bordered report-table">
				<thead>
					<tr>
						<th>#</th>	
						<th width="20%">Product Name</th>
						
						<th width="15%">Category Name</th>
						<th class="category_name">Sub Category Name</th>
						<th class="Company">Company Name</th>
						<th>Issue</th>
						<th>Received</th>
						<th>Return</th>
						<th>Replace</th>
						<th>Opening</th>
						<th>Current Stock</th>
						<th>U.Price</th>
						<th>Total Stock(Tk)</th>
						
					</tr>
				</thead>
				<?php if (!empty($records)) : ?>
				<tbody>
				    
					<?php 
						$tot_issue = 0;
						$tot_received = 0;
						$tot_return = 0;
						$tot_replace = 0;
						$tot_opening = 0;
						$tot_stock = 0;
						$tot_unit_price = 0;
						$tot_stock_tk = 0;
						$store_id = isset($store_id) ? $store_id : 0;
						foreach ($records as $key => $val) : 

							$tot_return += $val->p_return;
							$tot_replace += $val->p_replace;
							$tot_issue += $val->department_issue;
							$tot_received += $val->purchase_received;
							$tot_opening += $val->opening_balance;
							$tot_stock += $val->total_stock;
							if ($val->total_stock) {
								$tot_unit_price += $val->purchase_price;
							}
							$tot_stock_tk += $val->total_stock_taka;
							
					?>
					<tr>
						<td><?php echo ($key + 1); ?></td>
						<td><?php echo $val->product_name; ?></td>					
						<td><?php echo $val->category_name; ?></td>
						<td class="val_category_name"><?php echo $val->sub_category_name; ?></td>
						<td class="val_company"><?php echo $val->company_name; ?></td>
						<td><?php echo $val->department_issue; ?></td>
						<td><?php echo $val->purchase_received; ?></td>
						<td><?php echo $val->p_return; ?></td>
						<td><?php echo $val->p_replace; ?></td>
						<td><?php echo $val->opening_balance; ?></td>
						<td><?php echo $val->total_stock; ?></td>
						<td><?php echo $val->purchase_price; ?></td>
						<td><?php echo $val->total_stock_taka; ?></td>
						
					</tr>
					<?php endforeach; ?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3">Total</th>
						<th><?php echo $tot_issue; ?></th>
						<th><?php echo $tot_received; ?></th>
						<th><?php echo $tot_return; ?></th>
						<th><?php echo $tot_replace; ?></th>
						<th><?php echo $tot_opening; ?></th>
						<th><?php echo $tot_stock; ?></th>
						<th><?php echo $tot_unit_price; ?></th>
						<th><?php echo $tot_stock_tk; ?></th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
					<?php endif; ?>
			</table>
	    </div>

 
</div> 
</div>
   <?php echo ($records) ? $this->pagination->create_links() : ""; ?>
<script type="text/javascript">
    window.print();
    window.close();
</script>