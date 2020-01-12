
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>

    <?php echo report_header() ?>
    <div class="text-center">
      <h4>CONSULTANT PAYMENT SHEDULE
     </h4>
         <?php if(isset($records['0']->ref_name)):?>
      <h5><strong>Name:<?php echo $records['0']->ref_name; ?></strong> <br>
      <?php endif;?>
        <?php if(isset($records['0']->ref_quali)):?>
      <h5><strong><?php echo $records['0']->ref_quali; ?></strong><br>
      <?php endif;?>
    </div>
 

    <br>




    <div class="table-responsive">
    <table class="table table-bordered report-table" id="example">
        
        <thead>
            <tr>
                <th>SL No</th>
                <th>Reg No</th>
                <th>Name of Patient</th>
                <th>Period</th>
                <th>No of Visit</th>
                <th>Consultancey</th>
            
            </tr>
           
        </thead>
        <tbody>
      
    <?php $sl=0; $sum=0; 
        foreach($records as $record){
      $sum += $record->total_price;
      ?>
   
        
        <tr>
          <td><?php echo $sl+=1;?></td>
          <td><?php echo $record->patient_id;?></td>
          <td><?php echo $record->patient_name;?></td>
          <td><?php echo custom_date_format($record->period_from).'-'.custom_date_format($record->period_to);?></td>
          <td><?php echo $record->total_visit;?></td>
          <td><?php echo $record->total_price;?></td>
          
        </tr>
     
           <?php }
         
?>
           

        </tbody>
        
    </table>
    <h5 align="center"><strong>Total Amount=<?php echo $sum;?></strong></h5>
   
    <br><br><br><br>
  <!--  <table>
      <tr>
        <th width="5%"></th>
        <th>prepared by</th>
        <th width="20%"></th>
        <th>Executive Director</th>
        <th width="20%"></th>
        <th>Managing Director</th>
        <th width="20%"></th>
        <th>Chairman</th>
      </tr>
      <tr>
        <td width="5%"></td>
        <td>Lima</td>
        <td width="5%"></td>

        <td></td>
        <td width="5%"></td>

        <td></td>
        <td width="5%"></td>

        <td></td>
      </tr>
    </table>-->
    </div>
</div>

