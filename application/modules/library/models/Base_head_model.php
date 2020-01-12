<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Base_head_model extends BF_Model {

	protected $table_name	= "lib_base_head_info";
	protected $key			= "BASE_HEAD_ID";
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
	 
	 /*
	protected $validation_rules 		= array(
	
		array(
			"field"		=> "BASE_SYSTEM_HEAD",
			"label"		=> "lang:lib_base_system_head",
			"rules"		=> "required|max_length[50]"
		),
		
		array(
			"field"		=> "BASE_HEAD_TYPE", 
			"label"		=> "lang:lib_base_head_type",
			"rules"		=> "required"
		),
	
		
	);
	
	*/
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= FALSE;

	//-------------------------Get Records-------------------------------------------
	
}
