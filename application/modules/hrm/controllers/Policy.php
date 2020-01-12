<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Patient Admission  controller
 */
class Policy extends Admin_Controller
{
	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();					
		$this->load->model('policy_lunch_model', NULL, TRUE);		
		$this->lang->load('common');		
		$this->lang->load('policy');
		
		$this->config->load('config_policy');
		
		Assets::add_module_js('hrm', 'policy.js');		
		Assets::add_module_css('hrm', 'policy.css');
		
		Template::set_block('sub_nav', 'policy/_sub_nav_policy');	
	}
	/* Construct End Here*/
	
	
	
		
	//=====Policy Tab Start Here========//		
	public function policy_tab()
    {        		
		$policyId 		= (int)$this->uri->segment(5);
		$tab_active 	= $this->uri->segment(6);
		//echo ($tab_active); Exit;
		if(trim($tab_active) == ""){
			$tab_active = "#leavePolicy";
		}else{
			$tab_active = "#".$tab_active;
		}
		$tab_url	= $this->getTabURL($tab_active);
        Template::set("toolbar_title", lang("policy_create"));
        Template::set("policyId", $policyId);
		Template::set("tab_active", $tab_active);
		Template::set("tab_url", $tab_url);
        Template::render();		
	}
	
	// Load Redirect Tab 
	public function getTabURL($tab_id)
	{
		$tabURL="";
		if(trim($tab_id)=="#leavePolicy"){
			$tabURL="policy/hrm/leave_policy/";
		}else if(trim($tab_id)=="#absentPolicy"){
			$tabURL="policy/hrm/absent_policy/";
		}else if(trim($tab_id)=="#maternityPolicy"){
			$tabURL="policy/hrm/maternity_policy/";	
		}else if(trim($tab_id)=="#policyMedical"){
			$tabURL="policy/hrm/medical_policy/";
		}else if(trim($tab_id)=="#policyShift"){
			$tabURL="policy/hrm/shift_policy/";
		}else if(trim($tab_id)=="#policyBonus"){
			$tabURL="policy/hrm/bonus_policy/";
		}else if(trim($tab_id) == "#rosterPolicy") {
			$tabURL = "policy/hrm/roster_policy";
		}	

		
		return $tabURL;
	}	
	
	
	
	
	
	
	
	//--------------------------------------------------------------------
	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	/*public function show_list()
    {
		$this->auth->restrict('HRM.Employee.View');
		
		//load library	// Pagination
        $this->load->library('doctrine');
		
		
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');			
	
				
		Template::set('sendData');		
		Template::set('toolbar_title', lang("employee_list_details_view"));		
		Template::set_view('policy/policy_list');		
		Template::render();
    }
	*/

	
	
	//nasir
	//========Start Policy Leave ========//	
    public function leave_policy()
    {			
		$this->auth->restrict('HRM.Policy.View');
		$this->auth->restrict('HRM.Policy.Create');	
		
		//$POLIVY_ID = (int)$this->uri->segment(5);	
		if (isset($_POST['save']))
		{									
			redirect(SITE_AREA .'/policy/hrm/policy_tab/'.$POLIVY_ID.'/absent_policy');				
				
		}		
		
	
		$data = array();
		$data['leave_type'] 			= $this->config->item('leave_type');
		$data['limit_type'] 			= $this->config->item('limit_type');
		$data['formula'] 				= $this->config->item('formula');
		$data['leave_calculation'] 		= $this->config->item('leave_calculation');
		$data['leave_criteria'] 		= $this->config->item('leave_criteria');
		$data['fructional_leave'] 		= $this->config->item('fructional_leave');
		$data['leave_avail'] 			= $this->config->item('leave_avail');
		$data['offday_leave'] 			= $this->config->item('offday_leave');
		
        echo $this->load->view('policy/leave_form',$data, TRUE);
		$this->showLeaveInfo();
		
    }
	
	
	
