

<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
      

  
    </div>
	<div class="row">
	<div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
                <th width="50%">Consultant Name</th>
                <th >Total Patient</th>
                <th >Total Price</th>
                <th >Action</th>		
            </tr>
        </thead>
        
        <tbody>
 <?php foreach($records as $record){?>
         
          
       <tr>
					
					<td><?php echo $record->ref_name?></td>
					<td><?php echo $record->total?></td>
                 	<td><?php echo $record->total_price?></td>
                 	
                 <td>
						<a href="<?php echo site_url('admin/consultant_payment_schedule/report/details/'.$record->consultant_id) ?>" class="btn btn-primary btn-sm" >Details</a>
				</td>
		</tr>

          
           <?php }?>
       
       
        </tbody>
       
        
    </table>

    </div>
    </div>

    </div>
</div>

