<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//====== Static Set-up =======


 $config['shift_types'] = array(
					'1' => 'Fixed',
					'2' => 'Roster'					
					);
					
$config['status'] = array(
					'1' => 'Active',
					'0' => 'Inactive',
					);	
						


$config['leave_type'] = array(
					'1' => 'Casual Leave(CL)',
					'2' => 'Sick Leave(SL)',	
					'3' => 'Leave Without Pay(LWP)',
					'4' => 'Recreation Leave(RL)',
					'5' => 'Special Leave(SPL)',
					'6' => 'Earned Leave(EL)',
					'7' => 'Education Leave(EDL)'
					);
					
$config['limit_type'] = array(
					'1' => 'Fixed',
					'2' => 'Calculative'					
					);
						
$config['formula'] = array(
					'1' => '1 Day Leave For Every 18 Days Present',
					'2' => '1 Day Leave For Every 18 Working Days',
					'3' => '1 Day Leave For Every 18 Days Present Including Leave'	
					);

$config['leave_calculation'] = array(
					'1' => 'Date Of Joining',
					'2' => 'Date Of Confirmation'
					
					);
					
$config['leave_criteria'] = array(
					'1' => 'All At Any Time',
					'2' => 'Proportionately'
					
					);
					
$config['fructional_leave'] = array(
					'1' => 'Not Allowed',
					'2' => 'Allowed'
					
					);				
					
$config['leave_avail'] = array(
					'1' => 'Date Of Joining',
					'2' => 'Date Of Confirmation',
					'3' => '365 Days From DOJ',
					'4' => '2 Years From DOJ'
					
					);				
					
$config['offday_leave'] = array(
					'1' => 'Excluding Off-days',
					'2' => 'In-between Past/Next/WD',
					'3' => 'In-between Off-days'					
					
					);	


					
$config['parameter_type'] = array(
					'1' => 'persentage(%)',
					'2' => 'Fixed',
					'3' => 'Formula'					
					
					);
					
					
$config['parameter_calculation'] = array(
					'1' => "Per day average of 3 month's pay day",
					'2' => 'Regular salary'										
					
					);
				
					
				
$config['parameter_disburse'] = array(
					'1' => "50% before and 50% after delivery",
					'2' => '100%  before delivery',
					'2' => 'With regular monthly salary '
					
					);
					
					
$config['confirmation_status'] = array(
					'1' => 'Confirm',
					'0' => 'Not Confirm',
					);	


$config['amount_type'] = array(
					'1' => 'Percentage',
					'0' => 'Fixed',
					);		
				
					