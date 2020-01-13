<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class subCustomer_Model extends BF_Model {

	protected $table_name	= "bf_sub_customer";
	protected $key		= "id";
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
		/*array(
			"field"		=> "supplier_name",
			"label"		=> "supplier_name",
			"rules"		=> "required|max_length[50]"
		),
		array(
			"field"		=> "company_name",
			"label"		=> "company_name",
			"rules"		=> "required|max_length[150]"
		),
		array(
			"field"		=> "mobile_no",
			"label"		=> "mobile_no",
			"rules"		=> "required|max_length[15]"
		),
		array(
			"field"		=> "phone_no",
			"label"		=> "phone_no",
			"rules"		=> "max_length[15]"
		),
		array(
			"field"		=> "email",
			"label"		=> "email",
			"rules"		=> "required|max_length[50]"
		),
		array(
			"field"		=> "address",
			"label"		=> "address",
			"rules"		=> "required|max_length[255]"
		),
		array(
			"field"		=> "b_f_balance",
			"label"		=> "b_f_balance",
			"rules"		=> "max_length[11]"
		),
	
		array(
			"field"		=> "status",
			"label"		=> "status",
			"rules"		=> "max_length[1]"
		),*/
	);
	
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= FALSE;

	//--------------------------------------------------------------------

}