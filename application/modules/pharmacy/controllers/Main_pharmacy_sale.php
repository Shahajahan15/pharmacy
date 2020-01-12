<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Main_pharmacy_sale extends Admin_Controller
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

        $this->load->model('pharmacy_sales_dtls_model', NULL, TRUE);
        $this->load->model('pharmacy_sales_mst_model', NULL, TRUE);
        $this->load->model('report/pharmacy_client_wise_report_model', NULL, TRUE);
      //  $this->load->model('patient/admission_medicine_mst_model', NULL, TRUE);
        $this->load->model('product_model', NULL, TRUE);
        $this->load->model('customer_model', NULL, TRUE);
        $this->load->model('subCustomer_model', NULL, TRUE);
        $this->load->model('category_model', NULL, true);
        $this->lang->load('main_pharmacy');
        $this->load->library('pharmacyCommonService');
        Assets::add_module_js('pharmacy', 'main_pharmacy_sale.js');
        Template::set_block('sub_nav', 'main_pharmacy/_sub_nav');

    }
    /* customer sale */




    public function show_list()
    {
        $data = array();
        $this->auth->restrict('Pharmacy.Pres.Sale.View');
        $id = $this->session->userdata('master_id');
        Template::set('toolbar_title', "Sale List");
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Sale No';
        $search_box['ticket_no_flag'] = 0;
        $search_box['contact_no_flag'] = 0;
        //$search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['pharmacy_customer_name_flag']=1;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_name_flag'] = 0;
        $condition['psm.created_by>=']=0;
            if(count($_POST)>0){

            if($this->input->post('common_text_search')){
                $condition['psm.sale_no like']='%'.trim($this->input->post('common_text_search')).'%';
            }
            if($this->input->post('customer_name')){
                $condition['cusm.customer_name like']='%'.trim($this->input->post('customer_name')).'%';
            }
            if($this->input->post('customer_type')){
                $condition['psm.customer_type']=$this->input->post('customer_type');
            }
              if($this->input->post('from_date')){
                $from_date = custom_date_format(trim($this->input->post('from_date')));
                $condition['psm.created_date >='] = $from_date." 00:00:00";
               
            }
 
            if($this->input->post('to_date')){
                $to_date = custom_date_format(trim($this->input->post('to_date')));
                $condition['psm.created_date <='] = $to_date." 23:59:59";
            }
              
            }

        // echo '<pre>';print_r($condition);die();
        

        $records = $this->db->select("
                        SQL_CALC_FOUND_ROWS 
                        psm.id,
                        psm.cost_paid_by,
                        psm.customer_type,
                        psm.created_date,
                        psm.admission_id,
                        psm.customer_id,
                        psm.patient_id,
                        psm.employee_id,
                        psm.sale_no,
                        psm.created_date as date,
                        SUM(psm.tot_bill) as tot_bill,
                        SUM(psm.tot_return) as tot_return,
                        SUM(psm.tot_less_discount) as tot_less_discount,
                        IFNULL(cusm.customer_name, IFNULL(emp.EMP_NAME, IFNULL(pmast.patient_name, IFNULL(adpmst.patient_name,0)))) as customer_name
                     ",false)
                    ->from('pharmacy_sales_mst as psm')

                    ->join('bf_patient_master as pmast','psm.patient_id=pmast.id AND psm.customer_type!=6','left')
                    ->join('bf_admission_patient as adp','adp.id=psm.admission_id AND psm.customer_type!=6','left')
                    ->join('bf_hrm_ls_employee as emp','emp.EMP_ID=psm.employee_id AND psm.customer_type!=6','left')
                    ->join('bf_pharmacy_customer as cusm','cusm.id=psm.customer_id AND psm.customer_type!=6','left')
                    ->join('bf_patient_master as adpmst','adp.patient_id=adpmst.id','left')

                    ->where($condition)
                    ->where('psm.customer_type != ',6)
                    ->group_by('psm.admission_id')
                    ->group_by('psm.customer_id')
                    ->group_by('psm.patient_id')
                    ->group_by('psm.employee_id')
                    ->limit($limit, $offset)
                    ->order_by('psm.created_date','desc')
                    ->get()
                    ->result();

        $records[] = $this->db->select("
                        SQL_CALC_FOUND_ROWS
                        psm.id,
                        '6' as customer_type,
                        0 as admission_id,
                        0 as customer_id,
                        0 as patient_id,
                        0 as employee_id,
                        SUM(psm.tot_return) as tot_return,
                        IFNULL(SUM(psm.tot_bill),0) as tot_bill,
                        IFNULL(SUM(psm.tot_less_discount),0) as tot_less_discount,
                        'Hospital' as customer_name
                     ",false)
                    ->from('pharmacy_sales_mst as psm')
                    ->where('psm.customer_type',6)
                    ->get()
                    ->row();

        foreach ($records as $key => $record) {
            if($record->customer_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('customer_id',$record->customer_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_payment_transaction')
                                        ->row();
            }elseif($record->patient_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('patient_id',$record->patient_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_payment_transaction')
                                        ->row();
            }elseif($record->admission_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('admission_patient_id',$record->admission_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_payment_transaction')
                                        ->row();
            }elseif($record->employee_id && $record->customer_type!=6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('employee_id',$record->employee_id)
                                        ->where('customer_type != ',6)
                                        ->get('bf_pharmacy_payment_transaction')
                                        ->row();
            }elseif($record->customer_type==6){
                $tot_paid=$this->db->select('IFNULL(SUM(CASE WHEN type !=3 THEN amount ELSE 0 END),0) as total_paid,
                                                IFNULL(SUM(CASE WHEN type =3 THEN amount ELSE 0 END),0) as total_return
                                                ')
                                        ->where('customer_type',6)
                                        ->get('bf_pharmacy_payment_transaction')
                                        ->row();
            }
            $records[$key]->tot_paid=$tot_paid->total_paid;
            $records[$key]->total_return=$tot_paid->total_return;
            
        }
        //echo '<pre>';print_r($records);die();
        if ($id) {
            $data['print'] = $this->sale_print($id);
            $this->session->unset_userdata('master_id');
        }
        $data['records']=$records;
        //$data['full_paid'] = $this->getFullPaid();
        //echo '<pre>';print_r($data['records']);exit;
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/main_pharmacy_sale_list/pharmacy/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('main_pharmacy_list/list', compact('records','sl'), false);
            exit;
        }

        Template::set($data);
        $list_view='main_pharmacy_list/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();
    }

    public function robin(){
       // $obj = new Commonservice();

        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        //$dirfolder = $this->makeDirFolder($folder);
        //print_r($folder);exit;\
        $patient_code = 'asasasa';
        $master_id = 22;
         $file = Zend_Barcode::draw('code128', 'image', array('text' => $patient_code), array());
         $img_file = $master_id."-".$patient_code;
         $store_image = imagepng($file,"barcode/pharmacy_sale/{$img_file}.png");
         return $img_file.'.png';
       
    }
    public function convart_in_word($number){
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $giga = floor($number / 1000000);
        // Millions (giga)
        $number -= $giga * 1000000;
        $kilo = floor($number / 1000);
        // Thousands (kilo)
        $number -= $kilo * 1000;
        $hecto = floor($number / 100);
        // Hundreds (hecto)
        $number -= $hecto * 100;
        $deca = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $result = "";
        if ($giga) {
            $result .= $this->convert_number($giga) .  "Million";
        }
        if ($kilo) {
            $result .= (empty($result) ? "" : " ") .$this->convert_number($kilo) . " Thousand";
        }
        if ($hecto) {
            $result .= (empty($result) ? "" : " ") .$this->convert_number($hecto) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($deca || $n) {
            if (!empty($result)) {
                $result .= " and ";
            }
            if ($deca < 2) {
                $result .= $ones[$deca * 10 + $n];
            } else {
                $result .= $tens[$deca];
                if ($n) {
                    $result .= "-" . $ones[$n];
                }
            }
        }
        if (empty($result)) {
            $result = "zero";
        }
        return $result;
    }
   public function set_customer($id)
    {
          // $obj = new Commonservice();
          // $arr['patient_id'] = $id;
          // $arr['id'] = $id.'-mas';
          // $obj->barCode($arr, 'pharmacy_sale');
      
        //echo '<pre>';print_r($records);exit();
  
          
 // echo '<pre>'; print_r($records); exit();
         $records = $this->pharmacy_client_wise_report_model->getPharmacyClientList('', 20000, 0,200,0); 

        $row = $this->db
            ->select('pc.customer_name, pc.customer_mobile, pc.id, SUM(pism.tot_due) as tot_due')
            //->select_sum('pism.tot_due')
            ->from('pharmacy_customer as pc')
            ->join('pharmacy_sales_mst as pism','pc.id = pism.customer_id')
            ->where('pc.id',$id)
            ->get()
            ->row();
          

    if (!$row) {
        return;
    }
    echo "$('#pharmacy_customer_type').val('" . 1 . "').attr('readonly',true).trigger('change');";
    echo "$('#pharmacy_customer_name').val('" . $row->customer_name . "').attr('readonly',true).trigger('change');";
    echo "$('#pharmacy_customer_phone').val('" . $row->customer_mobile . "').attr('readonly',true).trigger('change');";
    foreach($records as $record){
        if($record->client_id == $id){
            echo "$('#prev_due').val('" . $record->due . "').attr('readonly',true).trigger('change');";
        }
    }
    
    echo "$('#customer_id').val('" . $row->id . "').attr('readonly',true).trigger('change');";

    $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        //$dirfolder = $this->makeDirFolder($folder);
        //print_r($folder);exit;\
        $patient_code = 'IP-10234234';
        $master_id = $row->id;
        $file = Zend_Barcode::draw('code128', 'image', array('text' => $patient_code), array());
        $img_file = $master_id."-".$patient_code;
        $store_image = imagepng($file,"barcode/pharmacy_sale/{$img_file}.png");
        return $img_file.'.png';

       

    }
/*kabir*/

public function set_adm_customer($id)
        {
            $row = $this->db
            ->select('bf_admission_patient.patient_id as adm_id,bf_patient_master.patient_name,
                bf_admission_patient.id as admission_id,
                bf_patient_master.contact_no
            ')
            ->from('bf_admission_patient')
            ->join('bf_patient_master','bf_patient_master.id=bf_admission_patient.patient_id','LEFT OUTER')
            ->where('bf_admission_patient.id',$id)
            ->get()
            ->row();
        if (!$row) {
            return;
        }
        /*echo "<pre>";
        print_r($row);
        die;*/
        echo "$('#pharmacy_customer_type').val('" . 1 . "').attr('readonly',true).trigger('change');";
        echo "$('#pharmacy_customer_name').val('" . $row->patient_name . "').attr('readonly',true).trigger('change');";
        echo "$('#pharmacy_customer_phone').val('" . $row->contact_no . "').attr('readonly',true).trigger('change');";
        echo "$('#customer_id').val('" . $row->adm_id . "').attr('readonly',true).trigger('change');";
         echo "$('#admission_id').val('" . $row->admission_id . "').attr('readonly',true).trigger('change');";
        exit;
        }
/*End*/


   public function main_pharmacy_sale_list(){
        $data = array();
        $this->auth->restrict('Pharmacy.Pres.Sale.View');
        $id = $this->session->userdata('master_id');
        Template::set('toolbar_title', "Sale List");
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;
        $sl=$offset;
        $data['sl']=$sl;
        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 1;
        $search_box['common_text_search_label'] = 'Sale No';
        $search_box['ticket_no_flag'] = 0;
        $search_box['contact_no_flag'] = 0;
        //$search_box['pharmacy_customer_type_flag'] = 1;
        $search_box['pharmacy_product_list_flag']=0;
        $search_box['pharmacy_customer_name_flag']=0;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;
        $search_box['patient_name_flag'] = 0;
        $condition['psm.created_by>=']=0;
        // echo '<pre>';
        // print_r($this->session->all_userdata());
        // die();
            if(count($_POST)>0){

            if($this->input->post('common_text_search')){
                $condition['psm.sale_no like']='%'.trim($this->input->post('common_text_search')).'%';
            }
           
            if($this->input->post('from_date')){
                $from_date = custom_date_format(trim($this->input->post('from_date')));
                $condition['psm.created_date >='] = $from_date." 00:00:00";
               
            }
 
            if($this->input->post('to_date')){
                $to_date = custom_date_format(trim($this->input->post('to_date')));
                $condition['psm.created_date <='] = $to_date." 23:59:59";
            }
              
            }
        $records=$this->db
        ->select('SQL_CALC_FOUND_ROWS 
            psm.*',false)
        ->from('pharmacy_sales_mst as psm')
        ->join('pharmacy_sales_dtls as psd','psd.master_id=psm.id')
        ->where($condition)
        ->limit($limit,$offset)
        ->order_by('psm.created_date','desc')
        ->get()
        ->result();
        $data['records']=$records;     
        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        $this->pager['base_url'] = site_url() . '/admin/main_pharmacy_sale/pharmacy/main_pharmacy_sale_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        if ($this->input->is_ajax_request()) {
            echo $this->load->view('main_pharmacy_sale_list/list', compact('records','sl'), true);
            exit;
        }

        Template::set($data);
        $list_view='main_pharmacy_sale_list/list';
        Template::set('list_view', $list_view);
        Template::set('search_box', $search_box);
        Template::set_view('report_template');
        Template::render();


    }
    public function getFullPaid()
    {
        $result = $this->db
                    ->select('IF(ppt.patient_id,IF(ppt.type = 1 ,SUM(ppt.amount),0) as paid, IF(ppt.type = 2,SUM(ppt.amount),0) as due_paid,IF(ppt.type = 3,SUM(ppt.amount),0) as return_paid')
                    ->from('pharmacy_payment_transaction as ppt')
                    ->group_by('ppt.patient_id')
                    ->group_by('ppt.customer_id')
                    ->group_by('ppt.admission_patient_id')
                    ->group_by('ppt.employee_id')
                    ->group_by('ppt.type')
                    ->get()
                    ->result();
        $data_array = array();
        foreach ($result as $value) {
            if(isset($data_array[$value->master_id])) {
                $data_array[$value->master_id]['paid'] += ($value->paid + $value->due_paid);
                $data_array[$value->master_id]['return_paid'] += $value->return_paid;
            } else {
                $data_array[$value->master_id] = array(
                        'paid' => ($value->paid + $value->due_paid),
                        'return_paid' => $value->return_paid
                    );
            }
        }
        return $data_array;
    }
    public function customer_sale()
    {
        $this->auth->restrict('Pharmacy.Sale.Add');
        $data = array();
        $data['categories'] = $this->db->order_by('category_name','asc')->get('bf_pharmacy_category')->result();

        Template::set($data);   
        Template::set('toolbar_title', lang("sale_new"));       
        Template::set_view('main_pharmacy/create');     
        Template::render();
    }


    /* sale */

    public function save()
    {   

       
        $this->auth->restrict('Pharmacy.Sale.Add');

       $PObj = new pharmacyCommonService();
        $Obj = new Commonservice();
        /*  tot normal discount    */
        $customer_idd=$this->input->post('customer_id',true);
        
        $admission_id=$this->input->post('admission_id',true);

        $ncount = count($this->input->post('product_id',true));
        $tot_less_discount = $this->input->post('pharmacy_total_less_discount',true);
        $tot_paid = $this->input->post('pharmacy_total_paid',true);
        $tot_price = $this->input->post('pharmacy_total_price',true);
        $tot_due = $this->input->post('pharmacy_total_due',true);
        $customer_type = $this->input->post('customer_type',true);
        $product_id = $this->input->post('product_id', true);
        $qnty = $this->input->post('qnty', true);

        $cus_data=array();
        $tot_normal_discount = 0;
        $tot_service_discount = 0;
        for ($i = 0; $i < $ncount; $i++)
        {
            $tot_n_discount = ((float)($this->input->post('nd_amount',true)[$i]) * (float)$qnty[$i]);
            $tot_s_discount = ((float)($this->input->post('sd_amount',true)[$i]) * (float)$qnty[$i]);
            $tot_normal_discount += $tot_n_discount;
            $tot_service_discount += $tot_s_discount;
        }
        //print_r($tot_n_discount);exit;
        $this->db->trans_begin();


        if ($customer_type == 1) {

            if ($this->input->post('admission_search_id')) {
                /* $cus_data = array(
                'customer_name' => $this->input->post('pharmacy_customer_name',true),
                'customer_mobile' => $this->input->post('pharmacy_customer_phone',true),
                'created_by' => $this->current_user->id,
                'type' => 1
            );      */ 
                  
                $customer_id = 0;

                $employee_id = 0;
                $type = 1;

            }elseif($this->input->post('customer_id')){
                $cutomer_info=$this->customer_model->find($customer_idd);
                $customer_id=$cutomer_info->id;
                $employee_id = 0;
                $type = 3;
            }else{
                 $cust_data = array(
                'customer_name' => $this->input->post('pharmacy_customer_name',true),
                'customer_mobile' => $this->input->post('pharmacy_customer_phone',true),
                'created_by' => $this->current_user->id,
                'type' => 1
            );       
                  
                $customer_id = $this->customer_model->insert($cust_data);

                $employee_id = 0;
                $type = 3;
            }
            /* customer insert   */
            
        } else {
            $customer_id = 0;
            $employee_id = $this->input->post('emp_id', true);
            $type = ($customer_type == 2) ? 4 : 5;
        }

        /*   sales master insert  */

// End Kabir

        /************* Kabir*****/
         if ($this->input->post('admission_search_id')){
             $adm_data=array(

            'patient_id'=>$this->input->post('admission_search_id'),
            'admission_id'=>$admission_id,
            'assigned_by'=>14,
            'cost_paid_by'=>1,
            'assigned_date'=>date('Y-m-d'),
            'status'=>1,
            'sale_pending'=>1

        );


       $adm_id=$this->admission_medicine_mst_model->insert($adm_data);


            $dtls['master_id']           = $adm_id;
            for($i=0;$i<$ncount;$i++){

                $dtls['medicine_id']    = $product_id[$i]; 
        
                $dtls['qnty']           =  $qnty[$i];
                $dtls['remarks']        = 'admitted';
                
                $this->db->insert('bf_admission_medicine_dtls',$dtls);
            }
         }
       

        /********** End **********/

        // Kabir 

        if ($this->input->post('admission_search_id')) {
            $mst_data = array(
            'sale_no' => $PObj->getSaleNo(1, $this->current_user),
            // 'customer_id' => $customer_id,
            'admission_id' => $this->input->post('admission_id',true),
            'employee_id' => $employee_id,
            'source_id' => $adm_id,
            'tot_bill' => $tot_price,
            'tot_normal_discount' => $tot_normal_discount,
            'tot_service_discount' => $tot_service_discount,
            'tot_less_discount' => $tot_less_discount,
            'tot_paid' => $tot_paid,
            'tot_due' => $tot_due,
            'created_by' => $this->current_user->id,
            'customer_type' => $type,
            'type' => 1
        );
        }else{
            $mst_data = array(
            'sale_no' => $PObj->getSaleNo(1, $this->current_user),
            'customer_id' => $customer_id,
            'employee_id' => $employee_id,
            'tot_bill' => $tot_price,
            'tot_normal_discount' => $tot_normal_discount,
            'tot_service_discount' => $tot_service_discount,
            'tot_less_discount' => $tot_less_discount,
            'tot_paid' => $tot_paid,
            'tot_due' => $tot_due,
            'created_by' => $this->current_user->id,
            'customer_type' => $type,
            'type' => 2
        );
        }

      /*  print_r($mst_data);
        die;
*/
        $master_id = $this->pharmacy_sales_mst_model->insert($mst_data);


       // End Kabir

       

        //Kabir
       if ($this->input->post('admission_search_id')){
           
            $sale_pay['admission_id']=$admission_id;
        }else{
             
            $sale_pay['admission_id']=0;
        }

       $sale_pay['patient_id']=0;
        $sale_pay['employee_id']=0;
        $sale_pay['customer_id']=0;
        $sale_pay['source_id']=$master_id;
        $sale_pay['due_paid']=$_POST['pharmacy_total_paid'];



        if($_POST['customer_type']==1){         
            $sale_pay['customer_id']=$customer_id;
        }
        if($_POST['customer_type']==2 || $_POST['customer_type']==3){
            $sale_pay['employee_id']=$_POST['emp_id'];
        }

        $PObj->pharmacyTranjactionPayment($sale_pay,$this->current_user,1,$mst_data['customer_type']);

       

       

        /*   sales dtls insert  */
        for ($j = 0; $j < $ncount; $j++){
            $dtl_data = array(
            'master_id' => $master_id,
            'product_id' => $product_id[$j],
            'qnty' => $qnty[$j],
            'unit_price' => $this->input->post('sale_price', true)[$j],
            'normal_discount_percent' => $this->input->post('nd_percent', true)[$j],
            'normal_discount_taka' => $this->input->post('nd_amount', true)[$j],
            'service_discount_percent' => $this->input->post('sd_percent', true)[$j],
            'service_discount_taka' => $this->input->post('sd_amount', true)[$j],
            'n_discount_id' => $this->input->post('n_discount_id', true)[$j],
            'n_discount_type' => $this->input->post('n_discount_type', true)[$j],
            's_discount_id' => $this->input->post('s_discount_id', true)[$j],
            's_discount_type' => $this->input->post('s_discount_type', true)[$j]
        );
            $this->pharmacy_sales_dtls_model->insert($dtl_data);
        }


        /*      pharmacy stock  */

       /* print_r($master_id);
        die;*/

        $PObj->stock($master_id, $product_id, $qnty, 1, 1, 2, $this->current_user);



        //Kabir


/*    admission patient   */
        if ($this->input->post('admission_search_id')) {
            $admission_data = array(
                    'admission_id' => $this->input->post('admission_id',true),
                    'source_id' => $master_id,
                    'amount' => $tot_price,
                    'source_type' => 5,
                    'discount' => ($tot_normal_discount + $tot_service_discount),
                    'less_discount' => $tot_less_discount,
                    'paid' => $tot_paid,
                    'paid_type' => 1,
                );
            $Obj->admissionPatientTransaction($admission_data, $this->current_user);
        }
    

        //Kabir End
      

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
                echo json_encode(array('success' => false,'message' => $this->pharmacy_sales_mst->error));
            } else {
                $this->db->trans_commit();
                $print = $this->sale_print($master_id);
                echo json_encode(array('success' => true,'print' => $print,'message' => 'Successfully done'));
            }
        exit;

    }

    public function checkMedicineStock(){

        $medicine_id = $this->input->post("medicine_id", true);
        $row = $this->db
                ->where('product_id', $medicine_id)
                ->get('pharmacy_stock')
                ->row();

        $res = false;
        if ($row){
            $stock = ($row->quantity_level) ? $row->quantity_level : 0;

            if ($stock > 0) {
                $res = true;
            }
        }
        echo $res;
        //print_r($res);
        //return (int)$res;
        //echo json_encode(array('stock' => $res));
        exit;
    }

    /*      sale print      */

    public function sale_print($id)
    {



       
        $row=$this->db->where('id',$id)->get('pharmacy_sales_mst')->row();


       
        if($row->customer_type==1){
            //name,bf_patient_master .age,bf_patient_master.contact_no as mobile,bf_users.username")
            $c_info = $this->db
            ->select("patient_master.patient_id as code,bf_patient_master.patient_name as name,bf_patient_master .age,bf_patient_master.contact_no as mobile,
                bf_admission_bed.bed_short_name,bf_lib_reference.ref_name
                ")
                        ->from("pharmacy_sales_mst")
                        ->join("admission_patient","admission_patient.id=pharmacy_sales_mst.admission_id")
                        ->join('bf_patient_master','bf_patient_master.id=admission_patient.patient_id')
                        ->join('bf_admission_bed','bf_admission_bed.id=admission_patient.bed_id')
                        ->join('bf_lib_reference','bf_lib_reference.id=admission_patient.reference_doctor')
                        ->where('pharmacy_sales_mst.id',$id)
                        ->get()
                        ->row();


        }
        elseif ($row->customer_type==2){
            $c_info = $this->db
            ->select("bf_patient_master.patient_id as code,bf_patient_master.patient_name as name,bf_patient_master .age,bf_patient_master.contact_no as mobile")
                        ->from("pharmacy_sales_mst")
                        ->join("bf_patient_master","bf_patient_master.id=pharmacy_sales_mst.patient_id")
                        ->where('pharmacy_sales_mst.id',$id)
                        ->get()
                        ->row();

        }
        elseif ($row->customer_type==3){
            $c_info=$this->db
            ->select("0 as code,bf_pharmacy_customer.customer_name as name,0 as age,bf_pharmacy_customer.customer_mobile as mobile")
                        ->select('bf_pharmacy_customer.customer_name,bf_pharmacy_customer.customer_mobile')
                        ->from("pharmacy_sales_mst")
                        ->join('bf_pharmacy_customer','bf_pharmacy_customer.id=pharmacy_sales_mst.customer_id')
                        ->where('pharmacy_sales_mst.id',$id)
                        ->get()
                        ->row();
        }
        elseif($row->customer_type==4||$row->customer_type==5){
            $c_info=$this->db
            ->select("0 as code,bf_hrm_ls_employee.EMP_NAME as name,0 as age,0 as mobile,pharmacy_sales_mst.id,bf_hrm_ls_employee.EMP_ID")
                        ->from("pharmacy_sales_mst")
                        ->join('bf_hrm_ls_employee',' bf_hrm_ls_employee.EMP_ID=pharmacy_sales_mst.employee_id')
                        ->where('pharmacy_sales_mst.id',$id)
                        ->get()
                        ->row();
        }


        //$data = array();
        

        $records=$this->db

        ->Select('pharmacy_sales_mst.*,pharmacy_products.product_name,pharmacy_sales_dtls.unit_price,pharmacy_products.category_id,pharmacy_sales_dtls.normal_discount_percent,pharmacy_sales_dtls.normal_discount_taka,pharmacy_sales_dtls.service_discount_percent,pharmacy_sales_dtls.service_discount_taka
            ,pharmacy_sales_dtls.qnty,pharmacy_category.category_name,bf_users.username,users.pharmacy_id')
        ->join('pharmacy_sales_dtls','bf_pharmacy_sales_dtls.master_id=pharmacy_sales_mst.id')
        ->join('pharmacy_products','pharmacy_products.id=bf_pharmacy_sales_dtls.product_id')
        ->join('pharmacy_category','pharmacy_category.id=pharmacy_products.category_id')
        ->join('bf_users','bf_users.id=pharmacy_sales_mst.created_by')
        //->join('patient_master','patient_master.id=pharmacy_sales_mst.patient_id')

        //->join('lib_hospital','pharmacy_sales_dtls.id=lib_hospital.id')
        ->where('pharmacy_sales_mst.id',$id)
        ->get('pharmacy_sales_mst')
        ->result();

        $pharmacy_id = '';
        foreach($records as $record){
            $pharmacy_id = $record->pharmacy_id;
        }

        $hospital=$this->db->select('pharmacy_setup.*')
        ->where('id', $pharmacy_id)
        ->get('pharmacy_setup')->row();

        $current_user=$this->current_user->username;
        // $this->load->view('main_pharmacy/sale_print', $data, true);
//echo '<pre>';print_r($records);exit();
        $patient_info=$this->db->select('pharmacy_sales_mst.*,patient_master.patient_id,patient_master.patient_name,patient_master.age,patient_master.contact_no')
        ->join('patient_master','patient_master.id=pharmacy_sales_mst.patient_id')
        ->get('pharmacy_sales_mst')
        ->row();
           //$condition['customer_type'] = $records['0']->customer_type;
           $condition['customer_type'] = $records['0']->customer_type;

            if($condition['customer_type'] == 1)
            {
                $condition['client_id'] = $records['0']->admission_id;
            }
            elseif($condition['customer_type'] == 3)
            {
                $condition['client_id'] =  $records['0']->customer_id;
            }
            elseif($condition['customer_type'] == 4||$condition['customer_type'] == 5)
            {
                $condition['client_id'] =  $records['0']->employee_id;
            }
            //print_r($condition) ;die;

        $previous_due = $this->pharmacy_client_wise_report_model->getPharmacyClientList($condition, 0, 0,200,1);
        
        $sendData=array(
            'hospital'=>$hospital,
            'records'=>$records,
            'patient_info'=>$patient_info,
            'c_info'=>$c_info,
            'current_user'=>$current_user,
            'previous_due'=>$previous_due
            );
               
        
        return $this->load->view('main_pharmacy/sale_print', $sendData,true);
         // $this->load->view('main_pharmacy/sale_print', $sendData);
            
       }
    
    /*   medicine info */
    public function getMedicineInfo($id, $customer_type = 1)
    {
        $data = array();
        $obj = new Commonservice();
        $nDiscountObj = new pharmacyCommonService();
        $data['product_info'] = $this->product_model->find($id);
        $sale_price = $data['product_info']->sale_price;
        $medicine_id = $data['product_info']->id;
        $category_id = $data['product_info']->category_id;
        $data['category_info'] = $this->category_model->find($category_id);
        $stock_level = $this->db
                            ->where('product_id', $medicine_id)
                            ->get('pharmacy_stock')
                            ->row();
        //print_r($stock_level);exit;
        $data['stock'] = (isset($stock_level)) ? $stock_level->quantity_level: 0;
        // if ($customer_type == 1) {
        //  $data['n_discount_percent'] = $nDiscountObj->discount_product($medicine_id, 0, 1);
        // } else {
        //  $data['n_discount_percent'] = $nDiscountObj->discount_emp_doctor($customer_type);

        // }


            $data['n_discount_percent'] =0;
            $data['n_discount_amount']=0;
            $data['n_discount_id']=0;
            $data['n_discount_type']=0;


        $over_all_discount= $nDiscountObj->normal_discount();
        if($over_all_discount)
        {
            $data['n_discount_percent'] =$over_all_discount->discount_parcent;
            $data['n_discount_amount']=percent_convert_amount($over_all_discount->discount_parcent,$sale_price);

            $data['n_discount_id']=$over_all_discount->id;
            $data['n_discount_type']=1; 

        } else {
            $normal_discount= $nDiscountObj->discount_emp_doctor($customer_type, 2);
            
            if($normal_discount)
            {
            $data['n_discount_percent'] = $normal_discount->discount_parcent;
            $data['n_discount_amount'] = percent_convert_amount($data['n_discount_percent'],$sale_price);

            $data['n_discount_id']=$normal_discount->id;
            $data['n_discount_type']=2; 
            }
        }
        // $data['s_discount_percent'] = $obj->patient_discount(0,2,$id);
        // $data['s_discount_amount'] = percent_convert_amount($data['s_discount_percent'],$sale_price);

        $data['s_discount_percent'] = 0;
        $data['s_discount_amount'] = 0;
        $data['s_discount_id']=0;
        $data['s_discount_type']=3;  

        $data['total_discount_amount'] = $data['s_discount_amount'] + $data['n_discount_amount'];
        $data['qnty'] = 1;
        $data['sub_total'] = (($sale_price - $data['total_discount_amount'])*1);
        echo json_encode($data);
    }

     public function getSubCategoryByCategoryId($category_id=0){

        $records=$this->db->where('category_id',$category_id)->get('bf_pharmacy_subcategory')->result();


            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->subcategory_name.'</option>';
        }
        echo $options;
    }

    public function getProductByCategoryId($category_id=0){

        $records=$this->db->where('category_id',$category_id)->order_by('product_name','asc')->get('bf_pharmacy_products')->result();

            $options='<option value="">Select Product</option>';
            foreach ($records as $record) {
                $options.='<option value="'.$record->id.'">'.$record->product_name.'</option>';
            }
            echo $options;
    }

    public function getProductBySubCategoryId($sub_cat_id=0){
        $records=$this->db->where('sub_category_id',$sub_cat_id)->get('bf_pharmacy_products')->result();


            $options='<option value="">Select Sub Category</option>';
        foreach ($records as $record) {
            $options.='<option value="'.$record->id.'">'.$record->product_name.'</option>';
        }
        echo $options;
    }

    public function getCustomerType()
    {
        $c_type = $this->input->post('c_type');
        $emp = array();
        $type = '';
        if ($c_type == 2) {
            $emp = $this->db
                    ->where('EMP_TYPE !=', 1)
                    ->get('hrm_ls_employee')
                    ->result();
            $type = "Employee";
        }
        if ($c_type == 3) {
            $emp = $this->db
                ->where('EMP_TYPE', 1)
                ->get('hrm_ls_employee')
                ->result();
            $type = "Doctor";
        }
        $options = '';
        $options='<option value="">Select '.$type.'</option>';
        foreach ($emp as $row) {
            $options.='<option value="'.$row->EMP_ID.'">'.$row->EMP_NAME.'</option>';
        }
        echo $options;
        //echo json_encode($options);

    }
        public function sale_reprint($id){
        if ($this->input->is_ajax_request()) {
            $print_body=$this->sale_print($id);
            echo $print_body;
        }
    }

    public function getPackageInfo($id)
    {   //echo $id;
        $data = array();
        $data['package_info'] = $this->getTestInfoByPackage($id);
        //echo "<pre>";print_r($data['package_info']);exit();
        $data['type'] = 2;
        $page=$this->load->view('main_pharmacy/test_list',$data,true);
        echo json_encode($page);
        exit;
    }

    public function getTestInfoByPackage($id)
    {
        $result = $this->db
                ->select('pp.product_name,ROUND(IFNULL(ps.quantity_level,0)) AS quantity_level,dtls.unit_price, mst.package_name, mst.id, pp.id as product_id')
                ->from('bf_pharmacy_package as mst')
                ->join('bf_pharmacy_package_details as dtls', 'dtls.master_id = mst.id')
                ->join('bf_pharmacy_products as pp', 'dtls.product_id = pp.id')
                ->join('bf_pharmacy_stock as ps', 'ps.product_id = dtls.product_id','left')
                ->where('mst.id', $id)
                ->get()
                ->result();
        return $result;
    }

    // update_work 8/1/2020 nobi
    public function get_customer_info($id = null) {
        $query = $this->db->query("SELECT id
            FROM `bf_sub_customer`
            WHERE `customer_id` = '" . $id . "'")->num_rows();
        if ($query > 0) {
            echo '<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <div class="form-group">
            <label for="sub_cust_id">Sub Customer</label>
            <select class="sub-customer-auto-complete form-control" name="sub_cust_id" id="sub_cust_id">
            </select>
            </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
            <div class="form-group">
            <button style="margin-left: 1px;margin-top: 12px;" type="button" class="btn btn-sm btn-info newSubCusotmer"><input type="hidden" id="cust_id" value="' . $id . '"><i class="fa fa-plus" aria-hidden="true"></i> </button>
            
            </div>
            </div>
                <script type="text/javascript">$(document).ready(function() {
                $(".sub-customer-auto-complete").select2(
                {
                    ajax: {
                        url: siteURL + "common/report/getPharmacySubCustomerByKey",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term,
                            };
                            },
                            processResults: function (data, params) {
                                return {
                                    results: data.items
                                };
                                },
                                cache: true
                                },

                                allowClear: true,
                                placeholder: "Customer Name",
                                minimumInputLength: 1
                                });
                            });</script>';
                        } else {
                            echo '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group">
                            <button style="margin-left: 1px;margin-top: 12px;" type="button" class="btn btn-sm btn-info newSubCusotmer"><input type="hidden" id="cust_id" value="' . $id . '"><i class="fa fa-plus" aria-hidden="true"></i> New Sub Customer</button>
                            </div>
                            </div>';
                        }
                    }

                    public function create($cust_id = null) {
                        //        $data['customer_details'] = $this->db->query("SELECT id, customer_name
                        //                FROM `bf_pharmacy_customer` ORDER BY customer_name asc")->result();
                        
                                                if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'create') {
                                                    show_404();
                                                }
                        
                        
                                                if (isset($_POST['save'])) {
                                                    if ($insert_id = $this->save_details('insert', $cust_id)) {
                                        // Log the activity
                                                        log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pharmacy_supplier');
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
                                                    } else {
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
                                                        Template::set_message(lang('bf_msg_create_failure'), 'error');
                                                    }
                                                }
                        
                                                $form_view = 'sub_customer/create';
                                                if ($this->input->is_ajax_request()) {
                                                    echo $this->load->view($form_view, '', true);
                                                    exit;
                                                }
                        
                                                Template::set('toolbar_title', 'pharmacy_supplier_create');
                                                Template::set($data);
                                                Template::set_view($form_view);
                                                Template::render();
                                            }
                        
                                            private function save_details($type = 'insert', $id = 0) {
                                                if ($type == 'update') {
                                                    $_POST['id'] = $id;
                                                }
                        
                                                $data = array();
                                                $data['sub_customer_name'] = $this->input->post('pharmacy_sub_customer_name');
                                                $data['sub_customer_phone_number'] = $this->input->post('pharmacy_sub_customer_name_phn');
                                                $data['customer_id'] = $id;
                                                if ($type == 'insert') {
                                    //  $this->auth->restrict('pharmacy.Supplier.Create');
                                                    $id = $this->subCustomer_model->insert($data);
                                                    if (is_numeric($id)) {
                                                        $return = $id;
                                                    } else {
                                                        $return = FALSE;
                                                    }
                                                } elseif ($type == 'update') {
                                                    $this->auth->restrict('Pharmacy.Supplier.Edit');
                                                    $return = $this->subCustomer_model->update($id, $data);
                                                }
                                                return $return;
                                            }



}