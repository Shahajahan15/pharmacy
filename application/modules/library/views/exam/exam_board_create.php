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
if (isset($exam_board_equivalent)){$exam_board_equivalent = (array) $exam_board_equivalent;  }
$id = isset($exam_board_equivalent['id']) ? $exam_board_equivalent['id'] : '';
?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Create Exam Board</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
          	<div class="form-group <?php echo form_error('exam_board_equivalent') ? 'error' : ''; ?>">					
					<label><?php echo lang('library_exam_exam_board_equivalent').lang('bf_form_label_required');?></label>
					<select class="form-control" name="library_exam_exam_board_equivalent" id="library_exam_exam_board_equivalent" required="">
						<option value="1" <?php if(isset($exam_board_equivalent['exam_board_equivalent'])){if($exam_board_equivalent['exam_board_equivalent'] == 1){ echo "selected";}}?> >Secondary & Higher Secondary</option>
						<option value="2" <?php if(isset($exam_board_equivalent['exam_board_equivalent'])){if($exam_board_equivalent['exam_board_equivalent'] == 2){ echo "selected";}}?> >Graduation/Master</option>	
						<option value="3" <?php if(isset($exam_board_equivalent['exam_board_equivalent'])){if($exam_board_equivalent['exam_board_equivalent'] == 3){ echo "selected";}}?> >Diploma</option>	
						<option value="4" <?php if(isset($exam_board_equivalent['exam_board_equivalent'])){if($exam_board_equivalent['exam_board_equivalent'] == 4){ echo "selected";}}?> >Undergraduate</option>				
					 </select>
					<span class='help-inline'><?php echo form_error('exam_board_equivalent'); ?></span>
				</div>	
					
   
            
            </div>      
        

  <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
	<div class="form-group <?php echo form_error('library_exam_exam_board') ? 'error' : ''; ?>">
					<label><?php echo lang('library_exam_exam_board').lang('bf_form_label_required');?></label> 
					<input class="form-control" id='library_exam_exam_board' type='text'  name='library_exam_exam_board' maxlength="255" value="<?php echo set_value('library_exam_exam_name', isset($exam_board_equivalent['exam_board']) ? $exam_board_equivalent['exam_board'] : ''); ?>" />           
				</div> 	
        
   </div>
    
			

        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
      
			<div class="form-group <?php echo form_error('exam_board_status') ? 'error' : ''; ?>">					
					<label><?php echo lang('library_exam_exam_status').lang('bf_form_label_required');?></label>
					<select class="form-control" name="exam_exam_board_status" id="exam_exam_board_status" required="">
						<option value="1" <?php if(isset($exam_board_equivalent['exam_board_status'])){if($exam_board_equivalent['exam_board_status'] == 1){ echo "selected";}}?> >Active</option>
						<option value="0" <?php if(isset($exam_board_equivalent['exam_board_status'])){if($exam_board_equivalent['exam_board_status'] == 0){ echo "selected";}}?> >Inactive</option>						
					 </select>
					<span class='help-inline'><?php echo form_error('exam_board_status'); ?></span>
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







