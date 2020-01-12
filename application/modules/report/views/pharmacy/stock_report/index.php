
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <?php if (!empty($records)) : ?>
    <div class="text-center">
        <h3>Pharmacy Wise Stock (<?php if($records[0]->pharmacy_name!=''){ echo $records[0]->pharmacy_name;}else {echo $pharmacy_name->pharmacy_name;} ?>)</h3>
        <?php if ($total_records) : ?>
            <h4 class="text-center">Total Stock Quantity : 
                <span style="font-weight: bold;">
                    <?php echo  $total_records->total_qnty; ?>
                </span> &nbsp; Total Stock Amount : <span style="font-weight: bold;">
                    <?php echo $total_records->total_stock_price; ?> TK
                </span>
             </h4>
        <?php endif; ?>
    </div>
<?php endif?>
    <br/>
    <br/>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered report-table">
            <thead>
                <tr>
                    <th>SL No.</th>                   
                    <th>Company Name</th>
                    <th>Category Name</th>
                    <th>Medicine Name</th>                  
                    <th>Opn Qtn</th>
                    <th>Sales Qtn</th>
                    <th>Purchase Qtn</th>                 
                    <th>Sale R. Qtn</th>
                    <th>Issue Qtn</th>
                    <th>Req. Qtn</th>
                    <th>P.Retrun</th>    
                    <th>P.Replace</th>                
                    <th>C. Stock</th>
                    <th>U. Price(Sales)</th>
                    <th>Total Stock(Tk)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if ($records) : 
                    foreach($records as $key => $val) : 
                    $product_id = ($val->product_id) ? $val->product_id : 0;
                    ?>
                <tr>
                <td><?php  echo $key+1; ?></td>             
                <td><?php echo $val->company_name; ?></td>
                <td><?php echo $val->category_name; ?></td>
                <td><?php echo $val->product_name; ?></td>
                <td><?php echo $val->opening_balance; ?></td>
                <td><?php echo $val->sale; ?></td>
                <td><?php echo $val->purchase; ?></td>
                <td><?php echo $val->sale_return; ?></td>
                <td><?php echo $val->issue_send; ?></td>
                <td><?php echo $val->req_receive; ?></td>
                <td><?php echo $val->purchase_return; ?></td>
                <td><?php echo $val->purchase_replace; ?></td>
                <td><?php echo $val->stock_qnty; ?></td>
                <td><?php echo $val->sale_price; ?></td>

                    <td><?php echo ($val->sale_price *$val->stock_qnty); ?></td>
                   <td><a class="btn btn-info btn-xs" href="<?php echo site_url(SITE_AREA . '/pharmacy_wise_stock/report/details/'.$product_id.'/'.$val->pharmacy_id); ?>" style="text-decoration:none;">details</a></td>

                </tr>
            <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
 <?php echo $this->pagination->create_links(); ?>
   

</div>
