<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Measurement_unit controller
 */
class Measurement_unit extends Admin_Controller
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

		$this->auth->restrict('Lib.Measure.Unit.View');
		$this->load->model('measurementunit_model', NULL, TRUE);
		$this->lang->load('initial');
		$this->lang->load('common');
		$this->load->config('config_measurement_unit');	

		Template::set_block('sub_nav', 'secondary/_sub_nav_measurementunit');
		
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
		$this->auth->restrict('Lib.Measure.Unit.View');
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Measure.Unit.Delete');
			$result = FALSE;
			foreach ($checked as $building_id){
					$result = $this->measurementunit_model->delete($building_id);
			}
			if ($result){
			// Log the activity
			log_activity($this->current_user->id, lang('library_act_delete_record') .': '. $building_id .' : '. $this->input->ip_address(), 'lib_measurement_unit');

			Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->measurementunit_model->error, 'error');
			}
		}				
		$records = $this->db->select('lib_measurement_unit.*')
		->get('lib_measurement_unit')
		->result_array();
		
		$measurement_unit 		= $this->config->item('measurement_unit');
		
		Template::set('records', $records);	
		Template::set('measurement_unit', $measurement_unit);	
		Template::set('toolbar_title', lang("lib_measurement_unit_view"));
		
		Template::set_view('secondary/measurementunit_list');			
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
			if ($insert_id = $this->save_unit_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_measurement_unit');

				Template::set_message(lang('measurement_create_success'), 'success');
				redirect(SITE_AREA .'/measurement_unit/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->measurementunit_model->error, 'error');
			}
		}
		
		$library_building['unit_name'] 	  	= $this->input->post('lib_measurement_unit_name'); 
		$library_building['unit_details']  	= $this->input->post('lib_measurement_unit_details');
		$library_building['status']  		= $this->input->post('lib_measurement_unit_status');
		
		$measurement_unit 		= $this->config->item('measurement_unit');
			
		Template::set('toolbar_title', lang("lib_measurement_unit_new"));
		Template::set('library_building', $library_building);
		Template::set('measurement_unit', $measurement_unit);
		
		Template::set_view('/secondary/measurementunit_create');		
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
			redirect(SITE_AREA .'/measurement_unit/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Measure.Unit.Edit');

			if ($this->save_unit_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_measurement_unit');

				Template::set_message(lang('building_update_success'), 'success');
				redirect(SITE_AREA .'/measurement_unit/library/show_list');
			}
			else
			{
				Template::set_message(lang('building_details_edit_failure') . $this->measurementunit_model->error, 'error');
			}
		}

		
		Template::set('library_building', $this->measurementunit_model->find($id));
		Template::set('measurement_unit', $this->config->item('measurement_unit'));
		Template::set('toolbar_title', lang('lib_measurement_unit_edit'));
		
        Template::set_view('/secondary/measurementunit_create');
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
	private function save_unit_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['unit_name'] 	  	= $this->input->post('lib_measurement_unit_name'); 
		$data['unit_details']  	= $this->input->post('lib_measurement_unit_details');
		$data['PARENT_UNIT_ID'] = $this->input->post('parent_unit_id');
		$data['status']  		= $this->input->post('lib_measurement_unit_status');
		$data['created_by']  	= $this->current_user->id;
		
		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Measure.Unit.Create');
			$id = $this->measurementunit_model->insert($data);

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
			$this->auth->restrict('Lib.Measure.Unit.Edit');
			$return = $this->measurementunit_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}