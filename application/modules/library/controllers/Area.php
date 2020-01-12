<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Area controller
 */
class Area extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	 //--------------------------------------------------------------------
	private $query_limit = 25;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('area_model', null, true);
		$this->lang->load('zone');
		Template::set_block('sub_nav', 'zone/_sub_nav_area');
		Assets::add_module_js('library','zone_area.js');

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
		$this->auth->restrict('Lib.Zone.Area.View');
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Zone.Area.Delete');

			$result = FALSE;
			$data = array();
			$data['is_deleted'] 		= 1;
			$data['deleted_by']			= $this->current_user->id;
			$data['deleted_date']    	= date('Y-m-d H:i:s');

            foreach ($checked as $area_id){

				$result = $this->area_model->update($area_id,$data);

			}

			if ($result)
			{
				Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $area_id .' : '. $this->input->ip_address(), 'zone_area');
			}else
			{
				Template::set_message(lang('library_delete_failure') . $this->area_model->error, 'error');
			}
		}
      	$this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Admission ID';
        $search_box['thana_name_flag'] = 1;
        $search_box['contact_no_flag'] = 0;
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['patient_id_flag']=0;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['patient_name_flag'] = 0;
        $condition['ar.area_status>=']=0;
            if(count($_POST)>0){

            if($this->input->post('thana_name')){
                $condition['ar.area_name']=$this->input->post('thana_name');
            }
        }
		$this->area_model->select("
        	                    SQL_CALC_FOUND_ROWS

									ar.*,
									di.division_name,
									ds.district_name
								 ",false);
		

		$this->area_model->from('zone_area AS ar');
		$this->db->where('ar.is_deleted',0);
		$this->db->where($condition);

		$this->db->join('zone_division as di', 'ar.division_no = di.division_id', 'left');
		$this->db->join('zone_district as ds', 'ar.district_no = ds.district_id', 'left');
		$this->db->distinct('ar.area_id');
		$this->db->limit( $limit, $offset);

		$records = $this->area_model->find_all();
        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/area/library/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('zone/area_list', compact('records','sl'), true);
            exit;
        }
        $list_view='zone/area_list';
        Template::set('list_view', $list_view);
		Template::set('records', $records);
		Template::set('toolbar_title', lang("library_zone_area_view"));
		Template::set('search_box', $search_box);
		Template::set_view('report_template');
		Template::render();
	}



	//--------------------------------------------------------------------
	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	**/
	//--------------------------------------------------------------------

	public function area_create()
    {
        //TODO you code here
        $this->load->model('district_model', null, true);
		$this->load->model('division_model', null, true);

		if (isset($_POST['save']))
		{
			if ($insert_id = $this->saveArea())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_zone_area');
				Template::set_message(lang('area_create_success'), 'success');
				redirect(SITE_AREA .'/area/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->area_model->error, 'error');
			}
		}

		$district_list  				= $this->district_model->find_all_by('is_deleted', 0);
		$division_list                  = $this->division_model->find_all_by('is_deleted', 0);

		Template::set('division_list', $division_list);
		Template::set('district_list', $district_list);
		Template::set('toolbar_title', lang("library_zone_area_new"));
		Template::set_view('zone/area_create');
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

		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('group_details_invalid_id'), 'error');
			redirect(SITE_AREA .'/area/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Zone.Area.Edit');

			if ($this->saveArea('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_area');

				Template::set_message(lang('area_update_success'), 'success');
                redirect(SITE_AREA .'/area/library/show_list');
			}
			else
			{
				Template::set_message(lang('area_details_edit_failure') . $this->area_model->error, 'error');
			}
		}


		$district_list  				= $this->district_model->find_all_by('is_deleted', 0);
		$division_list                  = $this->division_model->find_all_by('is_deleted', 0);

		Template::set('area_details', $this->area_model->find($id));

		Template::set('toolbar_title', lang('area_details_edit'));
		Template::set('division_list', $division_list);
		Template::set('district_list', $district_list);
		Template::set_view('zone/area_create');
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

	private function saveArea($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want

		$data = array();
        $data['division_no']   			= $this->input->post('library_zone_division_name');
        $data['district_no']   			= $this->input->post('library_zone_district_name');
		$data['area_name']  			= $this->input->post('library_zone_area_name');

		$data['area_status']			= $this->input->post('library_zone_area_status');

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Zone.Area.Create');

			$res =$this->db->query("
								SELECT
									area_name
								FROM
									bf_zone_area
								WHERE
								district_no = '".$this->input->post('library_zone_district_name')."'
								AND
								area_name= '".$this->input->post('library_zone_area_name')."'
								");

			$result = $res->num_rows();
			if($result > 0 )
			{
				Template::set_message(lang('area_details_edit_failure'), 'error');

			}else
			{
				$data['created_by']				= $this->current_user->id;
				$id = $this->area_model->insert($data);

				if (is_numeric($id))
				{
					$return = $id;
				}else{
					$return = FALSE;
				}

			}


		}elseif ($type == 'update'){
			$this->auth->restrict('Lib.Zone.Area.Edit');
			// $data['modify_by'] 		= $this->current_user->id;
			// $data['modify_date'] 	= date('Y-m-d H:i:s');
			$return = $this->area_model->update($id, $data);
		}

		//return $return;
	}

	//--------------------------------------------------------------------


	//========= Get District Options List by Ajax Function =========
	public function getDistrictListAjax(){
		$groupDropDown = ""; $groupId = 0;
		$this->load->model('list_model', NULL, true);
		$divisionId= $this->input->post('divisionId');
		if(intval($divisionId) > 0){

		/**
		* Summary
		*
		* @param Int	$divisionId,	The division Id of the record to get all district under this division
		*
		* @return array
		*/

		$this->load->library('GetDistrictListAjaxService');

		$GetDistrictListAjaxService = new GetDistrictListAjaxService($this);

		$district_list = $GetDistrictListAjaxService->setDivisionNo($divisionId)
													->execute();


			if(is_array($district_list)){
			$options = array();
			foreach($district_list as $result){ $options[$result["district_id"]] = $result["district_name"]; }
			$groupDropDown = $this->list_model->getDropdownOption($options,"library_zone_district_name");
			}
		}
		echo json_encode($groupDropDown);
	}

	//--------------------------------------------------------------------


	// public function checkThanaNameAjax()
	// {

	// 	$districtName			= $this->input->post('districtName');
	// 	$thanaName				= $this->input->post('thanaName');

	// 	if($districtName!= '')
	// 	{
	// 		$res =$this->db->query("SELECT area_name FROM bf_zone_area WHERE  district_no='$districtName' AND area_name='$thanaName' ");

	// 		$result = $res->num_rows();
	// 		if($result > 0 )
	// 		{
	// 			echo $result;

	// 		}else
	// 		{
	// 			echo false;
	// 		}

	// 	}
	// }
public function checkThanaNameAjax()
	{
		$districtName	= $this->input->post('districtName');
		$thanaName	= $this->input->post('thanaName');

		if(trim($districtName)!= '')
		{
			$res =$this->db->query("SELECT area_name FROM bf_zone_area WHERE district_no='$districtName' AND area_name='$thanaName'");

			$result = $res->num_rows();
			if($result > 0 )
			{
				echo json_encode(['status'=>1,'message'=>'Thana Name Already Exist with same District!!']);

			}

		}
	}



}