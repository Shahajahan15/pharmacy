<div class="admin-box">
    <?php echo report_header() ?>
    <div class="text-center">
         <h3><?php  if(count($pharmacy_name)>0){ if(isset($pharmacy_name->name)){echo $pharmacy_name->name;}else{echo "Main Pharmacy";}}else{echo "Main Pharmacy";}?>  Customer Wise Report</h3> 

         <?php  if(count($pharmacy_name)>0){ if(isset($pharmacy_name->name)){ $pharmacy_id=$pharmacy_name->id;}else{$pharmacy_id=200;}}else{$pharmacy_id=200;}?>
    </div>
    <br/>
    <br/>
    <?php
        $has_records = isset($records) && is_array($records) && count($records);
    ?>
    <div class="col-sm-12 col-md-12 col-lg-12">
         <table class="table table-striped m_ph_payment">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Customer Name</th>
                    <th>Customer Type</th>
                    <th>Total Bill</th>
                    <th>Total Payment</th>
                    <th>Total Return</th>
                    <th>Less Discount</th>
                    <th>Total Discount</th>
                    <th>Total Due</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($has_records) :
                $i=1;
                foreach ($records as $record) :
                    $tot_paid = isset($full_paid[$record->id]['paid']) ? $full_paid[$record->id]['paid'] : 0;
                    $tot_paid_return = isset($full_paid[$record->id]['return_paid']) ? $full_paid[$record->id]['return_paid'] : 0;
                    $tot_due = ($record->tot_bill) - ($tot_paid + $tot_paid_return);
                    ?>
                        <tr>
                             <td><?php echo $i++; ?></td>
                             <td><?php echo $record->customer_name; ?></td>
                            <td><?php 
                                if ($record->customer_type == 1):
                                    $id=$record->admission_id;
                                    echo "Admission Patient";
                                elseif ($record->customer_type == 2) :
                                    $id=$record->patient_id;
                                    echo "Patient";
                                elseif ($record->customer_type == 3) :
                                    echo "Customer";
                                 $id=$record->customer_id;
                                elseif ($record->customer_type == 4) :
                                    echo "Employee";
                                 $id=$record->employee_id;
                                 elseif ($record->customer_type == 5) :
                                    echo "Doctor";
                                 $id=$record->employee_id;
                                elseif ($record->customer_type == 6) :
                                    echo "Hospital";
                                 $id=0;
                                endif;
                             ?></td>
                            <td class="tot_bill"><?php e($record->tot_bill); ?></td>
                            <td class="tot_paid"><?php e($record->tot_paid); ?></td>
                            <td class="tot_return"><?php e($record->total_return); ?></td>
                            <td class="tot_return"><?php e($record->tot_less_discount); ?></td>
                            <td class="tot_discount"><?php e($record->total_discount); ?></td>
                            <td class="tot_due"><?php      
                    echo $tot_due = ($record->tot_bill) - ($record->tot_paid + $tot_paid_return+$record->tot_less_discount);
                ?></td>
                           <td>
                               <a href='<?php echo site_url()."/admin/client_wise_report/report/client_wise_all_details_information/$record->customer_type/$id/$pharmacy_id" ?>' class="btn btn-success btn-xs cbtn-mini">View</a>
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
    </div>
    <?php echo $this->pagination->create_links(); ?>
</div>

