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

if (isset($library_counter))
{
	$library_counter = (array) $library_counter;
}
$id = isset($library_counter['id']) ? $library_counter['id'] : '';

?>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Counter Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      <div class="form-group <?php echo form_error('counter_name') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_initial_counter_name'). lang('bf_form_label_required'), 'lib_counter_counter_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_counter_counter_name' type='text' name='lib_counter_counter_name' maxlength="285" value="<?php echo set_value('lib_counter_counter_name', isset($library_counter['counter_name']) ? $library_counter['counter_name'] : ''); ?>" required=""/>
                            <span class='help-inline'><?php echo form_error('counter_name'); ?></span>
                        </div>
                </div>
                
            </div>      
           
            
        

        <div class="col-sm-4 col-md-4 col-lg-3">
                    <div class="form-group <?php echo form_error('lib_counter_counter_status') ? 'error' : ''; ?>">                 
                        <label class="control-label">Status<?php echo lang('bf_form_label_required');?></label>
                        <select class="form-control" name="lib_counter_counter_status" id="lib_counter_counter_status" required="">
                            <option value="1" <?php if(isset($records['counter_status'])){if($records['counter_status'] == 1){ echo "selected";}}?> >Active</option>
                            <option value="0" <?php if(isset($records['counter_status'])){if($records['counter_status'] == 0){ echo "selected";}}?>>Inactive</option>                                           
                         </select>
                        <span class='help-inline'><?php echo form_error('status'); ?></span>
                    </div>
                </div>
       
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class=" pager">
           <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('save'); ?>"  />
                   
                   <button type="reset" class="btn btn-warning btn-sm">Reset</button>
                    
               </div>  
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>





