<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Patient_discount_setup extends Admin_Controller
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
    	$this->auth->restrict('Discount.Setup.View');
		//======= Delete Multiple or single=======cre
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{

			$result = FALSE;
			
			$data = array();
			$data['is_deleted'] 		= 1; 
			
            foreach ($checked as $pid){
				$result = $this->patient_discount_model->update($pid,$data);
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

		$conditon['is_deleted']=0;
		
		$data['records'] 	= $this->db->select("bf_lib_patient_discounts_mst.*,bf_patient_master.patient_name,bf_patient_master.patient_id as p_code")
									->join('bf_patient_master','bf_patient_master.id=bf_lib_patient_discounts_mst.patient_id','left')
									->where($conditon)
									->order_by('bf_lib_patient_discounts_mst.created_at','DESC')
									->get('bf_lib_patient_discounts_mst')
									->result();
		Template::set($data);
		Template::set('toolbar_title', 'Discount Service Lists');
        Template::set_view('patient_discount/list');
        Template::render();
    }

    
	 
    public function create($id=NULL,$patient_id=NULL,$patient_name=NULL)
    {
        //TODO you code here

			$this->auth->restrict('Discount.Setup.Add');

			if (isset($_POST['save']) && count($_POST)>4)
			{
				//echo '<pre>';print_r($_POST);die();

				if ($insert_id = $this->save())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_discount_service_setup');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/patient_discount_setup/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->patient_discount_model->error, 'error');
				}
			}

		$conditon['is_deleted']=0;
		$conditon['has_discount']=1;
		$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
		$data['patients'] 		= $this->db->get('bf_patient_master')->result();
		$data['id']             = $id;
		$data['patient_id']     = $patient_id;
		$data['patient_name']   = $patient_name;

		
		Template::set($data);
		Template::set('toolbar_title', 'Patient Discount Create');
        Template::set_view('patient_discount/create');
        Template::render();
    }
	

	
	public function ItemBaseEdit()
	{
		$this->auth->restrict('Discount.Setup.Edit');
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/discount_setup/library/show_list');
		}
			
		if (isset($_POST['save']) && count($_POST)>4)
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
		
			$data['record'] = $this->db->select("
										bf_lib_patient_discounts_mst.*,
										bf_patient_master.patient_name,
										bf_patient_master.patient_id
									")
							->join('bf_patient_master','bf_patient_master.id=bf_lib_patient_discounts_mst.patient_id')
							->where('bf_lib_patient_discounts_mst.id',$id)
							->get('bf_lib_patient_discounts_mst')
							->row();
							//echo '<pre>'; print_r($data['record']);die();

			$conditon['is_deleted']=0;
			$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
			//$data['patients'] 		= $this->db->get('bf_patient_master')->result();

			$discounts=$this->db->select("
									bf_lib_patient_discounts_dtls.*,
									bf_lib_discount_service_setup.service_name
								")
							->join('bf_lib_discount_service_setup','bf_lib_discount_service_setup.id=bf_lib_patient_discounts_dtls.service_id')
							->where('discount_mst_id',$id)
							->get('bf_lib_patient_discounts_dtls')
							->result_array();

			//echo '<pre>'; print_r($discounts);die();

			foreach($discounts as $key=>$discount){
					$table=$this->getTableNameByServiceId($discount['service_id']);
					$field=$this->getTableFieldNameByServiceId($discount['service_id']);
					if($table!=false || $field!=false){
						$discounts[$key]['sub_service_name']=$this->db->where('id',$discount['sub_service_id'])->get("$table")->row()->$field;
					}else{
						$discounts[$key]['sub_service_name']='No service Availabe';
					}				
					
				}
			//echo '<pre>'; print_r($discounts);die();


		$data['discounts']=$discounts;
		Template::set($data);
		Template::set('toolbar_title', 'Patient Discount Create');
        Template::set_view('patient_discount/create');
        Template::render();
	}

	public function ServiceBaseEdit()
	{
		$this->auth->restrict('Discount.Setup.Edit');
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/discount_setup/library/show_list');
		}
			
		if (isset($_POST['save']))
		{
			//print_r($_POST);die();
			//$this->auth->restrict('Lib.DiscountService.Edit');

			if ($this->save_service_based('update', $id))
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
		
		
		
			$data['record'] = $this->db->select("
										bf_lib_patient_discounts_mst.*,
										bf_patient_master.patient_name,
										bf_patient_master.patient_id
									")
							->join('bf_patient_master','bf_patient_master.id=bf_lib_patient_discounts_mst.patient_id')
							->where('bf_lib_patient_discounts_mst.id',$id)
							->get('bf_lib_patient_discounts_mst')
							->row();


			$conditon['is_deleted']=0;
			$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
			$data['patients'] 		= $this->db->get('bf_patient_master')->result();

			//echo '<pre>';print_r($data['record']);die();

		
			Template::set($data);
			Template::set('toolbar_title', 'Patient Discount Edit');
        	Template::set_view('patient_discount/create_service_base');
        	Template::render();
	}

	public function create_servie_base($id=NULL,$patient_id=NULL,$patient_name=NULL){
		$this->auth->restrict('Discount.Setup.Add');
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

		$conditon['is_deleted']=0;
		$conditon['has_discount']=1;
		$data['service_lists'] 	= $this->discount_service_model->find_all_by($conditon);
		$data['patients'] 		= $this->db->get('bf_patient_master')->result();
		$data['id']             = $id;
		$data['patient_id']     = $patient_id;
		$data['patient_name']   = $patient_name;
		
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
		
		$data['discount_start_date']    = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_start_date'))));
		$data['discount_end_date']      = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_end_date'))));


		

		if ($type == 'insert')
		{
			$this->db->trans_start();

			$data['patient_id']       		= $this->input->post('patient_id');
			$data['created_by']       		= $this->current_user->id;
			$data['has_overall_discount']  	= 2;


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

			$this->db->trans_start();			
			$return = $this->patient_discount_model->update($id, $data);

			$this->db->where('discount_mst_id',$id)->delete('bf_lib_patient_discounts_dtls');
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
		}

		return $return;
	}


	public function save_service_based($type='insert', $id=0){
		// make sure we only pass in the fields we want		
		$data = array();
		
		$data['discount_start_date']    = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_start_date'))));
		$data['discount_end_date']      = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('discount_end_date'))));
		

		$data['discount_type']			=$this->input->post('discount_type');
		$data['discount']				=$this->input->post('discount');
		$data['service_id']				=$this->input->post('service_id');

		//print_r($data);die();

		if($type=='insert'){
			$data['patient_id']       		= $this->input->post('patient_id');
			$data['has_overall_discount']  	= 1;
			$data['created_by']       		= $this->current_user->id;

			$id=$this->patient_discount_model->insert($data);


			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
			
		}elseif($type='update'){
			$data['last_edited_by']       	= $this->current_user->id;
			$data['edited_at']       		= date('Y-m-d H:i:s');

			$return = $this->patient_discount_model->update($id, $data);

		}

		return $return;



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



	public function getPatient(){
		$id= (int)$this->input->post('id'); 
		$row = $this->db
			->where('id',$id)
			->get('bf_patient_master')
			->row();
		if (!$row) {
            return;
        }
        echo "$('#patient_code').val('" . $row->patient_id. "');";
        echo "$('#patient_id').val('" . $row->id . "');";
        echo "$('#patient_name').val('" . $row->patient_name . "');";
        exit;
	}

}

?>
