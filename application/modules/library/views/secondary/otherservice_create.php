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

if (isset($lib_otherservice))
{
	$lib_otherservice = (array) $lib_otherservice;
}
$id = isset($lib_otherservice['id']) ? $lib_otherservice['id'] : '';

?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        
            <div class="col-sm-8 col-md-8 col-lg-8">     
                                        
         
				<div class="form-group <?php echo form_error('otherservice_name') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('lib_second_otherservice_name'). lang('bf_form_label_required'), 'lib_second_otherservice_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_second_otherservice_name' type='text' name='lib_second_otherservice_name' maxlength="285" value="<?php echo set_value('lib_second_otherservice_name', isset($lib_otherservice['otherservice_name']) ? $lib_otherservice['otherservice_name'] : ''); ?>" placeholder="<?php echo lang('lib_second_otherservice_name')?>" required="" />
                            <span class='help-inline'><?php echo form_error('otherservice_name'); ?></span>
                        </div>
                </div>
				
				
				<div class="form-group <?php echo form_error('other_service_price') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('lib_second_otherservice_price'). lang('bf_form_label_required'), 'lib_second_otherservice_price', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_second_otherservice_price' type='text' name='lib_second_otherservice_price' maxlength="10" value="<?php echo set_value('lib_second_otherservice_price', isset($lib_otherservice['other_service_price']) ? $lib_otherservice['other_service_price'] : ''); ?>" required=""  placeholder="<?php echo lang('lib_second_otherservice_price')?>"/>
                            <span class='help-inline'><?php echo form_error('other_service_price'); ?></span>
                        </div>
                </div>
				
				
				
				<div class="form-group <?php echo form_error('description') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('lib_second_otherservice_description'), 'lib_second_otherservice_description', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_second_otherservice_description' type='text' name='lib_second_otherservice_description' maxlength="285" value="<?php echo set_value('lib_second_otherservice_description', isset($lib_otherservice['description']) ? $lib_otherservice['description'] : ''); ?>" placeholder="<?php echo lang('lib_second_otherservice_description')?>" />
                            <span class='help-inline'><?php echo form_error('description'); ?></span>
                        </div>
                </div>
				
					
				<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">   
					<?php echo form_label(lang('lib_second_otherservice_status'). lang('bf_form_label_required'),'lib_second_otherservice_status',array('class'=> 'control-label col-sm-4')); ?>				
					<div class='control'>                          
						<select name="lib_second_otherservice_status" id="lib_second_otherservice_status" class="form-control" required="">
							<?php if(empty($lib_otherservice['status'])){?>
								<option selected value=""><?php echo lang('lib_second_otherservice_status')?></option>
								<?php }
								foreach($status as $vkey => $vval){	                              
								echo "<option value='".$vkey."'";
								
								if(isset($lib_otherservice['status']) ){									
								if($lib_otherservice['status']==$vkey){ echo "selected ";}									
								}								
								echo ">".$vval."</option>";
								}
								?>
						 </select>
						<span class='help-inline'><?php echo form_error('status'); ?></span>
					</div>
				</div>
                    
                <div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/otherservice/library/show_list', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>
