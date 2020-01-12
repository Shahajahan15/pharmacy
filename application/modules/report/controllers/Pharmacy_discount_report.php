<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Booth controller
 */
class Pharmacy_discount_report extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('common');
        $this->load->model('pharmacy_discount_model');
    }


    public function pharmacy_all_discount_report($offset = 0)
    {
        $this->auth->restrict('Report.PharmacyDisc.View');
        $data = array(); 
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['appointment_type_flag'] = 0;
        $search_box['pharmacy_product_list_flag']=1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['common_text_search_flag'] = 0;
        $search_box['pharmacy_company_list_flag']=1;
        $search_box['pharmacy_list_flag']=1;

        $pharmacy_name='Main Pharmacy';
       
        $where="";
        $pharmacy_id="";
        if(count($_POST)>0){
              if($this->input->post('pharmacy_category_name')){
                $where = "AND mp.category_id=".$this->input->post('pharmacy_category_name');
            } 
            if($this->input->post('pharmacy_company_id')){
                $where = "AND mp.company_id=".$this->input->post('pharmacy_company_id');
              
            }
            if($this->input->post('pharmacy_product_id')){
                $where = "AND mp.product_id=".$this->input->post('pharmacy_product_id');
            }
            if($from_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('from_date')))){
              $from_formated_date = $from_date->format('Y-m-d');
              $where = "AND mp.created_date >=".$from_formated_date;
            }
            if($to_date = \DateTime::createFromFormat('d/m/Y',trim($this->input->post('to_date')))){
              $to_formated_date = $to_date->format('Y-m-d');
              $where = "AND mp.created_date <=".$to_formated_date;
            }   
          if($this->input->post('pharmacy_name')){
              $pharmacy_id=$this->input->post('pharmacy_name');
          }
        }
        // $pharmacy_id=$this->input->post('pharmacy_name');
        if($pharmacy_id=='' || $pharmacy_id!=200){
            $pharmacy_name=$this->db->where('id',$pharmacy_id)
                                    ->get('bf_pharmacy_setup')
                                    ->row();
        }


        $data['pharmacy_name']=$pharmacy_name;

        $records=$this->pharmacy_discount_model->select_all_medicine_discount_info($where,$pharmacy_id);

        $data['records']=$records;
        
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/pharmacy_discount_report/report/pharmacy_all_discount_report' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view='report/pharmacy_discount/pharmacy_discount_report';


        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, compact('pharmacy_name','pharmacy_id','records','sl'), true);
            exit;
        }

        $set=array(
            'pharmacy_name' => $pharmacy_name,
            'pharmacy_id' => $pharmacy_id,
            'records' => $records,
            'toolbar_title' => 'Pharmacy Discount Report',
            'data' => $data,
            'list_view' => $list_view,
            'search_box' => $search_box
            );

        Template::set($set);
        Template::set_view('report_template');
        Template::render();
    }

}