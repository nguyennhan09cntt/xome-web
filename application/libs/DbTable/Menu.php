<?php
/**
* This class is generated automatically by schema_update. !!! Do not touch or modify
* Last modified : 2018-05-22 19:23:02
* Class DbTable_Menu
*/

class DbTable_Menu extends Application_Db_DbTable
{
    /**
    * @type <string>
    */
    const _tableName = 'menu';

    
    /**
    * @type <int(11)>
    * @null <NO>
    * @default <>
    * @extra <auto_increment>
    */
    const COL_MENU_ID = 'menu_id';
    
    /**
    * @type <varchar(45)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_NAME = 'name';
    
    /**
    * @type <varchar(128)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_LINK = 'link';
    
    /**
    * @type <int(11)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_PARENT_ID = 'parent_id';
    
    /**
    * @type <tinyint(4)>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_ACTIVE = 'active';
    
    /**
    * @type <datetime>
    * @null <YES>
    * @default <>
    * @extra <>
    */
    const COL_CREATED_AT = 'created_at';
    
    /**
    * @type <timestamp>
    * @null <YES>
    * @default <CURRENT_TIMESTAMP>
    * @extra <>
    */
    const COL_UPDATED_AT = 'updated_at';
    
    /**
    * @var DbTable_Menu
    */
    public static $_instance;

    public function __construct(){
        $this->_name = self::_tableName;
                    $this->_primary = $this->_name.'_id';
        
        parent::__construct();
    }

    /**
    * @return DbTable_Menu
    */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new DbTable_Menu();
        }
        return self::$_instance;
    }

}