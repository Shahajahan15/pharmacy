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
if (isset($symptom_name)){$symptom_name = (array) $symptom_name;  }
$id = isset($symptom_name['id']) ? $symptom_name['id'] : '';
?>

<style> .form-group .form-control, .control-group .form-control{ width: 50%;} </style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
        <fieldset class="box-body"> 
            <div class="col-sm-8 col-md-8 col-lg-8"> 

				<div class="form-group <?php echo form_error('symptom_name') ? 'error' : ''; ?>">
					<?php echo form_label(lang('library_symptom_name'). lang('bf_form_label_required'),'library_symptom_name',array('class'=> 'control-label col-sm-4')); ?>
                    <div id="sandbox-container" class='control'>                    
						<input class="form-control" id='library_symptom_name'  name='library_symptom_name' type='text'  maxlength="30" 
						value="<?php echo set_value('employee_master_emp_name', isset($symptom_name['symptom_name']) ? $symptom_name['symptom_name'] : ''); ?>" placeholder="<?php echo lang('library_symptom_name')?>" required="" />                  
                        <span class='help-inline'><?php echo form_error('symptom_name'); ?></span>
                    </div>
                </div>		
				
         </div>
         </fieldset>
         <fieldset>
              <div class="pager">
                  <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                 <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
              </div> 
              
            </div>
			
        </fieldset>
    <?php echo form_close(); ?>
</div>
