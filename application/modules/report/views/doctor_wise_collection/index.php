<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Doctor Wise Collection(Ticket &amp; Serial)</h3>

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
                <th>Ticket Type</th>
                <th>App.Type</th>
                <th>Male</th>
                <th>Female</th>
                <th>Common</th>
                <th>M+F+C</th>
                <th>Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
        	<?php
        	
        	 $d_arr = array();
        	 $dc = 0;
        	// echo '<pre>';print_r($doctor_ticket);exit;
        	 $i =0; foreach ($doctor_ticket as $key => $row) : $i++; 
        	 $row = (object) $row;
        	?>
        	<tr>
        		<td><?php echo $i; ?></td>
        		<td class="row-combine"><input type="hidden" value="<?php echo $row->doctor_id; ?>"/></span><?php echo $row->doctor_name; ?></td>
                <td class="">
                   <?php 
                    if ($row->ticket_type) :
                        echo $ticket_type[$row->ticket_type];
                    else :
                        echo "";
                    endif;
                    ?>
                </td>
                <td><?php echo isset($appointment_type[$row->appointment_type]) ? $appointment_type[$row->appointment_type] : ""; ?></td>
        		<td><?php echo $row->male; ?></td>
        		<td><?php echo $row->female; ?></td>
        		<td><?php echo $row->common; ?></td>
        		<td><?php echo ($row->male + $row->female + $row->common); ?></td>
        		<td><?php echo $row->amount; ?></td>
        		<td class="row-combine"><span><input type="hidden" value="<?php echo $row->doctor_id; ?>"/></span><b><?php echo isset($total_fee[$row->doctor_id]) ? $total_fee[$row->doctor_id] : 0;?></b></td>
        	</tr>
        	<?php endforeach; ?>
        </tbody>
        
    </table>
    </div>
</div>

<script>
 $(document).ready(function(){
     $("#example").rowspanizer({vertical_align: 'middle'});
 });
 
</script>