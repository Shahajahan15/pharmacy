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
if (isset($ticket_price)){$ticket_price = (array) $ticket_price;  }
$id = isset($ticket_price['id']) ? $ticket_price['id'] : '';
?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Ticket Price Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
             <div class="form-group <?php echo form_error('ticket_price') ? 'error' : ''; ?>">
					<?php echo form_label(lang('library_ticket_price'). lang('bf_form_label_required'),'library_ticket_price',array('class'=> 'control-label col-sm-4')); ?>                                        
					  <div class='control'>
						  <input class="form-control decimal" id='library_ticket_price' type='text' name='library_ticket_price' maxlength="285" value="<?php echo set_value('library_ticket_price', isset($ticket_price['ticket_price']) ? $ticket_price['ticket_price'] : ''); ?>" required=""/>
						  <span class='help-inline'><?php echo form_error('ticket_price'); ?></span>
					  </div>					 
				</div>
   
            
            </div>      
        

  <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
     <div class="form-group <?php echo form_error('app_type') ? 'error' : ''; ?>">
					<?php echo form_label(' type'. lang('bf_form_label_required'),'app_type',array('class'=> 'control-label col-sm-4')); ?>                                        
					  <div class='control'>
						  <select name="app_type" id="app_type" class="form-control">
						  	<option>Select One</option>
						  	<?php foreach($types as $key=> $type){?>
						  	<option value="<?php echo $key; ?>" <?php if(isset($ticket_price)){ echo ($ticket_price['app_type']==$key) ? 'selected':''; } ?>><?php echo $type; ?></option>
						  	<?php }?>
						  </select>
						  <span class='help-inline'><?php echo form_error('app_type'); ?></span>
					  </div>					 
				</div>
        
   </div>
                  

               
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <div class=" pager">
               <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>" />
                  &nbsp;
                  <?php echo anchor(SITE_AREA .'/ticket_price/library/show_list', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>	
                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>


