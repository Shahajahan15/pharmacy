
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3 style="font-weight: bold;">User Wise Collection</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
    <div class="row">
    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
    <div class="table-responsive">
    <table class="table table-bordered report table">
        
        <thead>
            <tr class="active">
                <th>User</th>
                <th>User Name</th>              
                <th>Collect Taka</th>
                <th>Return Taka</th>
                <th>Discount Taka</th>
                <th>View</th>
 
            </tr>
        </thead>
            
        <tbody>
             
             <tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
        </tbody>
       
        
    </table>
    </div>
    </div>

    </div>
    
</div>