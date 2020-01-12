
<?php
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

if (isset($records))
{
	$records = (array) $records;
}
$id = isset($records['id']) ? $records['id'] : '';

?>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        <div class="col-sm-4 col-md-4 col-lg-3">
                    <div class="form-group <?php echo form_error('library_customer_type') ? 'error' : ''; ?>">                 
                        <label class="control-label"><?php echo lang('library_customer_type').lang('bf_form_label_required');?></label>
                        <select class="form-control" name="library_customer_type" id="library_customer_type" required="">
                            <option value="1" <?php if(isset($records['customer_type'])){if($records['customer_type'] == 1){ echo "selected";}}?> >Normal</option>
                            <option value="2" <?php if(isset($records['customer_type'])){if($records['customer_type'] == 2){ echo "selected";}}?>>Employee</option>   
                            <option value="3" <?php if(isset($records['customer_type'])){if($records['customer_type'] == 3){ echo "selected";}}?>>Doctor</option>   
                            <option value="4" <?php if(isset($records['customer_type'])){if($records['customer_type'] == 4){ echo "selected";}}?>>Overall</option>                                       
                         </select>
                        <span class='help-inline'><?php echo form_error('library_customer_type'); ?></span>
                    </div>
                </div>
            <div class="col-sm-4 col-md-4 col-lg-3">
                    <div class="form-group <?php echo form_error('library_discount_for') ? 'error' : ''; ?>">                 
                        <label class="control-label"><?php echo lang('library_discount_for').lang('bf_form_label_required');?></label>
                        <select class="form-control" name="library_discount_for" id="library_discount_for" required="">
                            <option value="1" <?php if(isset($records['discount_for'])){if($records['discount_for'] == 1){ echo "selected";}}?> >Canteen</option>
                            <option value="2" <?php if(isset($records['discount_for'])){if($records['discount_for'] == 2){ echo "selected";}}?>>Pharmacy</option>
                            <option value="3" <?php if(isset($records['discount_for'])){if($records['discount_for'] == 3){ echo "selected";}}?>>Diagnosis</option>                                           
                         </select>
                        <span class='help-inline'><?php echo form_error('library_discount_for'); ?></span>
                    </div>
                </div>
                  
            <div class="col-sm-4 col-md-4 col-lg-3">
				<div class="form-group <?php echo form_error('library_discount_parcent') ? 'error' : ''; ?>">                      
					<label class="control-label"><?php echo lang('library_discount_parcent').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" id='library_discount_parcent' type='text' name='library_discount_parcent' maxlength="100" value="<?php echo set_value('library_discount_parcent', isset($records['discount_parcent']) ? $records['discount_parcent'] : ''); ?>"  required="" />
					<span class='help-inline'><?php echo form_error('library_discount_parcent'); ?></span>                        
                </div>
            </div>	
           <!-- Date Start-->
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group <?php echo form_error('date_start') ? 'error' : ''; ?>">
                <label class="control-label"><?php echo lang('date_start').lang('bf_form_label_required')?></label>             
                    <input type="text" name='date_start' id='date_start' required="" class="form-control datepickerCommon" value="<?php echo set_value('date_start',isset($records['start_date']) ? date('d/m/Y',strtotime($records['start_date'])) :  '');?>">
                    <span class='help-inline'><?php echo form_error('date_start'); ?></span>
            </div>
        </div>

        <!-- Date Start-->
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group <?php echo form_error('date_end') ? 'error' : ''; ?>">
                <label class="control-label"><?php echo lang('date_end').lang('bf_form_label_required')?></label>               
                    <input type="text" name='date_end' id='date_end' required="" class="form-control datepickerCommon" value="<?php echo set_value('date_end', isset($records['end_date']) ? date('d/m/Y',strtotime($records['end_date'])) :  '');?>">
                    <span class='help-inline'><?php echo form_error('end_date'); ?></span>
            </div>
        </div>
				<div class="col-sm-4 col-md-4 col-lg-3">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($records['status'])){if($records['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($records['status'])){if($records['status'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('status'); ?></span>
					</div>
				</div>
				  
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="footer pager">
                	<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"/>
                	<button type="reset" class="btn btn-warning">Reset</button>
					
                </div>
            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








