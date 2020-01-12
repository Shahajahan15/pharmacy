<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * District controller
 */
class District extends Admin_Controller
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

		$this->load->model('district_model', null, true);
		$this->load->model('division_model', null, true);
		$this->lang->load('zone');
		$this->lang->load('common');

		Template::set_block('sub_nav', 'zone/_sub_nav_district');
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
		$this->auth->restrict('Lib.Zone.District.View');
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Zone.District.Delete');
			$result = FALSE;
			$data = array();
			$data['is_deleted'] 		= 1;
			//$data['deleted_by']			= $this->current_user->id;
			//$data['deleted_date']    	= date('Y-m-d H:i:s');

            foreach ($checked as $district_id){

				$result = $this->district_model->update($district_id,$data);

			}

			if ($result)
			{
				Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $district_id .' : '. $this->input->ip_address(), 'zone_district');

			}else
			{
				Template::set_message(lang('library_delete_failure') . $this->district_model->error, 'error');
			}
		}

		$this->district_model->select("
										ds.*,
										di.division_name
									 ");
		$this->division_model->from('zone_district AS ds');
		$this->db->where('ds.is_deleted',0);
		$this->db->join('zone_division as di', 'ds.division_no = di.division_id', 'left');
		$this->db->distinct('ds.district_id');

		$records = $this->district_model->find_all();

		Template::set('records', $records);
		Template::set('toolbar_title', lang("library_zone_district_view"));
		Template::set_view('zone/district_list');
		Template::render();
	}



	//--------------------------------------------------------------------
	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	**/
	//--------------------------------------------------------------------

	public function district_create()
    {
        //TODO you code here

		if (isset($_POST['save']))
		{
			if ($insert_id = $this->saveDistrict())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'library_district');
				Template::set_message(lang('district_create_success'), 'success');
				redirect(SITE_AREA .'/district/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->district_model->error, 'error');
			}
		}

		$library_district['district_name'] 	= $this->input->post('library_zone_district_name');
		$library_district['division_no'] 	= $this->input->post('library_zone_division_name');
		$division_list                      = $this->division_model->find_all_by('is_deleted', 0);

		Template::set('toolbar_title', lang("library_zone_district_new"));
		Template::set('library_district', $library_district);
		Template::set('division_list', $division_list);
		Template::set_view('zone/district_create');
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
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('group_details_invalid_id'), 'error');
			redirect(SITE_AREA .'/district/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Zone.District.Edit');

			if ($this->saveDistrict('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_district');
				Template::set_message(lang('district_update_success'), 'success');
                redirect(SITE_AREA .'/district/library/show_list');
			}
			else
			{
				Template::set_message(lang('district_details_edit_failure') . $this->district_model->error, 'error');
			}
		}


		$division_list                     = $this->division_model->find_all_by('is_deleted', 0);
		Template::set('library_district', $this->district_model->find($id));
		Template::set('toolbar_title', lang('district_details_edit'));
		Template::set('division_list', $division_list);
		Template::set_view('zone/district_create');
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

	private function saveDistrict($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want

		$data = array();
        $data['division_no']   				= $this->input->post('library_zone_division_name');
		$data['district_name'] 				= $this->input->post('library_zone_district_name');

		$data['district_status']			= $this->input->post('library_zone_district_status');
		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Zone.District.Create');
			$data['created_by']		= $this->current_user->id;
			$id = $this->district_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			}
			else
			{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$this->auth->restrict('Lib.Zone.District.Edit');
			// $data['modify_by'] 		= $this->current_user->id;
			// $data['modify_date'] 	= date('Y-m-d H:i:s');
			$return = $this->district_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------

	public function checkDistrictNameAjax()
	{

		$districtName	= $this->input->post('districtName');

		if(trim($districtName)!= '')
		{
			$res =$this->db->query("SELECT district_name FROM bf_zone_district WHERE  district_name  LIKE '%$districtName%'");

			$result = $res->num_rows();
			if($result > 0 )
			{
				echo json_encode(['status'=>1,'message'=>'District Name Already Exist !!']);

			}

		}
	}

}