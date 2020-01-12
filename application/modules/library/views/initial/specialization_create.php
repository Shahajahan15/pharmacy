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

if (isset($library_specialization))
{
	$library_specialization = (array) $library_specialization;
}
$id = isset($library_specialization['id']) ? $library_specialization['id'] : '';

?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Specialization Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
               <div class="form-group <?php echo form_error('specialization_name') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_initial_specialization_name'). lang('bf_form_label_required'), 'library_initial_specialization_name', array('class' => 'control-label') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_specialization_specialization_name' type='text' name='lib_specialization_specialization_name' maxlength="285" value="<?php echo set_value('lib_specialization_specialization_name', isset($library_specialization['specialization_name']) ? $library_specialization['specialization_name'] : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('specialization_name'); ?></span>
                        </div>
                </div>
                
            </div>      
           <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
    <div class="form-group <?php echo form_error('lib_specialization_status') ? 'error' : ''; ?>">                 
                        <label><?php echo lang('lib_specialization_status').lang('bf_form_label_required');?></label>
                        <select class="form-control" name="lib_specialization_status" id="lib_specialization_status" required="">
                            <option value="1" <?php if(isset($library_specialization['status'])){if($library_specialization['status'] == 1){ echo "selected";}}?> >Active</option>
                            <option value="0" <?php if(isset($library_specialization['status'])){if($library_specialization['status'] == 0){ echo "selected";}}?>>Inactive</option>                                           
                         </select>
                        <span class='help-inline'><?php echo form_error('STATUS'); ?></span>
                    </div>
                 
                </div>
            
          
       
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <div class=" pager">
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('save'); ?>"  />
                 <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>



