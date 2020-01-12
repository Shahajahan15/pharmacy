<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TRTArea controller
 */
class TRTArea extends Admin_Controller
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
		$this->load->model('trtarea_model', null, true);
		$this->lang->load('zone');	
		Assets::add_module_js('library','zone_area.js');

		Template::set_block('sub_nav', 'zone/_sub_nav_trtarea');
	}

		
	//--------------------------------------------------------------------
	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function show_list()
	{
		$this->auth->restrict('Lib.Zone.TrtArea.View');
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Zone.TrtArea.Delete');
			$result = FALSE;		
			$data = array();
			$data['is_deleted'] 		= 1; 
			//$data['deleted_by']			= $this->current_user->id;	
			//$data['deleted_date']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $area_id){
				
				$result = $this->trtarea_model->update($area_id,$data);				
			}
			
			if ($result){
				Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $area_id .' : '. $this->input->ip_address(), 'zone_trtarea');
			}else
			{
				Template::set_message(lang('library_delete_failure') . $this->trtarea_model->error, 'error');
			}
		}		
				
		$this->trtarea_model->select("
									trt.*,
									di.division_name,
									ds.district_name,									
									ar.area_name
								 ");
		$this->trtarea_model->from('zone_trtarea AS trt');	
		$this->db->where('trt.is_deleted',0);
		$this->db->join('zone_division as di', 'trt.division_no = di.division_id', 'left');
		$this->db->join('zone_district as ds', 'trt.district_no = ds.district_id', 'left');
		$this->db->join('zone_area as ar', 'trt.area_no = ar.area_id', 'left');
		$this->db->distinct('trt.trt_id');
		$records = $this->trtarea_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_zone_trt_view"));
		Template::set_view('zone/trtarea_list');
		Template::render();
	}

        
        
	//--------------------------------------------------------------------
	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	**/
	//--------------------------------------------------------------------
	
	public function trtarea_create()
    {

        //TODO you code here
		$this->load->model('district_model', null, true);
		$this->load->model('division_model', null, true); 	
		$this->load->model('area_model', null, true);
	
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->saveTRTArea())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'library_area');

				Template::set_message(lang('trtarea_create_success'), 'success');
				redirect(SITE_AREA .'/trtarea/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->trtarea_model->error, 'error');
			}
		}
		
		
		$district_list  				= $this->district_model->find_all_by('is_deleted', 0);
		$division_list                  = $this->division_model->find_all_by('is_deleted', 0);	
		$area_list  					= $this->area_model->find_all_by('is_deleted', 0);
		
		$sendData = array(
			'division_list' => $division_list,
			'district_list' => $district_list,
			'area_list' 	=> $area_list	
			
		);	
		
		Template::set('sendData', $sendData);	
		Template::set('toolbar_title', lang("library_zone_trt_new"));
		Template::set_view('zone/trtarea_create');
		Template::render();
    }



	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Group Details data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function edit()
	{
		$this->load->model('district_model', null, true);
		$this->load->model('division_model', null, true); 	
		$this->load->model('area_model', null, true);
		
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('group_details_invalid_id'), 'error');
			redirect(SITE_AREA .'/trtarea/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Zone.TrtArea.Edit');

			if ($this->saveTRTArea('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_area');

				Template::set_message(lang('trtarea_update_success'), 'success');
                redirect(SITE_AREA .'/trtarea/library/show_list');
			}
			else
			{
				Template::set_message(lang('area_details_edit_failure') . $this->trtarea_model->error, 'error');
			}
		}
		
		
		$district_list  				= $this->district_model->find_all_by('is_deleted', 0);
		$division_list                  = $this->division_model->find_all_by('is_deleted', 0);	
		$area_list  					= $this->area_model->find_all_by('is_deleted', 0);
		$area_details  					= $this->trtarea_model->find($id);	
			
		$sendData = array(
			'division_list' => $division_list,
			'district_list' => $district_list,
			'area_list' 	=> $area_list,
			'area_details' 	=> $area_details		
			
		);
		
		
		Template::set('sendData', $sendData);		
        Template::set('toolbar_title', lang('library_zone_trt_edit'));
		Template::set_view('zone/trtarea_create');
		Template::render();
	}
	
	

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	 
	private function saveTRTArea($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
        $data['division_no']   	= $this->input->post('library_zone_division_name');
        $data['district_no']   	= $this->input->post('library_zone_district_name');
		$data['area_no']  		= $this->input->post('library_zone_area_name');
		$data['trt_name']		= $this->input->post('library_zone_trt_name');
		$data['trt_status']		= $this->input->post('library_zone_trt_status');		

		if ($type == 'insert')
		{
			$data['created_by']		= $this->current_user->id;
			$res =$this->db->query("
								SELECT 
									trt_name
								FROM 
									bf_zone_trtarea
								WHERE 
									district_no='".$this->input->post('library_zone_district_name')."'
								AND 
									area_no='".$this->input->post('library_zone_area_name')."'
								AND 
									trt_name='".$this->input->post('library_zone_trt_name')."'
								
								");	
				
			$result = $res->num_rows();
			if($result > 0 )
			{
				Template::set_message(lang('record_create_failure'), 'error');	
				
			}else
			{
				$id = $this->trtarea_model->insert($data);

				if (is_numeric($id))
				{
					$return = $id;
				}else{
					$return = FALSE;
				}
				
			}
			
		}elseif ($type == 'update')
		{
			$this->auth->restrict('Lib.Zone.TrtArea.Edit');
			//$data['modify_by'] 		= $this->current_user->id;    
			//$data['modify_date'] 	= date('Y-m-d H:i:s'); 
			$return = $this->trtarea_model->update($id, $data);
		}

		return $return;
	}
	
	//--------------------------------------------------------------------
	
	
	//========= Get District Options List by Ajax Function =========
	public function getAreaListAjax(){	
		$groupDropDown = ""; $groupId = 0;
		$this->load->model('list_model', NULL, true);		
		$divisionId= $this->input->post('divisionId'); 
		$districtId= $this->input->post('districtId');		
		if(intval($divisionId) > 0 && intval($districtId) > 0){
		
		/**
		* Summary
		*
		* @param Int	$divisionId,	The division Id of the record to get all area under this division 
		* @param Int	$districtId,	The district Id of the record to get all area under this district 
		*
		* @return array  
		*/
	 
		$this->load->library('GetAreaListAjaxService');
		
		$GetAreaListAjaxService = new GetAreaListAjaxService($this);
		
		$area_list = $GetAreaListAjaxService->setDivisionNo($divisionId)
												->setDistrictNo($districtId)
												->execute();
										
						
			if(is_array($area_list)){				
			$options = array();
			foreach($area_list as $result){ $options[$result["area_id"]] = $result["area_name"]; }			
			$groupDropDown = $this->list_model->getDropdownOption($options,"library_zone_area_name");			
			}
		}		
		echo json_encode($groupDropDown);
	}
	
	//--------------------------------------------------------------------
	public function checkPostNameAjax()
	{	
	
		$districtName			= $this->input->post('districtName'); 
		$thanaName				= $this->input->post('thanaName'); 
		$postOfficeName			= $this->input->post('postOfficeName'); 
		
		if($thanaName!= '') 
		{			
			$res =$this->db->query("SELECT trt_name FROM bf_zone_trtarea WHERE  district_no='$districtName' AND area_no='$thanaName' AND trt_name='$postOfficeName' ");	
				
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo $result;
				
			}else
			{
				echo false;
			}			
			
		}	
	}	


	public function modal_create()
    {
    	
        //TODO you code here
		$this->load->model('district_model', null, true);
		$this->load->model('division_model', null, true);
		$this->load->model('area_model', null, true);



		$division=$this->input->post('division');
		$police_station=$this->input->post('police_station');
		$district_no=$this->input->post('district');


		if($_POST['from']==1){
			$position_id='#employee_permanent_post_office';
			$form_class='post-office-add-form';
			$list_post_office='permanent-post-office';
		}else{
			$position_id='#employee_mailing_post_office';
			$form_class='mailing-post-office-add-form';
			$list_post_office='mailing-post-office';
		}
		
	
		if (isset($_POST['save']))
		{
			
			if ($insert_id = $this->saveTRTArea())
			{

				$condition['is_deleted']=0;
				$condition['division_no']=$this->input->post('library_zone_division_name');
				$condition['area_no']=$this->input->post('library_zone_area_name');
				$condition['district_no']=$this->input->post('library_zone_district_name');	

				$area_list=$this->db
						->where($condition)
						->get('bf_zone_trtarea')
						->result();

				
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'library_area');
				
				$option='';
				foreach($area_list as $list){
					$option.='<option value="'.$list->trt_id.'">'.$list->trt_name.'</option>';
				}
				$all_area_list=$this->db->get('bf_zone_trtarea')->result();
				$all_option='';
				foreach($all_area_list as $alist){
					$all_option.='<option value="'.$alist->trt_id.'">'.$alist->trt_name.'</option>';
				}				
				echo json_encode(['options'=>$option,'all_options'=>$all_option,'insert_id'=>$insert_id,'position_id'=>$position_id]);
				exit;
				
			}
			else
			{
				
			}
		}
		$condition['is_deleted']=0;
		$condition['division_no']=$this->input->post('division');
		$condition['area_no']=$this->input->post('police_station');
		$condition['district_no']=$this->input->post('district');	

		$area_list=$this->db
						->where($condition)
						->get('bf_zone_trtarea')
						->result();
			
		
		if($this->input->is_ajax_request()){
			echo $this->load->view('zone/modal_trtarea_create',compact('area_list','division','police_station','district_no','form_class','list_post_office'),true);
			exit;
		}
    }

	
	
	
	
	
}