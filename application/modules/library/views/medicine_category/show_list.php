<div class="table-responsive">
	<table class="table">
		<thead>
			<th>SL</th>
			<th>Category Name</th>
			<th>Status</th>
		</thead>
		<tbody>
			<?php $sl=0; foreach($records as $record){?>
			<tr>
				<td><?php echo $sl+=1; ?></td>
				<td><?php echo $record->category_name; ?></td>
				<td><?php echo ($record->status==1)? 'Active' : 'Inactive'; ?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	
</div>