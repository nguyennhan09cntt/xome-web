<?php

/**
 * Created by PhpStorm.
 * User: Nhan
 * Date: 3/21/2016
 * Time: 3:59 PM
 */
class Model_LogSearch extends Application_Singleton
{

    /**
     *
     * @var Model_Dao_LogSearch
     */
    private $_dao;

    protected function __construct()
    {
        $this->_dao = new Model_Dao_LogSearch ();
    }

    public function insert($key_content, $module=1)
    {
        $key_content = trim($key_content);
        $result = null;
        try {
            $params = array(
                DbTable_Log_Search::COL_KEY_CONTENT => $key_content,
                DbTable_Log_Search::COL_LOG_MODULE => $module,
                DbTable_Log_Search::COL_CREATED_AT => $this->_dao->mysqlSysDate()
            );
            $this->_dao->insert($params);            
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }
}