
<div class="box">
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Doctor Wise Collection(Test)</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered report-table" id="example">
        
        <thead>
            <tr>
            	<th>#</th>
                <th>Doctor Name</th>
                <th>Male</th>
                <th>Female</th>
                <th>Common</th>
                <th>M+F+C</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
        	<?php
        	 $i =0; foreach ($doctor_ticket as $key => $row) : $i++; 
        	 $row = (object) $row;
        	?>
        	<tr>
        		<td><?php echo $i; ?></td>
        		<td class="row-combine"><input type="hidden" value="<?php echo $row->doctor_id; ?>"/></span><?php echo $row->doctor_name; ?></td>
        		<td><?php echo $row->male; ?></td>
        		<td><?php echo $row->female; ?></td>
        		<td><?php echo $row->common; ?></td>
        		<td><?php echo ($row->male + $row->female + $row->common); ?></td>
        		<td><?php echo $row->amount; ?></td>
        	</tr>
        	<?php endforeach; ?>
        </tbody>
        
    </table>
    </div>
</div>