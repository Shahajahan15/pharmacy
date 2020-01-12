<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
</style>
   
    <div class="text-center">
        <h3>Sub Pharmacy Money Receipt Wise Collection</h3>

      
        
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>MR. No</th>
                <th>MR. Date</th>
                <th>Client Type</th>
                <th>Client Name</th>
                <th>Client ID</th>
                <th>Contact No</th>
                <th>Total Bill</th>   
                <th>Total Discount</th>   
                <th>Total Paid</th>
                <th>Total Refund</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
      

      <?php $sl=0;foreach($sum_collection as $record){ ?>
     
        	<tr>
                <td><?php echo $sl+=1;?></td>
                <td><?php echo $record['sale_no']; ?></td>
        		
                <td><?php echo custom_date_format($record['created_date']);?></td>
                <td>
                <?php 
                if($record['customer_type']==1){
                    echo 'Admission patient';
                }elseif ($record['customer_type']==2) {
                   echo 'Patient';
                }
                elseif ($record['customer_type']==3) {
                   echo 'Customer' ;               
                }
                elseif ($record['customer_type']==4) {
                    echo 'Employee';
                }
                 elseif ($record['customer_type']==5) {
                    echo 'Doctor';
                }
                elseif ($record['customer_type']==6) {
                    echo 'Hospital';
                }


                ?>
                        
                </td>
                <td><?php echo $record['name'];?></td>
                <td><?php echo $record['customer_id'];?></td>
                <td><?php echo $record['mobile'];?></td>
                <td><?php echo intval($record['tot_bill']);?></td>
                <td><?php echo intval($record['tot_normal_discount']+$record['tot_service_discount']+$record['tot_less_discount']);?></td>
                <td><?php echo  $record['total_collect']?></td>
                <td><?php echo $record['total_return_amount']?></td>
                <td><a class="btn btn-success xm-sm" href="">view</a></td>
               


        	</tr>
 
  
           <?php }?>
        
        </tbody>
         
    </table>
   
    
    </div>
</div>