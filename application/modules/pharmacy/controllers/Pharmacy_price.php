<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Test_name controller
 */
class Pharmacy_price extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Pharmacy.Price.View');
		$this->load->model('pharmacy_price_model', NULL, TRUE);		
		
		$this->lang->load('common');
		// $this->lang->load('test');
		// Assets::add_module_js('pathology', 'test.js');
	}

	//--------------------------------------------------------------------
	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function show_list()
    {  	
		//======= update Multiple =======
		if (isset($_POST['price_update']))
		{

			$result = $this->save();
			if ($result){
			// Log the activity
			log_activity($this->current_user->id,'Price Update'. $this->input->ip_address(), 'pathology_test_name');

			Template::set_message('Price Successfully Updated', 'success');
			redirect(SITE_AREA . '/pharmacy_price/pharmacy/show_list');
			}else{
			Template::set_message('Price update Failure'. $this->test_name_model->error, 'error');
			redirect(SITE_AREA . '/pharmacy_price/pharmacy/show_list');
			}
		}		
	    $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $sl=$offset;
        $data['sl']=$sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=1;
        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['pharmacy_company_list_flag']=1;
    

        $condition['pp.id>=']=0;
            if(count($_POST)>0){
             	if($this->input->post('pharmacy_category_name')){
                $condition['pc.id']=$this->input->post('pharmacy_category_name');
                // $pharmacy_id=$this->input->post('pharmacy_name');
            } 
            if($this->input->post('pharmacy_company_id')){
                $condition['ppc.id']=$this->input->post('pharmacy_company_id');
            }
            if($this->input->post('pharmacy_product_id')){
                $condition['pp.id']=$this->input->post('pharmacy_product_id');
            }
            }

$records=$this->db->select("
	SQL_CALC_FOUND_ROWS 
    pp.product_name,
    pp.sale_price,
    pp.updated_date,
    pp.id,
    pc.category_name,
    ppc.company_name
	",false)
    ->from("pharmacy_products as pp")
    ->join("bf_pharmacy_category as pc","pc.id=pp.category_id","left")
    ->join("bf_pharmacy_product_company as ppc","ppc.id=pp.company_id","left")
    ->where($condition)
    ->limit($limit,$offset)
    ->get()
    ->result();
$total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/pharmacy_price/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
		$data['records'] = $records;

         if ($this->input->is_ajax_request()) {
             echo $this->load->view('pharmacy_price/pharmacy_medicine_price', compact('records','sl'),true);
            exit();         
        }

		$list_view = 'pharmacy_price/pharmacy_medicine_price';
		Template::set($data);	
		Template::set('list_view', $list_view);
		Template::set('search_box', $search_box);
	    Template::set('toolbar_title', "Phramacy Medicine Price List");
		Template::set_view('report_template');							
		Template::render();
    }           

	
	private function save($type='insert', $id=0)
	{	
		$this->db->trans_start();
		for($i=0;$i<count($_POST['product_id']);$i++){
			if($_POST['sale_price'][$i]>0){
				$data['sale_price']=$_POST['sale_price'][$i];
				$data['updated_date']=date('Y-m-d H:i:s');
				$this->db->where('id',$_POST['product_id'][$i])->update('bf_pharmacy_products',$data);
				unset($data);
			}
		}
		$this->db->trans_complete();
		if($this->db->trans_status()===False){
			return false;
		}else{
			return true;
		}
		
	}
    
}