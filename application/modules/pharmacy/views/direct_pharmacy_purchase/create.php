<style type="text/css">

    .table tbody tr td {
        margin: 0;
        padding: 1px;
    }

    .table thead tr th {
        margin: 0;
        padding: 1px;
    }

    .plus-minus {
        width: 20px;
        height: 25px;
    }

    .panel-title {
    }

    .style-border {
        border: 3px solid #3c8dbc;
        padding: 5px;
        background: whitesmoke;
        margin: 2px;
    }

</style>

<?php

if (isset($_POST)) {
    unset($_POST);
}

$validation_errors = validation_errors();
if ($validation_errors) :
    ?>
    <div class="alert alert-block alert-error fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <h4 class="alert-heading">Please fix the following errors:</h4>
        <?php echo $validation_errors; ?>
    </div>
<?php
endif;

?>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal", id="purchase_pharmacy_form", data-toggle="validator"'); ?>
    <fieldset>
        <legend>Purchase Product Entry</legend>
        <div class="col-sm-12 col-md-4 col-lg-4 col-lg-offset-2">
            <div class="form-group">
                <label class="control-label">Select Supplier</label>
                <div class="input-group">
                    <select class="form-control pharmacy_supplier_auto" name="supplier_id" required="">

                    </select>
                    <div class="input-group-addon btn supplier-list">
                        <i class="fa fa-list"></i>
                    </div>
                    <div class="input-group-addon btn btn-success btn-xs add-new-supplier" style="color:white">
                        <i class="fa fa-plus"></i>
                    </div>
                </div>
            </div>
        </div>

     <!--   <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="store" class="control-label">Select Store</label>
                <select class="form-control" name="store_id" id="store" required="">
                    <option value="" style="font-size: 15px;">Select Store</option>
                    <?php
/*                    foreach ($stores as $store) {
                        $store = (object)$store;
                        */?>
                        <option style="font-size: 15px;"
                                value="<?php /*echo $store->id; */?>"><?php /*echo $store->name; */?></option>
                    <?php /*} */?>
                </select>

            </div>
        </div>-->

        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="pharmacy_product" class="control-label">Select Madecine</label>
                <div class="form-group">
                    <select tabindex="5" class="medicine-auto-complete form-control" id="pharmacy_product"></select>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Work-Order</legend>
        <table class="table">
            <thead>
            <tr class="info">
                <th width="10%">Category</th>
                <th width="10%">Product</th>
                <th width="10%">Stock</th>
                <th width="10%">Last Purchase Price</th>
                <th width="12%">Order Qnty</th>
                <th width="12%">Free Qnty</th>
                <th width="12%">Unit Price</th>
                <th width="10%">Total Price</th>
                <th width="4">-</th>
            </tr>
            </thead>
            <tbody id="pharmacy_product_rows">
            </tbody>
        </table>
    </fieldset>


    <fieldset>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm purchase_product_btn"
                       value="submit"/>
                <input type="reset" name="" class="btn btn-danger btn-sm" value="Reset"/>
            </div>
        </div>
    </fieldset>
    <?php echo form_close(); ?>
</div>

<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
