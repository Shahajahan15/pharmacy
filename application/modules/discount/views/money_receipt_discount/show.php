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
            		<td><span class="pull-right"><?php echo round($result->total_bill_amount + $result->less_discount + $result->discount); ?></span></td>
            	</tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">Total Discount</span></th>
            		<td><span class="pull-right"><?php echo round($result->discount + $result->less_discount + $result->mr_discount); ?></span></td>
            	</tr>
                <tr>
                    <th colspan="4"><span class="pull-right">Total Bill Refund</span></th>
                    <td><span class="pull-right"><?php echo round($result->refund_bill_amount); ?></span></td>
                </tr>
                <tr>
                    <th colspan="4"><span class="pull-right">Net Bill</span></th>
                    <td><span class="pull-right"><?php echo $tot_net_bill = ($result->total_bill_amount) - ($result->less_discount + $result->mr_discount + $result->refund_bill_amount); ?></span><input type="hidden" name="net_bill" value="<?php echo round($tot_net_bill); ?>" class="net_bill"><input type="hidden" name="service_id" value="<?php echo $service_id; ?>" class="service_id"></td>
                </tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">Total Paid</span></th>
            		<td><span class="pull-right"><?php echo round($result->collection); ?></span></td>
            	</tr>
                <tr>
                    <th colspan="4"><span class="pull-right">Total Refund</span></th>
                    <td><span class="pull-right"><?php echo round($result->refund); ?></span></td>
                </tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">Total Due</span></th>
            		<td><span class="pull-right"><?php echo round($tot_net_bill - $result->collection + $result->refund) ?></span></td>
            	</tr>
            	<tr>
            		<th colspan="4"><span class="pull-right">MR. Discount</span></th>
            		<td><span class="pull-right"><input type="text" name="mr_discount" value="0" class="form-control mr_discount" class="mr_discount"></span><input type="hidden" class="mr_no" name="mr_no" value="<?php echo $result->mr_no; ?>"><input type="hidden" class="patient_id" name="patient_id" value="<?php echo $result->patient_id; ?>"></td>
            	</tr>
            	<tr>
            		<td class="text-center" colspan="8">
            			<span class="money_recept_discount btn btn-success btn-xs" >Discount</span>
            		</td>
            	</tr>
            </tfoot>
        </table>
<?php endif; ?>