<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Rank_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Library.Rank.View'); 
		$this->load->model('rank_info_model', NULL, true);
		$this->lang->load('rank');
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');		
		Template::set_block('sub_nav', 'rank_info/_sub_nav_rankinfo');
	}

    /**
     * store company 
     */
	 
    public function show_ranklist()
    {
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Rank.Delete');
			$result = FALSE;

            foreach ($checked as $pid){
				$result = $this->rank_info_model->delete($pid);
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->rank_info_model->error, 'error');
			}
		}

        $records = $this->rank_info_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_rank_view"));		
        Template::set_view('rank_info/rank_info_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function rank_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_rank_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_rank_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/rank_info/library/show_ranklist');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->rank_info_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("Library_rank_create"));
        Template::set_view('rank_info/rank_info_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function rank_edit()
	{
		$ID = $this->uri->segment(5);
		if (empty($ID))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/rank_info/library/show_ranklist');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Rank.Edit');

			if ($this->save_rank_details('update', $ID))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $ID .' : '. $this->input->ip_address(), 'bank_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/rank_info/library/show_ranklist');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->rank_info_model->error, 'error');
			}
		}
		
		
		$rank_details = $this->rank_info_model->find($ID);		
		
		Template::set('rank_details',$rank_details);		
		Template::set('toolbar_title', lang('library_rank_update'));		
        Template::set_view('rank_info/rank_info_create');
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_rank_details($type='insert', $ID=0)
	{
		if ($type == 'update'){
			$_POST['ID'] = $ID;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['RANK_NAME']        		= $this->input->post('library_rank_name');
		$data['STATUS']      		    = $this->input->post('bf_status');
		$data['CREATED_BY'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Library.Rank.Create');
			$ID = $this->rank_info_model->insert($data);
			if (is_numeric($ID)){
				$return = $ID;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Library.Rank.Edit');
			$return = $this->rank_info_model->update($ID, $data);
		}

		return $return;
	}
	
}

