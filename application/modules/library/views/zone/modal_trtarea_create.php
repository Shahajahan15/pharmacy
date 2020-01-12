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

if (isset($area_details))
{
	$area_details = (array) $area_details;
}
$id = isset($area_details['id']) ? $area_details['id'] : '';

?>

<div class="box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal '.$form_class.'"'); ?>

    <fieldset>
    	<div class="col-md-6 col-lg-6">
    		<div class="form-group">
    			<input type="text" name="library_zone_trt_name" class="form-control" placeholder="Post office">

    			<input type="hidden" name="library_zone_area_name" value="<?php echo $police_station;?>">
    			<input type="hidden" name="library_zone_district_name" value="<?php echo $district_no; ?>">
    			<input type="hidden" name="library_zone_division_name" value="<?php echo $division?>">
    			<input type="hidden" name="library_zone_trt_status" value="1">
    		</div>
    	</div>
        <div class="">
            <input type="submit" name="save" class="btn btn-primary btn-sm" value="Add New"  required=""/>
                <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>

<table class="table" id="post-office-table">
	<thead>
		<tr>
			<th>SL</th>
			<th>Post Office</th>
			<th>Select</th>
		</tr>
	</thead>

	<tbody>
		<?php $sl=0; foreach($area_list as $list){?>
			<tr>
				<td><?php echo $sl+=1;?></td>
				<td><?php echo $list->trt_name;?></td>
				<td>
					<span class="btn btn-xs btn-success <?php echo $list_post_office;?>" id="<?php echo $list->trt_id;?>">Select</span>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>


<script type="text/javascript">
	$(document).ready(function(){
    $('#post-office-table').DataTable();
});
</script>



