<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Quata_info extends Admin_Controller 
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		 
		$this->load->model('quata_info_model', NULL, true);
        $this->lang->load('quata');	
		$this->lang->load('common');
		
		Template::set_block('sub_nav', 'quata_info/_sub_nav_quata');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {	$this->auth->restrict('Lib.Quata.View');
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Quata.Delete');
			$result = FALSE;
			$data = array();
			$data['IS_DELETED'] 		= 1; 
			$data['DELETED_BY']			= $this->current_user->id;	
			$data['DELETED_DATE']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $pid){
				
				$result = $this->quata_info_model->update($pid,$data);
				
			}
			
			
			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $pid .' : '. $this->input->ip_address(), 'lib_quata_info');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->quata_info_model->error, 'error');
			}
		}
		
		$this->load->model('library/district_model', NULL, TRUE);			
		 
		$this->db->select("
							qi.QUATA_NAME,
							districtName.district_name,
							qi.NO_OF_QUATA,
							qi.QUATA_NAME_BANGLA,
							qi.QUATA_ID,
							qi.STATUS
							
						 ");
								 
				$this->db->from('lib_quata_info as qi');			
				$this->db->where("qi.IS_DELETED = ",0);			
				$this->db->join('zone_district as districtName', 'districtName.DISTRICT_ID = qi.DISTRICT_ID', 'left');			
				$this->db->distinct("qi.QUATA_ID");	
				
				$records 	= $this->quata_info_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_quata_view"));		
        Template::set_view('quata_info/quata_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function quata_create()
    {
        //TODO you code here
			

			if (isset($_POST['save']))
			{
				if ($insert_id = $this->save_quata_details())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_quata_info');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/quata_info/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->quata_info_model->error, 'error');
				}
			}
			$this->load->model('library/district_model', NULL, TRUE);
			$districtName = $this->district_model->find_all();
			
			$records = $this->quata_info_model->find_all();
		
		Template::set('records', $records);	
		Template::set('districtName', $districtName);
		
		Template::set('toolbar_title', lang("Library_quata_create"));
        Template::set_view('quata_info/quata_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function quata_edit()
	{
		$QUATA_ID = $this->uri->segment(5);
		if (empty($QUATA_ID))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/quata_info/library/show_list');
		}
			
		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Quata.Edit');

			if ($this->save_quata_details('update', $QUATA_ID))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $QUATA_ID .' : '. $this->input->ip_address(), 'lib_quata_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/quata_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->quata_info_model->error, 'error');
			}
		}
		
		$this->load->model('library/district_model', NULL, TRUE);
		$districtName = $this->district_model->find_all();
		
		$this->db->select("
							qi.QUATA_NAME,						
							qi.QUATA_ID,
							qi.QUATA_NAME_BANGLA,
							qi.DISTRICT_ID,
							qi.NO_OF_QUATA,
							qi.STATUS
							
						 ");
								 
				$this->db->from('lib_quata_info as qi');			
				$this->db->where("qi.QUATA_ID","$QUATA_ID");			
				$this->db->join('zone_district as districtName', 'districtName.DISTRICT_ID = qi.DISTRICT_ID', 'left');			
				$this->db->distinct("qi.QUATA_ID");	
		
			$lib_quata = $this->quata_info_model->find($QUATA_ID);		
		
			Template::set('lib_quata',$lib_quata);
			Template::set('districtName', $districtName);			
			Template::set('toolbar_title', lang('library_quata_update'));		
			Template::set_view('quata_info/quata_create');
			Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_quata_details($type='insert', $QUATA_ID=0)
	{
		if ($type == 'update'){
			$_POST['QUATA_ID'] = $QUATA_ID;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['QUATA_NAME']       		= $this->input->post('library_quata_name');
		$data['QUATA_NAME_BANGLA']      = $this->input->post('library_quata_name_bangla');
		$data['DISTRICT_ID']       		= $this->input->post('library_district_name');
		$data['NO_OF_QUATA']       		= $this->input->post('library_no_of_quata');      

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Quata.Create');
			$data['CREATED_BY'] 		    = $this->current_user->id; 
			$data['STATUS']      		    = $this->input->post('bf_status');
			$QUATA_ID = $this->quata_info_model->insert($data);	
			
			if (is_numeric($QUATA_ID)){
				$return = $QUATA_ID;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.Quata.Edit');
			$data['MODIFY_BY'] 		= $this->current_user->id;    
			$data['MODIFY_DATE'] 	= date('Y-m-d H:i:s'); 
			$return = $this->quata_info_model->update($QUATA_ID, $data);
		}

		return $return;
	}
	
	
	
	public function checkQuotaNameAjax()
	{		
		$quotaName			= $this->input->post('quotaName'); 		
		if($quotaName!= '') 
		{			
			$res =$this->db->query("SELECT QUATA_NAME FROM bf_lib_quata_info WHERE QUATA_NAME LIKE '%$quotaName%' ");	
				
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo "Quota Name Already Created !";
				
			}else
			{
				
			}			
			
		}	
	}	
	
}

