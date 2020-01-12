
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Refund</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
                <th>#</th>
                <th>RMR. No</th>
                <th>Refund Date</th>
                <th>Patient Id</th>
                <th>Patient Name</th>
                <th>Service Name</th>
                <th>Sub Service Name</th>
                <th>Approved By</th>
                <th>Refund By</th>
                <th>Refund Amount</th>
            </tr>
        </thead>
     <?php if ($refund_info) : ?>
      <tbody>
            <?php 
            	$tot_collection = 0;
            	$tot_refund = 0;
            	foreach($refund_info as $key => $row) : 
            	$bc = ($key % 2 == 1) ? "#E4E4E4" : "#B0C4DE";
            ?>
            <tr style="background: <?php echo $bc; ?>">
                <td><?php echo $key+1; ?></td>
                <td><?php echo $row->rmr_no; ?></td>
                <td><?php echo custom_date_format($row->refund_date); ?></td>
                <td><?php echo $row->patient_code; ?></td>
                <td><?php echo $row->patient_name; ?></td>
                <td><?php echo $row->service_name; ?></td>
                <td><?php echo service_sub_service_name($row->service_id, $row->details_id); ?></td>
                <td><?php echo $row->approve_by; ?></td>
                <td><?php echo $row->refund_by; ?></td>
                <td><?php echo $row->sub_amount; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    <?php else: ?>
        <tr>
                <td colspan="14" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
    <?php endif; ?>
    </table>
    <?php echo isset($discount_info) ?  $this->pagination->create_links() : "" ; ?>
    </div>
    </div>
    </div>
</div>