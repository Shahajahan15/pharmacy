<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Measurement_unit controller
 */
class Consultant_price_setup extends Admin_Controller
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

		$this->auth->restrict('Lib.Consultant_price.View');
		$this->load->model('consultant_price_model', NULL, TRUE);
		$this->load->model('hrm/Employee_model', NULL, TRUE);

		$this->lang->load('consultant_price');
		$this->lang->load('common');

		Template::set_block('sub_nav', 'consultant_price/_sub_nav_consultant_price');
		
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
		$this->auth->restrict('Lib.Consultant_price.View');
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Consultant_price.Delete');
			$result = FALSE;
			foreach ($checked as $building_id){
					$result = $this->consultant_price_model->delete($building_id);
			}
			if ($result){
		

			Template::set_message(count($checked) .' '. 'delete success', 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->consultant_price_model->error, 'error');
			}
		}				
			$records=$this->db->select('lib_consultant_price.*,bf_hrm_ls_employee.EMP_NAME')
	             ->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=lib_consultant_price.doctor_id')
	             ->where('bf_hrm_ls_employee.EMP_TYPE',1)
	             
	             ->get('lib_consultant_price')
	             ->result_array();
	            // echo '<pre>';print_r($records);exit()_array
		
		//$measurement_unit 		= $this->config->item('measurement_unit');
		
		Template::set('records', $records);	
		//Template::set('measurement_unit', $measurement_unit);	
		Template::set('toolbar_title', lang("lib_consultant_price_list"));
		
		Template::set_view('consultant_price/list');			
		Template::render();
    }

              

	/**
	 * Creates a building object.
	 *
	 * @return void
	 **/
	
	public function create()
    {
        //TODO you code here
		
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_consultant_price');

				Template::set_message(lang('measurement_create_success'), 'success');
				redirect(SITE_AREA .'/consultant_price_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->consultant_price_model->error, 'error');
			}
		}
		$doctor_list = $this->db->select('*')
		->where(' bf_lib_reference.ref_type',1)
		->get('bf_lib_reference')
		->result();

		Template::set('toolbar_title', lang("library_consultant_price_view"));
		Template::set('doctor_list', $doctor_list);
		
		Template::set_view('consultant_price/create');		
		Template::render();
    }

	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Building Details data.
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
			redirect(SITE_AREA .'/consultant_price_setup/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Consultant_price.Edit');

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_consultant_price');

				Template::set_message(lang('consultant_price_update_success'), 'success');
				redirect(SITE_AREA .'/consultant_price_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('building_details_edit_failure') . $this->consultant_price_model->error, 'error');
			}
		}
        $doctor_list = $this->db->select('*')
		->where('bf_hrm_ls_employee.EMP_TYPE',1)
		->get('bf_hrm_ls_employee')
		->result();
	    $records=$this->consultant_price_model->find($id);

		//Template::set('library_building', $this->measurementunit_model->find($id));
		Template::set('records', $records);
		Template::set('doctor_list', $doctor_list);
		Template::set('toolbar_title', lang('lib_consultant_price_edit'));		
        Template::set_view('Consultant_price/create');
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
	private function save($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['doctor_id'] 	  	= $this->input->post('doctor_id'); 
		$data['patient_price']  	= $this->input->post('patient_price');
		
		$data['status']  		= $this->input->post('status');
		$data['created_by']  	= $this->current_user->id;
		
		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Consultant_price.Create');
			$id = $this->consultant_price_model->insert($data);

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
			$this->auth->restrict('Lib.Consultant_price.Edit');
			$return = $this->consultant_price_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}