<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Discount extends Admin_Controller 
{

    public function __construct() 
	{
        parent::__construct();

        $this->load->model('discount_model', NULL, true);
        $this->load->model('discount_history_model', NULL, true);
        $this->lang->load('common');
        $this->lang->load('company');
        Assets::add_module_js('pharmacy','discount.js');
        Template::set_block('sub_nav', 'discount/_sub_nav');
        $this->load->library('pagination');
        $this->load->library('GetDiscountList');
    }
  public function form_list(){
    $list_data=$this->show_list();
    $form_data=$this->create();
    $data=array();
    if(is_array($list_data))
        $data=array_merge($data,$list_data);
    if(is_array($form_data))
        $data=array_merge($data,$form_data);
    $data['form_action_url']=site_url('admin/discount/pharmacy/create');
    $data['list_action_url']=site_url('admin/discount/pharmacy/show_list');
    Template::set($data);
    Template::set_view('form_list_template');
    Template::set('toolbar_title', lang("pharmacy_discount_list"));
    Template::render();

}
    public function show_list() 
	{
        $this->auth->restrict('Pharmacy.Discount.View');

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;;
        $sl=$offset;
        $data['sl']=$sl;
         $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Order No';
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['pharmacy_supplier_list_flag']=0;

        $condition['bf_pharmacy_discount.created_by>']=0;
            if(count($_POST)>0){
               
                 if($this->input->post('from_date')){

                $condition['bf_pharmacy_discount.discount_from >=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
               
            }
 
            if($this->input->post('to_date')){

                $condition['bf_pharmacy_discount.discount_to <=']=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
            }    
             
            }
        $records=$this->db->select("
                                SQL_CALC_FOUND_ROWS 
                                bf_pharmacy_discount.*,
                                bf_pharmacy_products.product_name

                            ",false)
                    ->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_discount.product_id','left')
                    ->where($condition)
                    ->order_by('bf_pharmacy_discount.id','DESC')
                    ->limit($limit, $offset)
                    ->get('bf_pharmacy_discount')
                    ->result();

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/discount/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['records']=$records;

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('discount/list', compact('records','sl'), true);
            exit;
        }
       
        Template::set('records', $records);
        Template::set('toolbar_title', 'Pharmacy Discount Setup');
         $list_view='discount/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();
    }

    
    public function create() 
	{
        //TODO you code here	
        $data=array();
        if (isset($_POST['save']) && count($_POST)>2) 
		{
			//echo '<pre>'; print_r($_POST);die();
            if ($insert_id = $this->save()) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'Discount_model');
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
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/discount/pharmacy/show_list');
            } else 
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
                Template::set_message(lang('bf_msg_create_failure') . $this->company_model->error, 'error');
            }
        }
		$form_view='discount/create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view,$data,true);
            exit;
        }
        Template::set('toolbar_title','New Discount');
        Template::set_view($form_view);
        Template::render();
    }

    public function edit() 
	{
        $id = $this->uri->segment(5);
        if (empty($id)) 
		{
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/discount/pharmacy/show_list');
        }

        if (isset($_POST['save'])) 
		{
            $this->auth->restrict('Pharmacy.Discount.Edit');

            if ($this->save_details('update', $id)) 
			{
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'store_product_company');
                Template::set_message(lang('bf_msg_edit_success'), 'success');
                redirect(SITE_AREA . '/discount/pharmacy/show_list');
            } else 
			{
                Template::set_message(lang('bf_msg_edit_failure') . $this->company_model->error, 'error');
            }
        }
		
        $company_details = $this->company_model->find($id);
        Template::set('company_details', $company_details);
        Template::set('toolbar_title', lang('pharmacy_company_update'));
        Template::set_view('discount/company_create');
        Template::render();
    }

    private function save() 
	{
		//echo '<pre>';print_r($_POST);die();

        
        

        if ($this->input->post('discount_on') == 1) //all item
		{
			
			

			$discount['discount_type']		= 1;
			$discount['product_id']			= 0; //all item discount has no specific item .
			$discount['discount_parcent']	= $this->input->post('discount_parcent');
			$discount['discount_from']		= date('Y-m-d',strtotime(str_replace('/','-',$_POST['discount_from'])));
			$discount['discount_to']		= date('Y-m-d',strtotime(str_replace('/','-',$_POST['discount_to'])));
			$discount['created_at']			= date('Y-m-d H:i:s');
			$discount['created_by']			= $this->current_user->id;

			$this->db->insert('bf_pharmacy_discount',$discount);



        } elseif ($this->input->post('discount_on') == 2) //few specific
		{
			//for specific item 
										
        		
        		$data['created_at']			= date('Y-m-d H:i:s');
				$data['created_by']			= $this->current_user->id;
				$data['discount_type']		= 2;
				

			for($i=0;$i<count($_POST['product_id']);$i++){
				$data['product_id']			= $_POST['product_id'][$i];
				$data['discount_parcent']	= $_POST['discount_parcent'][$i];
				$data['discount_from']		= date('Y-m-d',strtotime(str_replace('/','-',$_POST['discount_from'][$i])));
				$data['discount_to']		= date('Y-m-d',strtotime(str_replace('/','-',$_POST['discount_to'][$i])));
				
				$this->db->insert('bf_pharmacy_discount',$data);

			}	


        }
        return true;
    }

    public function discount_list(){

        //print_r($_POST);die();

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['pharmacy_supplier_list_flag']=0;
        $search_box['from_date_flag']=0;
        $search_box['to_date_flag']=0;
        $search_box['by_date_flag']=1;


        /*
            @purpose : for saving single discount product
        */
    	if(isset($_POST) && count($_POST)>1 && !empty($_POST['discount'])){ 
        die();           
            if(isset($_POST['discount_id'])){
                $discount_row=$this->db->where('id',$_POST['discount_id'])->get('bf_pharmacy_discount')->row();
            }
    		
    		$data['discount_parcent']=$this->input->post('discount');
    		$data['product_id']=$this->input->post('product_id');
    		$data['discount_type']=2;
    		$data['created_at']=date('Y-m-d H:i:s');
    		$data['created_by']=$this->current_user->id;

            if(isset($_POST['discount_from'])){
                $data['discount_from']=date('Y-m-d',strtotime(str_replace('/','-',$_POST['discount_from'])));
                $data['discount_to']=date('Y-m-d',strtotime(str_replace('/','-',$_POST['discount_to'])));
            }else{
                $data['discount_from']=$discount_row->discount_from;
                $data['discount_to']=$discount_row->discount_to;
            }

    		
    		if($this->db->insert('bf_pharmacy_discount',$data)){
    			 Template::set_message(lang('bf_msg_edit_success'), 'success');
    		}

    		
    	}
        /*
            #return
        */
       

        /*
            @ get discount product list
        */
            $offset = (int)$this->input->get('per_page');
            $limit = isset($_POST['per_page'])?$this->input->post('per_page'):25;
            $sl=$offset;

            if(isset($_POST['by_date']) && strlen($_POST['by_date'])>3){
                $date=date('Y-m-d',strtotime(str_replace('/','-',$_POST['by_date'])));
            }else{
                $date=date('Y-m-d');
            }
            


            $getDiscountListObj = new GetDiscountList($this);
            $records=$getDiscountListObj->setDate($date)->setLimit($limit)->setOffset($offset)->execute();
            

            //echo '<pre>';print_r($records);die();
            $total = $getDiscountListObj->getCount();

            $this->pager['base_url'] = current_url() . '?';
            $this->pager['total_rows'] = $total;
            $this->pager['per_page'] = $limit;
            $this->pager['page_query_string'] = TRUE;

            $this->pagination->initialize($this->pager);
            

            //echo $offset;die();

        /*
            @ return
        */

    	

    	//echo '<pre>'; print_r($products);die();

       if ($this->input->is_ajax_request()) {
            echo $this->load->view('discount/product_list', compact('records','sl','search_box'), true);
            exit;
        } 
        Template::set('records',$records);
    	Template::set('sl',$sl);
        Template::set('toolbar_title', 'Pharmacy Product Discount');
        $list_view='discount/product_list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);  
        Template::set_view('report_template');
        Template::render();

    }



    public function without_from_all_list($id){

    	$all=$this->db->where('id',$id)->get('bf_pharmacy_discount')->row();

    	$products=$this->db->select("
        						bf_pharmacy_discount.*,
        						bf_pharmacy_products.product_name

        					")
        			->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_discount.product_id','left')
    				->where('bf_pharmacy_discount.discount_type',2)
    				->where('bf_pharmacy_discount.discount_from >=',$all->discount_from)
    				->where('bf_pharmacy_discount.discount_from <=',$all->discount_to)
    				->where('bf_pharmacy_discount.id >',$all->id)
    				->get('bf_pharmacy_discount')
    				->result();

    	echo json_encode($products);
    }



    /*
    give discount for individual product in individual date
    @ date format='Y-m-d'
    */
    public function discount_product($product_id=0,$date=0){
    	if(!$date){
    		$date=date('Y-m-d');    		
    	}
    	$record=$this->db
    					->where('bf_pharmacy_discount.discount_from <=',$date)
    					->where('bf_pharmacy_discount.discount_to >=',$date)
    					->where("(bf_pharmacy_discount.product_id = $product_id OR bf_pharmacy_discount.discount_type = 1 )")
    					->order_by('bf_pharmacy_discount.id','DESC')
    					->get('bf_pharmacy_discount')
    					->row_array();    	
        if(!isset($record['discount_parcent'])){
            $record['discount_parcent']=0;
        }
        //echo '<pre>';print_r($record);die();

        
    	return (object)$record;
    }




}
