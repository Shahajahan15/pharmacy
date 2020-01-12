<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Doctor Wise Collection(Test)</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>

<table class="table table-bordered report-table" id="example">
        
        <thead>
            <tr>
            	<th>#</th>
                <th>Doctor Type</th>
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
            $total_amount=0;
        	 foreach ($records as $key => $row){ 
                $total_amount += $row->test_amount; 

        	 ?>
        	<tr>
                <td><?php echo $key+1;?></td>
                <td><?php echo $row->doctor_type;?></td>
        		<td><?php echo $row->ref_name;?></td>
                <td><?php echo $row->male;?></td>
                <td><?php echo $row->female;?></td>
        		<td><?php echo $row->common;?></td>
                <td><?php echo $row->total_patient; ?></td>
                <td><?php echo $row->test_amount; ?></td>
        	</tr>
        	<?php } ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Total Amount=</strong></td>
                <td colspan="8"><?php echo $total_amount;?></td>
            </tr>
        </tbody>
        
    </table>
</div>
    <?php echo $this->pagination->create_links();?>