// Check Leave Policy Name
	public function leavePolicyCheckAjax()
	{	
	
		$leave_policy_name	= $this->input->post('leave_policy_name'); 
			
		if(trim($leave_policy_name)!= '')
		{			
			$res =$this->db->query("SELECT LEAVE_POLICY_NAME FROM bf_hrm_leave_policy_mst WHERE  LEAVE_POLICY_NAME  LIKE '%$leave_policy_name%'");	
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Name Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}
	
	
	
	
	//====== Ajax Function of Employee Leave Policy ====//	
	public function addLeavePolicyAjax()
	{										
							
		$this->load->model('Policy_leave_mst_model', NULL, TRUE);
		$this->load->model('policy_leave_dtls_model', NULL, TRUE);	

		//echo '<pre>';print_r($_POST);exit;
		//$this->db->trans_begin();
			
			$LEAVE_POLICY_MST_ID 	= $this->input->post('LEAVE_POLICY_MST_ID');	
			$LEAVE_POLICY_DTLS_ID 	= $this->input->post('LEAVE_POLICY_DTLS_ID');
			
			$datamst = array();			
			$datamst['LEAVE_POLICY_NAME'] 					= $this->input->post('leave_policy_name');				
			$datamst['LEAVE_POLICY_STATUS'] 		 		= $this->input->post('leave_policy_status');
	

			if($LEAVE_POLICY_MST_ID > 0)
			{
				$this->auth->restrict('HRM.Policy.Edit');
				
				$datamst['MODIFY_BY']				= $this->current_user->id;
				$datamst['MODIFY_DATE']				= date('Y-m-d H:i:s');
				$this->Policy_leave_mst_model->update($LEAVE_POLICY_MST_ID,$datamst);
				
				$this->db->delete('hrm_leave_policy_dtls', array('LEAVE_POLICY_MST_ID' => $LEAVE_POLICY_MST_ID));
				
				$masterId  = $LEAVE_POLICY_MST_ID;
				
			}else
			{									
				$this->auth->restrict('HRM.Policy.Create');
				$datamst['CREATED_BY']							= $this->current_user->id;	
				//echo '<pre>';print_r($datamst);exit;	
				$masterId = $this->Policy_leave_mst_model->insert($datamst);	
				
			} 
							
		
		
			
			$LEAVE_POLICY_MST_ID		= $masterId;			
			$leaveType					= $this->input->post('leaveType');				
			$limitType					= $this->input->post('limitType');			
			$leaveFormula				= $this->input->post('leaveFormula');
			$maxLimit 					= $this->input->post('maxLimit');		
			$consecutivDay				= $this->input->post('consecutivDay');	
			//$calculationStart			= $this->input->post('calculationStart');	
			$availableAfter				= $this->input->post('availableAfter');	
			$availableCriteria			= $this->input->post('availableCriteria');
			$ofDayCount					= $this->input->post('ofDayCount');	
			$fractionLeave				= $this->input->post('fractionLeave');			
			$accumulationLimit			= $this->input->post('accumulationLimit');	
			$fordwardAllow				= $this->input->post('fordwardAllow');			
			$CREATED_BY					= $this->current_user->id;
			
			
			
		$get_length=count($leaveType);
		if($LEAVE_POLICY_MST_ID == 0 || $get_length == 0)
		{
			return false;
		}
		
		
		
		$insertData = array();	
		
		for($i=0; $i<$get_length; $i++)
		{	
			if($leaveType[$i]):
			$insertData[] = array(
				'LEAVE_POLICY_MST_ID' => $LEAVE_POLICY_MST_ID,	
				'LEAVE_POLICY_TYPE' => $leaveType[$i],	
				'LEAVE_POLICY_LIMIT' => ($limitType[$i]) ? $limitType[$i] : 0,	
				'LEAVE_POLICY_FORMULA' => ($leaveFormula[$i]) ? $leaveFormula[$i] : 0,	
				'LEAVE_POLICY_MAX_LIMIT' => ($maxLimit[$i]) ? $maxLimit[$i] : 0,
				'CONSECUTIVE_LEAVE' => ($consecutivDay[$i]) ? $consecutivDay[$i] : 0,
				'LEAVE_CALCULATION_START_FROM' => 0,
				'LEAVE_AVAIL_AFTER' => ($availableAfter[$i]) ? $availableAfter[$i] : 0,
				'LEAVE_AVAIL_CRITERIA' => ($availableCriteria[$i]) ? $availableCriteria[$i] : 0,
				'OFFDAY_LEAVE_COUNT' => ($ofDayCount[$i]) ? $ofDayCount[$i] : 0,
				'FRACTIONAL_LEAVE' => ($fractionLeave[$i]) ? $fractionLeave[$i] : 0,
				'MAX_ACCUMULATION_LIMIT' => ($accumulationLimit[$i]) ? $accumulationLimit[$i] : 0,
				'CARRING_FORWARD' => $fordwardAllow[$i],				
				'CREATED_BY' => $CREATED_BY
			);
		  endif;
		}		
		
		
		$this->db->insert_batch('bf_hrm_leave_policy_dtls', $insertData);		
		$this->showLeaveInfo();	
		/*if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
				return false;
			} else {
				//return true;
			}	*/		
											
	}
	

	
	//Show Leave Info  Data
	public function showLeaveInfo()
	{		
		$this->load->model('Policy_leave_mst_model', NULL, TRUE);
		$this->load->model('policy_leave_dtls_model', NULL, TRUE);	
		
		$this->db->select("								
							a.LEAVE_POLICY_DTLS_ID,
							a.LEAVE_POLICY_TYPE,
							a.LEAVE_POLICY_LIMIT,
							a.LEAVE_POLICY_FORMULA,
							a.LEAVE_POLICY_MAX_LIMIT,
							a.CONSECUTIVE_LEAVE,
							a.LEAVE_CALCULATION_START_FROM,
							a.LEAVE_AVAIL_AFTER,
							a.LEAVE_AVAIL_CRITERIA,
							a.OFFDAY_LEAVE_COUNT,
							a.FRACTIONAL_LEAVE,
							a.MAX_ACCUMULATION_LIMIT,
							a.CARRING_FORWARD,
							m.LEAVE_POLICY_MST_ID,							
							m.LEAVE_POLICY_NAME,
							m.LEAVE_POLICY_STATUS						
							
							 ");
							 
			$this->db->from('hrm_leave_policy_dtls AS a');			
			$this->db->where("a.LEAVE_POLICY_DTLS_ID > ",0);
			$this->db->where("a.IS_DELETED = ",0);
			$this->db->join("hrm_leave_policy_mst AS m", "m.LEAVE_POLICY_MST_ID = a.LEAVE_POLICY_MST_ID","left");	
			$this->db->where("m.IS_DELETED = ",0);			
			$this->db->order_by("a.LEAVE_POLICY_DTLS_ID", "ASC");
			$this->db->distinct("a.LEAVE_POLICY_DTLS_ID");	
			
		$records =	$this->policy_leave_dtls_model->find_all();
		
		echo $this->load->view('policy/leave_policy_list', array('records' => $records), TRUE);
		
	}


	
	//=====show for edit leave policy
	public function leavePolicyShowtAjax()
	{	
		$this->load->model('Policy_leave_mst_model', NULL, TRUE);
		$this->load->model('policy_leave_dtls_model', NULL, TRUE);	
		
		$LEAVE_POLICY_MST_ID      = (int)$this->input->post('LEAVE_POLICY_MST_ID');
		
		if($LEAVE_POLICY_MST_ID > 0 && !$mstAbsentData =	$this->Policy_leave_mst_model->find($LEAVE_POLICY_MST_ID))
		{	
			return false;
		}			
				
		$this->db->select("								
						a.LEAVE_POLICY_DTLS_ID,
						a.LEAVE_POLICY_TYPE,
						a.LEAVE_POLICY_LIMIT,
						a.LEAVE_POLICY_FORMULA,
						a.LEAVE_POLICY_MAX_LIMIT,
						a.CONSECUTIVE_LEAVE,
						a.LEAVE_CALCULATION_START_FROM,
						a.LEAVE_AVAIL_AFTER,
						a.LEAVE_AVAIL_CRITERIA,
						a.OFFDAY_LEAVE_COUNT,
						a.FRACTIONAL_LEAVE,
						a.MAX_ACCUMULATION_LIMIT,
						a.CARRING_FORWARD,
						m.LEAVE_POLICY_MST_ID,							
						m.LEAVE_POLICY_NAME,
						m.LEAVE_POLICY_STATUS						
						
						 ");
						 
		$this->db->from('hrm_leave_policy_mst AS m');			
		$this->db->where("m.LEAVE_POLICY_MST_ID",$LEAVE_POLICY_MST_ID);		
		$this->db->join("hrm_leave_policy_dtls AS a", "m.LEAVE_POLICY_MST_ID = a.LEAVE_POLICY_MST_ID","left");	
		$this->db->where("a.IS_DELETED = ",0);
		$this->db->where("m.IS_DELETED = ",0);		
		$query = $this->db->get();
		$result = $query->result_array();
	
		
		$LEAVE_POLICY_MST_ID 	     		= $mstAbsentData->LEAVE_POLICY_MST_ID;	
		$LEAVE_POLICY_NAME 	     			= $mstAbsentData->LEAVE_POLICY_NAME;	
		$LEAVE_POLICY_STATUS	     		= $mstAbsentData->LEAVE_POLICY_STATUS;
			
			$returnData = array();
			$returnData['evalData'] = "
				$('#leave_policy_name').val('$LEAVE_POLICY_NAME');
				$('#leave_policy_status').val('$LEAVE_POLICY_STATUS');				
				$('#LEAVE_POLICY_MST_ID').val('$LEAVE_POLICY_MST_ID');	
			";	
		
	
		
			
			
			$detailsHtml = "";
			foreach($result as $row){
			$data = array();	
			$data['leave_type'] 			= $this->config->item('leave_type');
			$data['limit_type'] 			= $this->config->item('limit_type');
			$data['formula'] 				= $this->config->item('formula');
			$data['leave_calculation'] 		= $this->config->item('leave_calculation');
			$data['leave_criteria'] 		= $this->config->item('leave_criteria');
			$data['fructional_leave'] 		= $this->config->item('fructional_leave');
			$data['leave_avail'] 			= $this->config->item('leave_avail');
			$data['offday_leave'] 			= $this->config->item('offday_leave');	
			$data['result'] 				= $row;
			$data['removeRow'] 				= $detailsHtml == "" ? false : true;
			
			$detailsHtml .= $this->load->view('policy/leave_policy_details', $data, true);
		}
		
		$returnData['detailsHtml'] = $detailsHtml;
		
		echo json_encode($returnData);
		exit;	
			
		
			
	}		
	
	//====== Ajax Function of Employee Leave Policy Delete ====//	
	public function leaveDeleteAjax()
	{		

		$this->load->model('policy_leave_dtls_model', NULL, TRUE);		
		$LEAVE_POLICY_DTLS_ID		= $this->input->post('LEAVE_POLICY_DTLS_ID');	
		
		if($LEAVE_POLICY_DTLS_ID > 0)
		{	
			$data = array();			
			$data['IS_DELETED']				= 1;	
			$data['DELETED_BY']				= $this->current_user->id;	
			$data['DELETED_DATE']			= date('Y-m-d H:i:s');	
			$this->policy_leave_dtls_model->update($LEAVE_POLICY_DTLS_ID,$data);		
		
		}		
		$this->showLeaveInfo();														
	}
	
	
	
	//========End Policy Leave ========//	
	
	
	
	
	
// Nasir	
//========Start Policy Absent  ========//  	
	
	public function absent_policy()
    {	
		$this->load->model('library/base_head_model', NULL, TRUE);		
		$this->auth->restrict('HRM.Policy.View');
		$this->auth->restrict('HRM.Policy.Create');		
		
		//$POLIVY_ID = (int)$this->uri->segment(5);	
		if(isset($_POST['save']))
		{									
			redirect(SITE_AREA .'/policy/hrm/policy_tab/'.$POLIVY_ID.'/maternity_policy');				
				
		}		
	
					
		$this->db->select("								
							h.BASE_HEAD_ID,
							h.BASE_SYSTEM_HEAD							
								
							 ");
							 
			$this->db->from('lib_base_head_info AS h');			
			$this->db->where("h.BASE_HEAD_TYPE = ",1);
			$this->db->where("h.IS_DELETED = ",0);	
			$this->db->where("h.STATUS = ",1);			
			$this->db->distinct("h.BASE_HEAD_ID");				
				
			$baseHead 	= $this->base_head_model->find_all();
		
			
						
		$this->db->select("								
							h.BASE_HEAD_ID,
							h.BASE_SYSTEM_HEAD							
								
							 ");
							 
			$this->db->from('lib_base_head_info AS h');			
			$this->db->where("h.BASE_HEAD_TYPE = ",4);
			$this->db->where("h.IS_DELETED = ",0);	
			$this->db->where("h.STATUS = ",1);			
			$this->db->distinct("h.BASE_HEAD_ID");				
				
			$deductionHead 	= $this->base_head_model->find_all();
		
		
		$data = array();	
		$data['parameter_type'] 			= $this->config->item('parameter_type');
		$data['baseHead'] 					= $baseHead;
		$data['deductionHead'] 				= $deductionHead;
        echo $this->load->view('policy/absent_form',$data,TRUE);
		echo $this->showAbsentInfo();
    }
	
		
	

// Check Absent Policy Name
	public function absentPolicyCheckAjax()
	{	
	
		$absent_policy_name	= $this->input->post('absent_policy_name'); 
			
		if(trim($absent_policy_name)!= '')
		{			
			$res =$this->db->query("SELECT ABSENT_POLICY_NAME FROM bf_hrm_absent_leave_mst WHERE  ABSENT_POLICY_NAME  LIKE '%$absent_policy_name%'");	
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Name Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}
	
		
	// Insert Absent Info  Data
	public function addAbsentPolicyAjax()
	{							
			
		$this->load->model('absent_leave_dtls_model', NULL, TRUE);
		$this->load->model('absent_leave_mst_model', NULL, TRUE);
		
		$ABSENT_POLICY_DTLS_ID 	= $this->input->post('ABSENT_POLICY_DTLS_ID');
		$ABSENT_POLICY_MST_ID 	= (int)$this->input->post('ABSENT_POLICY_MST_ID');
		
		$datamst = array();		
		$datamst['ABSENT_POLICY_NAME']				= $this->input->post('absent_policy_name');
		//$datamst['ABSENT_DEDUCTION_HEAD']			= $this->input->post('absent_deduction_head');
		$datamst['ABSENT_DEDUCTION_HEAD']	= 0;
		$datamst['ABSENT_POLICY_STATUS'] 			= $this->input->post('absent_policy_status');	
			
					
		if($ABSENT_POLICY_MST_ID > 0)
		{
			$this->auth->restrict('HRM.Policy.Edit');				
			$datamst['MODIFY_BY']			= $this->current_user->id;
			$datamst['MODIFY_DATE']			= date('Y-m-d H:i:s');
			$this->absent_leave_mst_model->update($ABSENT_POLICY_MST_ID,$datamst);			
			$this->db->delete('hrm_absent_leave_dtls', array('ABSENT_POLICY_MST_ID' => $ABSENT_POLICY_MST_ID)); 		
			$masterId = $ABSENT_POLICY_MST_ID;
			
		}else
		{									
			$this->auth->restrict('HRM.Policy.Create');
			
			$datamst['CREATED_BY']				= $this->current_user->id;					
			$masterId = $this->absent_leave_mst_model->insert($datamst);				
			
		} 

		

		$ABSENT_POLICY_MST_ID			= $masterId;
		$ABSENT_PARAMETER_TYPE			= $this->input->post('absentParameterType');		
		$ABSENT_PERSENT_FORMULA			= $this->input->post('absentPercentage');		
		$ABSENT_BASE_HEAD 				= $this->input->post('absentBaseHead');
		$ABSENT_AMOUNT					= $this->input->post('absentAmount');
		$CREATED_BY						= $this->current_user->id;
		//echo '<pre>';print_r($_POST);exit;
					 								
		$this->auth->restrict('HRM.Policy.Create');
		
		$get_length=count($ABSENT_PARAMETER_TYPE);
		if($ABSENT_POLICY_MST_ID == 0 || $get_length == 0)
		{
			return false;
		}
		
		$insertData = array();		
		for($i=0; $i<$get_length; $i++)
		{
			if ($ABSENT_PARAMETER_TYPE[$i])	:	
			$insertData[] = array(
				'ABSENT_POLICY_MST_ID' => $ABSENT_POLICY_MST_ID,	
				'ABSENT_PARAMETER_TYPE' => $ABSENT_PARAMETER_TYPE[$i],	
				'ABSENT_PERSENT_FORMULA' => $ABSENT_PERSENT_FORMULA[$i],	
				'ABSENT_BASE_HEAD' => $ABSENT_BASE_HEAD[$i],	
				'ABSENT_AMOUNT' => ($ABSENT_AMOUNT[$i]) ? $ABSENT_AMOUNT[$i] : 0,
				'CREATED_BY' => $CREATED_BY
			);
			endif;
		}
		
		$this->db->insert_batch('bf_hrm_absent_leave_dtls', $insertData); 
						
		$this->showAbsentInfo();		
											
	}
	
	

	//Delete Absent Info  Data
	public function deleteAbsentPolicyAjax()
	{			
		//$this->load->model('absent_leave_mst_model', NULL, TRUE);
		$this->load->model('absent_leave_dtls_model', NULL, TRUE);
		
		$this->auth->restrict('HRM.Policy.Edit');
		
		$ABSENT_POLICY_DTLS_ID 		= $this->input->post('ABSENT_POLICY_DTLS_ID');		

		if($ABSENT_POLICY_DTLS_ID > 0)
		{			
			$data = array();			
			$data['IS_DELETED']				= 1;	
			$data['DELETED_BY']				= $this->current_user->id;	
			$data['DELETED_DATE']			= date('Y-m-d H:i:s');	
			$this->absent_leave_dtls_model->update($ABSENT_POLICY_DTLS_ID,$data);	
		}
		
		$this->showAbsentInfo();	
	}
	
	
	
	
	//Show Absent Info  Data
	public function showAbsentInfo()
	{		
		$this->load->model('absent_leave_dtls_model', NULL, TRUE);
		//$this->load->model('absent_leave_mst_model', NULL, TRUE);
		$this->load->model('library/base_head_model', NULL, TRUE);	
		
		$this->db->select("								
							a.ABSENT_POLICY_DTLS_ID,
							a.ABSENT_PARAMETER_TYPE,
							a.ABSENT_PERSENT_FORMULA,
							a.ABSENT_BASE_HEAD,
							a.ABSENT_AMOUNT,
							m.ABSENT_POLICY_MST_ID,							
							m.ABSENT_POLICY_NAME,
							m.ABSENT_DEDUCTION_HEAD,
							m.ABSENT_POLICY_STATUS,
							b.BASE_SYSTEM_HEAD	
							
							 ");
							 
			$this->db->from('hrm_absent_leave_dtls AS a');			
			$this->db->where("a.ABSENT_POLICY_DTLS_ID > ",0);
			$this->db->where("a.IS_DELETED = ",0);
			$this->db->join("hrm_absent_leave_mst AS m", "m.ABSENT_POLICY_MST_ID = a.ABSENT_POLICY_MST_ID","left");	
			$this->db->where("m.IS_DELETED = ",0);
			$this->db->join("lib_base_head_info AS b", "b.BASE_HEAD_ID = a.ABSENT_BASE_HEAD", "left");			
			$this->db->order_by("a.ABSENT_POLICY_MST_ID", "ASC");
			$this->db->distinct("a.ABSENT_POLICY_DTLS_ID");	
			
		$records =	$this->absent_leave_dtls_model->find_all();		
		 
		echo $this->load->view('policy/absent_policy_list', array('records' => $records), TRUE);
	}
	
	
	
	
	//=====show absent policy information for editing 
	public function editAbsentPolicyAjax()
	{	
		$ABSENT_POLICY_MST_ID        = (int)$this->input->post('ABSENT_POLICY_MST_ID');		
		
		$this->load->model('absent_leave_mst_model', NULL, TRUE);
		$this->load->model('absent_leave_dtls_model', NULL, TRUE);
		$this->load->model('library/base_head_model', NULL, TRUE);
		
		if($ABSENT_POLICY_MST_ID > 0 && !$mstAbsentData =	$this->absent_leave_mst_model->find($ABSENT_POLICY_MST_ID))
		{	
			return false;
		}
	
		$this->db->select("								
						a.ABSENT_POLICY_DTLS_ID,
						a.ABSENT_PARAMETER_TYPE,
						a.ABSENT_PERSENT_FORMULA,
						a.ABSENT_BASE_HEAD,
						a.ABSENT_AMOUNT,
						m.ABSENT_POLICY_MST_ID,							
						m.ABSENT_POLICY_NAME,
						m.ABSENT_DEDUCTION_HEAD,
						m.ABSENT_POLICY_STATUS,
						b.BASE_SYSTEM_HEAD
						 ");
						 
		$this->db->from('hrm_absent_leave_mst AS m');			
		$this->db->where("m.ABSENT_POLICY_MST_ID",$ABSENT_POLICY_MST_ID);		
		$this->db->join("hrm_absent_leave_dtls AS a", "m.ABSENT_POLICY_MST_ID = a.ABSENT_POLICY_MST_ID","left");	
		$this->db->join("lib_base_head_info AS b", "b.BASE_HEAD_ID = a.ABSENT_BASE_HEAD", "left");		
		$this->db->where("m.IS_DELETED = ",0);
		$this->db->where("a.IS_DELETED = ",0);
		$query = $this->db->get();
		$result = $query->result_array();
		
		$policyName 	     	= $mstAbsentData->ABSENT_POLICY_NAME;
		$deduction	     		= $mstAbsentData->ABSENT_DEDUCTION_HEAD;	
		$status			   		= $mstAbsentData->ABSENT_POLICY_STATUS;	
		$status			   		= $mstAbsentData->ABSENT_POLICY_STATUS;			
				
		$returnData = array();
		$returnData['evalData'] = "
			$('#absent_policy_name').val('$policyName');
			$('#absent_deduction_head').val('$deduction');
			$('#absent_policy_status').val('$status');
			$('#ABSENT_POLICY_MST_ID').val('$ABSENT_POLICY_MST_ID ');
		";	
		
		$this->db->select("								
							h.BASE_HEAD_ID,
							h.BASE_SYSTEM_HEAD							
								
							 ");
							 
		$this->db->from('lib_base_head_info AS h');			
		$this->db->where("h.BASE_HEAD_TYPE = ",1);
		$this->db->where("h.IS_DELETED = ",0);	
		$this->db->where("h.STATUS = ",1);			
		$this->db->distinct("h.BASE_HEAD_ID");				
			
		$baseHead 	= $this->base_head_model->find_all();
		
		
		
		$detailsHtml = "";
		foreach($result as $row){
			$data = array();	
			$data['parameter_type'] 			= $this->config->item('parameter_type');
			$data['baseHead'] 					= $baseHead;
			$data['result'] 					= $row;
			$data['removeRow'] 					= $detailsHtml == "" ? false : true;
			
			$detailsHtml .= $this->load->view('policy/absent_policy_details', $data, true);
		}
		
		$returnData['detailsHtml'] = $detailsHtml;
		
		echo json_encode($returnData);
		exit;	
	}
	
	
//========End Policy Absent ========//		
	

	
	
// Nasir	
//========Start Policy Maternity ========//	
    public function maternity_policy()
    {	
		$this->auth->restrict('HRM.Policy.View');
		$this->auth->restrict('HRM.Policy.Create');	
		
		$POLIVY_ID = (int)$this->uri->segment(5);	
		if (isset($_POST['save']))
		{									
			redirect(SITE_AREA .'/policy/hrm/policy_tab/'.$POLIVY_ID.'/leave_policy');				
				
		}		
		
		
			
		$data = array();
		$data['parameter_calculation'] 			= $this->config->item('parameter_calculation');
		$data['parameter_disburse'] 			= $this->config->item('parameter_disburse');		
		
        echo $this->load->view('policy/maternity_leave',$data,TRUE);
		echo $this->showMaternityInfo();
    }		
		
		
	// Insert Maternity Info  Data
	public function addMaternityPolicyAjax()
	{		
		$this->load->model('maternity_leave_model', NULL, TRUE);
		
		$MATERNITY_LEAVE_ID = $this->input->post('MATERNITY_LEAVE_ID');
		
		$data = array();		
		$data['MATERNITY_POLICY_NAME']				= $this->input->post('maternity_policy_name');
		$data['MAX_DAY_LIMIT']						= $this->input->post('maternity_max_day_limit');
		$data['MIN_SERVICE_LENGTH'] 				= ($this->input->post('maternity_mini_service')) ? $this->input->post('maternity_mini_service') : 0;	
		$data['LEAVE_BEFORE_DELIVERY'] 				= ($this->input->post('maternity_leave_before')) ? $this->input->post('maternity_leave_before') : 0;	
		$data['LEAVE_AFTER_DELIVERY'] 				= ($this->input->post('maternity_leave_after') ? $this->input->post('maternity_leave_after') : 0);		
		$data['PAYMENT_CALCULATION'] 				= ($this->input->post('maternity_payment_calculation')) ? $this->input->post('maternity_payment_calculation') : 0;
		$data['PAYMENT_DISBURSEMENT'] 				= ($this->input->post('maternity_payment_disburse')) ? $this->input->post('maternity_payment_disburse') : 0;
		$data['MATERNITY_STATUS'] 					= $this->input->post('maternity_policy_status');	
			
			if($MATERNITY_LEAVE_ID > 0)
			{
				$this->auth->restrict('HRM.Policy.Edit');				
				$data['MODIFY_BY']				= $this->current_user->id;
				$data['MODIFY_DATE']			= date('Y-m-d H:i:s');
				$this->maternity_leave_model->update($MATERNITY_LEAVE_ID,$data);
			}else
			{									
				$this->auth->restrict('HRM.Policy.Create');
				$data['CREATED_BY']				= $this->current_user->id;					
				$this->maternity_leave_model->insert($data);				
			} 			
			$this->showMaternityInfo();												
	}

	
	
	//Show Maternity Info  Data
	public function showMaternityInfo()
	{		
		$this->load->model('maternity_leave_model', NULL, TRUE);
		$this->db->where("IS_DELETED = ",0);	
		$records =	$this->maternity_leave_model->find_all();			
		echo $this->load->view('policy/maternity_policy_list', array('records' => $records), TRUE);
	}
	
	
	
	//Delete Maternity Info  Data
	public function deleteMaternityPolicyAjax()
	{			
		$this->load->model('maternity_leave_model', NULL, TRUE);
		
		$MATERNITY_LEAVE_ID 		= $this->input->post('MATERNITY_LEAVE_ID');		

		if($MATERNITY_LEAVE_ID > 0)
		{	$data = array();			
			$data['IS_DELETED']				= 1;	
			$data['DELETED_BY']				= $this->current_user->id;	
			$data['DELETED_DATE']			= date('Y-m-d H:i:s');	
			$this->maternity_leave_model->update($MATERNITY_LEAVE_ID,$data);		
		}
		
		$this->showMaternityInfo();	
	}
	
	
	
	
	//=====show maternity policy information for editing 
	public function editMaternityPolicyAjax()
	{	
		$MATERNITY_LEAVE_ID        = (int)$this->input->post('MATERNITY_LEAVE_ID');
	
		if($MATERNITY_LEAVE_ID > 0)
		{	$this->load->model('maternity_leave_model', NULL, TRUE);
			
			$maternityData 	 	 = $this->maternity_leave_model->find($MATERNITY_LEAVE_ID);	
			
			$name 	     		 = $maternityData->MATERNITY_POLICY_NAME;
			$maxLimitDay 	     = $maternityData->MAX_DAY_LIMIT;		
			$miniServiceLength	 = $maternityData->MIN_SERVICE_LENGTH;
			$leaveBefore 		 = $maternityData->LEAVE_BEFORE_DELIVERY;
			$leaveAfter     	 = $maternityData->LEAVE_AFTER_DELIVERY;
			$paymentCalculation  = $maternityData->PAYMENT_CALCULATION;
			$paymentDisburse   	 = $maternityData->PAYMENT_DISBURSEMENT;
			$status			   	 = $maternityData->MATERNITY_STATUS;
			$id				   	 = $maternityData->MATERNITY_LEAVE_ID;		
	
			
		    echo "
				$('#maternity_policy_name').val('$name');
				$('#maternity_max_day_limit').val('$maxLimitDay');
				$('#maternity_mini_service').val('$miniServiceLength');
				$('#maternity_leave_before').val('$leaveBefore');
				$('#maternity_leave_after').val('$leaveAfter');
				$('#maternity_payment_calculation').val('$paymentCalculation');
				$('#maternity_payment_disburse').val('$paymentDisburse');	
				$('#maternity_policy_status').val('$status');
				$('#MATERNITY_LEAVE_ID').val('$id');	
			";		
		}
				
	}	
	
	//========End Policy Maternity ========//
	
	
	

	
	//didar ( add by nasir 26.10.15) 	
	//========Start Medical Policy ========//	
    public function medical_policy()
    {	
		$this->load->model('library/base_head_model', NULL, TRUE);		
		$this->auth->restrict('HRM.Policy.View');
		$this->auth->restrict('HRM.Policy.Create');		
			
		$this->db->select("								
							h.BASE_HEAD_ID,
							h.BASE_SYSTEM_HEAD							
								
							 ");
							 
		$this->db->from('lib_base_head_info AS h');			
		$this->db->where("h.BASE_HEAD_TYPE = ",1);
		$this->db->where("h.IS_DELETED = ",0);	
		$this->db->where("h.STATUS = ",1);			
		$this->db->distinct("h.BASE_HEAD_ID");				
			
		$base_heade_list 	= $this->base_head_model->find_all();
		
		$data = array();
		$data['base_heade_list'] 			= $base_heade_list;
		$data['amount_type'] 				= $this->config->item('amount_type');		
		$data['status'] 					= $this->config->item('status');

		echo $this->load->view('policy/medical_policy_form',$data,TRUE);
		echo $this->showMedicalInfo();
    }
	
	
	//====== Ajax Function of  Medical policy information ====//

	public function medicalPolicyInfoAjax()
	{							
		
		$this->load->model('medical_policy_master_model', NULL, TRUE);
		$this->load->model('medical_policy_details_model', NULL, TRUE);
		
		$MEDICAL_POLICY_MASTER_ID 	= $this->input->post('MEDICAL_POLICY_MASTER_ID');
		$MEDICAL_POLICY_DETAILS_ID 	= $this->input->post('MEDICAL_POLICY_DETAILS_ID');
		
		$datamst = array();	
		
		$datamst['NAME']				= $this->input->post('NAME');
		$datamst['DESCRIPTION']			= $this->input->post('MPDESCRIPTION');
		$datamst['STATUS'] 				= $this->input->post('MPSTATUS');	
		
				
		if($MEDICAL_POLICY_MASTER_ID > 0)
		{
			$this->auth->restrict('HRM.Policy.Edit');				
			$datamst['MODIFY_BY']			= $this->current_user->id;
			$datamst['MODIFY_DATE_TIME']	= date('Y-m-d H:i:s');
			$this->medical_policy_master_model->update($MEDICAL_POLICY_MASTER_ID,$datamst);
			$this->db->delete('bf_hrm_ls_medical_policy_details', array('MEDICAL_POLICY_MASTER_ID' => $MEDICAL_POLICY_MASTER_ID)); 
			
			$masterId = $MEDICAL_POLICY_MASTER_ID;
			
		}else
		{									
			$this->auth->restrict('HRM.Policy.Create');
			
			$datamst['CREATED_BY']				= $this->current_user->id;					
			$masterId = $this->medical_policy_master_model->insert($datamst);				
			
		} 
		
		$MEDICAL_POLICY_MASTER_ID		= $masterId;
		$BASE_HEAD 						= $this->input->post('BASE_HEAD');		
		$AMOUNT_TYPE					= $this->input->post('AMOUNT_TYPE');
		$AMOUNT							= $this->input->post('AMOUNT');				
		$CREATED_BY						= $this->current_user->id;

					 								
		$this->auth->restrict('HRM.Policy.Create');
		
		$get_length=count($AMOUNT_TYPE);
		if($MEDICAL_POLICY_MASTER_ID == 0 || $get_length == 0)
		{
			return false;
		}
		
		$insertData = array();		
		for($i=0; $i<$get_length; $i++)
		{			
			$insertData[] = array(
				'MEDICAL_POLICY_MASTER_ID' => $MEDICAL_POLICY_MASTER_ID,	
				'AMOUNT_TYPE' => $AMOUNT_TYPE[$i],	
				'AMOUNT' => $AMOUNT[$i],
				'BASE_HEAD' => $BASE_HEAD[$i],				
				'CREATED_BY' => $CREATED_BY
			);
		}
		
		$this->db->insert_batch('bf_hrm_ls_medical_policy_details', $insertData); 
						
		$this->showMedicalInfo();		
										
	}
	
	
	//Show Medical Info  Data
	public function showMedicalInfo()
	{		
		$this->load->model('medical_policy_details_model', NULL, TRUE);	
		$this->load->model('library/base_head_model', NULL, TRUE);	
		
		$this->db->select("								
							md.MEDICAL_POLICY_DETAILS_ID,
							md.BASE_HEAD,		
							md.AMOUNT_TYPE,
							md.AMOUNT,

							mm.NAME,
							mm.DESCRIPTION,
							mm.STATUS,
							mm.MEDICAL_POLICY_MASTER_ID,
							b.BASE_SYSTEM_HEAD	
							
							 ");
							 
			$this->db->from('bf_hrm_ls_medical_policy_details AS md');			
			$this->db->where("md.MEDICAL_POLICY_DETAILS_ID > ",0);
			$this->db->where("md.IS_DELETED = ",0);
			$this->db->join("bf_hrm_ls_medical_policy_master AS mm", "mm.MEDICAL_POLICY_MASTER_ID = md.MEDICAL_POLICY_MASTER_ID","left");	
			$this->db->where("mm.IS_DELETED = ",0);
			$this->db->join("lib_base_head_info AS b", "b.BASE_HEAD_ID = md.BASE_HEAD", "left");
			$this->db->order_by("md.MEDICAL_POLICY_DETAILS_ID", "ASC");
			$this->db->distinct("md.MEDICAL_POLICY_DETAILS_ID");	
			
		$records =	$this->medical_policy_details_model->find_all();	
		 
		echo $this->load->view('policy/medical_policy_list', array('records' => $records), TRUE);
	}
	
	
	
	//=====show medical policy information for editing 
	public function editMedicalPolicyAjax()
	{	
		$MEDICAL_POLICY_MASTER_ID        = (int)$this->input->post('MEDICAL_POLICY_MASTER_ID');			
				
		$this->load->model('medical_policy_master_model', NULL, TRUE);
		$this->load->model('medical_policy_details_model', NULL, TRUE);
		$this->load->model('library/base_head_model', NULL, TRUE);
		
		if($MEDICAL_POLICY_MASTER_ID > 0 && !$mstMedicalData =	$this->medical_policy_master_model->find($MEDICAL_POLICY_MASTER_ID))
		{	
			return false;
		}
	
		$this->db->select("								
							md.MEDICAL_POLICY_DETAILS_ID,
							md.BASE_HEAD,		
							md.AMOUNT_TYPE,
							md.AMOUNT,
							mm.NAME,
							mm.DESCRIPTION,
							mm.STATUS,
							mm.MEDICAL_POLICY_MASTER_ID,
							b.BASE_SYSTEM_HEAD	
						 ");
						 
		$this->db->from('hrm_ls_medical_policy_master AS mm');			
		$this->db->where("mm.MEDICAL_POLICY_MASTER_ID",$MEDICAL_POLICY_MASTER_ID);		
		$this->db->join("hrm_ls_medical_policy_details AS md", "mm.MEDICAL_POLICY_MASTER_ID = md.MEDICAL_POLICY_MASTER_ID","left");	
		$this->db->join("lib_base_head_info AS b", "b.BASE_HEAD_ID = md.BASE_HEAD", "left");
		$this->db->where("mm.IS_DELETED = ",0);
		$this->db->where("md.IS_DELETED = ",0);		
		
		$query  = $this->db->get();
		$result = $query->result_array();
				
		$policyName 	     	= $mstMedicalData->NAME;
		$DESCRIPTION	     	= $mstMedicalData->DESCRIPTION;	
		$status			   		= $mstMedicalData->STATUS;
		
					
				
		$returnData = array();
		
		$returnData['evalData'] = "
			$('#NAME').val('$policyName');
			$('#MPDESCRIPTION').val('$DESCRIPTION');
			$('#MPSTATUS').val('$status');				
			$('#MEDICAL_POLICY_MASTER_ID').val('$MEDICAL_POLICY_MASTER_ID ');			
		";	
		
		$this->db->select("								
							h.BASE_HEAD_ID,
							h.BASE_SYSTEM_HEAD															
							 ");
							 
		$this->db->from('lib_base_head_info AS h');			
		$this->db->where("h.BASE_HEAD_TYPE = ",1);
		$this->db->where("h.IS_DELETED = ",0);	
		$this->db->where("h.STATUS = ",1);			
		$this->db->distinct("h.BASE_HEAD_ID");				
			
		$baseHead 	= $this->base_head_model->find_all();
		
		
		
		$detailsHtml = "";
		
		foreach($result as $row){
			$data = array();		
			$data['base_heade_list'] 			= $baseHead;
			$data['amount_type'] 				= $this->config->item('amount_type');				
			$data['result'] 					= $row;
			$data['removeRow'] 					= $detailsHtml == "" ? false : true;
		
			$detailsHtml .= $this->load->view('policy/medical_details_form', $data, true);
		}
		 
		$returnData['detailsHtml'] = $detailsHtml;
		
		echo json_encode($returnData);
		
		exit;	
	}
	
	
	//Delete Medical Info  Data
	public function deleteMedicalPolicyAjax()
	{			
		$this->load->model('medical_policy_details_model', NULL, TRUE);
		
		$this->auth->restrict('HRM.Policy.Edit');
		
		$MEDICAL_POLICY_DETAILS_ID 		= $this->input->post('MEDICAL_POLICY_DETAILS_ID');		

		if($MEDICAL_POLICY_DETAILS_ID > 0)
		{			
			$data = array();			
			$data['IS_DELETED']				= 1;	
			$data['DELETED_BY']				= $this->current_user->id;	
			$data['DELETED_DATE_TIME']		= date('Y-m-d H:i:s');
			
			$this->medical_policy_details_model->update($MEDICAL_POLICY_DETAILS_ID,$data);	
		}
		
		$this->showMedicalInfo();
	}


	// checking Policy Name exists or not
	public function checkMedicalPolicyNameAjax()
	{	
	
		$policyName	= $this->input->post('NAME'); 
			
		if($policyName!= '')
		{			
			$res =$this->db->query("SELECT NAME FROM bf_hrm_ls_medical_policy_master WHERE  NAME  LIKE '%$policyName%'");	
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'The Policy Name is Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}
	
	
//========End Policy Medical ========//	
	


	//didar ( add by nasir 25.10.15) 
	//========Start Policy Shift ========//	
    public function shift_policy()
    {	
		$this->auth->restrict('HRM.Policy.View');
		
		$data = array();
		//$data['shift_details']  	= $shift_details;
		$data['shift_types'] 		= $this->config->item('shift_types');
		$data['status'] 			= $this->config->item('status');
		echo $this->load->view('policy/shift_policy_form',$data,TRUE);
		echo $this->showShiftPolicyInfo();

    }

    //Show Shift Policy Info  Data
	public function showShiftPolicyInfo()
	{		
		$this->load->model('policy_shift_model', NULL, TRUE);
		$this->db->where("IS_DELETED = ",0);	
		$records =	$this->policy_shift_model->find_all();			
		echo $this->load->view('policy/shift_policy_list', array('records' => $records), TRUE);
	}
	
	
	
	//====== Ajax Function of Employee Family information ====//	
	public function shiftPolicyInfoAjax()
	{		
		$this->load->model('policy_shift_model', NULL, TRUE);
						
		$data = array();
		
		$data['SHIFT_NAME']					= $this->input->post('SHIFT_NAME');
		$data['DESCRIPTION'] 				= $this->input->post('DESCRIPTION');	
		$data['SHIFT_TYPE'] 				= $this->input->post('SHIFT_TYPE');
		$data['SHIFT_STARTS'] 				= $this->input->post('SHIFT_STARTS');	
		$data['SHIFT_ENDS'] 				= $this->input->post('SHIFT_ENDS');
		$data['LATE_MARKING_STARTS'] 		= $this->input->post('LATE_MARKING_STARTS');	
		$data['EXIT_BUFFER_TIME'] 			= $this->input->post('EXIT_BUFFER_TIME');
		$data['LUNCH_BREAK_STARTS'] 		= ($this->input->post('LUNCH_BREAK_STARTS')) ? $this->input->post('LUNCH_BREAK_STARTS') : "";
		$data['LUNCH_BREAK_ENDS'] 			= ($this->input->post('LUNCH_BREAK_ENDS')) ? $this->input->post('LUNCH_BREAK_ENDS'):  "";	
		$data['EXTRA_BREAK_STARTS'] 		= ($this->input->post('EXTRA_BREAK_STARTS')) ? $this->input->post('EXTRA_BREAK_STARTS') : "";		
		$data['EXTRA_BREAK_ENDS'] 			= ($this->input->post('EXTRA_BREAK_ENDS')) ? $this->input->post('EXTRA_BREAK_ENDS') : "";
		$data['EARLY_OUT_STARTS'] 			= $this->input->post('EARLY_OUT_STARTS');	
		$data['ENTRY_RESTRICTION_STARTS'] 	= $this->input->post('ENTRY_RESTRICTION_STARTS');
		$data['STATUS'] 					= $this->input->post('STATUS');
		
		$SHIFT_POLICY_ID_TARGET				= $this->input->post('SHIFT_POLICY_ID_TARGET'); 
		
		$shiftType							= $this->input->post('SHIFT_TYPE'); 
		
		if($shiftType){
			
			if($SHIFT_POLICY_ID_TARGET>0)
			{
				$this->auth->restrict('HRM.Shift_Policy.Edit');	
				
				$data['MODIFY_BY']					= $this->current_user->id;	
				$data['RECORD_MODIFY_DATE_TIME']    = date('Y-m-d H:i:s');
				
				$this->policy_shift_model->update($SHIFT_POLICY_ID_TARGET,$data);	
				
			} else
			{
				// insert new data 
				date_default_timezone_set('Asia/Dhaka');
				$this->auth->restrict('HRM.Shift_Policy.Create');	
				
				$data['CREATED_BY']		= $this->current_user->id;					
				$SHIFT_NAME 			= $this->input->post('SHIFT_NAME');
				$shiftType 				= $this->input->post('SHIFT_TYPE');  
				
				$res =$this->db->query("SELECT SHIFT_POLICY_ID FROM bf_hrm_ls_shift_policy WHERE  SHIFT_NAME  LIKE '%$SHIFT_NAME%' and SHIFT_TYPE LIKE '%$shiftType%'");	
				$result = $res->num_rows();
				if($result == 0 )
				{				
					$this->policy_shift_model->insert($data);	
				}	
			}
			
		}	

		$this->showShiftPolicyInfo();			
							
	}
	
	
	
	//=====show for edit Shift Policy
	public function getShiftpolicyInfoAjax()
	{		
		if($shift_policy_id_edit      = (int)$this->input->post('shift_policy_id_edit'))
		{
				$this->load->model('policy_shift_model', NULL, TRUE);
			$shiftPolicyData 	 		= $this->policy_shift_model->find($shift_policy_id_edit);

			
			$SHIFT_NAME 	     		= $shiftPolicyData->SHIFT_NAME;
			$DESCRIPTION 	     		= $shiftPolicyData->DESCRIPTION;
			$SHIFT_TYPE 	     		= $shiftPolicyData->SHIFT_TYPE;
			$SHIFT_STARTS 	     		= $shiftPolicyData->SHIFT_STARTS;
			$SHIFT_ENDS  		 		= $shiftPolicyData->SHIFT_ENDS;
			$LATE_MARKING_STARTS    	= $shiftPolicyData->LATE_MARKING_STARTS;
			$EXIT_BUFFER_TIME 	    	= $shiftPolicyData->EXIT_BUFFER_TIME;
			$LUNCH_BREAK_STARTS 		= $shiftPolicyData->LUNCH_BREAK_STARTS;
			$LUNCH_BREAK_ENDS  			= $shiftPolicyData->LUNCH_BREAK_ENDS;
			$EXTRA_BREAK_STARTS     	= $shiftPolicyData->EXTRA_BREAK_STARTS;
			$EXTRA_BREAK_ENDS 	    	= $shiftPolicyData->EXTRA_BREAK_ENDS;
			$EARLY_OUT_STARTS 			= $shiftPolicyData->EARLY_OUT_STARTS;
			$ENTRY_RESTRICTION_STARTS  	= $shiftPolicyData->ENTRY_RESTRICTION_STARTS;
			$STATUS     				= $shiftPolicyData->STATUS;
			
			
		    echo "
				$('#SHIFT_NAME').val('$SHIFT_NAME');	
				$('#DESCRIPTION').val('$DESCRIPTION ');	
				$('#SHIFT_TYPE').val('$SHIFT_TYPE');	
				$('#SHIFT_STARTS').val('$SHIFT_STARTS');	
				$('#SHIFT_ENDS').val('$SHIFT_ENDS');		
				$('#LATE_MARKING_STARTS').val('$LATE_MARKING_STARTS');		
				$('#EXIT_BUFFER_TIME').val('$EXIT_BUFFER_TIME');		
				$('#LUNCH_BREAK_STARTS').val('$LUNCH_BREAK_STARTS');	
				$('#LUNCH_BREAK_ENDS').val('$LUNCH_BREAK_ENDS');	
				$('#EXTRA_BREAK_STARTS').val('$EXTRA_BREAK_STARTS');	
				$('#EXTRA_BREAK_ENDS').val('$EXTRA_BREAK_ENDS');	
				$('#EARLY_OUT_STARTS').val('$EARLY_OUT_STARTS');		
				$('#ENTRY_RESTRICTION_STARTS').val('$ENTRY_RESTRICTION_STARTS');
				$('#STATUS').val('$STATUS');	
				$('#SHIFT_POLICY_ID_TARGET').val('$shift_policy_id_edit');
			";		
		}
				
	}
	
	

	//Delete Shift Policy Data
	public function deleteShiftPolicy()
	{	
		$this->load->model('policy_shift_model', NULL, TRUE);
		
		$SHIFT_POLICY_ID		= $this->input->post('SHIFT_POLICY_ID');		

		if($SHIFT_POLICY_ID > 0)
		{	$data = array();			
			$data['IS_DELETED']				= 1;	
			$data['DELETED_BY']				= $this->current_user->id;	
			$data['DELETED_DATE']			= date('Y-m-d H:i:s');	
			$this->policy_shift_model->update($SHIFT_POLICY_ID, $data);		
		}
		$this->showShiftPolicyInfo();
	}
	
	// ==================== end shift Policy info =========================//
	
	

	
	
	
	
//didar ( add by nasir 21.10.15) 
//========Start bonus Policy  ========//	
	public function bonus_policy()
    {	
		$this->load->model('library/base_head_model', NULL, TRUE);		
		$this->auth->restrict('HRM.Policy.View');
		$this->auth->restrict('HRM.Policy.Create');		
			
		$this->db->select("								
							h.BASE_HEAD_ID,
							h.BASE_SYSTEM_HEAD															
							 ");
							 
		$this->db->from('lib_base_head_info AS h');			
		$this->db->where("h.BASE_HEAD_TYPE = ",1);
		$this->db->where("h.IS_DELETED = ",0);	
		$this->db->where("h.STATUS = ",1);			
		$this->db->distinct("h.BASE_HEAD_ID");				
			
		$base_heade_list 	= $this->base_head_model->find_all();
		
		$data = array();
		$data['base_heade_list'] 			= $base_heade_list;
		$data['confirmation_status'] 		= $this->config->item('confirmation_status');
		$data['amount_type'] 				= $this->config->item('amount_type');		
		$data['status'] 					= $this->config->item('status');
        echo $this->load->view('policy/bonus_policy_form',$data,TRUE);
		echo $this->showBonusInfo();
    }


    //====== Ajax Function of  Bonus policy information ====//

	public function bonusPolicyInfoAjax()
	{							
			
		$this->load->model('policy_bonus_master_model', NULL, TRUE);
		$this->load->model('policy_bonus_details_model', NULL, TRUE);
		
		$BONUS_POLICY_MST_ID 	= $this->input->post('BONUS_POLICY_MST_ID');
		$BONUS_POLICY_DETAILS_ID 	= $this->input->post('BONUS_POLICY_DETAILS_ID');
		
		$datamst = array();		
		$datamst['NAME']				= $this->input->post('BONUS_NAME');
		$datamst['DESCRIPTION']			= $this->input->post('DESCRIPTIONS');
		$datamst['STATUS'] 				= $this->input->post('BPSTATUS');	
		
				
		if($BONUS_POLICY_MST_ID > 0)
		{
			$this->auth->restrict('HRM.Policy.Edit');				
			$datamst['MODIFY_BY']			= $this->current_user->id;
			$datamst['MODIFY_DATE_TIME']	= date('Y-m-d H:i:s');
			$this->policy_bonus_master_model->update($BONUS_POLICY_MST_ID,$datamst);
			$this->db->delete('bf_hrm_ls_bonus_policy_details', array('BONUS_POLICY_MST_ID' => $BONUS_POLICY_MST_ID)); 
			
			$masterId = $BONUS_POLICY_MST_ID;
			
		}else
		{									
			$this->auth->restrict('HRM.Policy.Create');
			
			$datamst['CREATED_BY']				= $this->current_user->id;					
			$masterId = $this->policy_bonus_master_model->insert($datamst);				
			
		} 
		
		$BONUS_POLICY_MST_ID			= $masterId;
		$CONFIRMATION_STATUS			= $this->input->post('CONFIRMATION_STATUS');		
		$WORKING_DAYS					= $this->input->post('WORKING_DAYS');
		$AMOUNT_TYPE					= $this->input->post('AMOUNT_TYPE');
		$AMOUNT							= $this->input->post('AMOUNT');	
		$BASE_HEAD 						= $this->input->post('BASE_HEAD');		
		$CREATED_BY						= $this->current_user->id;

					 								
		$this->auth->restrict('HRM.Policy.Create');
		
		$get_length=count($CONFIRMATION_STATUS);
		if($BONUS_POLICY_MST_ID == 0 || $get_length == 0)
		{
			return false;
		}
		
		$insertData = array();		
		for($i=0; $i<$get_length; $i++)
		{			
			$insertData[] = array(
				'BONUS_POLICY_MST_ID' => $BONUS_POLICY_MST_ID,	
				'CONFIRMATION_STATUS' => $CONFIRMATION_STATUS[$i],	
				'WORKING_DAYS_MORE_THAN' => $WORKING_DAYS[$i],	
				'AMOUNT_TYPE' => $AMOUNT_TYPE[$i],	
				'AMOUNT' => $AMOUNT[$i],
				'BASE_HEAD' => $BASE_HEAD[$i],				
				'CREATED_BY' => $CREATED_BY
			);
		}	
		
		$this->db->insert_batch('bf_hrm_ls_bonus_policy_details', $insertData); 
						
		$this->showBonusInfo();		
											
	}
	
	
	// Check Bonus Policy Name
	public function CheckBonusPolicyNameAjax()
	{	
	
		$BONUS_NAME	= $this->input->post('BONUS_NAME'); 
			
		if(trim($BONUS_NAME)!= '')
		{			
			$res =$this->db->query("SELECT NAME FROM bf_hrm_is_bonus_policy_master WHERE  NAME  LIKE '%$BONUS_NAME%'");	
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'The'. $BONUS_NAME . ' Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}
	
	//Delete Bonus Info  Data
	public function deleteBonusPolicyAjax()
	{			
		$this->load->model('policy_bonus_details_model', NULL, TRUE);
		
		$this->auth->restrict('HRM.Policy.Edit');
		
		$BONUS_POLICY_DETAILS_ID 		= $this->input->post('BONUS_POLICY_DETAILS_ID');		

		if($BONUS_POLICY_DETAILS_ID > 0)
		{			
			$data = array();			
			$data['IS_DELETED']				= 1;	
			$data['DELETED_BY']				= $this->current_user->id;	
			$data['DELETED_DATE_TIME']		= date('Y-m-d H:i:s');	
			$this->policy_bonus_details_model->update($BONUS_POLICY_DETAILS_ID,$data);	
		}
		
		$this->showBonusInfo();	
	}
	
	//Show Bonus Info  Data
	public function showBonusInfo()
	{		
		$this->load->model('policy_bonus_details_model', NULL, TRUE);	
		$this->load->model('library/base_head_model', NULL, TRUE);	
		
		$this->db->select("								
							bd.BONUS_POLICY_DETAILS_ID,							
							bd.CONFIRMATION_STATUS,
							bd.WORKING_DAYS_MORE_THAN,
							bd.AMOUNT_TYPE,
							bd.AMOUNT,
							bd.BASE_HEAD,	
							bm.NAME,
							bm.DESCRIPTION,
							bm.STATUS,
							bm.BONUS_POLICY_MST_ID,
							b.BASE_SYSTEM_HEAD								
							 ");
							 
			$this->db->from('hrm_ls_bonus_policy_details AS bd');			
			$this->db->where("bd.BONUS_POLICY_DETAILS_ID > ",0);
			$this->db->where("bd.IS_DELETED = ",0);
			$this->db->join("hrm_is_bonus_policy_master AS bm", "bm.BONUS_POLICY_MST_ID = bd.BONUS_POLICY_MST_ID","left");	
			$this->db->where("bm.IS_DELETED = ",0);
			$this->db->join("lib_base_head_info AS b", "b.BASE_HEAD_ID = bd.BASE_HEAD", "left");
			$this->db->order_by("bd.BONUS_POLICY_DETAILS_ID", "ASC");
			$this->db->distinct("bd.BONUS_POLICY_DETAILS_ID");	
			
		$records =	$this->policy_bonus_details_model->find_all();	
		 
		echo $this->load->view('policy/bonus_policy_list', array('records' => $records), TRUE);
	}
	
	
	//=====show bonus policy information for editing 
	public function editBonusPolicyAjax()
	{	
		$BONUS_POLICY_MST_ID        = (int)$this->input->post('BONUS_POLICY_MST_ID');			
		
		
		
		$this->load->model('policy_bonus_master_model', NULL, TRUE);
		$this->load->model('policy_bonus_details_model', NULL, TRUE);
		$this->load->model('library/base_head_model', NULL, TRUE);
		
		if($BONUS_POLICY_MST_ID > 0 && !$mstBonusData =	$this->policy_bonus_master_model->find($BONUS_POLICY_MST_ID))
		{	
			return false;
		}
	
		$this->db->select("								
							bd.BONUS_POLICY_DETAILS_ID,							
							bd.CONFIRMATION_STATUS,
							bd.WORKING_DAYS_MORE_THAN,
							bd.AMOUNT_TYPE,
							bd.AMOUNT,
							bd.BASE_HEAD,	
							bm.NAME,
							bm.DESCRIPTION,
							bm.STATUS,
							bm.BONUS_POLICY_MST_ID,
							b.BASE_SYSTEM_HEAD	
						 ");
						 
		$this->db->from('hrm_is_bonus_policy_master AS bm');			
		$this->db->where("bm.BONUS_POLICY_MST_ID",$BONUS_POLICY_MST_ID);		
		$this->db->join("hrm_ls_bonus_policy_details AS bd", "bm.BONUS_POLICY_MST_ID = bd.BONUS_POLICY_MST_ID","left");	
		$this->db->join("lib_base_head_info AS b", "b.BASE_HEAD_ID = bd.BASE_HEAD", "left");
		$this->db->where("bm.IS_DELETED = ",0);
		$this->db->where("bd.IS_DELETED = ",0);		
		
		$query = $this->db->get();
		$result = $query->result_array();
				
		$policyName 	     	= $mstBonusData->NAME;
		$DESCRIPTION	     	= $mstBonusData->DESCRIPTION;	
		$status			   		= $mstBonusData->STATUS;
		
					
				
		$returnData = array();
		$returnData['evalData'] = "
			$('#BONUS_NAME').val('$policyName');
			$('#DESCRIPTIONS').val('$DESCRIPTION');
			$('#BPSTATUS').val('$status');				
			$('#BONUS_POLICY_MST_ID').val('$BONUS_POLICY_MST_ID ');			
		";	
		
		$this->db->select("								
							h.BASE_HEAD_ID,
							h.BASE_SYSTEM_HEAD							
								
							 ");
							 
		$this->db->from('lib_base_head_info AS h');			
		$this->db->where("h.BASE_HEAD_TYPE = ",1);
		$this->db->where("h.IS_DELETED = ",0);	
		$this->db->where("h.STATUS = ",1);			
		$this->db->distinct("h.BASE_HEAD_ID");				
			
		$baseHead 	= $this->base_head_model->find_all();
		
		
		
		$detailsHtml = "";
		foreach($result as $row){
			$data = array();		
			$data['confirmation_status'] 		= $this->config->item('confirmation_status');	
			$data['base_heade_list'] 			= $baseHead;
			$data['amount_type'] 				= $this->config->item('amount_type');				
			$data['result'] 					= $row;
			$data['removeRow'] 					= $detailsHtml == "" ? false : true;
			
			$detailsHtml .= $this->load->view('policy/bonus_policy_details_form', $data, true);
		}
		 
		$returnData['detailsHtml'] = $detailsHtml;
		
		echo json_encode($returnData);
		exit;	
	}


	/*         roster policy          */

	public function roster_policy()
	{
		$data = [];
		$roster_arr = getRosterByShift(1);
		$data["combination_roster"] = rosterCombination($roster_arr);
		echo $this->load->view('policy/roster/form',$data, TRUE);
		$this->showRosterInfo();
	}

	//====== Ajax Function of Employee Roster Policy ====//	
	public function addRosterPolicyAjax()
	{														
		$this->load->model('policy_roster_model', NULL, TRUE);
			
		$ROSTER_POLICY_MST_ID 	= $this->input->post('ROSTER_POLICY_MST_ID');
			
		$data = [];			
		$data['SHIFT_POLICY_NAME'] 					= $this->input->post('combi_policy_name');
		$data['ROSTER_POLICY_NAME'] 		 		= $this->input->post('roster_policy_name');
		$data['AFTER_CHANGE_DAY'] 		 		= $this->input->post('after_change_day');
		$data['STATUS'] 		 		= $this->input->post('roster_policy_status');
	
		if($ROSTER_POLICY_MST_ID > 0)
		{
			$this->auth->restrict('HRM.Policy.Edit');
			
			$data['MODIFY_BY']				= $this->current_user->id;
			$data['MODIFY_DATE_TIME']			= date('Y-m-d H:i:s');
			$this->policy_roster_model->update($ROSTER_POLICY_MST_ID,$data);
		}else
		{									
			$this->auth->restrict('HRM.Policy.Create');
			$data['CREATED_BY'] = $this->current_user->id;
			$this->policy_roster_model->insert($data);

			/*$acday_arr = explode(",",trim($data['AFTER_CHANGE_DAY']));
			$length = count($acday_arr);

			for($i=0; $i<$length; $i++) {
				if ($acday_arr[$i]) {
					$insertData[] = [
					'SHIFT_POLICY_NAME' => $data['SHIFT_POLICY_NAME'],
					'ROSTER_POLICY_NAME' => $data['ROSTER_POLICY_NAME'],
					'AFTER_CHANGE_DAY' => $acday_arr[$i],
					'STATUS' => $data['STATUS'],
					'CREATED_BY' => $this->current_user->id
				];
				}
			}
			$this->db->insert_batch('hrm_ls_roster_policy', $insertData);	
			*/
		}		
		$this->showRosterInfo();						
	}

	//Delete Roster Policy Data
	public function deleteRosterPolicy()
	{	
		$this->load->model('policy_roster_model', NULL, TRUE);
		$ROSTER_POLICY_ID		= $this->input->post('ROSTER_POLICY_ID');		

		if($ROSTER_POLICY_ID > 0)
		{	$data = array();			
			$data['IS_DELETED']				= 1;	
			$data['DELETED_BY']				= $this->current_user->id;	
			$data['DELETED_DATE']			= date('Y-m-d H:i:s');	
			$this->policy_roster_model->update($ROSTER_POLICY_ID, $data);		
		}
		$this->showRosterInfo();
	}


	public function showRosterInfo()
	{		
		$this->load->model('policy_roster_model', NULL, TRUE);

		$this->db->where("IS_DELETED = ",0);
		$records =	$this->policy_roster_model->find_all();
		echo $this->load->view('policy/roster/list', array('records' => $records), TRUE);
	}	

	//=====show for edit Roster Policy
	public function getRosterPolicyInfoAjax()
	{		
		if($ROSTER_POLICY_ID      = (int)$this->input->post('ROSTER_POLICY_ID'))
		{
			$this->load->model('policy_roster_model', NULL, TRUE);
			$data 	 		= $this->policy_roster_model->find($ROSTER_POLICY_ID);

			$SHIFT_POLICY_NAME 	    = $data->SHIFT_POLICY_NAME;
			$ROSTER_POLICY_NAME 	= $data->ROSTER_POLICY_NAME;
			$AFTER_CHANGE_DAY 	    = $data->AFTER_CHANGE_DAY;
			$STATUS 	     		= $data->STATUS;
		    echo "	
				$('#combi_policy_name').val('$SHIFT_POLICY_NAME');	
				$('#roster_policy_name').val('$ROSTER_POLICY_NAME');		
				$('#after_change_day').val('$AFTER_CHANGE_DAY');
				$('#roster_policy_status').val('$STATUS');	
				$('#ROSTER_POLICY_MST_ID').val('$ROSTER_POLICY_ID');
			";		
		}
				
	}		
}

