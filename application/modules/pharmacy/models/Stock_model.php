<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_model extends BF_Model {

	protected $table_name	= "pharmacy_stock";
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


    public function get_datewise_stocks($filter = array(), $limit = null, $offset = null, $sql_only = false)
    {
        $where_clause = "";
        $where_clause_sp = "";
        $where_clause_issue_purchase = "";
        $where_clause_prod_code = "";

        if (isset($filter['product_id'])) {
            $filter['product_id'] = (int) $filter['product_id'];
            $where_clause .= " AND sd.product_id = {$filter['product_id']} ";
        }
        if (isset($filter['product_name'])) {
            $filter['product_name'] = trim($this->db->escape($filter['product_name']),"'");
            $where_clause_sp .= " AND sp.product_name LIKE '%{$filter['product_name']}%' ";
        }
        if (isset($filter['issue_purchase'])) {
            $filter['issue_purchase'] = $this->db->escape($filter['issue_purchase']);
            $issue_purchase_col = $filter['issue_purchase'] == '1' ? 'stock_out' : 'stock_in';
            $where_clause_issue_purchase = " AND {$issue_purchase_col} > 0 ";
        }
        if (isset($filter['product_code'])) {
            $filter['product_code'] = $this->db->escape($filter['product_code']);
            $where_clause_prod_code .= " AND product_code = {$filter['product_code']} ";
        }
        if (isset($filter['from_date'])) {
            $filter['from_date'] = $this->db->escape($filter['from_date']);
            $where_clause .= " AND sm.created_date >= {$filter['from_date']} ";
        }
        if (isset($filter['to_date'])) {
            $filter['to_date'] = $this->db->escape($filter['to_date']);
            $where_clause .= " AND sm.created_date <= {$filter['to_date']} ";
        }

        $limit_clause = '';
        if (!is_null($offset) and !is_null($limit)) {
            $limit_clause = "LIMIT {$offset},{$limit}";
        } elseif (!is_null($limit) && $limit !== 0) {
            $limit_clause = "LIMIT {$limit}";
        }

        $sql = "
            SELECT
                `date`,
                product_id,
                product_name,
                product_code,
                prev_stock,
                stock_in,
                stock_out,
                last_stock
            FROM (
                SELECT
                    `date`,
                    product_id,
                    product_name,
                    product_code,
                    IF(start_date = `date`, @last_stock := 0, @last_stock) as prev_stock,
                    stock_in,
                    stock_out,
                    (@last_stock := @last_stock + stock_in - stock_out) as last_stock
                FROM (
                    SELECT
                        m1.date,
                        m1.product_id,
                        sp.product_name,
                        CONCAT(LPAD(`sp`.`category_id`,2,0),LPAD(`sp`.`sub_category_id`,2,0),LPAD(`sp`.`id`,4,0)) as product_code,
                        (
                            SELECT DATE(MIN(created_date))
                            FROM bf_pharmacy_stock_mst sm
                            JOIN bf_pharmacy_stock_dtls sd ON sm.id = sd.stock_mst_id
                            WHERE sd.product_id = m1.product_id
                        ) as `start_date`,
                        SUM(m1.stock_in) as stock_in,
                        SUM(m1.stock_out) as stock_out
                    FROM (
                        (
                            SELECT DATE(sm.created_date) as date, sd.product_id, sd.quantity as stock_in, 0 as stock_out
                            FROM bf_pharmacy_stock_mst sm
                            JOIN bf_pharmacy_stock_dtls sd ON sm.id = sd.stock_mst_id
                            WHERE sm.type = 1
                            {$where_clause}
                        )
                        UNION
                        (
                            SELECT DATE(sm.created_date) as date, sd.product_id, 0 as stock_in, sd.quantity as stock_out
                            FROM bf_pharmacy_stock_mst sm
                            JOIN bf_pharmacy_stock_dtls sd ON sm.id = sd.stock_mst_id
                            WHERE sm.type = 2
                            {$where_clause}
                        )
                    ) as m1
                    JOIN bf_pharmacy_products sp ON sp.id = m1.product_id
                    WHERE sp.status = 1
                    {$where_clause_sp}
                    GROUP BY m1.date, m1.product_id
                    ORDER BY product_id, date
                )as m2
                JOIN (SELECT @last_stock := 0) as ls_stk
                WHERE 1 = 1
                {$where_clause_prod_code}
                ORDER BY product_id, `date`
            ) as m3
            WHERE 1 = 1
            {$where_clause_issue_purchase}
            ORDER BY date, product_name
            {$limit_clause}
        ";

        if ($sql_only == true) {
            return $sql;
        }

        $query = $this->db->query($sql);
        return ($limit === 0)
                    ? $query->num_rows()
                    : $query->result_array();
    }

    public function get_datewise_prev_page_stocks($filter = array(), $limit = null, $offset = null)
    {
        $offset = $offset - $limit;
        $limit = $limit + $offset;
        $offset = 0;
        $sql = $this->get_datewise_stocks($filter, $limit, $offset, true);

        $sql = "SELECT SUM(`current_value`) as `prev_page_stock` FROM ({$sql}) as a1";

        return $this->db->query($sql)->row()->prev_page_stock;
    }

    public function add_opening_product($product_id, $quantity, $created_by = 0)
    {
        if (!$quantity) {
            return true;
        }
        $data = array(
            'product_id' => $product_id,
            'quantity_level' => $quantity,
        );

        /*         opening balance            */
        $opening_balance = [
            'pharmacy_id' => 200,
            'product_id' => $product_id,
            'qnty' => $quantity,
            'created_by' => $created_by
        ];
        if ($quantity) {
            $this->db->insert('pharmacy_opening_balance', $opening_balance);
        }

        $this->db->insert('pharmacy_stock', $data);

        $mst_data = array(
            'stock_source_in_id' => $product_id,
            'type' => 1,
            'source' => 5,
            'created_by' => $created_by,
        );

        $this->db->insert('pharmacy_stock_mst', $mst_data);

        $dtls_data = array(
            'stock_mst_id' => $this->db->insert_id(),
            'product_id' => $product_id,
            'quantity' => $quantity,
        );

        $this->db->insert('pharmacy_stock_dtls', $dtls_data);

        return TRUE;
    }

}
