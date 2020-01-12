<?php
$can_edit=true;
?>
<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>

<div id="search_result">
<table class="table">
	<thead>
		<tr>
			<th>SL</th>
			<th>Package name</th>
			<th>Sale Price</th>
			<th>Package Details</th>
		</tr>
	</thead>
	<tbody>
		<?php $sl=1; foreach($records as $record){?>
			<tr>
				<td><?php echo $sl++;?></td>
				<?php if ($can_edit) : ?>
                                <td><?php echo anchor(SITE_AREA . '/pharmacy_package/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->package_name); ?></td>
                            <?php else : ?>
								<td><?php e($record->package_name); ?></td>
                            <?php endif; ?>
				<td><?php echo $record->package_price;?></td>
				<td><a class="btn btn-xs btn-primary p-details" id="<?php echo $record->id; ?>">Pacakage Details</a></td>
			</tr>
		<?php } ?>
	</tbody>
</table>


<?php echo form_close(); ?>  

<!-- <?php  echo $this->pagination->create_links(); ?> -->
</div>