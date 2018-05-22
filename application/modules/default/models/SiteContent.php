<?php
/**
 * Created by PhpStorm.
 * User: Nhan
 * Date: 6/18/2016
 * Time: 2:26 PM
 */

class Model_SiteContent extends Application_Singleton
{
    protected function __construct()
    {

    }

    public function getByIdentify($identify)
    {
        return Admin_Model_SiteContent::getInstance()->getByIdentify($identify);
    }
}
