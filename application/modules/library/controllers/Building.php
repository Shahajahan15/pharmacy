<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Building controller
 */
class Building extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Library.Building.View');
		$this->load->model('building_model', NULL, TRUE);
		$this->lang->load('initial');

		//Template::set_block('sub_nav', 'initial/_sub_nav_building');
		
	}

	//--------------------------------------------------------------------

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function form_list(){
	 	$list_data=$this->show_list();
	 	$form_data=$this->building_create();
	 	$data=array();
	 	if(is_array($list_data))
        $data=array_merge($data,$list_data);
        if(is_array($form_data))
        $data=array_merge($data,$form_data);
        $data['form_action_url']=site_url('admin/building/library/building_create');
        $data['list_action_url']=site_url('admin/building/library/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("library_initial_building_view"));
        Template::render();

	 } 
	public function show_list()
    {
        $data=array();
		if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }
		$this->auth->restrict('Library.Building.View');
		//======= Delete Multiple =======


		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Building.Delete');
			$result = FALSE;
			$data=[];
			$data['is_deleted']=1;
			foreach ($checked as $building_id){
					$result = $this->building_model->update($building_id,$data);
			}
			if ($result){
			// Log the activity
			log_activity($this->current_user->id, lang('library_act_delete_record') .': '. $building_id .' : '. $this->input->ip_address(), 'lib_building');

			Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
			}else{
			Template::set_message(lang('library_delete_failure') . $this->building_model->error, 'error');
			}
		}				
		$records = $this->building_model->find_all_by('is_deleted',0);
		$data['records']=$records;
		$form_list='initial/building_list';
		if($this->input->is_ajax_request()){
			echo $this->load->View($form_list,$data,TRUE);
			exit();
		}
		Template::set($data);			
		Template::set('toolbar_title', lang("library_initial_building_view"));
		Template::set_view($form_list);			
		Template::render();
    }

              

	/**
	 * Creates a building object.
	 *
	 * @return void
	 **/
	
	public function building_create()
    {
        //TODO you code here
		$data=array();
		if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'building_create') {
            show_404();
        }
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_building_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_building');
				if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $insert_id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('building_create_success'), 'success');
				redirect(SITE_AREA .'/building/library/show_list');
			}
			else
			{
				if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $insert_id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('record_create_failure').$this->building_model->error, 'error');
			}
		}
		
		//$data['building_name']	  = $this->input->post('lib_building_building_name'); 
		//$data['building_status']  = $this->input->post('lib_building_building_status');
		//$data['library_building']=$this->building_model->find($id);
		$form_view='initial/building_create';
		if($this->input->is_ajax_request()){
			echo $this->load->view($form_view,$data,true);
			exit();
		}
		Template::set($data);	
		Template::set_view($form_view);	
		Template::set('toolbar_title', lang("library_initial_building_new"));	
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
			redirect(SITE_AREA .'/building/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Building.Edit');

			if ($this->save_building_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_building');
					if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('building_update_success'), 'success');
				redirect(SITE_AREA .'/building/library/show_list');
			}
			else
			{
				if ($this->input->is_ajax_request()) {
                    $json = array(
                        'status' => true,
                        'message' => lang('bf_msg_create_success'),
                        'inserted_id' => $id,
                    );

                    return $this->output->set_status_header(200)
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($json));
                }
				Template::set_message(lang('building_details_edit_failure') . $this->building_model->error, 'error');
			}
		}
		$data['library_building']=$this->building_model->find($id);
		$form_view='initial/building_create';
		if($this->input->is_ajax_request()){
			echo $this->load->view($form_view,$data,true);
			exit();
		}
		Template::set($data);
		Template::set('toolbar_title', lang('library_initial_building_edit'));
        Template::set_view($form_view);
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
	private function save_building_details($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['building_name']            = $this->input->post('lib_building_building_name');
		$data['building_status']          = $this->input->post('lib_building_building_status');
		
		if ($type == 'insert')
		{
			$this->auth->restrict('Library.Building.Create');
			$id = $this->building_model->insert($data);

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
			$this->auth->restrict('Library.Building.Edit');
			$return = $this->building_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}