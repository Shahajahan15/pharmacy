<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Movement_Register extends Admin_Controller {
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() {		
        
		parent::__construct();
	
        $this->lang->load('common');
        $this->lang->load('movement_register');
		$this->lang->load('employee');
		Assets::add_module_js('hrm','movement_register');
		Assets::add_module_js('hrm','advance_salary');
		$this->load->model('employee_model', NULL, true);
		
		
		
		
		
		Template::set_block('sub_nav', 'movement_register/_sub_nav_movement_register');		
	} // end construct 
	
    
  
	
	/**
	 * Form data insert and update by calling save function.		
	*/
    public function create() {
		
		$this->load->model('employee_model', NULL, true);
		$this->load->model('library/designation_info_model', NULL, true);
		$this->load->model('library/department_model', NULL, true);
		$this->load->model('movement_register_model', NULL, true);
		
		
		if(isset($_POST['save'])){
			
			if($insert_id = $this->save_details())
			{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'hrm_movement_register_table');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/movement_register/hrm/create');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->movement_register_model->error, 'error');
				redirect(SITE_AREA .'/movement_register/hrm/create');
			}
		}   
		
		
        $employee_details 		= $this->employee_model->find_all();
/*		$designation_details	= $this->designation_info_model->find_all();
		$department_details 	= $this->department_model->find_all();

		// echo "<pre>"; print_r($employee_details);exit();
		
		$SendData = array
		(
			'employee_details' 		=> $employee_details,
			'designation_details' 	=> $designation_details,
			'department_details' 	=> $department_details
		
		); */
		
		
       
        Template::set('toolbar_title', lang("movement_register_title"));
		Template::set('employee_details', $employee_details );
        Template::set_view('movement_register/create');
        Template::render();
		
    } // end create function 
	public function show_list() 
	{
		  
		$this->load->model('movement_register_model', NULL, true);  
	  
		// $checked = $this->input->post('checked');
		$idDate = array(); 
		$time = array(); 
		$id = array();
		$Date = array();
		 
/*		foreach( $checked as $checke )
		{
			list($idDate[],$time[]) = explode(" ",$checke);
		}*/
		 
	/*	if (is_array($checked) && count($checked)) 
		{*/
            $this->auth->restrict('HRM.Movement_register.Delete');
            $result = FALSE;
            $data = array();
            $data['IS_DELETED'] = 1;
            $data['DELETED_BY'] = $this->current_user->id;
            $data['DELETED_DATE'] = date('Y-m-d h:i:s');
			
			foreach ($idDate as $dateId) 
			{
				list($id[],$Date[]) = explode(",",$dateId);
			}
			
			$IdLength = count($id);
			
			for($i = 0; $i < $IdLength ;$i++){
				if($Date[$i] !== date('Y-m-d')){
					
				}/*else{
					$deleteResult = $this->movement_register_model->update($id[$i],$data);
				}*/
				
			}
				
       /*     if ($deleteResult) {
                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else {
                Template::set_message(lang('delete_message') . $this->movement_register_model->error, 'error');
            }*/ 
        // }
				
		
		$query = $this->db->select('
									mr.* , 
									emp.EMP_NAME,
									pemp.EMP_NAME as permittedBy	
									
									')
									
									 ->from('hrm_movement_register_table as mr')
									 ->join('hrm_ls_employee as emp','mr.EMP_ID = emp.EMP_ID','inner')
									 ->join('hrm_ls_employee as pemp','mr.PERMITTED_BY = pemp.EMP_ID','left')									 
									 ->where('mr.IS_DELETED',0)							
									 ->get();
								
		$movement_details = $query->result();
		
									
		
        Template::set('toolbar_title', lang("m_r_show_list_title"));
		Template::set('movement_details', $movement_details);
        Template::set_view('movement_register/movement_register_list');
        Template::render();
		
    } //  end show_list function 
	
	
	public function edit(){
		
		
		$this->load->model('employee_model', NULL, true);
		$this->load->model('library/designation_info_model', NULL, true);
		$this->load->model('library/department_model', NULL, true);
		$this->load->model('movement_register_model', NULL, true);
		
		
		
		$id = $this->uri->segment(5);
		
        $edit = $this->uri->segment(4);
               
                if (empty($id))
                    {
                            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
                            redirect(SITE_AREA .'/movement_register/hrm/show_list');
                    }
                    if (isset($_POST['save'])){
                        $this->auth->restrict('HRM..Movement_register.Edit');
                        
                        if ($this->save_details('update', $id))
                        {
                            // Log the activity
                            log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'hrm_movement_register_table');

                            Template::set_message(lang('bf_msg_edit_success'), 'success');
                            redirect(SITE_AREA .'/movement_register/hrm/show_list');
                        } else {
                            Template::set_message(lang('bf_msg_edit_failure') . $this->movement_register_model->error, 'error');
                        }
                    }
                
				$employee_details 		= $this->employee_model->find_all();
			
										
										
				$record = $this->db->select('	mr.*,
									emp.EMP_NAME,
									
									dep.department_name,
									dv.division_name,
									des.DESIGNATION_NAME
											
									')
				          ->from('hrm_movement_register_table as mr') 
				          ->where('mr.MOVEMENT_REGISTER_ID', $id)
				          ->join('hrm_ls_employee as emp','mr.EMP_ID = emp.EMP_ID','inner')
				          ->join('lib_designation_info as des','emp.EMP_DESIGNATION = des.DESIGNATION_ID','left')	
				          ->join('lib_department as dep','emp.EMP_DEPARTMENT = dep.dept_id','left')	
				          ->join('hrm_ls_emp_contacts as ec','emp.EMP_ID = ec.EMP_ID','left')	
				          ->join('zone_trtarea as tr','ec.PERMANENT_POST_OFFICE_ID = tr.trt_id','left')
				          ->join('zone_area as ar','tr.area_no = ar.area_id', 'left')
				          ->join('zone_district as dist','ar.district_no = dist.district_id','left')
				          ->join('zone_division as dv','dist.division_no = dv.division_id','left')
				          ->get()			
				          ->result();
				          // echo "<pre>";	print_r($records);exit();
				
				$movement_details = $this->movement_register_model->find_by('mr.MOVEMENT_REGISTER_ID',$id);	
				//echo "<pre>"; print_r($movement_details) ; exit();				
						
			/* $fromeDate	= $movement_details->FROM_DATE;
			$movement_details['FROM_DATE'] = date('d-m-Y', strtotime($fromeDate));
			$movement_details->FROM_DATE;
			die;
				 */
				
				
			
		
      	$SendData = array
				(
					'record' 		        => $record,
					'employee_details' 		=> $employee_details,
					'movement_details' 		=> $movement_details
				); 
		// echo "<pre>"; print_r($SendData) ; exit();			
					
					
						
				
				Template::set('SendData', $SendData );
				Template::set('toolbar_title', lang('m_r_update_title'));
                Template::set_view('movement_register/movement_edit');
                Template::render();
	}   //end of edit function
	
	
	public function getEmployeeSearchAjax(){
        
         
        
       
		
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');		
						
        //====== Load Static List value from Config ==========			
        $sex 				= $this->config->item('gender_list');	
        $empType 			= $this->config->item('');	
		
		
        //====== Set search value ==========
		
        $src_emp['empId']						= $this->input->post('empId');
		$src_emp['empName'] 					= $this->input->post('empName'); 
       	$src_emp['empDesignation'] 				= $this->input->post('empDesignation');
		$src_emp['empDept'] 					= $this->input->post('empDept');
        
		
		
		
        //====== Set search value ==========

        $GetEmployeeMovementRegister 		= new GetEmployeeMovementRegister($this);		
        $records 							= $GetEmployeeMovementRegister	
                                                                ->setEmpId($src_emp['empId'])
																->setEmpName($src_emp['empName'])
																->setEmpDesignation($src_emp['empDesignation'])
                                                                ->setEmpDepartment($src_emp['empDept'])
                                                                ->setLimit($limit)
                                                                ->setOffset($offset)
                                                                ->execute();				

        $total = $GetEmployeeMovementRegister->getCount();
		
        $this->pager['base_url'] 			= current_url() .'?';
        $this->pager['total_rows'] 			= $total;
        $this->pager['per_page'] 			= $limit;
        $this->pager['page_query_string']	= TRUE;
		

        $this->pagination->initialize($this->pager);
		
		/* $sendData = array(
			'records' 			=> $records
			
			
		);
	   

		Template::set('sendData', $sendData);
		Template::set_view('movement_register/movement_register_form'); */

		  foreach($records as $record)
            {
            
            ?>
		
						<tr >
						
						
							<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record['EMP_ID'] ?>" class="" /></td>
							<td> <?php echo $record['EMP_NAME'] ?></td>				
							<td><?php echo $record['DESIGNATION_NAME'] ?></td>
							<td><?php echo $record['division_name'] ?></td>
							<td><?php echo $record['department_name'] ?></td>
							
						</tr>
						<?php   }
		 
		
    
    }//end of getEmployeeSearchAjax function 

	
	
	
	private function save_details($type='insert', $id=0)
	{		
		$this->load->model('movement_register_model', NULL, true);
           
		$current_user = $this->current_user->id;    
		
		$insertData = array();
		
		$from_Date 		= $this->input->post('from_date');
		$f_date 		= explode("/", $from_Date);
        $fromDate 		= $f_date[2] . '-' . $f_date[1] . '-' . $f_date[0];
		$to_date 		= $this->input->post('to_date');
		$t_date 		= explode("/", $to_date);
        $toDate 		= $t_date[2] . '-' . $t_date[1] . '-' . $t_date[0];
		$start_time 	= $this->input->post('start_time');
		$return_time 	= $this->input->post('return_time');
		
		
		$employee_id = $this->input->post('emp_id');
		// echo "<pre>"; print_r($employee_id);exit();
		$insertData = array();
		
			if($type == 'insert')
			{
				$this->auth->restrict('HRM.Movement_register.Create');
				if (is_array($employee_id) && count($employee_id))
				{
					
					foreach ($employee_id as $pid) 
					{
						
						$insertData[] = array( 
						'EMP_ID' 					=> $pid,
						'FROM_DATE'					=> date('Y-m-d', strtotime($fromDate)),
						'TO_DATE' 					=> date('Y-m-d', strtotime($toDate)),
						'MOVEMENT_PURPOSE' 			=> $this->input->post('movement_purpose'), 
						'DESTINATION'				=> $this->input->post('destination'), 
						'START_TIME'				=> date('h:i:s', strtotime($start_time)),
						'RETURN_TIME' 				=> date('h:i:s', strtotime($return_time)),
						'PERMITTED_BY' 				=> $this->input->post('permitted_by'),
						'CREATED_BY'				=> $this->current_user->id,
						'STATUS'					=> 1 
						);
						
					}
				}
				




				
				$mstId = $this->db->insert_batch('bf_hrm_movement_register_table',$insertData);
				return $mstId; 
				
            }
			
			 elseif ($type =='update')
			{
					$this->auth->restrict('HRM.Movement_register.Edit');
					
					
						//if(!$this->input->post('empCheckid')){ echo "select at least one employee";}
						
						$insertData['EMP_ID'] 						= $this->input->post('empCheckid');
						$insertData['FROM_DATE']					= date('Y-m-d', strtotime($fromDate));
						$insertData['TO_DATE'] 						= date('Y-m-d', strtotime($toDate));
						$insertData['MOVEMENT_PURPOSE'] 			= $this->input->post('movement_purpose');
						$insertData['DESTINATION']					= $this->input->post('destination'); 
						$insertData['START_TIME']					= date('h:i:s', strtotime($start_time));
						$insertData['RETURN_TIME'] 					= date('h:i:s', strtotime($return_time));
						$insertData['PERMITTED_BY'] 				= $this->input->post('permitted_by');
						$insertData['MODIFY_BY']					= $this->current_user->id;
						$insertData['MODIFY_DATE']					= date('Y-m-d h:i:s');
						$insertData['STATUS']						= 1;
						
						
				
					
                   
					$mstId  = $this->movement_register_model->update($id,$insertData);
				
					return $mstId;
            }
			
			
	}
            
}// end controller


                     
                     
                     

