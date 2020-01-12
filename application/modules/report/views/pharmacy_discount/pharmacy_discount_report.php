<div class="admin-box">
    <?php echo report_header() ?>
    <div class="text-center">
        <h3><?php  if(count($pharmacy_name)>0){ if(isset($pharmacy_name->name)){echo $pharmacy_name->name;}else{echo "Main Pharmacy";}}else{echo "Main Pharmacy";}?> Discount Report</h3>
    </div>
    <br/>
    <br/>
    <div class="col-sm-12 col-md-12 col-lg-12">
    <?php if($pharmacy_id=="200" || $pharmacy_id==""){ ?>
        <table class="table table-striped table-bordered report-table">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Date</th>
                    <th>Category Name</th>
                    <th>Company Name</th>
                    <th>Medicine Name</th>
                    <th>Discount Type</th>
                    <th>Total Discount (TK)</th>
                    <th>Quantity</th>
                    <th>Total Amount (TK)</th>
                    <th>Return Discount (TK)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)) : ?>
                     <?php  $i=1; foreach($records as $record) : ?>
                    <?php if($record->normal_discount_taka !=0 || $record->service_discount_taka !=0 || $record->tot_discount !=0){ ?>
                <tr>
                <td><?php  echo $i++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($record->created_date)) ?></td>
                <td><?php  echo $record->category_name; ?></td>
                <td><?php  echo $record->company_name; ?></td>
                <td><?php  echo $record->product_name; ?></td>
                    <td><?php
                     if($record->n_discount_type)
                     {
                     if($record->n_discount_type==1){echo "Over All ";} elseif($record->n_discount_type==2){echo "Normal ";}else {echo "Service";}
                     }
                     else 
                     {
                        echo "Service ";
                     } 


                     ?></td>
                    <td><?php  if($record->normal_discount_taka !=0){ echo $record->normal_discount_taka*$record->qnty;} else {echo $record->service_discount_taka*$record->qnty;}?></td>
                    <td><?php if($record->type==1){echo $record->qnty;}else {echo $record->r_qnty;} ?></td>
                    <td><?php if($record->type==1){echo $record->qnty*$record->unit_price;}else{echo $record->r_qnty*$record->unit_price;} ?></td>
                    <td><?php echo $record->tot_discount*$record->r_qnty; ?></td>
                </tr>
            <?php } endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php }else { ?>
        <table class="table table-striped table-bordered report-table">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Date</th>
                    <th>Category Name</th>
                    <th>Company Name</th>
                    <th>Medicine Name</th>
                    <th>Total Discount</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Return Discount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)) : ?>
                     <?php  $i=1; foreach($records as $record) : ?>
                <tr>
                <td><?php  echo $i++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($record->created_date)) ?></td>
                <td><?php  echo $record->category_name; ?></td>
                <td><?php  echo $record->company_name; ?></td>
                <td><?php  echo $record->product_name; ?></td>
                <td><?php  echo $record->totat_discount*$record->qnty;?></td>
                    <td><?php if($record->type==1){echo $record->qnty;}else {echo $record->r_qnty;} ?></td>
                    <td><?php if($record->type==1){echo $record->qnty*$record->unit_price;}else{echo $record->r_qnty*$record->price;} ?></td>
                    <td><?php echo $record->return_discount*$record->r_qnty; ?></td>
                </tr>
            <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php } ?>
    </div>

    <?php echo $this->pagination->create_links(); ?>
</div>

