<?php
extract($sendData);
//print_r($records);exit();   
if (isset($records))
{
  $records = (array) $records;
  //print_r($records);

}
$id = isset($records['id']) ? $records['id'] : '';
//echo '<pre>';print_r($edu_info);exit();
?>
<div class="row" style="font-family:'Times New Roman';">

  <div class="col-md-12">
      
       <div id="image_preview" style=" width: 200px; height: 200px; float: right;margin-top: 2px; display: block">
         <?php $img = ($records['EMP_PHOTO'] == "")? base_url("assets/images/profile/default.png") : base_url("assets/images/employee_img/" . $records['EMP_PHOTO']);?>
         <img id="previewing" src="<?php echo $img; ?>" width="150" height="150"align: left;/>
       </div>

<h1 align="center">Employee Curriculum</h1>
 <p align="center">Name:<?php echo $records['EMP_NAME'];?></br>
 Cell:<?php echo $records['MOBILE'];?> Email:<?php echo $records['EMAIL'];?></p>


</div>
<hr>
   <h2>Personal Info</h2>
    <div class="col-md-6">
      <table class="table">

   
        <tr>
         <th>Emp Name</th>
          <td><?php echo $records['EMP_NAME'];?></td>          
        </tr>
   
        <tr>
          <th>Emp Code</th>
        <td><?php echo $records['EMP_CODE'];?></td>
        </tr>
        <tr>
          <th>Emp National ID</th>
          <td><?php echo $records['NATIONAL_ID'];?></td>
        </tr>
        <tr>
          <th>Date of Birth</th>
          <td><?php echo $records['BIRTH_DATE'];?></td>
        </tr>
        <tr>
          <th>Gender</th>
          <td><?php if(isset($sex[$records['GENDER']])){ e($sex[$records['GENDER']]); } ?></td>
        </tr>

      </table>
    </div>
      <div class="col-md-5">

        <table class="table">
         <tr>
          <th>Marital Status</th>
          <td><?php if(isset($marital_status[$records['MARITAL_STATUS']])){ e($marital_status[$records['MARITAL_STATUS']]); } ?></td>
        </tr>
         <tr>
          <th>Designation</th>
          <td><?php echo $records['DESIGNATION_NAME'];?></td>
        </tr>
         <tr>
          <th>Department</th>
          <td><?php echo $records['department_name'];?></td>
        </tr>
          <tr>
           <th>Joining Date</th>
           <td><?php echo $records['JOINNING_DATE']?></td>
          </tr>
          <tr>
           <th>Job Confirm Date</th>
           <td><?php echo $records['JOB_CONFIRM_DATE']?></td>
          </tr>
         

        
        
        </table>

      </div>

<h2>Contact Info</h2>
    <div class="col-md-6">
      <table class="table"> 
      <tr>
          <th>Present Address</th>
          <td><?php e($records['PRESENT_CITY_TOWN']); ?></td>
        </tr>
     
        <tr>
         <th><b>Mobile</b></th>
          <td><?php echo $records['MOBILE'];?></td>          
        </tr>
   
        <tr>
          <th>Alternative Mobile</th>
        <td><?php echo $records['ALTERNATIVE_MOBILE'];?></td>
        </tr>
       
      </table>
    </div>
    <div class="col-md-5">
        <table class="table">
         <tr>
          <th>Permanent Address</th>
          <td><?php e($records['PERMANENT_CITY_VILLAGE']); ?></td>
        </tr>
        
         <tr>
          <th>Email</th>
          <td><?php echo $records['EMAIL'];?></td>
        </tr>
     <tr>
          <th>Telephone</th>
          <td><?php echo $records['TELEPHONE'];?></td>
        </tr>
        
        </table>

      </div>


   <hr/>
<h2>Educational Info</h2>
    <div class="col-md-11">
      <table class="table"> 
        <tr>
         <th><b>Exam Name</b></th>
          <th>Board</th>  
          <th>Pass Year</th>
          <th>Score</th>
          <th>Score Earned</th>

        </tr>
        <tr>
         <?php foreach($edu_info as $record){?>

        
        <td><?php echo $record->exam_name;?></td>
        <td><?php echo $record->exam_board;?></td>
        <td><?php echo $record->PASS_YEAR;?></td>
        <td><?php e($record->SCORE); ?></td>
        <td><?php echo $record->EARNED_SCORE;?></td>

        </tr>
        
       <?php }?>
      </table>
    </div>
  




  
