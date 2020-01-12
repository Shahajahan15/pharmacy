<?php
/**
* 
*/
class Canteen_purchase extends Admin_Controller
{
	
	 public function __construct()
	{
        parent::__construct();
        $this->load->model('canteen_report_model');
        
        
	}

	public function index(){

		    $data = array();
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        

        $this->load->library('pagination');
        $offset=$this->input->get('per_page');
        $limit=isset($_POST['per_page'])?$this->input->post('per_page'):25;
        $data['sl']=$offset;

        $condition['bcspm.id>=']=0;
        $first_date = date('Y-m-d');
        $second_date = date('Y-m-d');
        if(count($_POST)>0){
             if($this->input->post('from_date')){
                $first_date=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));
                $first_date=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('from_date'))));   
                }
            else{
                   $first_date=date("Y-m-d"); 
                    }
            if($this->input->post('to_date')){
                    $second_date=date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('to_date'))));
                    }
            else{
                    $second_date=date("Y-m-d");
                    }


        }

        $records = $this->canteen_report_model->getPurchaseList($condition,$first_date,$second_date,$limit,$offset);
       //$records=(array)$records;
       //echo "<pre>";print_r($records);exit();


        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/canteen/report/canteen_purchase/index' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view = 'canteen/purchase_list';
        $data['search_box'] = $search_box;
        $data['records'] = $records;
        $data['toolbar_title'] = 'Cafetarea Purchase Report';
        $data['list_view'] = $list_view ;

        if ($this->input->is_ajax_request()) {
        echo $this->load->view($list_view, $data, true);
                                   exit;
        }

        Template::set($data);
        Template::set_view('report_template');
        Template::render();
	}

}
?>