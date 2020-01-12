    
    <div class="box">
        <style type="text/css">
        @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
    </style>
        <a href="<?php echo site_url('admin/client_wise_report/report/index'); ?>"><button type="button" class="btn btn-success pull-right">
          <div class="glyphicon glyphicon-arrow-left pull-right"></div>
        </button></a>
    <?php 
    echo report_header();
    $has_records = isset($records) && is_array($records) && count($records);
    ?>
    <div class="text-center">
       <h3>Customer Wise Details Report(<?php echo $pharmacy_name; ?>)</h3>
       <h5 class="text-center"> Client Type:&nbsp;<span style="font-weight: bold;"><?php echo ($records) ? $records[0]->customer_type_name : ""; ?></span> &nbsp; <span style="font-weight: bold;"></span>&nbsp; Client Name : <span style="font-weight: bold;"><?php echo ($records) ? $records[0]->client_name : ""; ?></span></h5>
   </div>
   <div class="table-responsive">
    <table class="table table-striped report-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>MR. Type</th>
                <th>MR No</th>
                <th>Bill</th>
                <th>Less Discount</th>
                <th>Net Bill</th>
                <th>Overall Discount</th>
                <th>Amount</th>
                <th>Entry By</th>
            </tr>
        </thead>
        <?php
        $has_records = isset($records) && is_array($records) && count($records);
        ?>
        <tbody>
            <?php
            if ($has_records) :
                $i=1;
                $tot_bill=0;
                $less_discount=0;
                //$discount=0;
                foreach ($records as $record) :
                    if($record->type==1){
                $tot_bill+= $record->tot_bill; 
            }
                 
                $less_discount+= $record->tot_less_discount; 
             
               // $discount+=$record->tot_less_discount; 
               
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo date('d/m/Y h:i:sa',strtotime($record->create_time)); ?></td>
                        <td><?php 
                            if($record->type == 1){
                                echo "Cash Sale";
                                $bill = $record->tot_bill;
                                $less_discount = $record->tot_less_discount;
                                $overall_discount = 0;
                            } elseif ($record->type == 2) {
                                echo "Due Paid";
                                $bill = 0;
                                $less_discount = 0;
                                $overall_discount = $record->overall_discount;
                            } else {
                                echo "Return";
                                $bill = $record->return_bill;
                                $less_discount = $record->return_less_discount;
                                $overall_discount = $record->overall_discount;
                            }
                        ?></td>
                        <td><?php echo $record->mr_no; ?></td>
                        <td><?php echo round($bill); ?></td>
                        <td><?php echo round($less_discount); ?></td>
                        <td><?php echo round($bill - $less_discount); ?></td>
                        <td><?php echo round($overall_discount); ?></td>
                        <td><?php echo $record->amount; ?></td>
                        <td><?php echo $record->emp_name; ?></td>
                    </tr>
                    <?php
                endforeach;
                ?>
                <?php
            else:
                ?>
                <tr>
                    <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>


      
    


    </table>

    <table class="table">
        <tr>
                <th width="20%" class="">Total Bill</th>
                <th ><?php echo $tot_bill;?></th>
                
        </tr>
        
        <tr>
               <th width="10%" class="">Total Less Discount</th>
               <th><?php echo round($less_discount);?></th>
                
        </tr>
       <tr>
               <th width="10%" class="">Total Net Bill</th>
               <th><?php echo $tot_bill-$less_discount;?></th>
                
        </tr>
    </table>
     <?php echo ($records != null) ?  $this->pagination->create_links() : "" ; ?>
</div>
</div>