<?php
/**
* This class is generated automatically by schema_update. !!! Do not touch or modify
* Last modified : 2018-03-07 22:26:04
* Class DbTable_Suggest_Search
*/

class DbTable_Suggest_Search extends Application_Db_DbTable
{
    /**
    * @type <string>
    */
    const _tableName = 'suggest_search';

    
    /**
    * @type <int(11)>
    * @null <NO>
    * @default <>
    * @extra <>
    */
    const COL_SUGGEST_SEARCH_ID = 'suggest_search_id';
    
    /**
    * @type <varchar(128)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_KEY_NAME = 'key_name';
    
    /**
    * @type <varchar(128)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_KEY_IDENTIFY = 'key_identify';
    
    /**
    * @type <varchar(45)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_ADDRESS = 'address';
    
    /**
    * @type <varchar(45)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_DISTRICT = 'district';
    
    /**
    * @type <varchar(45)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_PROVINCE = 'province';
    
    /**
    * @type <int(11)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_PRICE_BEGIN = 'price_begin';
    
    /**
    * @type <int(11)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_PRICE_END = 'price_end';
    
    /**
    * @type <varchar(45)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_COMPONENT = 'component';
    
    /**
    * @type <tinyint(4)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_TYPE_KEY = 'type_key';
    
    /**
    * @type <date>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_CREATED_AT = 'created_at';
    
    /**
    * @var DbTable_Suggest_Search
    */
    public static $_instance;

    public function __construct(){
        $this->_name = self::_tableName;
                    $this->_primary = $this->_name.'_id';
        
        parent::__construct();
    }

    /**
    * @return DbTable_Suggest_Search
    */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new DbTable_Suggest_Search();
        }
        return self::$_instance;
    }

}