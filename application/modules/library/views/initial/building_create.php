

<?php
$validation_errors = validation_errors();
if ($validation_errors) :
?>

    <?php echo $validation_errors; ?>

<?php
endif;

if (isset($library_building))
{
    $library_building = (array) $library_building;
}
$id = isset($library_building['id']) ? $library_building['id'] : '';

?>




<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>

        <fieldset class="box-body">
         <legend>Building Create</legend>
            <div class="col-sm-8 col-md-4 col-lg-4">
                <div class="form-group <?php echo form_error('bank_name') ? 'error' : ''; ?>">
                       <?php echo form_label(lang('library_initial_building_name'). lang('bf_form_label_required'), 'lib_building_building_name', array('class' => 'control-label') ); ?>
                        <div class='control'>
                            <input type='text' class="form-control" name='lib_building_building_name' id='lib_building_building_name'  maxlength="100" value="<?php echo set_value('lib_building_building_name', isset($library_building['building_name']) ? $library_building['building_name'] : ''); ?>" placeholder="building name" required="" tabindex="1"/>
                            <span class='help-inline'><?php echo form_error('lib_building_building_name'); ?></span>
                        </div>
                </div>
            </div>  

                <div class="col-sm-8 col-md-4 col-lg-4">
                    <div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">                 
                        <label class="control-label"><?php echo lang('lib_building_building_status').lang('bf_form_label_required');?></label>
                        <select class="form-control" name="lib_building_building_status" id="lib_building_building_status" required="">
                            <option value="1" <?php if(isset($library_building['building_statusbuilding_status'])){if($library_building['building_status'] == 1){ echo "selected";}}?> >Active</option>
                            <option value="0" <?php if(isset($library_building['building_status'])){if($library_building['building_status'] == 0){ echo "selected";}}?>>Inactive</option>                                         
                         </select>
                        <span class='help-inline'><?php echo form_error('status'); ?></span>
                    </div>
                </div>
</fieldset>
 <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
                 <div class="pager">
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('save'); ?>"  />
                  <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
                    
                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>
























