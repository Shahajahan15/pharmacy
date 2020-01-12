<style type="text/css">
    
    p{
        margin:0;
        font-weight: bold;
        padding: 0;
        font-family: 'arial';
        }
        .company-title{
            font-size: 30px;
        }
        .title{
            font-size: 24px;
        }
        .att_rep_tab{
        	border:2px solid black;
        	padding:0px 5px;
        }
</style>
<?php
$num_columns = 8;
$can_delete = false;
$can_edit = false;
$has_records = isset($records) && count($records);
?>
<div id='search_result'>

<div class="admin-box">
<a onClick="printDiv('div_print')" class="pull-right btn btn-success">
      <span class="glyphicon glyphicon-print"></span> Print 
    </a>
    <div class="col-sm-12 col-md-12 col-lg-12" id="div_print">
    <style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
  
<?php echo form_open($this->uri->uri_string()); ?>
<section id="head-information">
    <center>
       <?php echo report_header() ?>
        <br>
        <br>
        <p class="title">Attendance Summery Report</p>
        <p class="company-sub-title">From <?php e(convert_to_displayable_date_format($first_date));?> To <?php e(convert_to_displayable_date_format($second_date));?></p>

</section>
<br>
<br>
        <table class="table table-bordered report-table">
            <thead>
                <tr>
<?php if ($can_delete && $has_records) : ?>
                        <th width="2%" class="column-check"><input class="check-all" type="checkbox" /></th>
                    <?php endif; ?>
                    <th>SL</th>
                  
                    <th>EMP ID</th>
                    <th>EMP NAME</th>
                    <th>DESIGNATION</th>
                    <th>Office Day</th>
                    <th>Present Day</th>
                    <th>Leave Day</th>
                    <th>Absence Day</th>
                    
                 
                </tr>
            </thead>
            <tbody>
            <?php
         
            if ($has_records) :
                $sl=0;
                foreach ($records as $record) :

                
                   
                    ?>
                        <tr>
                            <td><?php echo $sl+=1 ?></td>
                            <td><?php e($record->EMP_CODE); ?></td>
                            <td><?php e($record->EMP_NAME); ?></td>
                     

                            <td><?php e($record->designation_name); ?></td>
                            <td><?php e($record->presnet+$record->absent+$record->liave); ?>
                            <td><?php e($record->presnet); ?></td>
                            <td><?php e($record->liave); ?></td>
                            <td><?php e($record->absent); ?></td>
                            </td>
                        
                        </tr>
                        <?php
                   
                       


                                         endforeach;
                        
                    ?>
        
                
        <?php endif; ?>
            </tbody>
        </table>



    </div>
    </div>
    </div>



