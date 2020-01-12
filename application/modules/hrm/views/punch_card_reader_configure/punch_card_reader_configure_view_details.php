<?php extract($SendData); ?>
<style>

span#detailsId{ color: blue;
                    cursor:pointer;
    }
</style>
<?php 
//extract($sendData);
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

if (isset($reader_model_details))
{
    $records = (array) $reader_model_details;
}
$id = isset($records['id']) ? $records['id'] : '';

?>

<?php
$num_columns	= 8;
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
        
        <table class="table table-striped  table-responsive">
            <thead>
                <tr>	
				
                    <th width=""><?php echo lang('reader_model_n'); ?></th>
                    <th width=""><?php echo lang('reader_model_d_base_type'); ?></th>
					 <th width=""><?php echo lang('reader_model_d_base_name'); ?></th>
                    <th width=""><?php echo lang('reader_model_t_name'); ?></th>
					 <th width=""><?php echo lang('reader_model_s_name'); ?></th>
                    <th width=""><?php echo lang('reader_model_u_name'); ?></th>
					 <th width=""><?php echo lang('reader_model_p'); ?></th>
                    <th width=""><?php echo lang('reader_model_rf_c_f'); ?></th>
					 <th width=""><?php echo lang('reader_model_d_field'); ?></th>
                    <th width=""><?php echo lang('reader_model_t_field'); ?></th>
					 <th width=""><?php echo lang('reader_model_r_no_field'); ?></th>
                    <th width=""><?php echo lang('reader_model_n_no_field'); ?></th>
					 <th width=""><?php echo lang('reader_model_st_field'); ?></th>
                    <th width=""><?php echo lang('reader_model_id_f_name'); ?></th>
					 <th width=""><?php echo lang('reader_model_d_format'); ?></th>
                    <th width=""><?php echo lang('reader_model_sta'); ?></th>
                   
                </tr>
            </thead>
               
            <tbody>
               
                <tr>
					<?php if($records): ?> 
					<td><?php  e($records['READER_MODEL_NAME']);  ?>  </td>
                    <td><?php 

						foreach($database_type as $key => $val)
						{
							
							
							if(isset($records['DATABASE_TYPE']))
							{
								if($records['DATABASE_TYPE']== $key){ echo $val;}
							}
							
							
						} 
					
					 
					
					?> 


					</td>
					<td><?php  e($records['DATABASE_NAME']);  ?>  </td>
                    <td><?php  e($records['TABLE_NAME']); ?>  </td>
					<td><?php  e($records['SERVER_NAME']);  ?>  </td>
                    <td><?php  e($records['USER_NAME']);  ?>  </td>
					<td><?php echo  'xxxxxx';  ?>  </td>
                    <td><?php  e($records['RF_CODE_FIELD']);  ?>  </td>
					<td><?php  e($records['DATE_FIELD']);  ?>  </td>
                    <td><?php  e($records['TIME_FIELD']);  ?>  </td>
					<td><?php  e($records['READER_NO_FIELD']);  ?>  </td>
                    <td><?php  e($records['NETWORK_NO_FIELD']);  ?>  </td>
					<td><?php  e($records['STATUS_FIELD']);  ?>  </td>
                    <td><?php  e($records['ID_FIELD_NAME']);  ?>  </td>
					<td><?php 
						foreach($date_format as $key => $val)
						{
							if(isset($records['DATE_FORMAT']))
							{
								if($records['DATE_FORMAT']== $key){ echo $val;}
							}
						} 
					
					
						?>  
					</td>
                    <td><?php  e($records['STATUS']);?></td>
					<?php endif; ?>

                </tr>
               
                <tr>
				<?php if(!$records): ?>
                    <td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_records_not_found'); ?></td>
				 <?php endif; ?>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>