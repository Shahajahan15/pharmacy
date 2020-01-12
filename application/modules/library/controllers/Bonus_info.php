<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Bonus_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Lib.Bonus.View');
		$this->load->model('bonus_model', NULL, true);
        $this->lang->load('bonus');	
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');		
		Template::set_block('sub_nav', 'bonus_info/_sub_nav_bonusinfo');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Bonus.Delete');
			$result = FALSE;

            foreach ($checked as $bid){
				$result = $this->bonus_model->delete($bid);
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->bonus_model->error, 'error');
			}
		}

        $records = $this->bonus_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_bonus_view"));		
        Template::set_view('bonus_info/bonus_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function bonus_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_bonus_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_bonus_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/bonus_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->bonus_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("Library_bonus_create"));
        Template::set_view('bonus_info/bonus_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function bonus_edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/bonus_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Bonus.Edit');

			if ($this->save_bonus_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_bonus_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/bonus_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->bonus_model->error, 'error');
			}
		}
		
		
			$lib_bonus = $this->bonus_model->find($id);		
		
			Template::set('lib_bonus',$lib_bonus);		
			Template::set('toolbar_title', lang('library_bonus_update'));		
			Template::set_view('bonus_info/bonus_create');
			Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_bonus_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['BONUS_NAME']       		= $this->input->post('library_bonus_name');
		$data['DESCRIPTION']       			= $this->input->post('library_bonus_description');
		//$data['BUSINESS_DATE'] 		    = date("Y-m-d");
		$data['CREATED_BY'] 		    	= $this->current_user->id; 
		$data['STATUS']      		    	= $this->input->post('bf_status');
		      

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Bonus.Create');
			$id = $this->bonus_model->insert($data);
			//print($this->db->last_query()); die; 
			
			
			
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
			$this->auth->restrict('Lib.Bonus.Edit');
			$return = $this->bonus_model->update($id, $data);
		}

		return $return;
	}
	
}

