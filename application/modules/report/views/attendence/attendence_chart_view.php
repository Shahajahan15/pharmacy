<div id='search_result'>

<div class="admin-box">
<a onClick="printDiv('div_print')" class="pull-right btn btn-success">
      <span class="glyphicon glyphicon-print"></span> Print 
    </a>
    <div class="col-sm-12 col-md-12 col-lg-12" id="div_print">
    <style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>


<?php

$month_of_year=date("M-Y", strtotime($this_month));
//var_dump($month_of_year);

?>
<center>
<?php echo report_header() ?>
      
        <p  style="font-size:22px;margin-top: 25px;"><b> Monthly Attendance  Report</b></p>
        <p style="font-size:16px;"><b><strong><?php echo $month_of_year; ?></strong></b></p>
</center>  
<?php
   $total_day=(int)date("t", strtotime($this_month));
    echo "<table class='table table-bordered'>";

    echo "<tr class='active'>";
    echo "<td class='att_rep_tab'>"."Employee Code"."</td>";  
    echo "<td class='att_rep_tab'>"."Employee Name"."</td>"; 
    for($j=1;$j<=$total_day;$j++){
      echo "<td class='att_rep_tab'>".$j."</td>";  
    }
    echo "</tr>";

    
    for($i=0;$i<count($employees);$i++){
        echo "<tr>";
            echo '<td>';
                echo $employees[$i]->code;
            echo '</td>';
            echo '<td>';
                echo $employees[$i]->name;

            echo '</td>';
        for($j=1;$j<=$total_day;$j++){
            echo '<td>';
            if(isset($emp_present_status[$employees[$i]->id][$j])){
                if($emp_present_status[$employees[$i]->id][$j]==1){
                echo "<span style='color:green'>".substr($attendance_status[$emp_present_status[$employees[$i]->id][$j]],0,1)."</span>";
                        }
                          elseif($emp_present_status[$employees[$i]->id][$j]==2){
                echo "<span style='color:red'>".substr($attendance_status[$emp_present_status[$employees[$i]->id][$j]],0,1)."</span>";
                        }
                          elseif($emp_present_status[$employees[$i]->id][$j]==3){
                echo "<span style='color:blue'>".substr($attendance_status[$emp_present_status[$employees[$i]->id][$j]],0,1)."</span>";
                        }
            }else{
                echo '<p class="no-attendence"></p>';
            }
            echo '</td>';

        }
        echo "</tr>";
    }
    
   

echo "</table>";
echo "<p style='text-align: center;font-size:22px;margin-top: 25px;'><b>"."Attendance Summery  Report"."</b></p>" ;
echo "<p style='text-align: center;font-size:16px;'><b>".$month_of_year."</b></p>" ;
echo "<table class='table table-bordered'>";
echo "<tr class='active'>";
    echo "<td class='att_rep_tab'>"."Employee Code"."</td>";  
    echo "<td class='att_rep_tab'>"."Employee Name"."</td>"; 
    echo "<td class='att_rep_tab'>"."Total Office Day"."</td>";
    echo "<td class='att_rep_tab'>"."Total Present"."</td>";
    echo "<td class='att_rep_tab'>"."Total Absent"."</td>";
    echo "<td class='att_rep_tab'>"."Total Leave"."</td>";      
  
    echo "</tr>";
    for($i=0;$i<count($employees);$i++){
        echo "<tr>";
            echo '<td>';
                echo $employees[$i]->code;
            echo '</td>';
            echo '<td>';
                echo $employees[$i]->name;

            echo '</td>';
            $total_present=0;
            $total_absent=0;
            $total_leave=0;
        for($j=1;$j<=$total_day;$j++){
           if(isset($emp_present_status[$employees[$i]->id][$j])){
                if($emp_present_status[$employees[$i]->id][$j]==1){
                         $total_present=$total_present+1;
                        }
                        elseif($emp_present_status[$employees[$i]->id][$j]==2){
                         $total_absent=$total_absent+1;
                        }
                        elseif($emp_present_status[$employees[$i]->id][$j]==3){
                         $total_leave=$total_leave+1;
                        }
            }

        }
              echo '<td>';
                echo $total_present+$total_absent+$total_leave;
            echo '</td>';
            echo '<td>';
                echo $total_present;

            echo '</td>';
             echo '<td>';
                echo $total_absent;

            echo '</td>';
             echo '<td>';
                echo $total_leave;

            echo '</td>';
        echo "</tr>";
    }
    
   

echo "</table>";
?>

</div>
</div>
</div>
<script type="text/javascript">
    $('.no-attendence').closest('td').css('background-color','gray');
</script>