
<table class="table table-bordered c-table" style="margin-bottom:10px;">
	<thead>
		<tr class="active">
			<th>Emp. Code</th>
			<th>Emp. Name</th>
			<th>Emp. Department</th>
			<th>Roster Name</th>
		</tr>
	</thead>
	<tbody>
		<tr class="success">
			<td><?php echo $record->emp_code; ?></td>
			<td><?php echo "<b>".$record->designation_name."</b> ".$record->emp_name; ?></td>
			<td><?php echo $record->department_name; ?></td>
			<td><?php echo $record->roster_name; ?></td>
		</tr>
	</tbody>
</table>
<?php if($record) : ?>
	<input  type="hidden" value="<?php echo $record->EMP_ID; ?>" name="emp_id" class="emp_id"/>
	<table class="table table-bordered c-table" style="margin-bottom:0px; ">
		<thead>
			<tr class="info">
				<th>Start Date<span class="required">*</span></th>
				<th>End Date<span class="required">*</span></th>
			</tr>
		</thead>
		<tbody>
			<tr class="success">
				<td><input type="text" id="start_date" name="start_date" class="form-control datepickerCommon" required="" value="20/09/2017"></td>
			<td><input type="text" id="end_date" name="end_date" class="form-control datepickerCommon" required="" value=""></td>
		</tr>
		<tr style="text-align: center;">
			<td colspan="3"><input type="submit" class="btn btn-primary btn-xs" id="rd_processing" value="Process">
			&nbsp;<input type="button" onclick="resetRDPForm()" class="btn btn-warning btn-xs" value="Reset"></td>
		</tr>
	</tbody>
</table>
<?php endif; ?>
<?php $base_url = base_url()."themes/admin/js/global.js"; ?>
<script src='<?php echo $base_url; ?>' type='text/javascript'></script>

