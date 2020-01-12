<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
</style>
<div class="box">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Money Receipt Wise Collection</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
           
        <?php endif; ?>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>MR. No</th>
                <th>MR. Date</th>
                <th>Patient Id</th>
                <th>Patinet Name</th>
                <th>Contact No</th>
                <th>Service Name</th>
                <th>Collector</th>
                <th>Total Bill</th>               
                <th>Total Paid</th>
                <th>Total Due</th>
                <th>Total Due Paid</th>
                <th>Total Refund</th>
                
            </tr>
        </thead>
        <tbody>
        <?php if ($mr_wise_collection) :
            $tot_bill=0;
            $tot_paid=0;
            $tot_due=0;
            $tot_due_paid_amount=0;
            $tot_refund_amount=0;
        	foreach ($mr_wise_collection as $key1 => $row) : 
                $tot_bill+= $row->tot_bill;     
                $tot_paid+= $row->paid_amount;      
                $tot_due_paid_amount+= $row->due_paid_amount;   
                $tot_refund_amount+= $row->refund_amount;

                //due
                $due=$row->tot_bill-($row->paid_amount+$row->due_paid_amount); 
                $tot_due+=$due; 	
        ?>
        	<tr>
        		<td><?php echo $sl+=1; ?></td>
        		<td><?php echo $row->mr_no; ?></td>
        		<td><?php echo date('d/m/Y (h:i:sa)',strtotime($row->collection_date)); ?></td>
                <td><?php echo $row->patient_code; ?></td>
                <td><?php echo $row->patient_name; ?></td>
        		<td><?php echo $row->contact_no; ?></td>
        		<td><?php echo $row->service_name; ?></td>
                <td><?php echo $row->emp_name; ?></td>
        		<td><?php echo $row->tot_bill; ?></td>        		
                <td><?php echo $row->paid_amount; ?></td>
        		<td><?php echo $due; ?></td>
        		<td><?php echo $row->due_paid_amount; ?></td>
        		<td><?php echo $row->refund_amount; ?></td>
        		
        	</tr>
        <?php endforeach; ?>
        <tfoot>
            <tr>
                <th colspan="8">Total</th>
                <th><?php echo $tot_bill; ?></th>
                <th><?php echo $tot_paid; ?></th>
                <th><?php echo $tot_due; ?></th>
                <th><?php echo $tot_due_paid_amount; ?></th>
                <th><?php echo $tot_refund_amount; ?></th>
            </tr>
        </tfoot>
        <?php else : ?>
            <tr>
                <td colspan="14" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
        <?php endif; ?>
        </tbody>
         
    </table>
    <table class="table table-bordered">
        <tr>
            <th colspan="12">Total Bill</th>
            <th><?php echo $tot_bill ?></th>
        </tr>
        <tr>
            <th colspan="12">Total Paid</th>
            <th><?php echo $tot_paid ?></th>
        </tr>
        <tr>
            <th colspan="12">Total Due</th>
            <th><?php echo $tot_due ?></th>
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
    
    </div>
</div>
<script type="text/javascript">
	window.print();
	window.close();
</script>