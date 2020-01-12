<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

 
class Punch_Card_Reader_Configure extends Admin_Controller{
    public function __construct(){
		parent::__construct();           
		
                $this->lang->load('common');
				$this->lang->load('punch_card_reader_configure');
				$this->load->model('punch_card_reader_configure_model', NULL, TRUE);
				$this->load->config('punc_card_reader_config');
				Assets:: add_module_css('hrm','punch_card_config.css');
       
				Template::set_block('sub_nav', 'punch_card_reader_configure/_sub_nav_p_card_reader_configure');
    }
    
    
    public function create(){
        
		if(isset($_POST['save'])){
			
			if($insert_id = $this->save_details())
			{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'hrm_employe_salary_info_mst');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/punch_card_reader_configure/hrm/create');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->salary_info_mst_model->error, 'error');
			}
		}   
		$database_type 				= $this->config->item('database_type');
		$date_format 				= $this->config->item('date_format');
		
		 $SendData = array(
		'database_type'	 => $database_type,
		'date_format'	 => $date_format
		); 
		
		Template::set('SendData', $SendData);
		Template::set('toolbar_title', lang("p_card_reader_config_title"));
		Template::set_view('punch_card_reader_configure/punch_card_reader_configure_form');
		Template::render();       
    }
    
    
   
    public function show_list(){
        
        $checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('punch_card_reader_configure.HRM.Delete');
			

                    $result = FALSE;
                                foreach ($checked as $pid){
                                $result = $this->punch_card_reader_configure_model->delete($pid);
                                }

                    if ($result){
                                    Template::set_message(count($checked) .' '. lang('account_delete_success'), 'success');
                                }
                                else{
                                    Template::set_message(lang('account_delete_failure') . $this->punch_card_reader_configure_model->error, 'error');
                                }
		}
        $query = $this->db->select('rm.READER_MODEL_ID,rm.READER_MODEL_NAME,rm.DATABASE_TYPE,rm.DATABASE_NAME,rm.TABLE_NAME,rm.SERVER_NAME',false)
                ->from('hrm_reader_model_table as rm')
                ->get();
        $result = $query->result();
		
		$database_type 				= $this->config->item('database_type');
			
			
		
		Template::set('database_type', $database_type);
        Template::set('reader_model', $result);
		Template::set('toolbar_title', lang("p_card_reader_showlist_title"));		
        Template::set_view('punch_card_reader_configure/punch_card_reader_configure_list');
        Template::render();
    }
    
    

    public function edit(){
		$id = $this->uri->segment(5);
        $edit = $this->uri->segment(4);
               
                if (empty($id))
                    {
                            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
                            redirect(SITE_AREA .'/punch_card_reader_configure/hrm/show_list');
                    }
                    if (isset($_POST['save'])){
                        $this->auth->restrict('Store.Supplier.Edit');
                        $data['SUPPLIER_MODIFIED_BY']       = $this->current_user->id;       
                        $data['SUPPLIER_MODIFIED_DATE']     = date('Y-m-d H:i:s');   
                        if ($this->save_details('update',$id))
                        {
                            // Log the activity
                            log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'store_supplier');
							Template::set_message(lang('bf_msg_edit_success'), 'success');
                            redirect(SITE_AREA .'/punch_card_reader_configure/hrm/show_list');
                        } else {
                            Template::set_message(lang('bf_msg_edit_failure') . $this->punch_card_reader_configure_model->error, 'error');
                        }
                    }
                
                
				$reader_model_details = $this->punch_card_reader_configure_model->find_by('hrm_reader_model_table.READER_MODEL_ID',$id);									 
				
				
				$database_type 				= $this->config->item('database_type');
				$date_format 				= $this->config->item('date_format');
		
				 $SendData = array(
				'database_type'	 => $database_type,
				'date_format'	 => $date_format
				); 
		
				Template::set('SendData', $SendData);
                Template::set('reader_model_details', $reader_model_details);
				Template::set('toolbar_title', lang('p_card_reader_edit_title'));
                Template::set_view('punch_card_reader_configure/punch_card_reader_configure_form');
                Template::render();
				
	} 
        
        
        
    public function details_list(){
        
			$detailsId = $this->uri->segment(5);
	
       
            $reader_model_details = $this->punch_card_reader_configure_model->find_by('hrm_reader_model_table.READER_MODEL_ID',$detailsId);
				
				$database_type 				= $this->config->item('database_type');
				$date_format 				= $this->config->item('date_format');
		
				 $SendData = array(
				'database_type'	 => $database_type,
				'date_format'	 => $date_format
				); 
		
			Template::set('SendData', $SendData);
			Template::set('reader_model_details', $reader_model_details);
            Template::set('toolbar_title', lang("p_card_reader_view_title"));	
            Template::set_block('sub_nav', 'punch_card_reader_configure/_sub_nav_p_card_reader_configure _details');
            Template::set_view('punch_card_reader_configure/punch_card_reader_configure_view_details');
            Template::render();
            
    }
    
    
    public function details_edit() {
        
        $manual_salary_head  = $this->input->post('manual_salary_head');     
        $manual_amount = $this->input->post('manual_amount');
        $manual_rule = $this->input->post('manual_rule');
        $detailsId = $this->uri->segment(6);
        
        echo $manual_salary_head[0];
        echo $manual_amount[0];
        echo $manual_rule[0];
        echo $detailsId;
        
        $query = $this->db->select('*')
                          ->from('hrm_employe_salary_info_dtls')
                          ->where('HRM_EMPLOYEE_SALARY_INFO_DTLS_ID', $detailsId)
                          ->get();
        $records = $query->result();
    }
    
    
    
    public function detailsAjax(){
        
        $detailsId = $_POST['detailsId'];
        $query = $this->db->query("select * from hrm_employe_salary_info_dtls where HRM_EMPLOYEE_SALARY_INFO_DTLS_ID='$detailsId'");
        $records = $query->result();
        
        return $records;
        $productId = $records['REQUSITION_PRODUCT_ID'];

        $returnData = array();
        $returnData['evalData'] = "
        $('#store_Product_Name').val('$productId');

                    ";	
        
    }  
    
    
    private function save_details($type='insert', $id=0)
	{
           
		$current_user = $this->current_user->id;    
		
		$insertData = array();	
		 
		$insertData['READER_MODEL_NAME'] 	= $this->input->post('reader_model');
		$insertData['DATABASE_TYPE'] 		= $this->input->post('database_type');	
		$insertData['DATABASE_NAME']		= $this->input->post('database_name');
		$insertData['TABLE_NAME'] 			= $this->input->post('server_name');
		$insertData['SERVER_NAME']			= $this->input->post('table_name');
		$insertData['USER_NAME'] 			= $this->input->post('user_name');
		$insertData['PASSWORD'] 			= $this->input->post('password');
		$insertData['RF_CODE_FIELD'] 		= $this->input->post('rf_code_field');
		$insertData['DATE_FIELD'] 			= $this->input->post('date_field');
		$insertData['TIME_FIELD']			= $this->input->post('time_field');
		$insertData['READER_NO_FIELD'] 		= $this->input->post('reader_no_field');
		$insertData['NETWORK_NO_FIELD']		= $this->input->post('network_no_field');
		$insertData['STATUS_FIELD'] 		= $this->input->post('status_field');
		$insertData['ID_FIELD_NAME'] 		= $this->input->post('id_field_name');			
		$insertData['DATE_FORMAT'] 			= $this->input->post('date_format'); 
		
		
			if($type == 'insert')
			{
				$this->auth->restrict('HRM.punch_card_reader_configure.Create');
				$insertData['CREATED_BY']				= $this->current_user->id;
				$insertData['STATUS']					= 1;
                
                $mstId = $this->punch_card_reader_configure_model->insert($insertData);
				return $mstId;
				
            }elseif ($type =='update')
			{
					$this->auth->restrict('HRM.punch_card_reader_configure.Edit');
					$insertData['MODIFY_BY']				= $this->current_user->id;
					$insertData['STATUS']					= 1;
                   
					$mstId  = $this->punch_card_reader_configure_model->update($id,$insertData);
				
					return $mstId;
            }
                 
    }
    
    
    
    
    
} // end controller