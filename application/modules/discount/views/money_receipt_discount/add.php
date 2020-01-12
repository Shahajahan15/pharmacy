<style type="text/css">
	table.table tbody tr td{padding: 2px;}
	table.table tfoot tr th, table.table tfoot tr td{padding: 1px;}
</style>
<div class="box box-primary">
<?php echo form_open('admin/collection/refund/create', 'id="mr-discount-form"'); ?>

<fieldset>
    <legend>Add Money Receipt Discount</legend>
    	<div class="col-md-8 col-md-offset-2">
    		<div class="row">
    			<div class="col-sm-1">
    			</div>
		        <div class="col-sm-3">
		            <div class="form-group">
		                <label for="service_id">Service <?php echo lang('bf_form_label_required') ?></label>
		                <select id="service_id" name="service_id" class="form-control" required="required">
		                    <option></option>
		                    <?php foreach($services as $service) : ?>
		                    <option value="<?php echo $service->id; ?>"><?php echo $service->service_name ?></option>
		                    <?php endforeach; ?>
		                </select>
		            </div>
		        </div>
		        <div class="col-sm-3">
		            <div class="form-group">
		                <label for="mr_no">MR No. <?php echo lang('bf_form_label_required') ?></label>
		                <input type="text" id="mr_no" name="mr_no" class="form-control" required="required" />
		            </div>
		        </div>
		        <div class="col-sm-1">
		            <div class="form-group">
		                <br/>
		                <button class="btn btn-warning btn-xs" type="submit" id="search-money-receipt-btn">Search</button>
		            </div>
		        </div>
        </div>
    </div>
    <div class="row">
    	 <hr class="clearfix" />
    </div>

    <div class="col-sm-offset-2 col-sm-8">
        <div id="discount-money-recept-show">
        </div>
    </div>

</fieldset>

<?php echo form_close(); ?>
</div>