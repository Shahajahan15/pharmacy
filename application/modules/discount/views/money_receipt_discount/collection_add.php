<style type="text/css">
    table.table tbody tr td{padding: 2px;}
    table.table tfoot tr th, table.table tfoot tr td{padding: 1px;}
</style>
<?php if ($result): 
 $result = (object)$result;
?>
        <table id="searched-money-receipts" class="table table-bordered table-striped report-table">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>MR.Date</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>MR No.</th>
                </tr>
            </thead>
            <tbody>
            	<tr>
                    <td>Diagnosis</td>
                    <td><?php echo $result->mr_date; ?></td>
                    <td><?php echo $result->patient_code; ?></td>
                    <td><?php echo $result->patient_name; ?></td>
                    <td style="width: 80px;"><?php echo $result->mr_no; ?></td>
                </tr>
            </tbody>
            <tfoot>
            	<tr>
            		<th colspan="4"><span class="pull-right">Total Price</span></th>
            		<td><span class="pull-right"><?php echo ($result->total_bill_amount + $result->less_discount + $result->discount); ?></span></td>
            	</tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">Total Discount</span></th>
            		<td><span class="pull-right"><?php echo ($result->discount + $result->less_discount + $result->mr_discount); ?></span></td>
            	</tr>
                <tr>
                    <th colspan="4"><span class="pull-right">Total Bill Refund</span></th>
                    <td><span class="pull-right"><?php echo round($result->refund_bill_amount); ?></span></td>
                </tr>
                <tr>
                    <th colspan="4"><span class="pull-right">Net Bill</span></th>
                    <td><span class="pull-right"><?php echo $tot_net_bill = ($result->total_bill_amount) - ($result->less_discount + $result->mr_discount + $result->refund_bill_amount); ?></span></td>
                </tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">Total Paid</span></th>
            		<td><span class="pull-right"><?php echo ($result->collection); ?></span></td>
            	</tr>
                <tr>
                    <th colspan="4"><span class="pull-right">Refund</span></th>
                    <td><span class="pull-right"><?php echo ($result->refund); ?></span></td>
                </tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">Total Due</span></th>
            		<td><span class="pull-right"><?php echo ($tot_net_bill - $result->collection + $result->refund) ?></span></td>
            	</tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">Approve MR. Discount</span></th>
            		<td><span class="pull-right"><?php echo $mr_discount_info->mr_approve_discount; ?></span></td>
            	</tr>
                <tr>
                    <th colspan="3"><span class="pull-right">Return Taka</span></th>
                    <th colspan="2"><span class="pull-right">
                        <?php 
                         $tot_cu_payable = ($mr_discount_info->mr_approve_discount + $result->collection);
                            echo ($tot_cu_payable > $tot_net_bill) ? ($tot_cu_payable - $tot_net_bill) : 0;
                        ?>
                        </span></th>
                </tr>
            	<tr>
            		<td class="text-center" colspan="8">
                      <input type="hidden" name="master_id" class="master_id" value="<?php echo $mr_discount_info->id; ?>">
            			<span class="mr-discount-collection btn btn-success btn-xs" >Collection</span>
            		</td>
            	</tr>
            </tfoot>
        </table>
<?php endif; ?>