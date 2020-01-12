<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * shelf controller
 */
class Indoor_requisition_issue extends Admin_Controller {

    /**
     * Constructor
     * @return void
     */
    public function __construct() 
	{
        parent::__construct();          
        $this->load->model('indoor_stock_requisition_model', NULL, true);       
        $this->load->model('indoor_stock_requisition_dtls_model', NULL, true);

        $this->load->model('indoor_stock_mst_model', NULL, true);
        $this->load->model('indoor_stock_dtls_model', NULL, true);
        $this->load->model('indoor_stock_model', NULL, true);

        $this->load->model('stock_in_model', NULL, true);
        $this->load->model('stock_in_dtls_model', NULL, true);
        $this->load->library('pharmacyCommonService');

        $this->lang->load('common');
        $this->lang->load('indoor_requisition');
        Assets::add_module_js('pharmacy','indoor_requisition_issue');
        //$this->lang->load('shelf');
       Template::set_block('sub_nav', 'indoor_requisition_issue/_sub_nav');
    }

    /* Write from here */

    public function show_list()
	{
        $this->auth->restrict('Pharmacy.Indoor.Issue.View');
        Template::set('toolbar_title', lang("indoor_requisition_title_view"));

        if(isset($_POST['save'])){
            //echo '<pre>';print_r($_POST);die();
            if($insert_id=$this->submit_issue()){

                $print_page=$this->issue_print($insert_id);                  

                    $data['print_page']=$print_page;
                    Template::set('print_page',$print_page);
                

                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'bf_pharmacy_indoor_stock_mst');
                Template::set_message('Successfully Issued', 'success');
                redirect(SITE_AREA . '/indoor_requisition_issue/pharmacy/show_list');
            }
        }

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.Indoor.Issue.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->shelf_model->delete($pid);
            }

            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('shelf_delete_success'), 'success');
            } else {
                Template::set_message(lang('shelf_delete_failure') . $this->shelf_model->error, 'error');
            }
        }
       $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'requisition No';

        $search_box['pharmacy_product_list_flag']=0;
        $condition['bf_pharmacy_indoor_requision_mst.status>=']=0;
        $pharmacy_id = ($this->current_user->role_id == 23) ? $this->current_user->pharmacy_id : 200; 
        $condition['bf_pharmacy_indoor_requision_mst.issue_pharmacy_id'] = $pharmacy_id;
            if(count($_POST)>0){
               
                 if($this->input->post('from_date')){

                $condition['bf_pharmacy_indoor_requision_mst.requisition_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['bf_pharmacy_indoor_requision_mst.requisition_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }   
             if($this->input->post('common_text_search')){
                $condition['bf_pharmacy_indoor_requision_mst.requisition_no like']='%'.$this->input->post('common_text_search').'%';
            }  
             
            }
        $records = $this->db->select("
                                bf_pharmacy_indoor_requision_mst.*,
                                bf_hrm_ls_employee.EMP_NAME as emp_name,
                                bf_hrm_ls_employee.CODE as code,
                                bf_pharmacy_setup.name as pharmacy_name,

                                ")  

                    ->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_pharmacy_indoor_requision_mst.requisition_by','left')  
                    ->join('bf_pharmacy_setup','bf_pharmacy_setup.id=bf_pharmacy_indoor_requision_mst.pharmacy_id','left')    			
        			->where('bf_pharmacy_indoor_requision_mst.status',0)
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->order_by('bf_pharmacy_indoor_requision_mst.issue_date','DESC')
                    ->get('bf_pharmacy_indoor_requision_mst')->result();

                    //echo '<pre>';print_r($records);die();

         $data['records']=$records;  

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/indoor_requisition_issue/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('indoor_requisition_issue/requisition_pending_list', compact('records','sl'), true);
            exit;
        } 

        Template::set($data);
        $list_view='indoor_requisition_issue/requisition_pending_list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }

    public function pending_list()
    {
        $this->auth->restrict('Pharmacy.Indoor.Issue.View');
        Template::set('toolbar_title', lang("indoor_requisition_title_view"));
        $this->load->library('pagination');
        $offset=(int)$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box=$this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag']=1;
        $search_box['common_text_search_label']='requisition no';
        $search_box['from_date_flag']=1;
        $search_box['to_date_flag']=1;
        $condition=[];
        if($this->input->post('common_text_search')){
            $condition['bf_pharmacy_indoor_requision_mst.requisition_no like']='%'.trim($this->input->post('common_text_search')).'%';
        }
        if($this->input->post('from_date')){
                $from_date = custom_date_format(trim($this->input->post('from_date')));
                $condition['bf_pharmacy_indoor_requision_mst.requisition_date >='] = $from_date." 00:00:00";
               
            }
 
        if($this->input->post('to_date')){
                $to_date = custom_date_format(trim($this->input->post('to_date')));
                $condition['bf_pharmacy_indoor_requision_mst.requisition_date <='] = $to_date." 23:59:59";
            }
        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {

            //$this->auth->restrict('pharmacy.indoor.requisition.Delete');
            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->shelf_model->delete($pid);
            }
            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('shelf_delete_success'), 'success');
            } else {
                Template::set_message(lang('shelf_delete_failure') . $this->shelf_model->error, 'error');
            }
        }
        $pharmacy_id = ($this->current_user->role_id == 23) ? $this->current_user->pharmacy_id : 200; 
        $condition['bf_pharmacy_indoor_requision_mst.issue_pharmacy_id'] = $pharmacy_id;

        $data['records'] = $this->db->select("
                                    bf_pharmacy_indoor_requision_mst.*,
                                    bf_hrm_ls_employee.EMP_NAME as emp_name,
                                    bf_hrm_ls_employee.CODE as code,
                                    bf_pharmacy_setup.name as pharmacy_name
                                ")   
                    ->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_pharmacy_indoor_requision_mst.requisition_by','left')
                    ->join('bf_pharmacy_setup','bf_pharmacy_setup.id=bf_pharmacy_indoor_requision_mst.pharmacy_id','left')
                    ->where('bf_pharmacy_indoor_requision_mst.status',2)
                    ->where($condition)
                    ->order_by('bf_pharmacy_indoor_requision_mst.issue_date','DESC')
                    ->limit($limit,$offset)
                    ->get('bf_pharmacy_indoor_requision_mst')
                    ->result();
    //echo '<pre>';print_r($data['records']);exit;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/indoor_requisition_issue/pharmacy/pending_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
                   // echo '<pre>';print_r($data['records']);die();
            if($this->input->is_ajax_request()){
                echo $this->load->view('indoor_requisition_issue/requisition_pending_list',$data,true);
                exit();
            }
       

        $list_view='indoor_requisition_issue/requisition_pending_list';
        Template::set($data);
        Template::set('list_view',$list_view);
        Template::set('search_box',$search_box);
        Template::set_view('report_template');
        Template::render();
    }
    
    /**
     * Account sub category setup & list
     */
    public function create() {

    }
    
    /**
     * Allows editing of company data.
     *
     * @return void
     */
  

     


  
    public function getDtlsRequisition($mst_id=0){

        $records=$this->db->select("bf_pharmacy_indoor_requision_dtls.*,bf_pharmacy_products.product_name,bf_pharmacy_category.category_name")
        	->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_indoor_requision_dtls.product_id','left')
            ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id','left')
        	->where('bf_pharmacy_indoor_requision_dtls.master_id',$mst_id)
        	->where('bf_pharmacy_indoor_requision_dtls.status <',3)
        	->get('bf_pharmacy_indoor_requision_dtls')
        	->result_array();

          //  echo '<pre>'; echo print_r($records);echo '</pre>';die();

            $master=$this->db->where('id',$mst_id)->get('bf_pharmacy_indoor_requision_mst')->row();

            $issue_pharmacy_id = $master->issue_pharmacy_id;
            $requ_master_id = $master->pharmacy_id;

        	if(!$records){
        		echo 'No Record Availabe for issue';
        		exit;
        	}else{

                for($i=0;$i<count($records);$i++){
                    $issue_stock = $this->getStock($records[$i]['product_id'], $issue_pharmacy_id);
                    $records[$i]['issue_stock']=isset($issue_stock) ? $issue_stock :'0';

                    $req_stock = $this->getStock($records[$i]['product_id'], $requ_master_id);
                    $records[$i]['req_stock']=isset($req_stock) ? $req_stock :'0';
                }
              
            }

        	return $this->load->view('indoor_requisition_issue/issue_submit_form',['records'=>$records]);
    }

    private function getStock($product_id = 0, $pharmacy_id = 0) 
    {
        $con = [];
        if ($pharmacy_id == 200) {
            $table = 'bf_pharmacy_stock';
        } else {
            $table = 'bf_pharmacy_indoor_stock';
            $con['pharmacy_id'] = $pharmacy_id;
        }
        $main_stock = $this->db
                    ->where('product_id',$product_id)
                    ->where($con)
                    ->get($table)
                    ->row();
        if ($main_stock) {
            $qnty = $main_stock->quantity_level;
        } else {
            $qnty = 0;
        }

        return $qnty;
    }



    public function getCompleteDtlsRequisition($mst_id=0){

        $records=$this->db->select('
                                bf_pharmacy_indoor_requision_dtls.req_qnty,
                                bf_pharmacy_indoor_requision_dtls.issue_qnty,
                                bf_pharmacy_indoor_requision_dtls.req_qnty,
                                bf_pharmacy_indoor_requision_mst.issue_date,
                                bf_pharmacy_indoor_requision_mst.requisition_no,
                                bf_pharmacy_indoor_requision_mst.requisition_date,
                                bf_pharmacy_indoor_requision_mst.issue_by,
                                bf_pharmacy_products.product_name,
                                bf_hrm_ls_employee.EMP_NAME
                            ')
                ->join('bf_pharmacy_indoor_requision_dtls','bf_pharmacy_indoor_requision_dtls.master_id=bf_pharmacy_indoor_requision_mst.id')
                ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_indoor_requision_dtls.product_id')
                ->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_pharmacy_indoor_requision_mst.issue_by','left')
                ->where('bf_pharmacy_indoor_requision_mst.id',$mst_id)
                ->where('bf_pharmacy_indoor_requision_dtls.status',3)
                ->get('bf_pharmacy_indoor_requision_mst')
                ->result_array();

          //  echo '<pre>'; echo print_r($records);echo '</pre>';die();

           



            return $this->load->view('indoor_requisition_issue/issue_complete_table',['records'=>$records]);
    }




    /*
    For submiting product issue Quntity and update bf_pharmacy_indoor_requision_dtls by product
    and update mst status for somethine issue
    */

    public function submit_issue(){
        $this->auth->restrict('Pharmacy.Indoor.Issue.Add');  

        $count=0;
        $something_complete=0;

    	if(isset($_POST['save'])){

            if(!isset($_POST['product_id'])){
                Template::set_message('Empty Product', 'error');
                redirect(SITE_AREA . '/indoor_requisition_issue/pharmacy/show_list');
            }

            /*
                @Stock Cheack
            */

          /*  for($i=0;$i<count($_POST['product_id']);$i++){
               $stock=$this->db->where(['product_id'=>$_POST['product_id'][$i]])->get('bf_pharmacy_stock')->row();

               if($stock){
                    if($stock->quantity_level < $_POST['issue_qnty']){
                        $not_found_product[]=$_POST['product_id'][$i];
                    }else{
                    
                    }
                }else{
                    $not_found_product[]=$_POST['product_id'][$i];
                }

            } */

             // echo "<pre>"; print_r($_POST);exit;


           // $this->db->trans_start();
            $this->db->trans_begin();

            /*
                @requisition master=status and dtls=issue quanity,status update
            */

    		//$id=$this->input->post('id');
    		for($i=0;$i<count($_POST['product_id']);$i++){

                $requi_mst_id=$_POST['master_id'][$i];

                $prv_issue=$this->db->where('id',$_POST['id'][$i])->get('bf_pharmacy_indoor_requision_dtls')->row();
    			$data['issue_qnty']=$_POST['issue_qnty'][$i]+$prv_issue->issue_qnty;

                if((int)$prv_issue->req_qnty == (int)$data['issue_qnty']){
                    $data['status']=3;
                    $count++;
                }elseif($prv_issue->req_qnty > (int)$data['issue_qnty']){
    				$data['status']=2;
                    $something_complete++;
    			}elseif($prv_issue->req_qnty < (int)$data['issue_qnty']){
    				$data['status']=3;
                    $count++;
    			}

    			$this->db->where('id',$_POST['id'][$i])->update('bf_pharmacy_indoor_requision_dtls', $data);
    		}
            
            if($count==count($_POST['issue_qnty'])){
                $mst['status']=1;
            }elseif($something_complete){
                $mst['status']=2;
            }else{
                $mst['status']=3;
            } 
                $mst['issue_date']=date('Y-m-d');
                $mst['issue_by']=$this->current_user->id;

            $this->db->where('id',$requi_mst_id)->update('bf_pharmacy_indoor_requision_mst', $mst);

            $requ_master_info=$this->db->where('id',$requi_mst_id)->get('bf_pharmacy_indoor_requision_mst')->row();

            /*     stock       */   
            $obj = new pharmacyCommonService();     
            $product_id  = $this->input->post('product_id', true);
            $issue_qnty = $this->input->post('issue_qnty', true);
            $issue_pharmacy_id = $requ_master_info->issue_pharmacy_id;
            $req_pharmacy_id =  $requ_master_info->pharmacy_id;

             /*          stock out(issue)      */
            if ($issue_pharmacy_id) {
                if ($issue_pharmacy_id == 200) {
                    $obj->stock($requi_mst_id, $product_id, $issue_qnty, 2, 1, 2, $this->current_user, $req_pharmacy_id); 
                } else {
                    $sub_pharmacy_id = $issue_pharmacy_id;
                    $obj->stock($requi_mst_id, $product_id, $issue_qnty, 1, 2, 2, $this->current_user, $req_pharmacy_id, $sub_pharmacy_id); 
                }
            }
             /*          stock in(requ)      */

            if ($req_pharmacy_id) {
                 if ($req_pharmacy_id == 200) {
                $obj->stock($requi_mst_id, $product_id, $issue_qnty, 6, 1, 1, $this->current_user, $issue_pharmacy_id);
                } else {
                    $sub_pharmacy_id = $req_pharmacy_id;
                    $obj->stock($requi_mst_id, $product_id, $issue_qnty, 0, 2, 1, $this->current_user, $issue_pharmacy_id, $sub_pharmacy_id); 
                }
            }
            /*$this->db->trans_complete();
            return true;*/
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                    Template::set_message('Unsuccessfully', 'error');
                    redirect(SITE_AREA . '/indoor_requisition_issue/pharmacy/show_list');
            } else {
                $this->db->trans_commit();
                Template::set_message('Successfully done', 'success');
                redirect(SITE_AREA . '/indoor_requisition_issue/pharmacy/show_list');
            }
    	}
    }
    /*
    @return
    */


    public function issue_print($stock_mst_id=0){
     
        $records=$this->db->select('
                                bf_pharmacy_indoor_stock_dtls.*,
                                bf_pharmacy_indoor_stock_mst.created_by,
                                bf_pharmacy_indoor_stock_mst.created_date,
                                bf_pharmacy_products.product_name,
                                bf_hrm_ls_employee.EMP_NAME
                            ')
                ->join('bf_pharmacy_indoor_stock_dtls','bf_pharmacy_indoor_stock_dtls.stock_mst_id=bf_pharmacy_indoor_stock_mst.id')
                ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_indoor_stock_dtls.product_id')
                ->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_pharmacy_indoor_stock_mst.created_by','left')
                ->where('bf_pharmacy_indoor_stock_mst.id',$stock_mst_id)
                ->get('bf_pharmacy_indoor_stock_mst')
                ->result_array();

        $hospital=$this->db->get('bf_lib_hospital')->result();
        $hospital=$hospital[0];

        // echo $this->load->view('indoor_requisition_issue/issue_print', compact('records','hospital'), true);exit;

        if(isset($records)){
            return $this->load->view('indoor_requisition_issue/issue_print', compact('records','hospital'), true);
        }else{
            return false;
        }

         

    }
        public function issue_print_complete($mst_id=0){
           
     
        $records=$this->db->select('
                                bf_pharmacy_indoor_requision_dtls.req_qnty,
                                bf_pharmacy_indoor_requision_dtls.issue_qnty,
                                bf_pharmacy_indoor_requision_mst.issue_date,
                                bf_pharmacy_indoor_requision_mst.requisition_no,
                                bf_pharmacy_indoor_requision_mst.requisition_date,
                                bf_pharmacy_indoor_requision_mst.issue_by,
                                bf_pharmacy_products.product_name,
                                bf_hrm_ls_employee.EMP_NAME
                            ')
                ->join('bf_pharmacy_indoor_requision_dtls','bf_pharmacy_indoor_requision_dtls.master_id=bf_pharmacy_indoor_requision_mst.id')
                ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_indoor_requision_dtls.product_id')
                ->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_pharmacy_indoor_requision_mst.issue_by','left')
                ->where('bf_pharmacy_indoor_requision_mst.id',$mst_id)
                ->where('bf_pharmacy_indoor_requision_dtls.status',3)
                ->get('bf_pharmacy_indoor_requision_mst')
                ->result_array();
               //echo "<pre>"; print_r($records);exit();
                

        $hospital=$this->db->get('bf_lib_hospital')->result();
        $hospital=$hospital[0];
        //echo "<pre>"; print_r($hospital);exit();
      

         echo $this->load->view('indoor_requisition_issue/issue_print_complete', compact('records','hospital'), true);exit;

   

         

    }

        public function issue_list()
    {
       $this->auth->restrict('Pharmacy.Indoor.Issue.View');
        Template::set('toolbar_title', lang("indoor_requisition_title_view"));

        if(isset($_POST['save'])){
            //echo '<pre>';print_r($_POST);die();
            if($insert_id=$this->submit_issue()){

                $print_page=$this->issue_print($insert_id);                  

                    $data['print_page']=$print_page;
                    Template::set('print_page',$print_page);
                

                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'bf_pharmacy_indoor_stock_mst');
                Template::set_message('Successfully Issued', 'success');
                redirect(SITE_AREA . '/indoor_requisition_issue/pharmacy/show_list');
            }
        }

        //======= Delete Multiple=======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            $this->auth->restrict('Pharmacy.Indoor.Issue.Delete');

            $result = FALSE;
            foreach ($checked as $pid) {
                $result = $this->shelf_model->delete($pid);
            }

            if ($result) {
                Template::set_message(count($checked) . ' ' . lang('shelf_delete_success'), 'success');
            } else {
                Template::set_message(lang('shelf_delete_failure') . $this->shelf_model->error, 'error');
            }
        }
       $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'requisition No';

        
         $condition['bf_pharmacy_indoor_requision_mst.status>=']=0;
         $pharmacy_id = ($this->current_user->role_id == 23) ? $this->current_user->pharmacy_id : 200; 
         $condition['bf_pharmacy_indoor_requision_mst.issue_pharmacy_id'] = $pharmacy_id;
            if(count($_POST)>0){
               
                 if($this->input->post('from_date')){

                $condition['bf_pharmacy_indoor_requision_mst.issue_date >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['bf_pharmacy_indoor_requision_mst.issue_date <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }   
             if($this->input->post('common_text_search')){
                $condition['bf_pharmacy_indoor_requision_mst.requisition_no like']='%'.$this->input->post('common_text_search').'%';
            }  
             
            }
        $records = $this->db->select("
                                bf_pharmacy_indoor_requision_mst.*,
                                bf_hrm_ls_employee.EMP_NAME as emp_name,
                                bf_hrm_ls_employee.CODE as code,
                                bf_pharmacy_setup.name as pharmacy_name,

                                ")  

                    ->join('bf_hrm_ls_employee','bf_hrm_ls_employee.EMP_ID=bf_pharmacy_indoor_requision_mst.requisition_by','left')  
                    ->join('bf_pharmacy_setup','bf_pharmacy_setup.id=bf_pharmacy_indoor_requision_mst.pharmacy_id','left')              
                    ->where('bf_pharmacy_indoor_requision_mst.status',1)
                    ->where($condition)
                    ->limit($limit, $offset)
                    ->order_by('bf_pharmacy_indoor_requision_mst.issue_date','DESC')
                    ->get('bf_pharmacy_indoor_requision_mst')->result();

                    //echo '<pre>';print_r($records);die();

         $data['records']=$records;  

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/indoor_requisition_issue/pharmacy/issue_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('indoor_requisition_issue/requisition_issue_list', compact('records','sl'), true);
            exit;
        } 

        Template::set($data);
        $list_view='indoor_requisition_issue/requisition_issue_list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }

}
