<div>
	<table class="table">
		<thead>
			<th>SL</th>
			<th>Rule</th>
			<th>Status</th>
		</thead>
		<tbody>
			<?php foreach($records as $record){?>
			<tr>
				<td><?php echo $sl+=1; ?></td>
				<td><?php echo $record->rule_name?></td>
				<td><?php echo ($record->status==1)? 'Active' : "Inactive" ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>