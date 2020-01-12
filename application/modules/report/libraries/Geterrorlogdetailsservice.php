<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class GetErrorLogDetails
 *
 * Get Error logs
 */

class GetErrorLogDetailsService
{

    /**
     * @var
     */
    private $context;

    /**
     * @var
     */
    private $errorLogs;

    /**
     * @var
     */
    private $limit = 25;

    /**
     * @var
     */
    private $offset = 0;

    /**
     * @param $context
     */
    public function __construct($context) {
        $this->context = $context;
    }

    /**
     * @param $var
     * @return $this
     */
    public function setLimit($var)
    {
        if(intval($var) > 0){
            $this->limit = $var;
        }

        return $this;
    }

    /**
     * @param $var
     * @return $this
     */
    public function setOffset($var)
    {
        if(intval($var) > 0){
            $this->offset = $var;
        }

        return $this;
    }

    public function getCount()
    {
        return $this->context->doctrine->db->fetchColumn('SELECT FOUND_ROWS()');
    }

    /**
     * @return array
     */
    public function execute(){

        $conn = $this->context->doctrine->db;
        $dbtablePrefix = $this->context->db->dbprefix;

        $sql = sprintf("SELECT SQL_CALC_FOUND_ROWS
                              *
                        FROM
                              ".$dbtablePrefix."error_logs
                        WHERE
                              1
                        ORDER BY
                              log_date DESC
                        LIMIT %s, %s",
                        $this->offset,
                        $this->limit
                    );

        $smt = $conn->prepare($sql);
        $smt->execute();
        $this->errorLogs = $smt->fetchAll();

        return $this->errorLogs;
	}
			
}