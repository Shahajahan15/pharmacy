<?php
//extract($sendData);
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

if (isset($product_details)) {
    $product_details = (array)$product_details;
}
$id = isset($product_details['id']) ? $product_details['id'] : '';
?>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'id="pharmacy-product-form" role="form" class="nform-horizontal local-form-submit"') ?>
    <fieldset>
        <legend>Product Add</legend>

        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="<?php echo form_error('pharmacy_company_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_company_name') . lang('bf_form_label_required'), 'pharmacy_company_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <select name="pharmacy_company_name" id="pharmacy_company_name" class="form-control"
                                required="">
                            <option value=""><?php echo lang('select_a_one'); ?></option>
                            <?php foreach ($company_name as $row) : ?>
                                <option value="<?php echo $row->id; ?>" <?php if (isset($product_details)) {
                                    echo(($product_details['company_id'] == $row->id) ? "selected" : "");
                                } ?>><?php echo $row->company_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('pharmacy_company_name'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="<?php echo form_error('pharmacy_category_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_category_name') . lang('bf_form_label_required'), 'pharmacy_category_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <select name="pharmacy_category_name" id="pharmacy_category_name" class="form-control"
                                required="">
                            <option value=""><?php echo lang('select_a_one'); ?></option>
                            <?php foreach ($category_name as $row) : ?>
                                <option value="<?php echo $row->id; ?>" <?php if (isset($product_details)) {
                                    echo(($product_details['category_id'] == $row->id) ? "selected" : "");
                                } ?>><?php echo $row->category_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('pharmacy_category_name'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!--  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="<?php echo form_error('pharmacy_sub_category_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_sub_category_name') . lang('bf_form_label_required'), 'pharmacy_sub_category_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                    	<select name="pharmacy_sub_category_name" id="pharmacy_sub_category_name" class="form-control" required="">
                    		<option value=""><?php echo lang('select_a_one'); ?></option>
                    		<?php foreach ($sub_category_name as $row) : ?>
                    			<option value="<?php echo $row->id; ?>" <?php if (isset($product_details)) {
            echo(($product_details['sub_category_id'] == $row->id) ? "selected" : "");
        } ?>><?php echo $row->subcategory_name; ?></option>
                    		<?php endforeach; ?>
                    	</select>
                        <span class='help-inline'><?php echo form_error('pharmacy_sub_category_name'); ?></span>
                    </div>
                </div>
            </div>
            </div> -->

        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
            <div class="<?php echo form_error('pharmacy_unit') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_unit') . lang('bf_form_label_required'), 'pharmacy_unit', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <select name="pharmacy_unit" id="pharmacy_unit" class="form-control" required="">
                            <option value=""><?php echo lang('select_a_one'); ?></option>
                            <?php foreach ($unit_name as $row) : ?>
                                <option value="<?php echo $row->id; ?>" <?php if (isset($product_details)) {
                                    echo(($product_details['unit_id'] == $row->id) ? "selected" : "");
                                } ?>><?php echo $row->unit_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('pharmacy_unit'); ?></span>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
            <div class="<?php echo form_error('pharmacy_opening_balance') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_opening_balance') . lang('bf_form_label_required'), 'pharmacy_opening_balance', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_opening_balance' type='number' min="0"
                               name='pharmacy_opening_balance' maxlength="100"
                               value="<?php echo set_value('pharmacy_opening_balance', isset($product_details['opening_balance']) ? $product_details['opening_balance'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_opening_balance') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_opening_balance'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
            <div class="<?php echo form_error('pharmacy_opening_price') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_opening_price') . lang('bf_form_label_required'), 'pharmacy_opening_price', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_opening_price' type='number' min="0"
                               name='pharmacy_opening_price' maxlength="100"
                               value="<?php echo set_value('pharmacy_opening_price', isset($product_details['opening_price']) ? $product_details['opening_price'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_opening_price') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_opening_price'); ?></span>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
            <div class="<?php echo form_error('pharmacy_purchase_price') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_purchase_price') . lang('bf_form_label_required'), 'pharmacy_purchase_price', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_purchase_price' type='text' min="0"
                               name='pharmacy_purchase_price' maxlength="100"
                               value="<?php echo set_value('pharmacy_purchase_price', isset($product_details['purchase_price']) ? $product_details['purchase_price'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_purchase_price') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_purchase_price'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
            <div class="<?php echo form_error('pharmacy_sale_price') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_sale_price') . lang('bf_form_label_required'), 'pharmacy_sale_price', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_sale_price' type='text' min="0"
                               name='pharmacy_sale_price' maxlength="100"
                               value="<?php echo set_value('pharmacy_sale_price', isset($product_details['sale_price']) ? $product_details['sale_price'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_sale_price') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_sale_price'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <!--
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="<?php echo form_error('pharmacy_shelf') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_shelf') . lang('bf_form_label_required'), 'pharmacy_shelf', array('class' => 'control-label ')); ?>
                    <div class='control'>
                    	<select name="pharmacy_shelf" id="pharmacy_shelf" class="form-control" required="">
                    		<option value=""><?php echo lang('select_a_one'); ?></option>
                    		<?php foreach ($shelf_name as $row) : ?>
                    			<option value="<?php echo $row->id; ?>"<?php if (isset($product_details)) {
            echo(($product_details['shelf_id'] == $row->id) ? "selected" : "");
        } ?>><?php echo $row->self_name; ?></option>
                    		<?php endforeach; ?>
                    	</select>
                        <span class='help-inline'><?php echo form_error('pharmacy_shelf'); ?></span>
                    </div>
                </div>
            </div>
            </div>-->

        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
            <div class="<?php echo form_error('pharmacy_reorder') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_reorder') . lang('bf_form_label_required'), 'pharmacy_reorder', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_reorder' type='number' min="0" name='pharmacy_reorder'
                               maxlength="100"
                               value="<?php echo set_value('pharmacy_reorder', isset($product_details['reorder_lebel']) ? $product_details['reorder_lebel'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_reorder') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_reorder'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="<?php echo form_error('pharmacy_product_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_product_name') . lang('bf_form_label_required'), 'pharmacy_product_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_product_name' type='text' name='pharmacy_product_name'
                               maxlength="100"
                               value="<?php echo set_value('pharmacy_product_name', isset($product_details['product_name']) ? $product_details['product_name'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_product_name') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_product_name'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
            <div class="<?php echo form_error('pharmacy_free') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_free'), 'pharmacy_free', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input type="checkbox" name="pharmacy_free" id="pharmacy_free"
                               value="1" <?php if (isset($product_details)) {
                            echo(($product_details['free_status'] == 1) ? "checked" : "");
                        } ?>/>
                        <span class='help-inline'><?php echo form_error('pharmacy_free'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
            <div class="form-group <?php echo form_error('pharmacy_product_status') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_product_status'), 'pharmacy_product_status', array('class' => 'control-label')); ?>
                <div class='control'>
                    <select name="pharmacy_product_status" id="pharmacy_product_status" class="form-control">
                        <option value="1" <?php if (isset($product_details)) {
                            echo(($product_details['status'] == 1) ? "selected" : "");
                        } ?>>Active
                        </option>
                        <option value="0" <?php if (isset($product_details)) {
                            echo(($product_details['status'] == 0) ? "selected" : "");
                        } ?>>Inactive
                        </option>
                    </select>
                    <span class='help-inline'><?php echo form_error('pharmacy_product_status'); ?></span>
                </div>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm"
                       value="<?php echo lang('bf_action_save'); ?>"/>
                &nbsp;
                <button type="reset" class="btn btn-warning btn-sm">Reset</button>
            </div>
        </div>
    </fieldset>

    <?php echo form_close() ?>

</div>
