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
if (isset($exam_equivalent)){$exam_equivalent = (array) $exam_equivalent;  }
$id = isset($exam_equivalent['id']) ? $exam_equivalent['id'] : '';
?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Exam Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
          <div class="form-group <?php echo form_error('exam_equivalent') ? 'error' : ''; ?>">					
					<label><?php echo lang('library_exam_exam_equivalent').lang('bf_form_label_required');?></label>
					<select class="form-control" name="library_exam_exam_equivalent" id="library_exam_exam_equivalent" required="">
						<option value="1" <?php if(isset($exam_equivalent['exam_equivalent'])){if($exam_equivalent['exam_equivalent'] == 1){ echo "selected";}}?> >Secondary & Higher Secondary</option>
						<option value="2" <?php if(isset($exam_equivalent['exam_equivalent'])){if($exam_equivalent['exam_equivalent'] == 2){ echo "selected";}}?> >Graduation</option>						
					 </select>
					<span class='help-inline'><?php echo form_error('exam_equivalent'); ?></span>
				</div>	
					
					
   
            
            </div>      
        

  <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
	
				<div class="form-group <?php echo form_error('library_exam_exam_name') ? 'error' : ''; ?>">			   
					<label><?php echo lang('library_exam_exam_name').lang('bf_form_label_required');?></label>               
						<input class="form-control" id='library_exam_exam_name' placeholder="<?php echo form_error('library_exam_exam_name'); ?>" type='text' name='library_exam_exam_name' maxlength="255" value="<?php echo set_value('library_exam_exam_name', isset($exam_equivalent['exam_name']) ? $exam_equivalent['exam_name'] : ''); ?>" />                    
				</div> 	
        
   </div>
    
			

        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
      
			
				<div class="form-group <?php echo form_error('exam_status') ? 'error' : ''; ?>">					
					<label><?php echo lang('library_exam_exam_status').lang('bf_form_label_required');?></label>
					<select class="form-control" name="exam_exam_status" id="exam_exam_status" required="">
						<option value="1" <?php if(isset($exam_equivalent['exam_status'])){if($exam_equivalent['exam_status'] == 1){ echo "selected";}}?> >Active</option>
						<option value="0" <?php if(isset($exam_equivalent['exam_status'])){if($exam_equivalent['exam_status'] == 0){ echo "selected";}}?> >Inactive</option>						
					 </select>
					<span class='help-inline'><?php echo form_error('exam_status'); ?></span>
				</div>
        </div>  
     
            </fieldset>
          
            <fieldset>
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
            <div class=" pager">
              <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
                    
                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>


