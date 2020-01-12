<style>
select{
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: "";
}    
</style>
<div class="row">
    <div class=" col-md-2"> <!--firstDivShow-->
        
    </div>


    <div class=" col-md-3 padding-left-div" id="">
        <div class="row">
            <!-- option width start -->
            <div class="col-md-6">							                          
                <div class="form-group <?php echo form_error('store_product_width') ? 'error' : ''; ?>">
                    <label><?php echo lang('store_product_width'); ?></label>
                    <input type='text' name='width' value="" id='store_sroduct_width' class="form-control" placeholder="<?php echo lang('store_product_width') ?>" required=""/>
                    <span class='help-inline'><?php echo form_error('store_product_width'); ?></span>
                </div>						
            </div> 
            <!-- option width End -->	

            <!-- measurement unit  start -->
            <div class="col-md-6">
                <div class="form-group <?php echo form_error('Store_Measurement_Unit_Id') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('store_measurement_unit_id') . lang('bf_form_label_required'), 'Store_Measurement_Unit_Id'); ?>
                    <select class="form-control" name="widthUnit" id="Store_Measurement_Unit_Id" required="" >
                        <option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
                        <?php
                        if (isset($lenght_units)) {

                            foreach ($lenght_units as $v_lenght_units) {
                                echo "<option value='" . $v_lenght_units->id . "'";
                                echo ">" . $v_lenght_units->unit_name . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('Store_Measurement_Unit_Id'); ?></span>
                </div>
            </div> 
            <!-- measurement unit  end -->

        </div>
    </div>



    <div class=" col-md-2 padding-left-div" id="">
        <div class="row">
            <!-- option Length start -->
            <div class="col-md-6">       		                        
                <div class="form-group <?php echo form_error('store_product_length') ? 'error' : ''; ?>">
                    <label><?php echo lang('store_product_length'); ?></label>
                    <input type='text' name='length' value="" id='store_product_length' class="form-control" placeholder="<?php echo lang('store_product_length') ?>" required=""/>
                    <span class='help-inline'><?php echo form_error('store_product_length'); ?></span>
                </div>		       
            </div> 
            <!-- option Length End -->	
            <!-- measurement unit  start -->
            <div class="col-md-6">
                <div class="form-group <?php echo form_error('Store_Measurement_Unit_Id') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('store_measurement_unit_id') . lang('bf_form_label_required'), 'Store_Measurement_Unit_Id'); ?>
                    <select class="form-control" name="lengthUnit" id="Store_Measurement_Unit_Id" required="" >
                        <option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
                        <?php
                        if (isset($lenght_units)) {

                            foreach ($lenght_units as $v_lenght_units) {
                                echo "<option value='" . $v_lenght_units->id . "'";
                                echo ">" . $v_lenght_units->unit_name . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('Store_Measurement_Unit_Id'); ?></span>
                </div>
            </div> 
            <!-- measurement unit  end -->
        </div>
    </div>

    <div class=" col-md-2 padding-left-div" id="">
        <div class="row">		
            <!-- option weight start -->
            <div class="col-md-6">        
                <div class="form-group <?php echo form_error('store_product_weight') ? 'error' : ''; ?>">
                    <label><?php echo lang('store_product_weight'); ?></label>
                    <input type='text' name='weight' value="" id='store_product_weight' class="form-control" placeholder="<?php echo lang('store_product_weight') ?>" required=""/>
                    <span class='help-inline'><?php echo form_error('store_product_weight'); ?></span>
                </div>
            </div> 
            <!-- option weight end -->	

            <!-- measurement unit  start -->
            <div class="col-md-6">
                <div class="form-group <?php echo form_error('Store_Measurement_Unit_Id') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('store_measurement_unit_id') . lang('bf_form_label_required'), 'Store_Measurement_Unit_Id'); ?>
                    <select class="form-control" name="weightUnit" id="Store_Measurement_Unit_Id" required="" >
                        <option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
                        <?php
                        if (isset($weight_units)) {

                            foreach ($weight_units as $v_weight_units) {
                                echo "<option value='" . $v_weight_units->id . "'";
                                echo ">" . $v_weight_units->unit_name . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('Store_Measurement_Unit_Id'); ?></span>
                </div>
            </div> 
            <!-- measurement unit  end -->		
        </div>	
    </div>		

    <div class=" col-md-2 padding-left-div" id="">
        <div class="row">
            <!-- option Power start -->
            <div class="col-md-6">
                <!-- ----------- Power ---------------- -->               
                <div class="form-group <?php echo form_error('Store_Product_Power') ? 'error' : ''; ?>">
                    <label><?php echo lang('store_product_power'); ?></label>
                    <input type='text' name='power' value="" id='Store_Product_Power' class="form-control" placeholder="<?php echo lang('store_product_power') ?>" required=""/>
                    <span class='help-inline'><?php echo form_error('Store_Product_Power'); ?></span>
                </div>
            </div> 
            <!-- option Power end -->	
            <!-- measurement unit  start -->
            <div class="col-md-6">
                <div class="form-group <?php echo form_error('Store_Measurement_Unit_Id') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('store_measurement_unit_id') . lang('bf_form_label_required'), 'Store_Measurement_Unit_Id'); ?>
                    <select class="form-control" name="powerUnit" id="Store_Measurement_Unit_Id" required="" >
                        <option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
                        <?php
                        if (isset($power_units)) {

                            foreach ($power_units as $v_power_units) {
                                echo "<option value='" . $v_power_units->id . "'";
                                echo ">" . $v_power_units->unit_name . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('Store_Measurement_Unit_Id'); ?></span>
                </div>
            </div> 
            <!-- measurement unit  end -->	
        </div>
    </div>

    <div class="col-md-3 padding-left-div" id="">
        <div class="row">
            <!-- option Capacity start -->
            <div class="col-md-6">
                <!-- ----------- Capacity ---------------- -->               
                <div class="form-group <?php echo form_error('Store_Product_Capacity') ? 'error' : ''; ?>">
                    <label><?php echo lang('store_product_capacity'); ?></label>
                    <input type='text' name='capacity' value="" id='Store_Product_Capacity' class="form-control" placeholder="<?php echo lang('store_product_capacity') ?>" required=""/>
                    <span class='help-inline'><?php echo form_error('Store_Product_Capacity'); ?></span>
                </div>
            </div> 
            <!-- option Capacity end -->

            <!-- measurement unit  start -->
            <div class="col-md-6">
                <div class="form-group <?php echo form_error('Store_Measurement_Unit_Id') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('store_measurement_unit_id') . lang('bf_form_label_required'), 'Store_Measurement_Unit_Id'); ?>
                    <select class="form-control" name="capacityUnit" id="Store_Measurement_Unit_Id" required="" >
                        <option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
                        <?php
                        if (isset($power_units)) {

                            foreach ($power_units as $v_power_units) {
                                echo "<option value='" . $v_power_units->id . "'";
                                echo ">" . $v_power_units->unit_name . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('Store_Measurement_Unit_Id'); ?></span>
                </div>
            </div> 
            <!-- measurement unit  end -->	
        </div>
    </div>

    <!-- option Generic Name start -->
    <div class="col-sm-2 col-md-2 padding-left-div">

        <!-- ----------- Generic Name ---------------- -->               
        <div class="form-group <?php echo form_error('Store_Generic_Name') ? 'error' : ''; ?>">    
            <label><?php echo lang('store_generic_name'); ?></label>
            <select class=" form-control chosenCommon chosen-single " name="genericId" id="Store_Generic_Name" required="" >
                <option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
                <?php
                //if (isset($units)) {
                foreach ($generic_name_list as $v_units) {
                    echo "<option value='" . $v_units->GENERIC_ID . "'";
                    echo ">" . $v_units->GENERIC_NAME . "</option>";
                }
                // }
                ?>
            </select>
            <span class='help-inline'><?php echo form_error('Store_Generic_Name'); ?></span>
        </div>    


    </div> 
<!-- option Generic Name -->	

    <!-- option Depreciation start -->
    <div class="col-sm-2 col-md-2 padding-left-div">
        <!-- ----------- Depreciation ---------------- -->               
        <div class="form-group <?php echo form_error('Store_Product_Depreciation') ? 'error' : ''; ?>">
            <label><?php echo lang('store_product_depreciation'); ?></label>
            <input type='text' name='depreciation' value="" id='Store_Product_Depreciation' class="form-control" placeholder="<?php echo lang('store_product_depreciation') ?>" required=""/>
            <input type="checkbox" name="depreciationValueType" value="1"> Flat Rate
            <span class='help-inline'><?php echo form_error('Store_Product_Depreciation'); ?></span>
        </div>

    </div>
<!-- option Depreciation end -->

    <!-- option manufacture date start -->
    <div class="col-sm-12 col-md-2 padding-left-div">                         
        <div class="form-group <?php echo form_error('Store_Product_Manufacture_Date') ? 'error' : ''; ?>">
            <label><?php echo lang('store_product_manufacture_date'); ?></label>
            <input type='text' name='manufactureDate' class="form-control datepickerCommon" id='Store_Product_Manufacture_Date' placeholder="<?php echo lang('store_product_manufacture_date') ?>" required="" />
            <span class='help-inline'><?php echo form_error('Store_Product_Manufacture_Date'); ?></span>
        </div>
    </div>
    <!-- option manufacture date start -->	

    <!-- ----------- option Expire Date start ---------------- -->  
    <div class="col-sm-12 col-md-2 padding-left-div">                       

        <div class="form-group <?php echo form_error('Store_Product_Expire_Date') ? 'error' : ''; ?>">
            <label><?php echo lang('store_product_expire_date'); ?></label>
            <input type='text' name='expireDate' class="form-control datepickerCommon" id='Store_Product_Expire_Date' placeholder="<?php echo lang('store_product_expire_date') ?>" required="" />
            <span class='help-inline'><?php echo form_error('Store_Product_Expire_Date'); ?></span>
        </div>
    </div> 
    <!-- ----------- option Expire Date end ---------------- -->  	
</div>