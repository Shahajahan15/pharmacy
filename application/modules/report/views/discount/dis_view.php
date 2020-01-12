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
        <h3>Discount Report Details</h3>
        <p><strong>MR No:<?php echo  $discount_info[0]->mr_no; ?>;Patient Name:<?php echo  $discount_info[0]->patient_name; ?></strong></p>
        <p><strong></strong></p>

        
    </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
                <th>#</th>
                <th>Sub Service Name</th>
                <th>Amount Type</th>
                <th>Service Discount</th>
                <th>D. Discount</th>
                <th>H. Discount</th>
                <th>MR. Discount</th>
                <th>Special Day Discount</th>
                <th>Refund</th>
                <th>Total Discount</th>
                
                
            
            </tr>
        </thead>
     <?php if ($discount_info) : ?>
      <tbody>
            <?php 
            	$total_discount_amount = 0;
                $hospital_discount_amount = 0;
                $doctor_discount_amount = 0;
                $mr_discount_amount = 0;
                $service_discount_amount = 0;
                $special_day_discount = 0;
                $refund_dicount = 0;
               
            	
            	foreach($discount_info as $key => $row) : 
            	$bc = ($key % 2 == 1) ? "#E4E4E4" : "#B0C4DE";
            ?>
            <tr style="background: <?php echo $bc; ?>">
                <td><?php echo $key+1; ?></td>
                <td><?php e(service_sub_service_name($row->service_id,0,$row->sub_service_id)); ?></td>
                
                <td><?php if($row->discount_type==1){echo "Amount";}
                elseif($row->discount_type==2)
                    {
                        if ($row->type == 1) {
                            echo $row->doctor_discount_percent."%";
                        }
                        elseif ($row->type == 0) {
                            echo $row->discount_persent."%";
                        }
                        elseif ($row->type == 3) {
                            echo $row->discount_persent."%";
                        }
                        
                    }

                ?></td>
                <td><?php if($row->type == 0 && $row->source != 10)
                    {
                        echo  $row->service_dicount;
                    }
                     else
                    {
                        echo "0";
                    } 
                     ?>
                        
                </td>
                <td>
                    <?php 
                    if($row->type == 1)
                    {
                        echo  $row->doctor_dicount;
                    }
                    else
                    {
                        echo "0";
                    } 
                    ?>
                        
                </td>
                <td>
                    <?php 
                    if($row->type == 1)
                    {
                        echo  $row->hospital_dicount;
                    } 
                    else
                    {
                        echo "0";
                    } 
                    ?>
                        
                </td>
                <td>
                    <?php 
                    if($row->type == 2)
                    {
                        echo  $row->mr_discount;
                    }
                    else
                    {
                        echo "0";
                    }  
                    ?>
                    
                        
                </td>

                      <td>
                    <?php 
                    if($row->type == 3)
                    {
                        echo  $row->sd_dicount;
                    }
                    else
                    {
                        echo "0";
                    }  
                    ?>
                    
                        
                </td>

                <td>
                    <?php 
                    if($row->source == 10)
                    {
                        echo  $row->refund_dicount;
                    }
                    else
                    {
                        echo "0";
                    }  
                    ?>
                    
                        
                </td>

                <td><?php 

                if($row->type == 0 && $row->source != 10)
                    {
                        echo $total_discount = $row->service_dicount;
                    }
                elseif($row->type == 0 && $row->source == 10)
                    {
                        echo $total_discount = $row->refund_dicount;
                    }     
                elseif($row->type == 1 && $row->source != 10)
                    {
                        echo $total_discount = $row->doctor_dicount+$row->hospital_dicount;
                    }
                elseif($row->type == 2 && $row->source != 10)
                    {
                        echo $total_discount = $row->mr_discount;
                    }    
                elseif($row->type == 3 && $row->source != 10){
                        echo $total_discount = $row->sd_dicount;  //special day discount
                   }
                

                 ?></td>

      <?php 

                $doctor_discount_amount = $doctor_discount_amount+ $row->doctor_dicount;
                $hospital_discount_amount = $hospital_discount_amount+ $row->hospital_dicount;
                $mr_discount_amount = $mr_discount_amount+ $row->mr_discount;
                $total_discount_amount = $total_discount_amount+ $total_discount;
                $service_discount_amount = $service_discount_amount+ $row->service_dicount;
                $refund_dicount = $refund_dicount+ $row->refund_dicount;
                $special_day_discount = $special_day_discount+ $row->sd_dicount;
                ?>

            </tr>
            <?php endforeach; ?>
               <tr>
                <td colspan="3"><strong>Total:</strong></td>
                <td><?php echo $service_discount_amount; ?></td>
                <td><?php echo $doctor_discount_amount; ?></td>
                <td><?php echo $hospital_discount_amount; ?></td>
                <td><?php echo $mr_discount_amount; ?></td>
                <td><?php echo $special_day_discount; ?></td>
                <td><?php echo $refund_dicount; ?></td>
                <td><?php echo $total_discount_amount; ?></td>
                
            
            </tr>
        </tbody>
    <?php else: ?>
        <tr>
                <td colspan="14" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
    <?php endif; ?>
    </table>

    </div>
    </div>
    </div>
</div>
</div>
</div>

 <?php echo $this->pagination->create_links(); ?>