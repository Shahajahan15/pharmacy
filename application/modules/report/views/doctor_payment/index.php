
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Doctor Payment Summary</h3>

     <?php if (isset($from_date) && isset($to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
                <th>SL</th>
                <th>Doctor Type</th>
                <th>DoctorName</th>
                <th>Payment Type</th>
                <th>Total Payable</th>
                <th>Total Paid</th>
                <th>Total Due</th>

            </tr>
        </thead>
        
        <tbody>
       <?php 
         $sl=0;
         
       foreach($records as $record){?>
           <tr>
          
           <td><?php echo $sl+=1;?></td>
             <td><?php
                            if($record->doctor_type==1){
                             echo 'Internal Doctor';
                             }
                             elseif ($record->doctor_type==2) {
                                 echo 'Reference Doctor';
                             }
                             elseif ($record->doctor_type==3) {
                                 echo 'External Doctor';
                             }
                             elseif ($record->doctor_type==4) {
                                 echo 'Surgeon Doctor';
                             }
                              ?></td>
                            <td><?php echo $record->ref_name; ?></td>
                            <td>


                            <?php
                            if($record->payment_type==1){
                             echo 'Commission';
                             }
                             elseif ($record->payment_type==2) {
                                 echo 'ConsultantRound';
                             }
                             elseif ($record->payment_type==3) {
                                 echo 'Surgeon';
                             }
                             elseif ($record->payment_type==4) {
                                 echo 'Report Doctor';
                             }
                              ?></td>
           <td>
                      <?php 
                          if($record->payment_type==1)
                           {
                             echo $record->tot_commission_amount;
                           }
                          elseif($record->payment_type==2)
                           {

                            echo $record->tot_round_amount;

                           }
                            elseif($record->payment_type==3)
                           {

                            echo $record->tot_ope_amount;

                           }
                           ?>
                   
                 </td>
           <td>
                         <?php 
                          if($record->payment_type==1)
                           {
                             echo $record->tot_commission_payment;
                           }
                          elseif($record->payment_type==2)
                           {

                            echo $record->tot_round_payment;

                           }
                            elseif($record->payment_type==3)
                           {

                            echo $record->tot_operation_payment;

                           }
                           ?>
           </td>
           <td>
                         <?php 
                          if($record->payment_type==1)
                           {
                             echo $record->tot_commission_amount-$record->tot_commission_payment;
                           }
                          elseif($record->payment_type==2)
                           {

                            echo $record->tot_round_amount-$record->tot_round_payment;

                           }
                           elseif($record->payment_type==3)
                           {

                            echo $record->tot_ope_amount-$record->tot_operation_payment;

                           }
                           ?>
           </td>


           </tr>
           <?php }?>
       
          
          
        </tbody>
       
        
    </table>
          

    </div>
    </div>

    </div>
</div>
  <?php //echo $this->pagination->create_links();?>


