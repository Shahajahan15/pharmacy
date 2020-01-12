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
if (isset($doctor_ref_doc)){$doctor_ref_doc = (array) $doctor_ref_doc;  }
$id = isset($doctor_ref_doc['id']) ? $doctor_ref_doc['id'] : ''
?>

<div class="box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal ref_form_submit"'); ?>
    <fieldset>
        <legend class="ref-legend">Surgeon Doctor</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
             <div class="form-group <?php echo form_error('ref_name') ? 'error' : ''; ?>">
        <?php echo form_label('Surgeon Name'. lang('bf_form_label_required'),'doctor_ref_doc_or_org_name',array('class'=> 'control-label')); ?>                                        
                  <div class='control'>
                      <input class="form-control" id='doctor_ref_doc_or_org_name' placeholder="Surgeon Name" type='text' name='surgeon_name' maxlength="285" value="<?php echo set_value('doctor_ref_doc_or_org_name', isset($doctor_ref_doc['ref_name']) ? $doctor_ref_doc['ref_name'] : ''); ?>" required=""/>
                      <span class='help-inline'><?php echo form_error('ref_name'); ?></span>
                  </div>
              </div> 
   
        
        
        
                
            </div>      
        

  <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
      <div class="form-group <?php echo form_error('ref_mobile') ? 'error' : ''; ?>">
        <?php echo form_label('Mobile'. lang('bf_form_label_required'),'doctor_ref_doc_or_org_mobile',array('class'=> 'control-label')); ?>                                        
                  <div class='control'>
                      <input class="form-control" placeholder="Mobile" id='doctor_ref_doc_or_org_mobile' type='text' name='mobile' maxlength="285" value="<?php echo set_value('doctor_ref_doc_or_org_mobile', isset($doctor_ref_doc['ref_mobile']) ? $doctor_ref_doc['ref_mobile'] : ''); ?>" required=""/>
                      <span class='help-inline'><?php echo form_error('ref_mobile'); ?></span>
                  </div>
              </div> 
        
                </div>
        
    
             
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-6">
                       <div class="form-group <?php echo form_error('doctor_ref_doc_or_org_address') ? 'error' : ''; ?>">
            <?php echo form_label('Address','doctor_ref_doc_or_org_name',array('class'=> 'control-label')); ?> 
                  <div class='control'>
                      <input class="form-control" id='doctor_ref_doc_or_org_address' type='text' name='address' maxlength="285" value="<?php echo set_value('doctor_ref_doc_or_org_address', isset($doctor_ref_doc['ref_address']) ? $doctor_ref_doc['ref_address'] : ''); ?>" />
                      <span class='help-inline'><?php echo form_error('doctor_ref_doc_or_org_address'); ?></span>
                  </div>
              </div>  
                     </div>
       <!-- <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
                       <div class="form-group <?php echo form_error('ref_commission') ? 'error' : ''; ?>">
        <?php //echo form_label(lang('doctor_ref_doc_or_org_comission'). lang('bf_form_label_required'),'doctor_ref_doc_or_org_name',array('class'=> 'control-label')); ?>
                  <div class='control'>
                      <input class="form-control" id='doctor_ref_doc_or_org_comission' type='text' name='doctor_ref_doc_or_org_comission' maxlength="285" value="<?php //echo set_value('doctor_ref_doc_or_org_comission', isset($doctor_ref_doc['ref_commission']) ? $doctor_ref_doc['ref_commission'] : ''); ?>" required=""/>
                      <span class='help-inline'><?php echo form_error('ref_commission'); ?></span>
                  </div>
              </div>  
                     </div> -->
               

<div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
          <div class="form-group <?php echo form_error('doctor_ref_doc_or_org_status') ? 'error' : ''; ?>">
          <?php echo form_label('Status','doctor_ref_doc_or_org_status',array('class'=> 'control-label')); ?>
                  <div class='control'>
                    <select name="status" id="doctor_ref_doc_or_org_status" class="form-control">
            <option value="1" <?php if(isset($doctor_ref_doc['ref_status'])){echo (($doctor_ref_doc['ref_status'] == 1)? "selected" : "");} ?>>Active</option>
            <option value="0" <?php if(isset($doctor_ref_doc['ref_status'])){echo (($doctor_ref_doc['ref_status'] == 0)? "selected" : "");} ?>>Inactive</option>
          </select>
                    <span class='help-inline'><?php echo form_error('doctor_ref_doc_or_org_status'); ?></span>
                  </div>
                  </div> 
                    </div>
               

               
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <div class=" pager">
                <input type="submit" name="save" class="ref_add_cls btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                 <button type="reset" class="btn btn-warning btn-sm">Reset</button>
                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>








