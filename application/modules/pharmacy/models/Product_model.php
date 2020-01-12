<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends BF_Model {

	protected $table_name	= "pharmacy_products";
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

	protected $insert_validation_rules 	= array();
	protected $skip_validation 		= FALSE;

	//--------------------------------------------------------------------


	public static function prodcut_return_reasons(){
		return [
			'1' => 'Date Expired',
			'2' => 'Product Damage',
			'3' => 'Unexpected Reason'
		];
	}


    public function current_product_total_stock($product_id)
    {
        return $this->db->select('(IFNULL(ss.quantity_level,0) + IFNULL(sds.quantity_level,0)) as `total`')
                        ->from('pharmacy_products sp')
                        ->join('pharmacy_stock ss','ss.product_id = sp.id','left')
                        ->join('pharmacy_indoor_stock sds','sds.product_id = sp.id','left')
                        ->get()->row()->total;
    }


    public function get_product_datewise_stocks($filter = null, $limit = null, $offset = 0)
    {
        $this->db->select('ssm.created_date, ssm.stock_source_in_id, ssm.stock_source_out_id, ssm.type, ssm.source, ssd.quantity')
                    ->from('pharmacy_stock_mst as ssm')
                    ->join('pharmacy_stock_dtls as ssd','ssm.id = ssd.stock_mst_id')
                    ->where($filter)
                    ->order_by('ssm.created_date DESC');

        if ($limit === 0) {
            return $this->db->get()->num_rows();
        }

        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get()->result_array();
    }


    public function get_stocks_ledger($filter = array(), $limit = null, $offset = 0, $sql_only = false)
    {
        $where_clause = "";

        if (isset($filter['product_id'])) {
            $filter['product_id'] = (int) $filter['product_id'];
            $where_clause .= " AND sp.product_id = {$filter['product_id']} ";
        }
        // if (isset($filter['issue_purchase'])) {
        //     $filter['issue_purchase'] = $this->db->escape($filter['issue_purchase']);
        //     $issue_purchase_col = $filter['issue_purchase'] == '1' ? 'stock_out' : 'stock_in';
        //     $where_clause_issue_purchase = " AND {$issue_purchase_col} > 0 ";
        // }

        $limit_clause = '';
        if (!is_null($offset) and !is_null($limit)) {
            $limit_clause = "LIMIT {$offset},{$limit}";
        } elseif (!is_null($limit) && $limit !== 0) {
            $limit_clause = "LIMIT {$limit}";
        }

        $sql = "
            SELECT
                sp.product_name,
                CONCAT(LPAD(`sp`.`category_id`,2,0),LPAD(`sp`.`sub_category_id`,2,0),LPAD(`sp`.`id`,4,0)) as product_code,
                sp.id as product_id,
                m1.main_stock,
                IFNULL(d1.sub_stock,0) as sub_stock,
                (m1.main_stock + IFNULL(d1.sub_stock,0)) as `total_stock`,
                (
                    SELECT IFNULL(AVG(order_unit_price),0)
                    FROM (
                        SELECT spod.order_unit_price, MAX(DATE(spos.created_date)) as created_date, spod.product_id
                        FROM bf_pharmacy_purchase_order_dtls spod
                        JOIN bf_pharmacy_purchase_order_mst spos ON spod.purchase_order_mst_id = spos.id
                        GROUP BY `created_date`, `product_id`
                        ORDER BY `created_date` DESC
                    ) as `k1`
                    WHERE k1.product_id = sp.id
                    LIMIT 3
                ) as `current_value`
            FROM bf_pharmacy_products sp
            JOIN (
                SELECT ss.product_id, SUM(ss.quantity_level) as main_stock
                FROM bf_pharmacy_stock ss
                GROUP BY ss.product_id
            ) as m1 ON sp.id = m1.product_id
            LEFT JOIN (
                SELECT sds.product_id, SUM(sds.quantity_level) as sub_stock
                FROM bf_pharmacy_indoor_stock sds
                GROUP BY sds.product_id
            ) as d1 ON sp.id = d1.product_id
            WHERE 1 = 1
            {$where_clause}
            ORDER BY product_name
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


    public function get_product_ledger_details($filter = array(), $limit = null, $offset = 0, $sql_only = false)
    {
        $where_clause = "";
        $where_clause_main_source = "";
        $where_clause_sub_source = "";
        $where_clause_sub_pharmacy_id = "";

        if (isset($filter['product_id'])) {
            $filter['product_id'] = (int) $filter['product_id'];
            $where_clause .= " AND sd.product_id = {$filter['product_id']} ";
        }
        if (isset($filter['sub_pharmacy_id'])) {
            $filter['sub_pharmacy_id'] = $this->db->escape($filter['sub_pharmacy_id']);
            $where_clause_sub_pharmacy_id .= " AND sub_pharmacy_id = {$filter['sub_pharmacy_id']} ";
        }
        if (isset($filter['main_source'])) {
            $filter['main_source'] = $this->db->escape($filter['main_source']);
            $where_clause_main_source .= " AND main_source = {$filter['main_source']} ";
        }
        if (isset($filter['sub_source'])) {
            $filter['sub_source'] = $this->db->escape($filter['sub_source']);
            $where_clause_sub_source .= " AND sub_source = {$filter['sub_source']} ";
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
                q1.date,
                q1.main_source,
                q1.main_issue_sub_id,
                q1.sub_source,
                q1.sub_issue_id,
                pi.name as issue_sub_pharmacy_name,
                q1.main_stock_sale,
                q1.main_stock_issue,
                q1.main_stock_return,
                q1.sub_pharmacy_id,
                ph.name as sub_pharmacy_name,
                q1.sub_stock_issue,
                q1.sub_stock_sale,
                q1.sub_stock_return
            FROM (
                (
                    SELECT
                        DATE(sm.created_date) as `date`,
                        sm.source as `main_source`,
                        0 as `main_issue_sub_id`,
                        0 as `sub_source`,
                        0 as `sub_issue_id`,
                        IF(sm.source = 1, SUM(sd.quantity), 0) as main_stock_sale,
                        0 as main_stock_issue,
                        0 as main_stock_return,
                        0 as sub_pharmacy_id,
                        0 as sub_stock_issue,
                        0 as sub_stock_sale,
                        0 as sub_stock_return
                    FROM bf_pharmacy_stock_mst sm
                    JOIN bf_pharmacy_stock_dtls sd ON sm.id = sd.stock_mst_id
                    WHERE 1 = 1
                    {$where_clause}
                    GROUP BY `date`
                    HAVING main_stock_sale > 0
                )
                UNION
                (
                    SELECT
                        DATE(sm.created_date) as `date`,
                        sm.source as `main_source`,
                        IF(sm.source = 2, sm.stock_source_out_id, 0) as `main_issue_sub_id`,
                        0 as `sub_source`,
                        0 as `sub_issue_id`,
                        0 as main_stock_sale,
                        IF(sm.source = 2, SUM(sd.quantity), 0) as main_stock_issue,
                        0 as main_stock_return,
                        0 as sub_pharmacy_id,
                        0 as sub_stock_issue,
                        0 as sub_stock_sale,
                        0 as sub_stock_return
                    FROM bf_pharmacy_stock_mst sm
                    JOIN bf_pharmacy_stock_dtls sd ON sm.id = sd.stock_mst_id
                    WHERE 1 = 1
                    {$where_clause}
                    GROUP BY `date`
                    HAVING main_stock_issue > 0
                )
                UNION
                (
                    SELECT
                        DATE(sm.created_date) as `date`,
                        sm.source as `main_source`,
                        0 as `main_issue_sub_id`,
                        0 as `sub_source`,
                        0 as `sub_issue_id`,
                        0 as main_stock_sale,
                        0 as main_stock_issue,
                        IF(sm.source = 3, SUM(sd.quantity), 0) as main_stock_return,
                        0 as sub_pharmacy_id,
                        0 as sub_stock_issue,
                        0 as sub_stock_sale,
                        0 as sub_stock_return
                    FROM bf_pharmacy_stock_mst sm
                    JOIN bf_pharmacy_stock_dtls sd ON sm.id = sd.stock_mst_id
                    WHERE 1 = 1
                    {$where_clause}
                    GROUP BY `date`
                    HAVING main_stock_return > 0
                )
                UNION
                (
                    SELECT
                        DATE(sm.created_date) as `date`,
                        0 as `main_source`,
                        0 as `main_issue_sub_id`,
                        sm.source as `sub_source`,
                        IF(sm.source = 1, sm.store_source_out_id, 0) as `sub_issue_id`,
                        0 as main_stock_sale,
                        0 as main_stock_issue,
                        0 as main_stock_return,
                        sm.pharmacy_id as sub_pharmacy_id,
                        IF(sm.source = 1, SUM(sd.quantity), 0) as sub_stock_issue,
                        0 as sub_stock_sale,
                        0 as sub_stock_return
                    FROM bf_pharmacy_indoor_stock_mst sm
                    JOIN bf_pharmacy_indoor_stock_dtls sd ON sm.id = sd.stock_mst_id
                    WHERE 1 = 1
                    {$where_clause}
                    GROUP BY `date`
                    HAVING sub_stock_issue > 0
                )
                UNION
                (
                    SELECT
                        DATE(sm.created_date) as `date`,
                        0 as `main_source`,
                        0 as `main_issue_sub_id`,
                        sm.source as `sub_source`,
                        0 as `sub_issue_id`,
                        0 as main_stock_sale,
                        0 as main_stock_issue,
                        0 as main_stock_return,
                        sm.pharmacy_id as sub_pharmacy_id,
                        0 as sub_stock_issue,
                        IF(sm.source = 2, SUM(sd.quantity), 0) as sub_stock_sale,
                        0 as sub_stock_return
                    FROM bf_pharmacy_indoor_stock_mst sm
                    JOIN bf_pharmacy_indoor_stock_dtls sd ON sm.id = sd.stock_mst_id
                    WHERE 1 = 1
                    {$where_clause}
                    GROUP BY `date`
                    HAVING sub_stock_sale > 0
                )
                UNION
                (
                    SELECT
                        DATE(sm.created_date) as `date`,
                        0 as `main_source`,
                        0 as `main_issue_sub_id`,
                        sm.source as `sub_source`,
                        0 as `sub_issue_id`,
                        0 as main_stock_sale,
                        0 as main_stock_issue,
                        0 as main_stock_return,
                        sm.pharmacy_id as sub_pharmacy_id,
                        0 as sub_stock_issue,
                        0 as sub_stock_sale,
                        IF(sm.source = 3, SUM(sd.quantity), 0) as sub_stock_return
                    FROM bf_pharmacy_indoor_stock_mst sm
                    JOIN bf_pharmacy_indoor_stock_dtls sd ON sm.id = sd.stock_mst_id
                    WHERE 1 = 1
                    {$where_clause}
                    GROUP BY `date`
                    HAVING sub_stock_return > 0
                )
            ) as `q1`
            LEFT JOIN bf_pharmacy_setup ph ON ph.id = q1.sub_pharmacy_id
            LEFT JOIN bf_pharmacy_setup pi ON ph.id = q1.sub_issue_id
            WHERE 1 = 1
            {$where_clause_sub_pharmacy_id}
            {$where_clause_main_source}
            {$where_clause_sub_source}
            ORDER BY `date`
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

}
