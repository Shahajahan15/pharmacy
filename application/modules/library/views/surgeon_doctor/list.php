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

if (isset($exam_exam))
{
	$exam_exam = (array) $exam_exam;
}
$id = isset($exam_exam['id']) ? $exam_exam['id'] : '';
$equi=array('','Secondary & Higher Secondary','Graduation');

?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
				
									
					
                    <th>Surgeon Name</th>
                    
					<th>Mobile</th>
				</tr>
			</thead>
			
			<tfoot>
				
			</tfoot>
			
			<tbody>
			
				<tr>
				
				<?php foreach($records as $record){ ?>	

				
                <td><?php echo $record->surgeon_name; ?></td>				
                
			
                <td><?php echo $record->mobile; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan=""><?php echo lang('bf_msg_no_records_found'); ?></td>
				</tr>
			
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
</div>