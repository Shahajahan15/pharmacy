<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Division controller
 */
class Time_range extends Admin_Controller
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
		$this->auth->restrict('Lib.Initial.Time.View');
		$this->load->model('time_range_model', null, true);
		$this->lang->load('time_range');
		$this->lang->load('common');
		Template::set_block('sub_nav', 'initial/_sub_nav_time_range');
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
		$this->auth->restrict('Lib.Initial.Time.View');
		$this->lang->load('time_range');
		Template::set('toolbar_title', lang("library_initial_time_view"));
		Template::set_block('sub_nav', 'initial/_sub_nav_time_range');	
		//======= Delete Multiple ======= 
	   	$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Initial.Time.Delete');
			$this->load->model('initial/time_range_model', NULL, true);
			$result = FALSE;
			$data=array();
			$data['is_deleted']=1;
			$data['deleted_by']=$this->current_user->id;
			$data['deleted_date']=date('Y-m-d H-i-s');
			foreach ($checked as $id){
					$result = $this->time_range_model->update($id,$data);
					log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'time_range');
			}
			if ($result){
			Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');	    
			
			}else{
			Template::set_message(lang('bf_msg_delete_failure') . $this->time_range_model->error, 'error');
			}
		}					
		$records = $this->time_range_model->find_all_by('is_deleted',0);
		Template::set('records', $records);	
		Template::set_view('initial/time_range_list');
		Template::render();		
	}
 
	//--------------------------------------------------------------------
	/**
	 * Creates a division object.
	 *
	 * @return void
	**/
	//--------------------------------------------------------------------
	
	public function time_range_create()
    {
        //TODO you code here
		/*$this->load->model('initial/time_range_model', NULL, true);
		$this->lang->load('time_range');
		Template::set_block('sub_nav', 'initial/_sub_nav_time_range');	*/
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_time_range_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'time range');
				
				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/time_range/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_create_failure').$this->time_range_model->error, 'error');
			}
		}	         
		Template::set('toolbar_title', lang("library_initial_time_create"));	
		Template::set_view('initial/time_range_create');
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
			Template::set_message(lang('library_initial_time_edit'), 'error');
			redirect(SITE_AREA .'/time_range/library/show_list');
		}
		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Initial.Time.Edit');
			if ($this->save_time_range_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'time_range');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
                redirect(SITE_AREA .'/time_range/library/show_list');
			}
			else
			{
				Template::set_message(lang('library_initial_time_edit') . $this->time_range_model->error, 'error');
			}
		}
		
		Template::set('time_range', $this->time_range_model->find($id));
		Template::set('toolbar_title', lang('library_initial_time_edit'));		
        Template::set_view('initial/time_range_create'); 
		Template::render();
	}

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
	 
	private function save_time_range_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['time_range']   	        = $this->input->post('initial_time_range');
		$data['am_or_pm']   	        = $this->input->post('initial_time_range_am_or_pm'); 
		$data['time_range_status']   	= $this->input->post('initial_time_range_status'); 
		$data['created_by']         	= $this->current_user->id;
		if ($type == 'insert')
		{	
	
			$this->auth->restrict('Lib.Initial.Time.Create');
			$id = $this->time_range_model->insert($data);

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
			$this->auth->restrict('Lib.Initial.Time.Edit');
			$return = $this->time_range_model->update($id, $data);
		}
		return $return;
	}

	//--------------------------------------------------------------------


}