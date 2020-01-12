<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Discount extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		
		$this->load->model('patient_sub_type_model', NULL, true);
		$this->load->model('patient_type_model', NULL, true);
		$this->load->model('discount_model', NULL, true);
		$this->load->model('discount_service_model', NULL, true);
		$this->lang->load('common');
		Assets::add_module_js('library', 'discount');
		Template::set_block('sub_nav', 'discount/_sub_nav_discount');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {
    	$this->auth->restrict('Discount.Type.View');
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{

			$result = FALSE;
			
			$data = array();
			$data['is_deleted'] 		= 1; 
			
            foreach ($checked as $pid){
				$result = $this->discount_model->update($pid,$data);
				
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->discount_model->error, 'error');
			}
		}

		$condition['bf_discount.is_deleted'] = 0;
		$this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = 10;
        $sl=$offset;
        $data['sl']=$sl;
		$search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['patient_name_flag'] = 0;
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Service';   
        $search_box['patient_id_flag']=0;
        $search_box['contact_no_flag']=0;

        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_type_list_flag'] = 1;
        $search_box['discount_service_list_flag'] = 1;

            if(count($_POST)>0){
                
            
            
              if($this->input->post('discount_service_id')){
                $condition['bf_discount.service_id']=$this->input->post('discount_service_id');
            }  
              if($this->input->post('patient_subtype_id')){
                $condition['bf_discount.patient_sub_type_id']=$this->input->post('patient_subtype_id');
            }  
             if($this->input->post('patient_type_id')){
                $condition['bf_discount.patient_type_id']=$this->input->post('patient_type_id');
            }  
              if($this->input->post('from_date')){

                $condition['bf_discount.date_start']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
     
            if($this->input->post('to_date')){

                $condition['bf_discount.date_end']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }      
            } 
              
		$records	= $this->db->select('
					 SQL_CALC_FOUND_ROWS

							bf_discount.*,
							bf_lib_discount_service_setup.service_name,
							bf_lib_patient_type_setup.type_name,
							bf_lib_patient_sub_type_setup.sub_type_name
							',false)
					->join('bf_lib_discount_service_setup','bf_lib_discount_service_setup.id=bf_discount.service_id')
					->join('bf_lib_patient_type_setup','bf_lib_patient_type_setup.id=bf_discount.patient_type_id')

					->join('bf_lib_patient_sub_type_setup','bf_lib_patient_sub_type_setup.id=bf_discount.patient_sub_type_id','left')
					->where($condition)
					->order_by('id','DESC')
					->get('bf_discount')->result();

		//echo '<pre>';print_r($records);die();

		foreach ($records as $key => $record) {
			if($record->sub_service_id>0){
				$field=getTableFieldNameByServiceId($record->service_id);
				$table=getTableNameByServiceId($record->service_id);
				$records[$key]->sub_service_name=$this->db->select("IFNULL($field,'') as sub_service_name")->get("$table")->row()->sub_service_name;
			}else{
				$records[$key]->sub_service_name='Overall';
			}
			
		}
		//echo '<pre>';print_r($records);die();


        $data['records']=$records;    
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/discount/library/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('discount/list', compact('records','sl'), true);
            exit;
        } 
		
		Template::set($data);
		Template::set('toolbar_title', 'Patitent Discount List');
		$list_view='discount/list';
		Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
        //TODO you code here
			$this->auth->restrict('Discount.Type.Add');

			if (isset($_POST['save']))
			{
				//echo '<pre>';print_r($_POST);die();

				if ($insert_id = $this->save())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_patient_sub_type_setup');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/discount/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->discount_model->error, 'error');
				}
			}



		$condition['is_deleted']=0;
		$serviceCondition['is_deleted']=0;
		$serviceCondition['has_discount']=1;
		$data['types']=$this->patient_type_model->find_all_by($condition);
		$data['sub_types']=$this->patient_sub_type_model->find_all_by($condition);
		$data['services'] 	= $this->discount_service_model->find_all_by($serviceCondition);

		
		
		Template::set($data);
		Template::set('toolbar_title', 'Discount Set');
        Template::set_view('discount/create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->auth->restrict('Discount.Type.Edit');
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/discount/library/show_list');
		}
			
		if (isset($_POST['save']))
		{

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $DESIGNATION_ID .' : '. $this->input->ip_address(), 'bf_lib_patient_sub_type_setup');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/discount/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->discount_model->error, 'error');
			}
		}


			$condition['is_deleted']=0;
			$data['types']=$this->patient_type_model->find_all_by($condition);
			$data['sub_types']=$this->patient_sub_type_model->find_all_by($condition);
			$data['services'] 	= $this->discount_service_model->find_all_by($condition);
		
			$record = $this->discount_model->find($id);

			if($record->sub_service_id>0){
				$field=getTableFieldNameByServiceId($record->service_id);
				$table=getTableNameByServiceId($record->service_id);
				$record->sub_service_name=$this->db->select("IFNULL($field,'') as sub_service_name")->get("$table")->row()->sub_service_name;
			}else{
				$record->sub_service_name='Overall';
			}

			$data['record']=$record;

		
			Template::set($data);
			Template::set('toolbar_title', 'Type Discount Edit');
        	Template::set_view('discount/create');
        	Template::render();
	}

	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save($type='insert', $id=0)
	{
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['patient_type_id']       	= $this->input->post('patient_type_id');
		$data['patient_sub_type_id']    = (int)$this->input->post('patient_sub_type_id');
		$data['service_id']       		= $this->input->post('service_id');
		$data['date_start'] = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('date_start'))));
		if(strlen($this->input->post('date_end'))>6){
		$data['date_end'] = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('date_end'))));	
		}
		
		$data['status']       			= $this->input->post('status');
		$data['discount']       		= $this->input->post('discount');
		$data['is_campaign']       		= $this->input->post('campaign');
		

		if ($type == 'insert')
		{
			$data['created_by']       	= $this->current_user->id;
			$data['discount_type']      = 1;//overall sub service
			

			$id=$this->discount_model->insert($data);

			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){			
			$data['last_updated_by']       	= $this->current_user->id;
			$data['last_updated_at']       	= date('Y-m-d H:i:s');
			$return = $this->discount_model->update($id, $data);
		}

		return $return;
	}



	public function getSubTypeByTypeId($id){

		$condition['patient_type_id']=$id;
		$condition['is_deleted']=0;
		$records=$this->db->where($condition)->get('bf_lib_patient_sub_type_setup')->result();

		$options="<option value=''>Select One</option>";
		foreach ($records as $record) {
			$options.='<option value="'.$record->id.'">'.$record->sub_type_name.'</option>';
		}
		echo json_encode($options);
	}





	public function specific_create(){
		$this->auth->restrict('Discount.Type.Add');
        //TODO you code here
        Assets::add_module_js('library', 'patient_discount');
			

			if (isset($_POST['save']))
			{
				//echo '<pre>';print_r($_POST);die();

				if ($insert_id = $this->specific_save())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_patient_sub_type_setup');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/discount/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->discount_model->error, 'error');
				}
			}

		$conditon['is_deleted']=0;
		$conditon['has_discount']=1;
		$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);

		$deletedCon['is_deleted']=0;
		$data['types']=$this->patient_type_model->find_all_by($deletedCon);
		$data['sub_types']=$this->patient_sub_type_model->find_all_by($deletedCon);

		
		
		Template::set($data);
		Template::set('toolbar_title', 'Discount Set');
        Template::set_view('discount/specific_create');
        Template::render();
    }

    private function specific_save($type='insert', $id=0)
	{
		//echo '<pre>';print_r($_POST);die();
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['patient_type_id']       	= $this->input->post('patient_type_id');
		$data['patient_sub_type_id']    = (int)$this->input->post('patient_sub_type_id');		
		$data['date_start'] = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('date_start'))));
		if(strlen($this->input->post('date_end'))>6){
		$data['date_end'] = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('date_end'))));	
		}
		

		if ($type == 'insert')
		{
			$data['created_by']       	= $this->current_user->id;
			$data['discount_type']      = 2;//specific sub service
			$data['status']       		= '1';
			for($i=0;$i<count($_POST['discount']);$i++){
				$data['service_id']   	= $this->input->post('service_id')[$i];
				$data['sub_service_id'] = $this->input->post('sub_service_id')[$i];
				$data['discount']     	= $this->input->post('discount')[$i];

				//echo '<pre>';print_r($data);die();
				$id=$this->discount_model->insert($data);		
			}
			

			

			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){			
			$data['last_updated_by']       	= $this->current_user->id;
			$data['last_updated_at']       	= date('Y-m-d H:i:s');
			$return = $this->discount_model->update($id, $data);
		}

		return $return;
	}

	
}