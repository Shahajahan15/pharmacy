<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy_package extends Admin_Controller 
{
	    public function __construct()
    {
        parent::__construct();

        $this->load->model('package_model', NULL, TRUE);
        Assets::add_module_js('pharmacy', 'pharmacy_package.js');		
		Template::set_block('sub_nav', 'package/_sub_nav');
        
    }

       public function index()
    {
    			$records=$this->db->select("
					SQL_CALC_FOUND_ROWS
					bf_pharmacy_package.*
					",false)
				//->where($con)
				->order_by('id','DESC')
				//->limit($limit, $offset)
				->get('bf_pharmacy_package')
				->result();


				$data['records']=$records;
				$list_view='package/index'; 
		if ($this->input->is_ajax_request()) {			
            echo $this->load->view('package/index',compact('records','s1'),true);
            exit;
        }
		Template::set($data);	
		Template::set('toolbar_title','Pharmacy Package List');		
        Template::set('list_view', $list_view);
        //Template::set('search_box', $search_box);  
        Template::set_view('report_template');
		Template::render();

    }

    	public function create()
	{
	
		if(isset($_POST['save'])){
			if($insert_id=$this->save()){			

                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/pharmacy_package/pharmacy/create');
            }else{
                Template::set_message(lang('bf_msg_create_failure') . $this->product_model->error, 'error');
            }
		}

		$data['categories'] = $this->db->get('bf_pharmacy_category')->result();
		Template::set($data);			
		Template::set('toolbar_title','Create New Package');		
		Template::set_view('package/create');		
		Template::render();
	}

		/* Save */
	
	public function save($type='insert',$id=0)
	{	

		$package['package_name']=$this->input->post('package_name');
		$package['package_price']=$this->input->post('package_price');
		$package['package_discount']=$this->input->post('discount_taka');
		//echo "<pre>";print_r($package['product_name']);exit();	
			
		if($type=='insert'){

			$package['direct_sale']=1;//It willl be sale 
			$package['created_by']=$this->current_user->id;


			$this->db->trans_start();

			$master_id=$this->package_model->insert($package);
			$package_mst['master_id']=$master_id;

			for($i=0;$i<count($_POST['product_id']);$i++){
				$package_mst['product_id']=$_POST['product_id'][$i];
				$package_mst['unit_price']=$_POST['unit_price'][$i];
				$this->db->insert('bf_pharmacy_package_details',$package_mst);
			}

		}elseif($type='update'){			

			$this->db->trans_start();

			$master_id=$id;
			$this->db->where('id',$id)->update('bf_pharmacy_package',$package);

			$this->db->where('master_id',$id)->delete('bf_pharmacy_package_details');

			$package_mst['master_id']=$id;

			for($i=0;$i<count($_POST['product_id']);$i++){
				$package_mst['product_id']=$_POST['product_id'][$i];
				$package_mst['unit_price']=$_POST['unit_price'][$i];
				$this->db->insert('bf_pharmacy_package_details',$package_mst);
			}
		}
		$this->db->trans_complete();
		
		return $master_id;
		
	}

		public function edit($id){

		if(isset($_POST['save'])){
			if($insert_id=$this->save('update',$id)){				
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/pharmacy_package/pharmacy/index');
            }else{
                Template::set_message(lang('bf_msg_create_failure') . $this->product_model->error, 'error');
            }
		}

		$data['package']=$this->db->where('id',$id)->get('bf_pharmacy_package')->row();
		//print_r($data['package']);

		$data['package_products']=$this->db->select("
						bf_pharmacy_package_details.*,
						bf_pharmacy_package.package_name,
						bf_pharmacy_package.package_price,
						bf_pharmacy_products.product_name,
					")
					->join('bf_pharmacy_package','bf_pharmacy_package.id=bf_pharmacy_package_details.master_id','left')
					->join('bf_pharmacy_products','bf_pharmacy_products.id=bf_pharmacy_package_details.	product_id','left')
			        ->where('bf_pharmacy_package_details.master_id',$id)
					->get('bf_pharmacy_package_details')
					->result();

		$data['net_total']=$this->db->select("bf_pharmacy_package.package_price as net_total,")
					->join('bf_pharmacy_package','bf_pharmacy_package.id=bf_pharmacy_package_details.master_id','left')
					->where('bf_pharmacy_package_details.master_id',$id)
					->get('bf_pharmacy_package_details')
					->row()->net_total;

		//echo '<pre>'; print_r($data['net_total']);die();

		

		$data['categories'] = $this->db->get('bf_pharmacy_category')->result();
		Template::set($data);			
		Template::set('toolbar_title','Create New Package');		
		Template::set_view('package/create');		
		Template::render();
	}

  }  