<div id='search_result'>

<div class="admin-box">
<a onClick="printDiv('div_print')" class="pull-right btn btn-success">
      <span class="glyphicon glyphicon-print"></span> Print 
    </a>
    <div class="col-sm-12 col-md-12 col-lg-12" id="div_print">
    <style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Diagnosis Cash Collection</h3>

        <?php if (isset($first_date, $second_date) && $first_date != date('Y-m-d 00:00:00')  && !empty($first_date) && !empty($second_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($first_date)) ?> to <?php echo date('d/m/Y',strtotime($second_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>MR No</th>
                <th>Patient Id</th>
                <th>Patinet Name</th>
                <th>Total Bill</th>
                <th>Discount</th>
                <th>Less Discount</th>               
                <th>Net Bill</th>
                <th>Advance/Received Amount</th>
                <th>Due Bill</th>
                
            </tr>
        </thead>
        <tbody>
        <?php if ($mr_wise_collection) :
            $tot_bill=0;
            $tot_paid=0;
            $net_bill_amount=0;
            $tot_due_paid_amount=0;
            $tot_refund_amount=0;
            $tot_due=0;
        	foreach ($mr_wise_collection as $key1 => $row) : 
                $tot_bill+= $row->tot_bill;     
                $tot_paid+= $row->paid_amount;      
                $tot_due_paid_amount+= $row->due_paid_amount;   
                $tot_refund_amount+= $row->refund_amount;
                $net_bill_amount+= $row->tot_bill-$row->less_discount_amount;
                $tot_due+= $row->tot_bill-$row->less_discount_amount-$row->paid_amount;
	
        ?>
        	<tr>
        		<td><?php echo $sl+=1; ?></td>
                <td><?php echo $row->mr_no; ?></td>
                <td><?php echo $row->patient_code; ?></td>
                <td><?php echo $row->patient_name; ?></td>
        		<td><?php echo $row->tot_bill; ?></td>
                <td><?php echo $row->discount_amount; ?></td>
                <td><?php echo $row->less_discount_amount; ?></td>     
                <td><?php echo $net_bill=$row->tot_bill-$row->less_discount_amount; ?></td>    		
                <td><?php echo $row->paid_amount; ?></td>
        		<td><?php echo $due=$net_bill-$row->paid_amount; ?></td>
        		
        	</tr>
        <?php endforeach; ?>
        <tfoot>
            <tr>
                <th colspan="4"><strong>Total:</strong></th>
                <th><?php echo $tot_bill; ?></th>
                <th></th>
                <th></th>
                <th><?php echo $net_bill_amount; ?></th>
                <th><?php echo $tot_paid; ?></th>
                <th><?php echo $tot_due; ?></th>
            </tr>
        </tfoot>
             <table class="table table-bordered">
        
        <tr>
            <th colspan="12">Total Paid</th>
            <th><?php echo $tot_paid ?></th>
        </tr>
        <tr>
            <th colspan="12">Total Due Paid</th>
            <th><?php echo $tot_due_paid_amount ?></th>
        </tr>
        <tr>
            <th colspan="12">Total Refund</th>
            <th><?php echo $tot_refund_amount ?></th>
        </tr>
        <tr>
            <th colspan="12">Total Collection</th>
            <th><?php echo $total_collection=($tot_paid+$tot_due_paid_amount)-$tot_refund_amount ?></th>
        </tr>
    </table>
    <p style="border:3px solid black;padding:6px;font-size:18px">
        <b>Total Collection(In word): <?php echo inWords($total_collection)?> <?php echo ($total_collection>0)?'Only':'';?></b>
    </p>
        <?php else : ?>
            <tr>
                <td colspan="10" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
        <?php endif; ?>
        </tbody>
         
    </table>

    
    </div>
</div>
</div>
</div>
<?php echo ($mr_wise_collection != null) ?  $this->pagination->create_links() : "" ; ?>