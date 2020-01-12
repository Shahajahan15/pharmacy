
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
       
        <h4>Return Report Details </h4>
        <h5><strong>Clients Name :</strong> <?php echo $patient_name[0] ; ?></h5>

    </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
    <div class="table-responsive">
    <table class="table table-bordered 
    ">
        
        <thead>
            <tr class="active">
                <th>SL</th>
                <th>Sub Service Name</th>
                <!--<th>Refund</th>-->
                <th>Refund Amount</th>
                <th>Qntity</th>
                <th>Discount</th>
                <th>MR Discount</th>
                <th>Total Discount</th>
                
                
               
            </tr>
        </thead>
        
        <tbody>
            <?php 
   
            	$sl = 0;
                $total_discount = 0;
                $total_amount = 0;
                $total_quantity = 0;
            	foreach($records as  $row) : 
           
            	//$total_qnty+=$row->qnty;

              
            ?>
            <tr>
              <td><?php echo $sl+=1; ?></td>
                <td><?php echo $row->test_name;?></td>
                <!--<td><?php echo $row->total_refund_amount;?></td>-->
                <td><?php echo $row->amount;?></td>
                <td><?php echo $row->qnty;?></td>
                <td><?php echo $row->discount;?></td>
                <td><?php echo $row->mr_discount;?></td>
                <td><?php echo $row->discount+$row->mr_discount;?></td>
   

            </tr>
            <?php $total_discount+= $row->discount + $row->mr_discount; ?>
            <?php $total_amount+= $row->amount; ?>
             <?php $total_quantity+= $row->qnty; ?>
            <?php endforeach; ?>
            <tr>
            	<td></td>
            	<td></td>
            	<td><b>Total:<?php echo $total_amount;?></b></td>
            	<td><b>Total:<?php echo $total_quantity;?></b></td>
                <td></td>
                <td></td>
            	<td><b>Total:<?php echo $total_discount;?></b></td>
 
            	
            </tr>
        </tbody>
       
        
    </table>

    </div>
    </div>

    </div>

</div>

 <?php // echo $this->pagination->create_links(); ?>

