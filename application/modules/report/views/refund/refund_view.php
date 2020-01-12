
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Refund</h3>

        <?php if (isset($first_date, $second_date) && $first_date != date('Y-m-d 00:00:00')  && !empty($first_date) && !empty($second_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($first_date)) ?> to <?php echo date('d/m/Y',strtotime($second_date)) ?> </h6>
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
                <th>MR. No</th>
                <th>Refund Date</th>
                <th>Patient ID</th>
                <th>Patient Name</th>
                <th>Survice Name</th>
                <th>Refund Type</th>
                <th>Payable/Receiveable</th>
                <th>Total Refund</th>
                <th>View</th>
              
            </tr>
        </thead>
     <?php if ($records) : ?>
      <tbody>
            <?php 
            	$tot_collection = 0;
            	$tot_refund = 0;
            	foreach($records as $key => $row) : 
            	$bc = ($key % 2 == 1) ? "#E4E4E4" : "#B0C4DE";
            ?>
            <tr style="background: <?php echo $bc; ?>">
                <td><?php echo $key+1; ?></td>
                <td><?php echo $row->mr_no; ?></td>
                <td><?php echo custom_date_format($row->refunded_at); ?></td>
                <td><?php echo $row->patient_id; ?></td>
                <td><?php echo $row->patient_name; ?></td>
                <td><?php echo $row->service_name; ?></td>
                <td><?php 
                if($row->refund_type==1){ echo "Full Service" ; }
                if($row->refund_type==2){ echo "Sub Service" ; }
                if($row->refund_type==3){ echo "MR Discount" ; }
                ?></td>
                <td><?php echo $row->payable_receivable_amount; ?></td>
                <td><?php echo $row->total_refund_amount; ?></td>
                   <td><a class="btn btn-info btn-xs" href="<?php echo site_url(SITE_AREA . '/refund_mod/report/refund_details/'.$row->id); ?>" style="text-decoration:none;">view</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    <?php else: ?>
        <tr>
                <td colspan="14" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
    <?php endif; ?>
    </table>
    <?php echo isset($records) ?  $this->pagination->create_links() : "" ; ?>
    </div>
    </div>
    </div>
</div>