
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

if (isset($records)) {
    $records = (array)$records;
}

$id = isset($records['id']) ? $records['id'] : '';

?>

<div class="row box box-primary">
<?php echo form_open_multipart($this->uri->uri_string(), 'role="form", class="nform-horizontal", enctype="multipart/form-data"'); ?>

    <fieldset>
        <legend>Hospital Info Add</legend>

        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
            <div class="<?php echo form_error('name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('hospital_name') . lang('bf_form_label_required'), 'pharmacy_setup_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='name' type='text' name='name'
                               maxlength="100"
                               value="<?php echo set_value('name', isset($records['name']) ? $records['name'] : ''); ?>"
                               placeholder="<?php echo lang('hospital_name') ?>" required/>
                        <span class='help-inline'><?php echo form_error('name'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3">
            <div class="form-group 
            <?php echo form_error('mobile') ? 'error' : ''; ?>">
                <label class="control-label"><?php echo lang('mobile').lang('bf_form_label_required');?></label>
                <input type='text' class="form-control" id='mobile' type='text' name='mobile' maxlength="100" value="<?php echo set_value('mobile', isset($records['mobile']) ? $records['mobile'] : ''); ?>"  
                placeholder="<?php echo lang('mobile') ?>" required="" />
                <span class='help-inline'><?php echo form_error('mobile'); ?></span>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3">
            <div class="form-group <?php echo form_error('phone') ? 'error' : ''; ?>">
                <label class="control-label"><?php echo lang('phone').lang('bf_form_label_required');?></label>
                <input type='text' class="form-control" id='phone' type='text' name='phone' maxlength="100" value="<?php echo set_value('phone', isset($records['phone']) ? $records['phone'] : ''); ?>"  
                placeholder="<?php echo lang('phone') ?>" required="" />
                <span class='help-inline'><?php echo form_error('phone'); ?></span>
            </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-3">
            <div class="form-group <?php echo form_error('email') ? 'error' : ''; ?>">
                <label class="control-label"><?php echo lang('email').lang('bf_form_label_required');?></label>
                <input type='text' class="form-control" id='email' type='text' name='email' maxlength="100" value="<?php echo set_value('email', isset($records['email']) ? $records['email'] : ''); ?>"  
                placeholder="<?php echo lang('email') ?>" required="" />
                <span class='help-inline'><?php echo form_error('email'); ?></span>
            </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-3">
            <div class="form-group <?php echo form_error('address') ? 'error' : ''; ?>">
                <label class="control-label"><?php echo lang('address').lang('bf_form_label_required');?></label>
                <input type='text' class="form-control" id='address' type='text' name='address' maxlength="100" value="<?php echo set_value('address', isset($records['address']) ? $records['address'] : ''); ?>"  
                placeholder="<?php echo lang('address') ?>" required="" />
                <span class='help-inline'><?php echo form_error('address'); ?></span>
            </div>
        </div>

        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">

        <div class="form-group <?php echo form_error('logo') ? 'error' : ''; ?>">						
			<label><?php echo lang('logo');?></label>
			<input type='file' class="form-control" id='logo' name='logo' value="<?php echo set_value('logo', isset($records['logo']) ? $records['logo'] : ''); ?>" required="" />
			<span class='help-inline'><?php echo form_error('logo'); ?></span>						
		</div>
        </div>
        
        <div class="col-sm-4 col-md-4 col-lg-3">
            <div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">
                <label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
                <select class="form-control" name="status" id="bf_status" required="">
                    <option value="1" <?php if(isset($records['status'])){if($records['status'] == 1){ echo "selected";}}?> >Active</option>
                    <option value="0" <?php if(isset($records['status'])){if($records['status'] == 0){ echo "selected";}}?>>Inactive</option>
                </select>
                <span class='help-inline'><?php echo form_error('status'); ?></span>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="footer pager">
                <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"/>
                <button type="reset" class="btn btn-warning btn-sm">Reset</button>

            </div>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>








