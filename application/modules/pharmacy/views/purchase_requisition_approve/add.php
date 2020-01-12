<style>
	.fa-times{cursor: pointer;color: #860e0e;font-size: 20px;}
</style>

<?php echo form_open(site_url().'/admin/dept_requisition_issue/store/submit_issue', 'role="nform", class="nform-horizontal"'); ?>
<table class="table">
	<thead>
        <tr class="active">
            <th>Requisition No</th>
            <th>Requisition Date</th>
            <th>Store</th>
        </tr>
        <tr class="success">
            <td><?php echo $requisition_name->requisition_no; ?></td>
            <td><?php echo $requisition_name->requisition_date; ?></td>
            <td><?php echo ($requisition_name->store_id == 1) ? "Manin Store" : ""; ?></td>
        </tr>
    </thead>
</table>
<table class="table">
    <thead>
        <tr class="active">
            <th>Product Name</th>
            <th>Stock</th>
            <th>Re. Qnty.</th>
            <th>Approve Qnty.</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($records as $key => $record){ 
         $cl = ($key % 2 == 0) ? "success" : "info";
        ?>
        <tr class="<?php echo $cl; ?>">
            <td>
                <?php echo $record->product_name; ?> 
                <input type="hidden" name="product_id[]" value="<?php echo $record->product_id; ?>"/>
                <input type="hidden" name="id" value="<?php echo $record->id; ?>">
            </td> 
            <td>0</td>        
            <td><?php echo $record->req_qnty; ?></td>
            <td><input type="text" name="approve_qnty[]" class="form-control" value="" required=""/></td>
            <td><span class="p-requisition-remove" id="<?php echo $record->id; ?>"><i class="fa fa-times" aria-hidden="true"></i></span></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<center>
    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
    <button type="reset" class="btn btn-warning btn-sm">Reset</button>
</center>




<?php echo form_close(); ?>