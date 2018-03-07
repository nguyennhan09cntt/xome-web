<?php
/**
* This class is generated automatically by schema_update. !!! Do not touch or modify
* Last modified : 2018-03-07 22:26:04
* Class DbTable_Log_Search
*/

class DbTable_Log_Search extends Application_Db_DbTable
{
    /**
    * @type <string>
    */
    const _tableName = 'log_search';

    
    /**
    * @type <int(11)>
    * @null <NO>
    * @default <>
    * @extra <auto_increment>
    */
    const COL_LOG_SEARCH_ID = 'log_search_id';
    
    /**
    * @type <varchar(256)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_KEY_CONTENT = 'key_content';
    
    /**
    * @type <varchar(45)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_CREATED_AT = 'created_at';
    
    /**
    * @var DbTable_Log_Search
    */
    public static $_instance;

    public function __construct(){
        $this->_name = self::_tableName;
                    $this->_primary = $this->_name.'_id';
        
        parent::__construct();
    }

    /**
    * @return DbTable_Log_Search
    */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new DbTable_Log_Search();
        }
        return self::$_instance;
    }

}