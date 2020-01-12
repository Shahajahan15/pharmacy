<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Money Receipt Wise Collection</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
            	<th>#</th>
                <th>MR. No</th>
                <th>MR. Date</th>
                <th>Patient Id</th>
                <th>Service Name</th>
                <th>Tot. Bill</th>
                <th>Sub Service Name</th>
                <th>Sub Bill</th>
                <th>Discount</th>
               <!-- <th>Day</th>  -->
                <th>Tot.Paid</th>
                <th>Tot.Due Paid</th>
                <th>Tot.Refund</th>
               <!-- <th>Tot.Due</th> -->
                <th>Collector</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($mr_wise_collection) :
        $tot_bill=0;
        $tot_paid=0;
        $tot_due_paid=0;
        $discount=0;
        	foreach ($mr_wise_collection as $key1 => $row) :
                $tot_bill+= $row->tot_bill;
                $tot_paid+= $row->paid_amount;
                $tot_due_paid+= $row->due_paid_amount;
        	$bc = ($key1 % 2 == 1) ? "#E1E1E1" : "#E0F9FC";
        ?>
        	<tr style="background: <?php echo $bc; ?>">
        		<td><?php echo $key1+1; ?></td>
        		<td><?php echo $row->mr_no; ?></td>
        		<td><?php echo custom_date_format($row->collection_date); ?></td>
        		<td><?php echo $row->patient_code; ?></td>
        		<td><?php echo $row->service_name; ?></td>
        		<td><?php echo $row->tot_bill; ?></td>
    			<td colspan="3">
    				<table class="table table-bordered" style="margin-bottom: 0px;background: none;">
    				<?php if ($mr_wise_collection_dtls && ($row->transaction_type == 1)): 
    					foreach ($mr_wise_collection_dtls as $key => $col) :
                       

    					if ($col->mst_id == $row->id) :
                             $discount+= $col->discount;
    				?>
    					<tr>
    						<?php if ($col->service_id == 3) : ?>
    							<td>Bed Fee</td>
    						<?php elseif ($col->service_id == 1) : ?>
    							<td><?php echo $col->test_name; ?></td>
    						<?php else : ?>
    							<td><?php echo $col->otherservice_name; ?></td>
    						<?php endif; ?>
    						<td><?php echo $col->amount; ?></td>
    						<td><?php echo $col->discount; ?></td>
    						<!--<td><?php // echo ($col->day != 0) ? $col->day."days": 0; ?></td> -->
    					</tr>
    				<?php endif; endforeach; endif; ?>
    				</table>
    			</td>
        		
        		<td><?php echo $row->paid_amount; ?></td>

        		<td><?php echo $row->due_paid_amount; ?></td>
        		<td><?php echo $row->refund_amount; ?></td>
                <!--<td><?php //echo $row->tot_bill-$row->paid_amount; ?></td> -->
        		<td><?php echo $row->emp_name; ?></td>
        	</tr>
        <?php endforeach; ?>
           <table class="table table-bordered">
          <tr>
                <th colspan="8">Total</th>
                <th> Bill=<?php echo $tot_bill; ?></th>
                <th> Discount=<?php echo $discount;?></th>
                <th> Paid=<?php echo $tot_paid;?></th>
                <th> Due Paid=<?php echo $tot_due_paid;?></th>
               

            </tr>
         <tr>
            <th colspan="13">Total Bill</th>
            <th><?php echo $tot_bill;?></th>
        </tr>
          <tr>
            <th colspan="13">Total Discount</th>
            <th><?php echo $discount;?></th>
        </tr>
        <tr>
            <th colspan="13">Total Paid</th>
            <th><?php echo $tot_paid;?></th>
        </tr>
        <tr>
            <th colspan="13">Total Due Paid</th>
            <th><?php echo $tot_due_paid; ?></th>
        </tr>
       
      <?php else : ?>
            <tr>
                <td colspan="12" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
        <?php endif; ?>
        </tbody>   
    </table>
       

    <?php echo ($mr_wise_collection != null) ?  $this->pagination->create_links() : "" ; ?>
    </div>
</div>