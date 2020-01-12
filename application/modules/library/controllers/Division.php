<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Division controller
 */
class Division extends Admin_Controller
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

		$this->load->model('division_model', null, true);
		$this->lang->load('zone');
		$this->lang->load('common');

		Template::set_block('sub_nav', 'zone/_sub_nav_division');
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
		$this->auth->restrict('Lib.Zone.Division.View');		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Zone.Division.Delete');			
			$result = FALSE;
			$data = array();
			$data['is_deleted'] 		= 1; 
			$data['deleted_by']			= $this->current_user->id;	
			$data['deleted_date']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $division_id){
				
				$result = $this->division_model->update($division_id,$data);
				
			}		
			
			if ($result)
			{
				Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $division_id .' : '. $this->input->ip_address(), 'zone_division');
			}else
			{
				Template::set_message(lang('library_delete_failure') . $this->division_model->error, 'error');
			}
		}
		
		$records = $this->division_model->find_all_by('is_deleted', 0);
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_zone_division_view"));		
		Template::set_view('zone/division_list');
		Template::render();
	}

        
        
	//--------------------------------------------------------------------
	/**
	 * Creates a division object.
	 *
	 * @return void
	**/
	//--------------------------------------------------------------------
	
	public function division_create()
    {
        //TODO you code here		
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_division_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'library_division');

				Template::set_message(lang('division_create_success'), 'success');
				redirect(SITE_AREA .'/division/library/show_list');
			}else{
				Template::set_message(lang('record_create_failure').$this->division_model->error, 'error');
			}
		}
		
		$library_division['division_name'] 				= $this->input->post('zone_division_division_name'); 
		$library_division['division_name_bangla']   	= $this->input->post('division_name_bangla');
		
		Template::set('toolbar_title', lang("library_zone_division_new"));
		Template::set('library_division', $library_division);
		
		Template::set_view('zone/division_create');
		Template::render();
    }

	
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of division data.
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
			redirect(SITE_AREA .'/division/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Zone.Division.Edit');

			if ($this->save_division_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_division');

				Template::set_message(lang('division_update_success'), 'success');
                redirect(SITE_AREA .'/division/library/show_list');
			}
			else
			{
				Template::set_message(lang('division_details_edit_failure') . $this->division_model->error, 'error');
			}
		}
		
		Template::set('library_division', $this->division_model->find($id));
		
		Template::set('toolbar_title', lang('division_details_edit'));
		
        Template::set_view('zone/division_create');
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
	 
	private function save_division_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['division_name']   			= $this->input->post('zone_division_division_name');
		$data['division_name_bangla']   	= $this->input->post('division_name_bangla');
		$data['division_status']   			= $this->input->post('zone_division_division_status');
		$data['created_by']   				= $this->current_user->id;	
	
		if ($type == 'insert')
		{	
			$this->auth->restrict('Lib.Zone.Division.Create');	
			$data['created_by'] 	= $this->current_user->id; 
			$id = $this->division_model->insert($data);

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
			$this->auth->restrict('Lib.Zone.Division.Edit');
			
			$data['modify_by'] 		    = $this->current_user->id;    
			$data['modify_date'] 		= date('Y-m-d H:i:s'); 
			$return = $this->division_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------
	// public function checkDivisionNameAjax()
	// {	
	
	// 	$divisionName			= $this->input->post('divisionName'); 
		
	// 	if(trim($divisionName)!= '')
	// 	{			
	// 		$res =$this->db->query("SELECT division_name FROM bf_zone_division WHERE  division_name  LIKE '%$divisionName%'");	
			
	// 		$result = $res->num_rows();
			
					
	// 		if($result > 0 )
	// 		{
	// 			echo json_encode(['status'=>1,'message'=>'Division Name Already Exist !!']);

	// 		}
	// 	}	
	// }	
public function checkDivisionNameAjax()
	{	
	
		$divisionName			= $this->input->post('divisionName'); 
		
		if(trim($divisionName)!= '')
		{			
			$res =$this->db->query("SELECT division_name FROM bf_zone_division WHERE  division_name  LIKE '%$divisionName%'");	
			
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Division Name Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}	
}