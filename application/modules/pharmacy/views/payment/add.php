<style>
	.fa-times{cursor: pointer;color: #860e0e;font-size: 20px;}
</style>
<table class="table">
	<thead>
        <tr class="active">
            <th>Bill No</th>
            <th>Supplier Name</th>
            <th>Received Date</th>
            <th>Approve Date</th>
        </tr>
        <tr class="success">
            <td><?php echo  $pr_mst->bill_no; ?></td>
            <td><?php echo  $sup_name->supplier_name; ?></td>
            <td><?php echo  $pr_mst->received_date; ?></td>
            <td><?php echo  $pr_mst->approve_date; ?></td>
        </tr>
    </thead>
</table>
<div class="row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal payment_form"'); ?>
    <div class="">
<!--     	<table class="table">
    		<tr>
    			<td><b>Total Bill</b></td>
          <td><b>Previous Paid</b></td>
          <td><b>Current Paid</b></td>
          </tr>
          <tr>
    			<td ><input type="text" name="total_bill" class="store_total_bill form-control" value="<?php echo $total_bill->total_bill; ?>" readonly=""/></td>
    			
    			<td><input type="text" name="total_p_paid" class="store_total_p_paid form-control" value="<?php echo $p_paid; ?>" readonly=""/></td>
    			
    			<td><input type="text" name="total_c_paid" class="store_total_c_paid form-control" value="<?php echo ($total_bill->total_bill - $p_paid); ?>" readonly=""/></td>
    			<td>&nbsp;</td>
    		</tr>
    	</table> -->
         <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="form-group <?php echo form_error('total_bill') ? 'error' : ''; ?>">
          <?php echo form_label('Total Bill','total_bill',array('class'=> 'control-label')); ?>
                  <div class='control'>
                    <input type="text" name="total_bill" class="form-control store_total_bill" value="<?php echo $total_bill->total_bill; ?>" readonly="" />
                  </div>
                  </div>
                        
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="form-group <?php echo form_error('total_p_paid') ? 'error' : ''; ?>">
          <?php echo form_label('Previous Paid','total_p_paid',array('class'=> 'control-label')); ?>
                  <div class='control'>
                    <input type="text" name="total_p_paid" class="form-control store_total_p_paid" value="<?php echo $p_paid; ?>" readonly=""/>
                     <input type="hidden" name="supplier_id" class="form-control store_supplier_id" value="<?php echo $sup_name->id; ?>" />
                  </div>
                  </div>
                        
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="form-group <?php echo form_error('total_c_paid') ? 'error' : ''; ?>">
          <?php echo form_label('Due','total_c_paid',array('class'=> 'control-label')); ?>
                  <div class='control'>
                    <input type="text" name="total_c_paid" class="form-control store_total_c_paid" value="<?php echo ($total_bill->total_bill - $p_paid); ?>" readonly="" />
                  </div>
                  </div>
                        
        </div>
    </div>
    <div class="">
    	<input type="hidden" name="recieve_order_id" value="<?php echo $pr_mst->id; ?>"/>
      <input type="hidden" name="recieve_mst_paid" value="<?php echo $pr_mst->paid; ?>"/>
    	
    	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="form-group <?php echo form_error('payment_type') ? 'error' : ''; ?>">
				  <?php echo form_label('Payment Type','payment_type',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<select name="payment_type" id="payment_type" class="form-control">
						<option value="1">Cash</option>
						<option value="2">Bank</option>
					</select>
                  </div>
                  </div> 		
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="form-group <?php echo form_error('payment') ? 'error' : ''; ?>">
				  <?php echo form_label('Payment','payment',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<input type="text" name="payment" class="form-control store_payment" required="" />
                  </div>
                  </div>
                    		
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="form-group <?php echo form_error('due') ? 'error' : ''; ?>">
				  <?php echo form_label('Due','due',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<input type="text" name="due" class="form-control store_due" readonly="" />
                  </div>
                  </div>
                    		
        </div>
        </div>
        <div class="row">
        <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 branch" style="display: none;">
        	<div class="form-group <?php echo form_error('branch_id') ? 'error' : ''; ?>">
				  <?php echo form_label('Branch Name','branch_id',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<select name="branch_id" id="branch_id" class="form-control branch_id">
						<option value="">Select a One</option>
						<?php foreach ($branch_list as $row) : ?>
						<option value="<?php echo $row->id; ?>"><?php echo $row->bank_name."->".$row->branch_name."->".$row->account_no; ?></option>
						<?php endforeach; ?>
					</select>
                  </div>
                  </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 check_date" style="display: none;">     
            <div class="form-group <?php echo form_error('check_date') ? 'error' : ''; ?>">
				  <?php echo form_label('Check Date','check_date',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<input type="text" name="check_date" class="form-control datepickerCommon c_date"/>
                  </div>
                  </div>
                    		
        </div>
        
        </div>
        
        <div class="row">
       
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<center>
    <button type="submit" id="save" class="btn btn-primary btn-xs">Submit</button>
    <button type="reset" class="btn btn-warning btn-xs">Reset</button>
</center>
</div>
</div>
 <?php echo form_close(); ?>
</div>
<script>
	$(".datepickerCommon").datepicker({
    format:'dd/mm/yyyy'
});
</script>