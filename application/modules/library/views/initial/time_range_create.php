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
if (isset($time_range)){$time_range = (array) $time_range;  }
$id = isset($time_range['id']) ? $time_range['id'] : '';
?>
<style> .form-group .form-control, .control-group .form-control{ width: 50%;}</style>
<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
        <fieldset class="box-body"> 
            <div class="col-sm-8 col-md-8 col-lg-8">     
				                   
              <div class="form-group <?php echo form_error('initial_time_range') ? 'error' : ''; ?>">
            <?php echo form_label(lang('initial_time_range'). lang('bf_form_label_required'),'library_initial_time_range',array('class'=> 'control-label col-sm-4')); ?>                                        
                  <div class='control'>
                      <input class="form-control" id='initial_time_range' placeholder='00:00:00' name='initial_time_range' maxlength="285" value="<?php echo set_value('initial_time_range', isset($time_range['time_range']) ?  $time_range['time_range'] : ''); ?>" required=""/>
                      
                  </div>
              </div>  
              
              
     
              
              <?php // Change the values in this array to populate your dropdown as required
					$options = array('1' => 'AM','2' => 'PM');
	
	echo form_dropdown("initial_time_range_am_or_pm", $options, set_value('initial_time_range_am_or_pm', isset($time_range['am_or_pm']) ? $time_range['am_or_pm'] : '1'), lang('initial_time_range_am_or_pm'), "class='form-control'", '', "class='control-label col-sm-4'");
                ?>        
		<?php //Change the values in this array to populate your dropdown as required
            $options = array('1' => 'Active','0' => 'In Active');
            echo form_dropdown("initial_time_range_status", $options, set_value('initial_time_range_status', isset($time_range['time_range_status']) ? $time_range['time_range_status'] : '1'), lang('initial_time_range_status'), "class='form-control'", '', "class='control-label col-sm-4'");
        ?>             
              <div class="box-footer pager">
                  <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                  <button type="Reset" class="btn btn-warning btn-sm">Reset</button>	
              </div>               
            </div>
        </fieldset>
    <?php echo form_close(); ?>
</div>


