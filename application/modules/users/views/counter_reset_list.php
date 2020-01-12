<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<?php
$num_columns = 5;
$has_records = $records ? true : false;
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="10%">SL</th>
					<th width="10%">Counter ID</th>
					<th width="30%">Counter Name</th>
					<th width="30%">Status</th>
					<th width="20%">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $key => $record) :
                    ?>
                        <tr>
                              <td><?php e($key+1); ?></td>
                              <td><?php e($record->counter_id) ?><input type="hidden" name="counter_id" value="<?php echo $record->counter_id?>"></td>
                              <td><?php e($record->counter_name) ?></td>
                              <td><?php e($record->counter_status == 1 ? 'Active' : 'In-Active') ?></td>
                              <td>
                                  <input type="submit" name="reset" id="reset_me" class="btn btn-danger" value="Reset" onclick="return confirm('<?php e(js_escape('RESET: Are you sure you want to reset?')); ?>')" />
                              </td>
                        </tr>
                    <?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">No data found</td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
</div>