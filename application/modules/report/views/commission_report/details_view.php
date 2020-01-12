
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
       
        <h4>Commission Report Details of </h4>
        <?php if (!empty($records)) : ?>
        <h5><strong>Doctor Name :</strong> <?php echo $emp_name[0] ;?>(<?php echo $agent_t ?>)</h5>
    <?php endif ?>
       </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
    <div class="table-responsive">
    <table class="table table-bordered 
    ">
        
        <thead>
            <tr class="active">
                <th>SL</th>
                <th>MR NO</th>
                <th>Date</th>
                <th>Test Name</th>
                <th>Test Price</th>
                <th>Qntity</th>
                <th>Commisssion</th>
                <th>Discount</th>
                <th>Total</th>
                
                
               
            </tr>
        </thead>
        
        <tbody>
            <?php 
            	$sl=0;
            	$total_qnty=0;
            	$total_commission=0;
            	$total_discount=0;
            	$total=0;
            	
            	foreach($records as  $row) : 
           
            	$total_qnty+=$row->qnty;
                $total_commission+=$row->commission_amount;
                $total_discount+=$row->discount_amount;
              
            ?>
            <tr>
              <td><?php echo $sl+=1; ?></td>
                <td><?php echo $row->mr_no;?></td>
                <td><?php echo $row->diagnosis_date;?></td>
                <td><?php echo $row->test_name;?></td>
                <td><?php echo $row->test_price;?></td>
                <td><?php echo $row->qnty;?></td>
                <td><?php echo $row->commission_amount;?></td>
                <td><?php echo $row->discount_amount;?></td>
                <td><?php echo $row->commission_amount-$row->discount_amount;?></td>

            </tr>
            <?php $total+=$row->commission_amount-$row->discount_amount; ?>
            <?php endforeach; ?>
            <tr>
            	<td></td>
            	<td></td>
            	<td></td>
            	<td></td>
            	<td></td>
            	<td><b>Total:<?php echo $total_qnty;?></b></td>
            	<td><b>Total:<?php echo round($total_commission);?></b></td>
            	<td><b>Total:<?php echo round($total_discount);?></b></td>
            	<td><b>Total:<?php echo round($total);?></b></td>
            	
            </tr>
        </tbody>
       
        
    </table>

    </div>
    </div>

    </div>

</div>

 <?php echo $this->pagination->create_links(); ?>

