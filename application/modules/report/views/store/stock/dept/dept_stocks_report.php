<!--<?php if (strtolower($current_user->role_name) == 'administrator') : ?>
<div class="panel">
	<div class="panel-body">
		<div class="col-sm-6 col-md-4">
			<strong>Select Department : </strong>
			<select id="store_department" name="store_department" data-href="<?php echo site_url('admin/store_stock_dept/report/stocks') ?>">
				<option value="0">-- Select --</option>
				<?php foreach($departments as $department_option): ?>
				<option value="<?php echo $department_option['dept_id'] ?>" <?php echo isset($department) && $department['dept_id'] == $department_option['dept_id'] ? 'selected="selected"' : '' ?> ><?php echo $department_option['department_name'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>
<?php endif; ?>-->

<div class="box">

    <?php echo report_header() ?>
    <div class="text-center">
        <h4>Department Stock Report(<?php
        	if (isset($department) && !empty($department)) : 
        		echo "<b>".$department->department_name."</b>";
        	else :
        		echo "All";
        	endif;
         ?>)</h4>
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
    <br>
		<table class="table table-striped table-bordered report-table">
			<thead>
				<tr>
					<th>#</th>
					<th>Company Name</th>
					<th>Category Name</th>
					<th>Sub Category Name</th>
					<th>Product Name</th>
					<th>Requisition</th>
					<th>Issue</th>
					<th>Opening Balance</th>
					<th>Current Stock</th>
					<th>U.Price</th>
					<th>Total Stock(Tk)</th>
					<th>Action</th>
				</tr>
			</thead>
			<?php if (!empty($records)) : ?>
			<tbody>
			    <?php 
			    $dept_id = isset($dept_id) ? $dept_id : 0; 
			    $tot_requ = 0;
			    $tot_issue = 0;
			    $tot_open = 0;
			    $tot_stock = 0;
			    $tot_unit_price = 0;
			    $tot_stock_tk = 0;
				 foreach ($records as $key => $val) : 
				 	$tot_issue += $val->issue;
					$tot_requ += $val->requisition_received;
					$tot_open += $val->opening_balance;
					$tot_stock += $val->total_stock;
					if ($val->total_stock) {
						$tot_unit_price += $val->purchase_price;
					}
					$tot_stock_tk += $val->total_stock_taka;
				 	?>
				<tr>
					<td><?php echo ($key + 1); ?></td>
					<td><?php echo $val->company_name; ?></td>
					<td><?php echo $val->category_name; ?></td>
					<td><?php echo $val->sub_category_name; ?></td>
					<td><?php echo $val->product_name; ?></td>
					<td><?php echo $val->requisition_received; ?></td>
					<td><?php echo $val->issue; ?></td>
					<td><?php echo $val->opening_balance; ?></td>
					<td><?php echo $val->total_stock; ?></td>
					<td><?php echo $val->purchase_price; ?></td>
					<td><?php echo $val->total_stock_taka; ?></td>
					<td>
						<a href="<?php echo site_url('admin/store/report/store_stock_dept/details/'.$dept_id.'/'.$val->id) ?>" class="btn btn-primary btn-xs" >Details</a>
					</td>
				</tr>
				<?php endforeach; ?>
				<tfoot>
					<tr>
						<th colspan="5">Total</th>
						<th><?php echo $tot_requ; ?></th>
						<th><?php echo $tot_issue; ?></th>
						<th><?php echo $tot_open; ?></th>
						<th><?php echo $tot_stock; ?></th>
						<th><?php echo $tot_unit_price; ?></th>
						<th><?php echo $tot_stock_tk; ?></th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
				
			</tbody>
			<?php else: ?>
				<tr>
					<td colspan="15"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
		</table>

    <?php echo $this->pagination->create_links(); ?>
</div>