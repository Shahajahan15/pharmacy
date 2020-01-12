<div class="box">
    <style type="text/css">
        @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
    </style>
    <?php 
        echo report_header();

        if($pharmacy_id==200)
        {
            $pname = 'Main Pharmacy';
        }
        else
        {
            $pname = 'Taj Enterprise';
        }
        //$pharmacy_id = 200;
        //if(count($pharmacy_name) > 0 && isset($pharmacy_name->name))  
         //   $pname = $pharmacy_name->name;
          //  $pharmacy_id=$pharmacy_name->id;
        
        $has_records = isset($records) && is_array($records) && count($records);
    ?>
    <div class="text-center">
         <h3>Customer Wise Report(<?php echo $pname; ?>)</h3>
    </div>
    <div class="table-responsive">
         <table class="table table-striped report-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client Name</th>
                    <th>Client Type</th>
                    <th>P.Bill</th>
                    <th>R.Bill</th>
                    <!--<th>Net Bill</th>-->
                    <th>L.Discount</th>
                    <th>R.L.Discount</th>
                    <th>Over.Discount</th>
                    <th>Over.Discount</th>
                    <th>P. Amount</th>
                    <th>R. Amount</th>
                    <th>Discount</th>
                    <th>Due</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                $i=1;
                foreach ($records as $record) :
                   // $net_less_discount = ($record->less_discount - $record->return_less_discount);
                   // $net_bill = ($record->bill - $record->return_bill);
                   // $net_payment = ($record->payment - $record->return_amount);
                    //$due = (($net_bill + $record->return_less_discount) - ($net_payment + $record->less_discount));
                    ?>
                        <tr>
                             <td><?php echo $i++; ?></td>
                             <td><?php echo $record->client_name; ?></td>
                            <td><?php echo $record->customer_type_name; ?></td>
                            <td><?php e($record->bill); ?></td>
                            <td><?php e($record->return_bill); ?></td>
                            <!--<td><?php e($net_bill); ?></td>-->
                            <td><?php e($record->less_discount); ?></td>
                            <td><?php e($record->return_less_discount); ?></td>
                            <!--<td><?php e($net_less_discount); ?></td>-->
                            <td><?php e($record->overall_discount); ?></td>
                            <td><?php e($record->return_overall_discount); ?></td>
                            <td><?php e($record->payment); ?></td>
                            <td><?php e($record->return_amount); ?></td>
                            <td><?php e($record->discount); ?></td>
                            <td><?php e($record->due) ?></td>
                           <td>
                               <a href='<?php echo site_url()."/admin/client_wise_report/report/test_details/$record->customer_type/$record->client_id/$pharmacy_id" ?>' class="btn btn-success btn-xs cbtn-mini">View</a>
                           </td>
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
        <?php echo ($has_records != null) ?  $this->pagination->create_links() : "" ; ?>
    </div>
</div>

