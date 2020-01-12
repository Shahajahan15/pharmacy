
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Department Serial Report</h3>
          <h4 class="text-center"> Department Name:&nbsp;<span style="font-weight: bold;"><?php if(isset($records['0']->department_name)) {echo $records['0']->department_name;}else{ echo '';}?></h4>

      <?php if (isset($first_date, $second_date) && $first_date != date('Y-m-d 00:00:00')  && !empty($first_date) && !empty($second_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($first_date)) ?> to <?php echo date('d/m/Y',strtotime($second_date)) ?> </h6>
        <?php else: ?>
            
        <?php endif; ?>
    </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
                 <th>SL</th>
                 <th>Schedule Date</th>
                 <th>Patient Name</th>
                 <th>Serial No</th>
                 <th>Contact</th>
                 <th>Department Name</th>
                


            </tr>
        </thead>
        
        <tbody>
          <?php $sl=0;foreach($records as $record){ ?>
           <tr>
             <td><?php echo $sl+=1;?></td>
             <td><?php echo $record->schedule_date;?></td>
             <td><?php echo $record->patient_name;?></td>
             <td><?php echo 'SL-'.$record->id;?></td>
             <td><?php echo $record->contact_no;?></td>
             <td><?php echo $record->department_name;?></td>

           </tr>
           <?php } ?>
        </tbody>
       
        
    </table>
          

    </div>
    </div>

    </div>

</div>


      <?php echo $this->pagination->create_links();?>
