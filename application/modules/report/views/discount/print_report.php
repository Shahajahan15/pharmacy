
<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
    .page-break{
    page-break-before: always;
    page-break-after: always;
  }
  @page { margin: 0; }
</style>
<div class="box">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Discount Report</h3>

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
                <th>Discount Date</th>
                <th>MR No.</th>
                <th>Patient Id</th>
                <th>Patient Name</th>
                <th>Service Name</th>
                <th>Amount Type</th>
                <th>Total Discount</th>
                
                
            
            </tr>
        </thead>
     <?php if ($discount_info) : ?>
      <tbody>
            <?php 
                $total_discount_amount = 0;
               
                
                foreach($discount_info as $key => $row) : 
                $bc = ($key % 2 == 1) ? "#E4E4E4" : "#B0C4DE";
            ?>
            <tr style="background: <?php echo $bc; ?>">
                <td><?php echo $key+1; ?></td>
                <td><?php echo $row->created_time; ?></td>
                 <td><?php echo $row->mr_no; ?></td>
                 <td><?php echo $row->patient_id; ?></td>
                <td><?php echo $row->patient_name; ?></td>
                <td><?php echo $row->service_name; ?></td>
                
                
                <td><?php if($row->discount_type==1){echo "Amount";}
                elseif($row->discount_type==2)
                    {
                        if ($row->type == 1) {
                            echo $row->doctor_discount_percent."%";
                        }
                        elseif ($row->type == 0) {
                            echo $row->discount_persent."%";
                        }
                        
                    }

                ?></td>
                
                
                
                
                <td><?php 

                if($row->type == 0)
                    {
                        echo $total_discount = $row->service_dicount;
                    }
                elseif($row->type == 1)
                    {
                        echo $total_discount = $row->doctor_dicount+$row->hospital_dicount;
                    }
                else{
                       echo $total_discount = $row->mr_discount;
                   }
                

                 ?></td>
              

      <?php 

                
                $total_discount_amount = $total_discount_amount+ $total_discount;
                
                ?>

            </tr>
            <?php endforeach; ?>
               <tr>
                <td colspan="7"><strong>Total:</strong></td>
                
                <td><?php echo $total_discount_amount; ?></td>
                <td></td>
            
            </tr>
        </tbody>
    <?php else: ?>
        <tr>
                <td colspan="14" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
            </tr>
    <?php endif; ?>
    </table>

    </div>
    </div>
    </div>
</div>



 <?php echo $this->pagination->create_links(); ?>
 <script type="text/javascript">
    window.print();
    window.close();
</script>