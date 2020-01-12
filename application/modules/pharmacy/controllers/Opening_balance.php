<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Opening_balance extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict('Pharmacy.Opening.Balance.Add');

        $this->lang->load('common');
        $this->load->model('stock_model');
        $this->load->model('stock_in_model');
        $this->load->model('stock_in_dtls_model');
        $this->load->model('opening_balance_model');
        $this->load->model('product_model');

        Assets::add_module_js('pharmacy','opening_balance');
    }


    public function create($offset = 0)
    {

        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);

        $search_box['product_name_flag'] = 1;
        $search_box['is_serial_flag'] = 1;
        $search_box['pharmacy_list_flag'] = 1;
        $search_box['category_name_flag']=1;
       // $search_box['company_name_flag']=1;
        $search_box['pharmacy_company_list_flag']=1;

     

        $condition['pharmacy_products.status >=']=0;


            if($this->input->post('product_name')){            
                $condition['pharmacy_products.product_name like']='%'.trim($this->input->post('product_name')).'%';
            }
             if($this->input->post('category_name')){            
                $condition['bf_pharmacy_category.category_name like']='%'.trim($this->input->post('category_name')).'%';
            }
              if($this->input->post('pharmacy_company_id')){
                $condition['pharmacy_products.company_id']=$this->input->post('pharmacy_company_id');
            }
            if($this->input->post('pharmacy_name')){            
                $pharmacy_id=$this->input->post('pharmacy_name');
            }else{
               $pharmacy_id=200; 
            }
        //echo '<pre>';print_r($condition);exit;
            //IFNULL(bf_pharmacy_stock.quantity_level,0) as quantity_level
        $branch_id=$this->current_user->branch_id;
        $records = $this->db->select(
            'SQL_CALC_FOUND_ROWS
            bf_pharmacy_products.*,
            pharmacy_product_company.company_name,
            bf_pharmacy_category.category_name

            ',false)
        ->join('pharmacy_product_company','pharmacy_product_company.id=bf_pharmacy_products.company_id','left')
        ->join('bf_pharmacy_category','bf_pharmacy_category.id=bf_pharmacy_products.category_id','left') 
       // ->join("bf_pharmacy_stock","bf_pharmacy_stock.product_id=bf_pharmacy_products.id","left") 
        ->join("bf_pharmacy_opening_balance","bf_pharmacy_opening_balance.product_id=bf_pharmacy_products.id AND bf_pharmacy_opening_balance.pharmacy_id=$pharmacy_id","left")   
        ->where($condition)
        ->where('bf_pharmacy_opening_balance.id is NULL')
        ->limit($limit, $offset)
       // ->order_by('bf_pharmacy_stock.quantity_level','DESC')
        ->get('bf_pharmacy_products')
        ->result();
        //echo $this->db->last_query();
        $data['records']=$records;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/opening_balance/pharmacy/create' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);


        //echo '<pre>';print_r($records);die();
        $pharmacy['id']=$pharmacy_id;
        if($pharmacy_id==200){
           $pharmacy['name']='Main Pharmacy';
        }else{
            $pharmacy['name']=$this->db->where('id',$pharmacy_id)->get('bf_pharmacy_setup')->row()->name;
        }
        $data['pharmacy']=(object)$pharmacy;
        
        $list_view='opening_balance/product_list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }

        Template::set($data);
        Template::set('toolbar_title', 'Opening Balance Create');
        Template::set('search_box', $search_box);
        Template::set('list_view', $list_view);
        Template::set_view('report_template');
        //Template::set_block('sub_nav', 'pharmacy/opening_balance/_sub_report');
        Template::render();
    }
    public function opening_balance_list(){
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        //echo "<pre>"; print_r($search_box); exit();
        $search_box['product_name_flag'] = 1;
        $search_box['is_serial_flag'] = 1;
        $search_box['pharmacy_list_flag'] = 1;
        $search_box['category_name_flag']=1;
       // $search_box['company_name_flag']=1;
        $search_box['pharmacy_company_list_flag']=1;

        $condition['pp.status >='] = 0;

            if($this->input->post('product_name')){            
                $condition['pp.product_name like'] = '%'.trim($this->input->post('product_name')).'%';
            }
             if($this->input->post('category_name')){            
                $condition['pc.category_name like']='%'.trim($this->input->post('category_name')).'%';
            }
              if($this->input->post('pharmacy_company_id')){
                $condition['pp.company_id']=$this->input->post('pharmacy_company_id');
            }
            if($this->input->post('pharmacy_name')){            
                $condition['pob.pharmacy_id']=$this->input->post('pharmacy_name');
            }else{
               $condition['pob.pharmacy_id']=200; 
            }
       $branch_id=$this->current_user->branch_id;
       $records= $this->db
        ->select('
             SQL_CALC_FOUND_ROWS
            pob.qnty,pob.updated_qnty,pob.pharmacy_id,pob.id,pob.product_id,pp.product_name,ppc.company_name,pc.category_name',false)
        ->from('bf_pharmacy_opening_balance as pob')
        ->join('bf_pharmacy_products as pp','pp.id=pob.product_id')
        ->join('bf_pharmacy_product_company as ppc','ppc.id=pp.company_id')

        ->join('bf_pharmacy_category as pc','pc.id=pp.category_id')
        //->where($condition)
        ->where($condition)
        ->order_by('pp.product_name','asc')
        ->limit($limit,$offset)
        ->get()
        ->result();

        $data['records'] = $records;
   
       $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/opening_balance/pharmacy/opening_balance_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);   
        $list_view='opening_balance/list';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }        

        Template::set($data);
        Template::set('toolbar_title', 'Product Opening Balance List');
        Template::set('search_box', $search_box);
        Template::set('list_view', $list_view);
        Template::set_view('report_template');
        //Template::set_block('sub_nav', 'pharmacy/opening_balance/_sub_report');
        Template::render();
    }
    public function opening_balance_add(){

        $this->db->trans_start();
             $this->opening_balance_save($_POST);
         $this->db->trans_complete();

        if($this->db->trans_status()==false){
            echo json_encode(['status'=>false,'message'=>'Stored Failed']);
        }else{
            echo json_encode(['status'=>true,'message'=>'Successfully Stored']);
        }

        
    }

    public function opening_balance_update(){

//echo "<pre>"; print_r($_POST['updated_qnty']);exit();
    if($_POST['updated_qnty'] == NULL){
    $this->db->trans_start();
             $this->opening_balance_updating($_POST);
    $this->db->trans_complete();

        if($this->db->trans_status()==false){
            echo json_encode(['status'=>false,'message'=>'Stored Failed']);
        }else{
            echo json_encode(['status'=>true,'message'=>'Successfully Updated']);
        }
    }
       else
        {
            echo json_encode(['status'=>false,'message'=>'Do not try, It is already updated!']);
         } 

        
    }

    public function opening_balance_multi_add(){
    	$this->db->trans_start();
            for($i=0;$i<count($_POST['product_id']);$i++){
                $data['product_id']=$_POST['product_id'][$i];
                $data['qnty']=$_POST['qnty'][$i];
                $data['pharmacy_id']=$_POST['pharmacy_id']; //main pharmacy

                $this->opening_balance_save($data);
            }
         $this->db->trans_complete();

    	if($this->db->trans_status()==false){
    		echo json_encode(['status'=>false,'message'=>'Stored Failed']);
		}else{
			echo json_encode(['status'=>true,'message'=>'Successfully Stored']);
		}

    	
    }

    public function opening_balance_save($input){
        

        $data['product_id']     = $input['product_id'];
        $data['qnty']           = $input['qnty'];
        $data['pharmacy_id']    = $input['pharmacy_id'];
        $data['created_by'] = $this->current_user->id;
        $master_id=$this->opening_balance_model->insert($data);

        /*
            @stock in
        */
        if($data['pharmacy_id']==200){
            $current_stock=$this->db->where('product_id',$data['product_id'])->get('bf_pharmacy_stock')->row();
            if($current_stock){
                $stock_up['quantity_level']=$data['qnty']+$current_stock->quantity_level;
                $this->db->where('id',$current_stock->id)->update('bf_pharmacy_stock',$stock_up);
            }else{
                $stock_up['product_id']=$data['product_id'];
                $stock_up['quantity_level']=$data['qnty'];
                $this->db->insert('bf_pharmacy_stock',$stock_up);
            }
        }else{
            $current_stock=$this->db->where('product_id',$data['product_id'])->where('pharmacy_id',$data['pharmacy_id'])->get('bf_pharmacy_indoor_stock')->row();
            if($current_stock){
                $stock_up['quantity_level']=$data['qnty']+$current_stock->quantity_level;
                $this->db->where('id',$current_stock->id)->where('pharmacy_id',$data['pharmacy_id'])->update('bf_pharmacy_indoor_stock',$stock_up);
            }else{
                $stock_up['product_id']=$data['product_id'];
                $stock_up['quantity_level']=$data['qnty'];
                $stock_up['pharmacy_id']=$data['pharmacy_id'];
                $this->db->insert('bf_pharmacy_indoor_stock',$stock_up);
            }
        }
        /*
            @ return
        */

        /*
            @stock history
        */
        if($data['pharmacy_id']==200){
            $stock_mst['stock_source_in_id']=$master_id;
            $stock_mst['type']=1;//stock in
            $stock_mst['source']=5;//opening balance
            $stock_mst['created_by']=$this->current_user->id;

            $stock_mst_id=$this->stock_in_model->insert($stock_mst);

            $stock_dtls['stock_mst_id']=$stock_mst_id;
            $stock_dtls['product_id']=$data['product_id'];
            $stock_dtls['quantity']=$data['qnty'];
            $this->db->insert('bf_pharmacy_stock_dtls',$stock_dtls);
        }else{
            $stock_mst['store_source_in_id']=$master_id;
            $stock_mst['pharmacy_id']=$data['pharmacy_id'];
            $stock_mst['type']=1;//stock in
            $stock_mst['source']=5;//opening balance
            $stock_mst['created_by']=$this->current_user->id;

            $this->db->insert('bf_pharmacy_indoor_stock_mst',$stock_mst);

            $stock_mst_id=$this->db->insert_id();

            $stock_dtls['stock_mst_id']=$stock_mst_id;
            $stock_dtls['product_id']=$data['product_id'];
            $stock_dtls['quantity']=$data['qnty'];
            $this->db->insert('bf_pharmacy_indoor_stock_dtls',$stock_dtls);
        }


        /*
            @ retun
        */
    }

    public function opening_balance_updating($input){

        $id     = $input['id'];
        $data = array();
        $data['updated_qnty'] = $input['qnty_c']; 
        $data['updated_date'] = date('Y-m-d');
        $data['updated_by'] = $this->current_user->id; 
        $data['qnty']           = $input['qnty'];
        $master_id=$this->opening_balance_model->update($id,$data);


                /*
            @stock in
        */
        $data['product_id'] = $input['product_id'];
        $data['pharmacy_id']    = $input['pharmacy_id'];
        $data['qnty_c']    = $input['qnty_c'];
        $update_stock_qnty = $data['qnty'] - $data['qnty_c'] ;
        if($data['pharmacy_id']==200){
            $pharmacy_stock=$this->db->where('source','5')->where('stock_source_in_id',$id)->get('bf_pharmacy_stock_mst')->row();
            $dtls['quantity'] = $data['qnty'];
            $this->db->where('stock_mst_id',$pharmacy_stock->id)->update('bf_pharmacy_stock_dtls',$dtls);

            $current_stock=$this->db->where('product_id',$data['product_id'])->get('bf_pharmacy_stock')->row();
            if($current_stock){
                $stock_up['quantity_level']=$update_stock_qnty+$current_stock->quantity_level;
                $this->db->where('id',$current_stock->id)->update('bf_pharmacy_stock',$stock_up);
            }else{
                $stock_up['product_id']=$data['product_id'];
                $stock_up['quantity_level'] = $update_stock_qnty;
                $this->db->insert('bf_pharmacy_stock',$stock_up);
            }
        }else{
            $pharmacy_stock = $this->db->where('source','5')->where('store_source_in_id',$id)->get('bf_pharmacy_indoor_stock_mst')->row();
            $dtls['quantity'] = $data['qnty'];

            $this->db->where('stock_mst_id',$pharmacy_stock->id)->update('bf_pharmacy_indoor_stock_dtls',$dtls);

            $current_stock=$this->db->where('product_id',$data['product_id'])->where('pharmacy_id',$data['pharmacy_id'])->get('bf_pharmacy_indoor_stock')->row();
            if($current_stock){
                $stock_up['quantity_level']=$update_stock_qnty+$current_stock->quantity_level;
                $this->db->where('id',$current_stock->id)->where('pharmacy_id',$data['pharmacy_id'])->update('bf_pharmacy_indoor_stock',$stock_up);
            }else{
                $stock_up['product_id']=$data['product_id'];
                $stock_up['quantity_level']=$update_stock_qnty;
                $stock_up['pharmacy_id']=$data['pharmacy_id'];
                $this->db->insert('bf_pharmacy_indoor_stock',$stock_up);
            }
        }
    }


    public function test() {
        $result = $this->db->get('pharmacy_opening_balance')->result();

        /*foreach ($result as $key => $val) {
            $data = [
                'id' => $key+1,
                'product_id' => $val->product_id,
                'quantity_level' => $val->qnty
            ];

            $this->db->insert("pharmacy_stock", $data);
        } */


        foreach ($result as $key => $val) {
            $master_data = [
                'id' => $key+1,
                'stock_source_in_id' => $val->id,
                'type' => 1,
                'source' => 5,
                'pharmacy_id' => 200,
                'created_by' => $val->created_by
            ];
            $stock_mst_id=$this->stock_in_model->insert($master_data);

            $dtls_data = [
                'id' => $key+1,
                'stock_mst_id' => $stock_mst_id,
                'product_id' => $val->product_id,
                'quantity' => $val->qnty
            ];

            $this->db->insert('pharmacy_stock_dtls', $dtls_data);
            }

        echo '<pre>';print_r($result);exit;
    }

}