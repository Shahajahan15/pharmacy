<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * supplier_setup controller
 */
class Generic_setup extends Admin_Controller 
{
    /* ========================================================================================
      Start Main Store
      ======================================================================================== */

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

        $this->load->model('generic_model', NULL, TRUE);
       $this->load->model('Generic_group_model', NULL, TRUE);
        $this->lang->load('common');
        $this->lang->load('generic');
                $this->lang->load('generic_group');

        //Assets::add_module_js('store', 'supplier.js');
        Template::set_block('sub_nav', 'generic_setup/_sub_nav_generic_name_info');
    }

    public function show_list() 
    {
        //$this->auth->restrict('Store.Supplier.View');

        //======= Delete Multiple =======
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) 
        {
            $this->auth->restrict('Pharmacy.GenericName.Delete');
            $result = FALSE;
            foreach ($checked as $store_id) 
            {
                $result = $this->generic_model->delete($store_id);
            }
            if ($result) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_delete_record') . ': ' . $store_id . ' : ' . $this->input->ip_address(), 'pharmacy_generic_name');

                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
            {
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->generic_model->error, 'error');
            }
        }
             

	    $this->db->select('pharmacy_generic_name.*,pharmacy_product_generic_group.group_name');
	    $this->db->join('pharmacy_product_generic_group','pharmacy_product_generic_group.id=pharmacy_generic_name.group_id');
	    $records=$this->db->get('pharmacy_generic_name')->result_array();
        Template::set('records', $records);
        Template::set('toolbar_title', lang("pharmacy_generic_view"));
        Template::set_view('generic_setup/generic_info_list');
        Template::render();
    }

    //--------------------------------------------------------------------
    /**
     * Creates a building object.
     *
     * @return void
     * */
    //--------------------------------------------------------------------

    public function create() 
    {
        //TODO you code here

        if (isset($_POST['save'])) 
        {
            if ($insert_id = $this->save()) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pharmacy_generic_name');

                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/generic_setup/pharmacy/show_list');
            } else 
            {
                Template::set_message(lang('bf_msg_create_failure') . $this->generic_model->error, 'error');
            }
        }



        $group_details = $this->Generic_group_model->find_all();
     

        Template::set('toolbar_title', lang("pharmacy_generic_create"));
        Template::set('group_details', $group_details);
        Template::set_view('generic_setup/generic_info_create');
        Template::render();
    }

    //--------------------------------------------------------------------
    /**
     * Allows editing of Building Details data.
     *
     * @return void
     */
    //--------------------------------------------------------------------

    public function edit() 
    {
        $id = $this->uri->segment(5);
        if (empty($id)) 
        {
            Template::set_message(lang('bf_act_invalid_record_id'), 'error');
            redirect(SITE_AREA . '/generic_setup/pharmacy/show_list');
        }if (isset($_POST['save'])) 
        {
            $this->auth->restrict('Pharmacy.GenericName.Edit');
            $data['modify_by'] = $this->current_user->id;
            $data['modify_date'] = date('Y-m-d H:i:s');
            if ($this->save('update', $id)) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pharmacy_generic_name');

                Template::set_message(lang('bf_msg_edit_success'), 'success');
                redirect(SITE_AREA . '/generic_setup/pharmacy/show_list');
            } else 
            {
                Template::set_message(lang('bf_msg_edit_failure') . $this->generic_model->error, 'error');
            }
        }
        
        $group_details = $this->Generic_group_model->find_all();
        $records= $this->generic_model->find($id);
        Template::set('records', $records);
        Template::set('toolbar_title', lang('pharmacy_generic_update'));
       	Template::set('group_details', $group_details);
        Template::set_view('generic_setup/generic_info_create');
        Template::render();
    }

    //--------------------------------------------------------------------
    //--------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------

    /**
     * Summary
     *
     * @param String $type Either "insert" or "update"
     * @param Int    $id    The ID of the record to update, ignored on inserts
     *
     * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
     */
   
         private function save($type = 'insert', $id = 0) 
		    {
		        if ($type == 'update') 
		        {
		            $_POST['id'] = $id;
		        }

		        $data = array();
		        $data['group_id'] = $this->input->post('group_id');

		       
		        $data['generic_name'] = $this->input->post('pharmacy_generic_name');
		        $data['status'] = $this->input->post('bf_status');
		        $data['created_by'] = $this->current_user->id;
		        $data['created_date'] = date('Y-m-d H:i:s');
		         if ($type == 'insert') 
		        {
		            $this->auth->restrict('pharmacy.GenericName.Create');
		            $id = $this->generic_model->insert($data);
		            if (is_numeric($id)) 
		            {
		                $return = $id;
		            } else 
		            {
		                $return = FALSE;
		            }
		        }  elseif ($type == 'update') 
		        {
		            $this->auth->restrict('Pharmacy.GenericName.Edit');
		            $data['modify_by'] = $this->current_user->id;
		            $data['modify_date'] = date('Y-m-d H:i:s');
		            $return = $this->generic_model->update($id, $data);
		        }
		        return $return;
		        
		    }
    

    /* ========================================================================================
      End Main Store
      ======================================================================================== */
}
