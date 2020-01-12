<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Discount_setup extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('patient_discount_model', NULL, true);
		$this->load->model('discount_service_model', NULL, true);
		$this->load->model('patient_discount_dtls_model', NULL, true);
		$this->lang->load('common');
		Assets::add_module_js('library', 'patient_discount');
		Template::set_block('sub_nav', 'patient_discount/_sub_nav_patient_discount');
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

			$result = FALSE;
			
			$data = array();
			$data['is_deleted'] 		= 1; 
			
            foreach ($checked as $pid){
				$result = $this->db->where('id',$pid)->update('bf_lib_patient_discounts_mst',$data);

				$this->db->where('discount_mst_id', $pid)->update('bf_lib_patient_discounts_dtls', $data);
				
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->patient_discount_model->error, 'error');
			}
		}

		//$conditon['is_deleted']=0;


	    $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$_POST['per_page']:'25';
        $sl=$offset;
        $data['sl']=$sl;
		$search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['patient_name_flag'] = 1;
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Requisition No';   
        $search_box['patient_id_flag']=1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_type_list_flag'] = 0;
        //$search_box['patient_subtype_flag'] = 1;

        $condition['bf_lib_patient_discounts_mst.created_by>=']=0;
           
                
            
           if(trim($this->input->post('patient_name'))){
                $condition['bf_patient_master.patient_name like']='%'.trim($this->input->post('patient_name')).'%';
            }
              if(trim($this->input->post('patient_id'))){
                $condition['bf_patient_master.patient_id like']='%'.trim($this->input->post('patient_id')).'%';
            }
             if($this->input->post('from_date')){

                $condition['bf_lib_patient_discounts_mst.discount_start_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['bf_lib_patient_discounts_mst.discount_end_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }   
             
            

		
		$records 	= $this->db->select("        	                   
		 SQL_CALC_FOUND_ROWS
         bf_lib_patient_discounts_mst.*,bf_patient_master.patient_name,bf_patient_master.patient_id as p_code",false)
					->join('bf_patient_master','bf_patient_master.id=bf_lib_patient_discounts_mst.patient_id','left')
					->where($condition)
					->where('bf_lib_patient_discounts_mst.is_deleted',0)
					->order_by('bf_lib_patient_discounts_mst.created_at','DESC')
									
					->limit($limit,$offset)
					->get('bf_lib_patient_discounts_mst')
					->result();


        $data['records']=$records;    

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/discount_setup/library/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('patient_discount/list', compact('records','sl'), true);
            exit;
        } 

		//$data['records'] 	= $this->patient_discount_model->find_all_by($conditon);
		//echo '<pre>';var_dump($data);die();
		
		Template::set($data);
		Template::set('toolbar_title', 'Discount Service Lists');
		$list_view='patient_discount/list';
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
			

			if (isset($_POST['save']))
			{
				//echo '<pre>';print_r($_POST);die();

				if ($insert_id = $this->save())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_discount_service_setup');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/discount_setup/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->patient_discount_model->error, 'error');
				}
			}

		$conditon['is_deleted']=0;
		$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
		//$data['patients'] 		= $this->db->get('bf_patient_master')->result();
		
		Template::set($data);
		Template::set('toolbar_title', 'Patient Discount Create');
        Template::set_view('patient_discount/create');
        Template::render();
    }
	

	
	public function ServiceBaseEdit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/discount_setup/library/show_list');
		}
			
		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Lib.DiscountService.Edit');

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $DESIGNATION_ID .' : '. $this->input->ip_address(), 'bf_lib_discount_service_setup');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/discount_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->patient_discount_model->error, 'error');
			}
		}
		
		
		
			$data['record'] = $this->patient_discount_model->find($id);

			$conditon['is_deleted']=0;
			$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
			$data['patients'] 		= $this->db->get('bf_patient_master')->result();

		
			Template::set($data);
			Template::set('toolbar_title', 'Patient Discount Edit');
        	Template::set_view('patient_discount/create_service_base');
      
        	Template::render();
	}
	



	public function create_servie_base(){
		if (isset($_POST['save']))
			{
				//echo '<pre>';print_r($_POST);die();

				if ($insert_id = $this->save_service_based())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_patient_discounts_mst');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/discount_setup/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->patient_discount_model->error, 'error');
				}
			}

		$conditon['is_deleted']	= 0;
		$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
		$data['patients'] 		= $this->db->get('bf_patient_master')->result();
		
		Template::set($data);
		Template::set('toolbar_title', 'Patient Discount Create');
        Template::set_view('patient_discount/create_service_base');
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
		$data['patient_id']       		= $this->input->post('patient_id');
		$data['discount_start_date']    = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_start_date'))));
		$data['discount_end_date']      = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_end_date'))));


		

		if ($type == 'insert')
		{
			$this->db->trans_start();

			$data['created_by']       	= $this->current_user->id;
			$data['has_overall_discount']  = 2;

			$id=$this->patient_discount_model->insert($data);

			for($i=0;$i<count($this->input->post('service_id'));$i++){
				$data_dtls['service_id']       		= $_POST['service_id'][$i];
				$data_dtls['sub_service_id']    	= $_POST['sub_service_id'][$i];
				$data_dtls['discount_type']      	= $_POST['discount_type'][$i];
				$data_dtls['discount']       		= $_POST['discount'][$i];
				$data_dtls['discount_unit']      	= $_POST['discount_unit'][$i];
				$data_dtls['created_by']       		= $this->current_user->id;
				$data_dtls['discount_mst_id']      	= $id;

				$this->patient_discount_dtls_model->insert($data_dtls);
			}
			$this->db->trans_complete();

			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$data['last_edited_by']     = $this->current_user->id;			
			$data['edited_at']      	= date('Y-m-d');
			
			$return = $this->patient_discount_model->update($id, $data);
		}

		return $return;
	}


	public function save_service_based($type='insert', $id=0){
		// make sure we only pass in the fields we want		
		$data = array();
		$data['patient_id']       		= $this->input->post('patient_id');
		$data['discount_start_date']    = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_start_date'))));
		$data['discount_end_date']      = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_end_date'))));
		$data['has_overall_discount']  	= 1;
		$data['created_by']       		= $this->current_user->id;

		$data['discount_type']			=$this->input->post('discount_type');
		$data['discount']				=$this->input->post('discount');
		$data['service_id']				=$this->input->post('service_id');

		//print_r($data);die();

		if($type=='insert'){

			$id=$this->patient_discount_model->insert($data);


			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}

			return $return;
		}



	}

	public function getSubServiceByServiceId($id=''){
		//Diagnosis from bf_pathology_test_name

		if((int)$id===1){
			$records=$this->db->select("id as id,test_name as name")->get('bf_pathology_test_name')->result();

		}elseif((int)$id===2){
			$records=$this->db->select("id as id,product_name as name")->get('bf_pharmacy_products')->result();
		}elseif((int)$id==3){
			$records=$this->db->select("id as id,otherservice_name as name")->where('service_id',3)->get('bf_lib_otherservice')->result();			
		}elseif((int)$id==4){
			$records=$this->db->select("id as id,otherservice_name as name")->where('service_id',4)->get('bf_lib_otherservice')->result();			
		}

		if ($this->input->is_ajax_request()) {

			$list='<option>Select One</option>';
			foreach($records as $record){
				$list.='<option value="'.$record->id.'">'.$record->name.'</option>';
			}
            echo json_encode($list);
            exit;
        }

	}

	public function getDetailsDiscountInfo($id){

		$conditon['bf_lib_patient_discounts_mst.is_deleted']=0;
		$conditon['bf_lib_patient_discounts_dtls.is_deleted']=0;
		$conditon['bf_lib_patient_discounts_mst.id']=$id;
		$records	= $this->db->select("

					bf_lib_patient_discounts_mst.id,
					bf_lib_patient_discounts_mst.discount_start_date,
					bf_lib_patient_discounts_mst.discount_end_date,
					bf_lib_patient_discounts_mst.has_overall_discount,
					bf_lib_patient_discounts_mst.service_id as overall_service_id,
					bf_lib_patient_discounts_mst.discount_type as overall_discount_type,
					bf_lib_patient_discounts_mst.discount as ovarall_discount,
					bf_patient_master.patient_name,
					bf_patient_master.patient_id as p_code,
					bf_lib_discount_service_setup.service_name,
					bf_lib_patient_discounts_dtls.*

					")

					->join('bf_patient_master','bf_patient_master.id=bf_lib_patient_discounts_mst.patient_id')
					->join('bf_lib_patient_discounts_dtls','bf_lib_patient_discounts_dtls.discount_mst_id=bf_lib_patient_discounts_mst.id')
					->join('bf_lib_discount_service_setup','bf_lib_discount_service_setup.id=bf_lib_patient_discounts_dtls.service_id')
					->where($conditon)
					->get('bf_lib_patient_discounts_mst')
					->result_array();


			if(count($records)==0){
				unset($conditon['bf_lib_patient_discounts_dtls.is_deleted']);
				$records	= $this->db->select("

					bf_lib_patient_discounts_mst.id,
					bf_lib_patient_discounts_mst.discount_start_date,
					bf_lib_patient_discounts_mst.discount_end_date,
					bf_lib_patient_discounts_mst.has_overall_discount,
					bf_lib_patient_discounts_mst.service_id,
					bf_lib_patient_discounts_mst.discount_type,
					bf_lib_patient_discounts_mst.discount,
					bf_patient_master.patient_name,
					bf_patient_master.patient_id as p_code,
					bf_lib_discount_service_setup.service_name

					")

					->join('bf_patient_master','bf_patient_master.id=bf_lib_patient_discounts_mst.patient_id')
					->join('bf_lib_discount_service_setup','bf_lib_discount_service_setup.id=bf_lib_patient_discounts_mst.service_id')
					->where($conditon)
					->get('bf_lib_patient_discounts_mst')
					->result_array();
			}else{
				foreach($records as $key=>$record){
					$table=$this->getTableNameByServiceId($record['service_id']);
					$field=$this->getTableFieldNameByServiceId($record['service_id']);
					if($table!=false || $field!=false){
						$records[$key]['sub_service_name']=$this->db->where('id',$record['sub_service_id'])->get("$table")->row()->$field;
					}else{
						$records[$key]['sub_service_name']='No service Availabe';
					}
					
					
				}
			}





		if ($this->input->is_ajax_request()) {

			if(count($records)==0){
				echo json_encode('Record Not Found');
				exit;
			}

			//table ready for showing details discount
			$table='<table class="table">
					<thead>
						<tr>
							<th>Service Name</th>';
			if($records[0]['has_overall_discount']==2){
				$table.=		'<th>Sub Service Name</th>';
			}
			$table.=		'<th>Discount Type</th>
							<th>Discount</th>
						</tr>
					</thead>
					<tbody>';

			foreach($records  as $record){
				$table.='<tr>';

				$table.='<td>'.$record['service_name'].'</td>';
				if($records[0]['has_overall_discount']==2){
					$table.='<td>'.$record['sub_service_name'].'</td>';
				}

				if($record['discount_type']==1){
					$table.='<td>Parcent(%)</td>';
				}else{
					$table.='<td>Amount(TK)</td>';
				}
				$table.='<td>'.$record['discount'].'</td>';
				
				$table.='</tr>';
			}

			$table.='</tbody></table>';
			echo json_encode($table);
        }
        
		
	}

	private function getTableNameByServiceId($id){
		if($id==1){
			return 'bf_pathology_test_name';
		}elseif($id==2){
			return 'bf_pharmacy_products';
		}elseif($id==3){
			return 'bf_lib_otherservice';
		}elseif ($id==4) {
			return 'bf_lib_otherservice';		
		}else{
			return false;
		}
	}

	private function getTableFieldNameByServiceId($id){
		if($id==1){
			return 'test_name';
		}elseif($id==2){
			return 'product_name';
		}elseif ($id==3 || $id==4) {
			return 'otherservice_name';		
		}else{
			return false;
		}
	}


}

?>
