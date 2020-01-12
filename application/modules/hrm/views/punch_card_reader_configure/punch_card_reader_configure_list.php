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

if (isset($reader_model))
{
	$records = (array) $reader_model;
}
$id = isset($records['READER_MODEL_ID']) ? $records['READER_MODEL_ID'] : '';

?>

<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('Store.supplier.Delete');
$can_edit		= $this->auth->has_permission('Store.supplier.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
        
        <?php echo form_open($this->uri->uri_string()); ?>
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <?php if ($can_delete && $has_records) : ?>
                        <th width="2%" class="column-check"><input class="check-all" type="checkbox" /></th>
                        <?php endif;?>					
                        <th width="15%"><?php echo lang('reader_model_name'); ?></th>
                        <th width="15%"><?php echo lang('reader_model_database_type'); ?></th>
                        <th width="10%"><?php echo lang('reader_model_database_name'); ?></th>
                        <th width="10%"><?php echo lang('reader_model_table_name'); ?></th>
						 <th width="10%"><?php echo lang('reader_model_server_name'); ?></th>
                    </tr>
                </thead>
                    <?php if ($has_records) : ?>
                <tfoot>
                        <?php if ($can_delete) : ?>
                    <tr>
                        <td colspan="<?php echo $num_columns; ?>">
                            <?php echo lang('bf_with_selected'); ?>
                            <input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('bf_msg_delete_confirm'))); ?>')" />
                        </td>
                    </tr>
                        <?php endif; ?>
                </tfoot>
                <?php endif; ?>
                <tbody>
                        <?php

                        if ($has_records) :
                                foreach ($records as $record) : 




                        ?>
                    <tr>
                        <?php if ($can_delete) : ?>
                        <td class="column-check"><input type="checkbox" name="checked[]" value="<?php  echo $record->READER_MODEL_ID; ?>" /></td>
                        <?php endif;?>

                        <?php if ($can_edit) : ?>
                        <td><?php echo anchor(SITE_AREA . '/punch_card_reader_configure/hrm/edit/' . $record->READER_MODEL_ID, '<span class="glyphicon glyphicon-pencil"></span>' .  $record->READER_MODEL_NAME ); ?></td>
                        <?php else : ?>
                        <?php endif; ?>
                        <td><?php foreach($database_type as $key => $val)
													{
														
														
														if(isset($record->DATABASE_TYPE))
														{
															if($record->DATABASE_TYPE== $key){ echo $val;}
														}
														
														
													} 
						
						
						//e($record->DATABASE_TYPE);?>  </td>
                        <td><?php e($record->DATABASE_NAME); ?> </td>
                        <td><?php e($record->TABLE_NAME); ?> </td>
                        <td><?php e($record->SERVER_NAME); ?> </td>
                        <td><?php  ?> </td>
                        <td><?php // if($record->APPROVAL_STATUS==0){
//                                                   e("Not Approve");
//                                              } elseif($record->APPROVAL_STATUS==1){ 
//                                                   e("Incharge Approved"); 
//                                              }else{ 
//                                                   e("Admin Approved"); 
//                                              }  
                        ?></td>
                        <td> 
                            <a class="btn btn-primary btn-xs" href="<?php echo site_url().'/admin/punch_card_reader_configure/hrm/details_list/'.$record->READER_MODEL_ID ; ?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                        </td>
                    </tr>
                        <?php
                                endforeach;
                        else:
                        ?>
                    <tr>
                        <td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_records_not_found'); ?></td>
                    </tr>
                        <?php endif; ?>
                </tbody>
            </table>
	<?php echo form_close(); ?>
    </div>
</div>