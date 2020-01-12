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


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Consultant Price Add</legend>
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">     
            <div class="<?php echo form_error('library_doctor_id') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('library_doctor_id') . lang('bf_form_label_required'), '', array('class' => 'control-label ')); ?>
                    <div class='control'>
                      <select name="doctor_id" id="doctor_id" class="form-control" required="">
                        <option value=""><?php echo lang('select_a_one'); ?></option>
                        <?php foreach ($doctor_list as $row) : ?>
                          <option value="<?php echo $row->id; ?>"<?php if(isset($records)){echo (($records['doctor_id'] == $row->id)? "selected" : "");} ?>><?php echo $row->ref_name; ?></option>
                        <?php endforeach; ?>
                      </select>
                        <span class='help-inline'><?php echo form_error('library_doctor_id'); ?></span>
                    </div>
                </div>
            </div>    
            </div> 


          <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
             <div class="form-group <?php echo form_error('library_patient_price') ? 'error' : ''; ?>">
          <?php echo form_label(lang('library_patient_price'). lang('bf_form_label_required'),'library_patient_price',array('class'=> 'control-label col-sm-4')); ?>                                        
            <div class='control'>
              <input class="form-control decimal" id='library_patient_price_id' type='text' name='patient_price' maxlength="285" value="<?php echo set_value('library_ticket_price', isset($records['patient_price']) ? $records['patient_price'] : ''); ?>" required=""/>
              <span class='help-inline'><?php echo form_error('library_patient_price'); ?></span>
            </div>           
          </div>
   
            
            </div>    
              

          <div class="col-sm-4 col-md-4 col-lg-3">
          <div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">         
            <label class="control-label"><?php echo lang('status').lang('bf_form_label_required');?></label>
            <select class="form-control" name="status" id="bf_status" required="">
              <option value="1" <?php if(isset($records['status'])){if($records['status'] == 1){ echo "selected";}}?> >Active</option>
              <option value="0" <?php if(isset($records['status'])){if($records['status'] == 0){ echo "selected";}}?>>Inactive</option>                     
             </select>
            <span class='help-inline'><?php echo form_error('status'); ?></span>
          </div>
        </div>



            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class=" pager">
                <input type="submit" name="save" class="btn btn-primary btn-sm
                " value="Save"  />
                     <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
                </div>
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>

