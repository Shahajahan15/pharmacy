<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends BF_Model {

	protected $table_name	= "menu";
	protected $key			= "id";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";
	protected $log_user 	= FALSE;
	protected $set_created	= false;
	protected $set_modified = false;

	/*
		Customize the operations of the model without recreating the insert, update,
		etc methods by adding the method names to act as callbacks here.
	 */
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 		= array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	/*
		For performance reasons, you may require your model to NOT return the
		id of the last inserted row as it is a bit of a slow method. This is
		primarily helpful when running big loops over data.
	 */
	protected $return_insert_id 	= TRUE;
	protected $_debug 				= TRUE;

	// The default type of element data is returned as.
	protected $return_type 			= "object";

	// Items that are always removed from data arrays prior to
	// any inserts or updates.
	protected $protected_attributes = array();

	/*
		You may need to move certain rules (like required) into the
		$insert_validation_rules array and out of the standard validation array.
		That way it is only required during inserts, not updates which may only
		be updating a portion of the data.
	 */
	protected $validation_rules 		= array(

	);
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= FALSE;

	protected $menu_query_path          = ROOTPATH.'/db/'.TODAY_DATE.'/menus/';

	//-------------------------Get Records-------------------------------------------

	public function insert($data = null)
    {
        $parent = parent::insert($data);

        if (ENVIRONMENT == 'development')
        {
            write_file($this->menu_query_path.date('Y-m-d').'.sql', $this->db->last_query().";\n", 'a');
        }

        return $parent;
    }

    public function update($where = null, $data = null)
    {
        $parent = parent::update($where, $data);

        if (ENVIRONMENT == 'development')
        {
            write_file($this->menu_query_path.date('Y-m-d').'.sql', $this->db->last_query().";\n", 'a');
        }

        return $parent;
    }

    public function delete($id = null)
    {
        $parent = parent::delete($where, $data);

        if (ENVIRONMENT == 'development')
        {
            write_file($this->menu_query_path.date('Y-m-d').'.sql', $this->db->last_query().";\n", 'a');
        }

        return $parent;
    }

    public function filter_modules_list()
    {
        return $this->select('id, name')
                        ->where([
                            'parent_id' => 0,
                            'parent_parent_id' => 0,
                        ])
                        ->order_by('name')
                        ->as_array()
                        ->find_all();
    }

    public function filter_items_list()
    {
        return $this->select('id, name, parent_id')
                        ->where([
                            'parent_id !=' => 0,
                            'parent_parent_id' => 0,
                        ])
                        ->order_by('name')
                        ->as_array()
                        ->find_all();
    }

    public function filter_sub_items_list()
    {
        return $this->select('id, name, parent_id, parent_parent_id')
                        ->where([
                            'parent_id !=' => 0,
                            'parent_parent_id !=' => 0,
                        ])
                        ->order_by('name')
                        ->as_array()
                        ->find_all();
    }


}
