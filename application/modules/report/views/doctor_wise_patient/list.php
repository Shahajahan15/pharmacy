

<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
      

        <?php if (isset($first_date, $second_date) && $first_date != date('Y-m-d 00:00:00')  && !empty($first_date) && !empty($second_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($first_date)) ?> to <?php echo date('d/m/Y',strtotime($second_date)) ?> </h6>
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
                <th width="50%">Doctor Name</th>
             
                <th width="50%">Total Patient</th>
            </tr>
        </thead>
        
        <tbody>
 <?php 
 $total=0;

 foreach($records as $record){
         $total+= $record->total;?>
          
       <tr>
					
					<td><?php echo $record->ref_name?></td>
					
					<td><?php echo $record->total?></td>
                 	
              
		</tr>

          
           <?php }?>
       
       <tr>
           
              
           
            <td colspan="" class="pull-right">
              <strong>Total Patient=</strong>
            </td> 
              <td colspan="" class="">
              <strong><?php echo $total;?></strong>
            </td> 
          </tr>
        </tbody>
       
        
    </table>

    </div>
    </div>

    </div>
</div>
<?php echo $this->pagination->create_links();?>

