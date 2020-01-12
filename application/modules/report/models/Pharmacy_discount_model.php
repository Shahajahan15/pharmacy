<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy_discount_model extends CI_Model {

	public function select_all_medicine_discount_info($where,$pharmacy_id)
	{
		if($pharmacy_id=="200" || $pharmacy_id=="")
		{
		$sql = "SELECT
                     mp.tot_discount,
                     mp.r_qnty,
                     mp.qnty,
                     mp.unit_price,
                     mp.normal_discount_taka,
                     mp.service_discount_taka,
                     mp.n_discount_type,
                     mp.n_discount_id,
                     mp.s_discount_type,
                     mp.s_discount_id,
                     mp.created_date,
                     mp.product_id,
                     mp.product_name,
                     mp.company_name,
                     mp.company_id,
                     mp.category_name,
                     mp.category_id,
                     mp.type
                  FROM 
                    (
                       (
                            SELECT
                                    0 as tot_discount,
                                    0 as  r_qnty,
                                    qnty,
                                    unit_price,
                                    normal_discount_taka,
                                    service_discount_taka,
                                    n_discount_type,
                                    n_discount_id,
                                    s_discount_type,
                                    s_discount_id,
                                    DATE_FORMAT(psmst.created_date,'%Y-%m-%d') as created_date,
                                    pp.id as product_id,
                                    product_name,
                                    company_name,
                                    ppc.id as company_id,
                                    category_name,
                                    pc.id as category_id,
                                    1 as type
                                FROM 
                                    bf_pharmacy_sales_mst as psmst
                                JOIN 
                                    bf_pharmacy_sales_dtls as psdtls 
                                 ON 
                                    psmst.id=psdtls.master_id
                                JOIN 
                                    bf_pharmacy_products as pp
                                 ON 
                                    psdtls.product_id=pp.id
                                JOIN 
                                    bf_pharmacy_product_company as ppc
                                 ON 
                                    pp.company_id=ppc.id
                                JOIN 
                                    bf_pharmacy_category as pc
                                 ON 
                                    pp.category_id=pc.id
                        )
                        UNION 
                        (
                            SELECT
                                 tot_discount, 
                                 r_qnty,
                                 0 as qnty,
                                 price as unit_price,
                                 0 as normal_discount_taka,
                                 0 as service_discount_taka,
                                 n_discount_type,
                                 n_discount_id,
                                 s_discount_type,
                                 s_discount_id,
                                 DATE_FORMAT(psrmst.created_date,'%Y-%m-%d') as created_date,
                                 pp.id as product_id,
                                 product_name,
                                 company_name,
                                 ppc.id as company_id,
                                 category_name,
                                 pc.id as category_id,
                                 2 as type
                                FROM
                                    bf_pharmacy_sale_return_mst as psrmst 
                                JOIN 
                                    bf_pharmacy_sale_return_dtls as psrdtls
                                  ON 
                                    psrmst.id=psrdtls.master_id 
                                JOIN 
                                    bf_pharmacy_products as pp
                                 ON 
                                    psrdtls.product_id=pp.id
                                JOIN 
                                    bf_pharmacy_sales_dtls as psdtls 
                                 ON 
                                    psrmst.sale_id=psdtls.master_id
                                JOIN 
                                    bf_pharmacy_product_company as ppc
                                 ON 
                                    pp.company_id=ppc.id
                                JOIN 
                                    bf_pharmacy_category as pc
                                 ON 
                                    pp.category_id=pc.id
                        )
                    ) as mp 
                        WHERE 
                             1=1 {$where}
                ";
        $query = $this->db->query($sql);
        $records = $query->result();
        }
        else
        {
        	$sql = "SELECT 
                   mp.qnty,
                   mp.unit_price,
                   mp.totat_discount,
                   mp.return_discount,
                   mp.price,
                   mp.r_qnty,
                   mp.product_name,
                   mp.company_name,
                   mp.company_id,
                   mp.category_name,
                   mp.category_id,
                   mp.created_date,
                   mp.type
                    FROM
                        (
                            (
                                SELECT 
                                     qnty,
                                     unit_price,
                                     normal_discount_taka+service_discount_taka AS totat_discount,
                                     0 as return_discount,
                                     0 as price,
                                     0 as r_qnty,
                                     product_name,
                                     company_name,
	                                 ppc.id as company_id,
	                                 category_name,
	                                 pc.id as category_id,
                                     pismst.created_date,
                                     1 as type
                                     FROM 
                                        bf_pharmacy_indoor_sales_dtls AS pisdtls
                                     JOIN
                                        bf_pharmacy_indoor_sales_mst AS pismst 
                                     ON
                                        pisdtls.master_id=pismst.id 
                                    JOIN
                                        bf_pharmacy_products AS pp 
                                     ON
                                        pisdtls.product_id=pp.id 
                                     JOIN 
                                        bf_pharmacy_product_company AS ppc
                                     ON  
                                        pp.company_id=ppc.id 
                                     JOIN 
	                                    bf_pharmacy_category as pc
	                                 ON 
	                                    pp.category_id=pc.id
                            )
                            UNION
                            (
                                SELECT
                                     0 as qnty,
                                     0 as unit_price,
                                     0 as totat_discount,
                                     tot_discount AS return_discount,
                                     price,
                                     r_qnty,
                                     product_name,
                                     company_name,
	                                 ppc.id as company_id,
	                                 category_name,
	                                 pc.id as category_id,
                                     pisrmst.created_date,
                                     2 as type
                                     FROM 
                                        bf_pharmacy_indoor_sale_return_dtls AS pisrdtls
                                     JOIN
                                        bf_pharmacy_indoor_sale_return_mst AS pisrmst 
                                     ON
                                        pisrdtls.master_id=pisrmst.id 
                                    JOIN
                                        bf_pharmacy_products AS pp 
                                     ON
                                        pisrdtls.product_id=pp.id 
                                     JOIN 
                                        bf_pharmacy_product_company AS ppc
                                     ON  
                                        pp.company_id=ppc.id 
                                     JOIN 
	                                    bf_pharmacy_category as pc
	                                 ON 
	                                    pp.category_id=pc.id
                            )
                        ) AS mp
                              WHERE 
                                1=1 {$where}
                ";
        $query = $this->db->query($sql);
        $records = $query->result();
        }

        // echo $this->db->last_query();
        // echo "<pre>";
        // print_r($records);
        // exit();
        return $records;
	}
}