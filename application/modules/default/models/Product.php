<?php

/**
 * Created by PhpStorm.
 * User: Nhan
 * Date: 3/19/2016
 * Time: 1:30 PM
 */
class Model_Product extends Application_Singleton
{
    /**
     *
     * @var Model_Dao_Product
     */
    private $_dao;

    protected function __construct()
    {
        $this->_dao = new Model_Dao_Product ();
    }

    /**
     * encode
     * @param $id
     * @return string
     */
    public function encode($id)
    {
        return Admin_Model_Product::getInstance()->encode($id);
    }

    /**
     * decode
     * @param $encode
     * @return int
     */
    public function decode($encode)
    {
        return Admin_Model_Product::getInstance()->decode($encode);
    }

    /**
     * @param $name
     * @param $referCode
     * @param $category
     * @param $originalPrice
     * @param $paidPrice
     * @param $thumbNail
     * @param $description
     * @param int $component
     * @param string $note
     * @param string $shortDescription
     * @return null|string
     */
    public function insert($name, $referCode, $category, $originalPrice, $paidPrice, $thumbNail, $description, $component, $note, $shortDescription, $promotionPrice, $address, $area, $own = null, $phone = null, $object = null, $district = null, $customerId = null, $lat = 0, $lng = 0, $flagUpload = 1, $productOwnId = null, $cookie = null)
    {
        $name = trim($name);
        $referCode = trim($referCode);
        $category = $category ? intval($category) : null;
        $originalPrice = intval($originalPrice);
        $paidPrice = intval($paidPrice);
        $thumbNail = trim($thumbNail);
        $description = trim($description);
        $component = intval($component);
        $note = trim($note);
        $shortDescription = trim($shortDescription);
        $identify = Application_Function_String::getFormatUrl($name) . '-' . Application_Function_String::randomString(6) . '-' . Application_Function_String::getFormatUrl($referCode);
        $promotionPrice = intval($promotionPrice);
        $address = trim($address);
        $area = intval($area);
        $district = $district ? intval($district) : null;
        $customerId = $customerId ? intval($customerId) : null;
        $lng = floatval($lng);
        $lat = floatval($lat);
        $flagUpload = intval($flagUpload);
        $productOwnId = $productOwnId? intval($productOwnId) : null;
        $cookie = $cookie ? trim($cookie): null;
        $object  = $object ? intval($object) : 0;
        $result = null;
        try {
            $params = array(
                DbTable_Product::COL_PRODUCT_NAME => $name,
                DbTable_Product::COL_PRODUCT_REFER_CODE => $referCode,
                DbTable_Product::COL_PRODUCT_IDENTIFY => $identify,
                DbTable_Product::COL_PRODUCT_ORIGINAL_PRICE => $originalPrice,
                DbTable_Product::COL_PRODUCT_PROMOTION_PRICE => $promotionPrice,
                DbTable_Product::COL_PRODUCT_OWN => $own,
                DbTable_Product::COL_PRODUCT_PHONE => $phone,
                DbTable_Product::COL_PRODUCT_PAID_PRICE => $paidPrice,
                DbTable_Product::COL_PRODUCT_THUMB_NAIL => $thumbNail,
                DbTable_Product::COL_FK_PRODUCT_COMPONENT => $component,
                DbTable_Product::COL_PRODUCT_CATEGORY_ID => $category,
                DbTable_Product::COL_PRODUCT_DESCRIPTION => $description,
                DbTable_Product::COL_PRODUCT_NOTE => $note,
                DbTable_Product::COL_PRODUCT_SHORT_DESCRIPTION => $shortDescription,
                DbTable_Product::COL_PRODUCT_QUALITY => 1,
                DbTable_Product::COL_PRODUCT_ADDRESS => $address,
                DbTable_Product::COL_PRODUCT_AREA => $area,
                DbTable_Product::COL_FK_DISTRICT => $district,
                DbTable_Product::COL_PRODUCT_OBJECT => $object,
                DbTable_Product::COL_FK_CUSTOMER => $customerId,
                DbTable_Product::COL_PRODUCT_LAT => $lat,
                DbTable_Product::COL_PRODUCT_LONG => $lng,
                DbTable_Product::COL_PRODUCT_FLAG_UPLOAD_IMAGE => $flagUpload,
                DbTable_Product::COL_FK_PRODUCT_OWNER => $productOwnId,
                DbTable_Product::COL_CUSTOMER_COOKIE => $cookie,
                DbTable_Product::COL_PRODUCT_FLAG_CROP => 1,
                DbTable_Product::COL_PRODUCT_CREATED_AT => $this->_dao->mysqlSysDate()
            );
            $result = $this->_dao->insertAndGetLastInsertId($params);
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }
    /**
     * get product listing at home page
     * @param $page
     * @param $limit
     * @param $categoryId
     * @return array|false|mixed
     */
    public function getHomeListing($page, $limit, $categoryId)
    {
        $page = intval($page);
        $limit = intval($limit);
        $categoryId = intval($categoryId);

        $isCache = $page <= Application_Constant_Cache::CACHE_PAGE;
        $result = array();
        $key = Application_Cache_Default::getInstance()->homeProduct();
        if ($isCache) {
            $result = Application_Cache_Default::getInstance()->load($key);
        }
        if (!$result) {
            $paramsPage = $isCache ? 0 : $page;
            $paramsLimit = $isCache ? Application_Constant_Cache::CACHE_PAGE * $limit : $limit;
            $data = $this->_dao->getListing($paramsPage, $paramsLimit, null, $categoryId);
            if ($data) {
                $total = isset($data[Application_Constant_Global::KEY_TOTAL]) ? $data[Application_Constant_Global::KEY_TOTAL] : 0;
                if ($total) {
                    $data[Application_Constant_Global::KEY_DATA] = $data[Application_Constant_Global::KEY_DATA]->toArray();
                    if ($isCache) {
                        Application_Cache_Default::getInstance()->save($data, $key, Application_Constant_Cache::HOME_PRODUCT_ALL_INFO_LIFE_TIME, array(Application_Constant_Global::KEY_COMPONENT_PRODUCT));
                    }
                    $result = $data;
                }
            }
        }
        if ($isCache && $result) {
            $offset = ($page - 1) * $limit;
            $result[Application_Constant_Global::KEY_DATA] = array_slice(
                $result[Application_Constant_Global::KEY_DATA],
                $offset,
                $limit
            );
        }
        return $result;
    }

    /**
     * get product listing at home page
     * @param int $page
     * @param int $limit
     * @param int $componentId
     * @param int $categoryId
     * @return array|false|mixed
     */
    public function getListing($page, $limit, $componentId, $categoryId)
    {
        $page = intval($page);
        $limit = intval($limit);
        $categoryId = intval($categoryId);

        $isCache = $page <= Application_Constant_Cache::CACHE_PAGE;
        $result = array();
        $key = Application_Cache_Default::getInstance()->productListing($componentId, $categoryId);
        if ($isCache) {
            $result = Application_Cache_Default::getInstance()->load($key);
        }
        if (!$result) {
            $paramsPage = $isCache ? 0 : $page;
            $paramsLimit = $isCache ? Application_Constant_Cache::CACHE_PAGE * $limit : $limit;
            $data = $this->_dao->getListing($paramsPage, $paramsLimit, $componentId, $categoryId);
            if ($data) {
                $total = isset($data[Application_Constant_Global::KEY_TOTAL]) ? $data[Application_Constant_Global::KEY_TOTAL] : 0;
                if ($total) {
                    $data[Application_Constant_Global::KEY_DATA] = $data[Application_Constant_Global::KEY_DATA]->toArray();
                    if ($isCache) {
                        Application_Cache_Default::getInstance()->save($data, $key, Application_Constant_Cache::PRODUCT_LISTING_ALL_INFO_LIFE_TIME, array(Application_Constant_Global::KEY_COMPONENT_PRODUCT));
                    }
                    $result = $data;
                }
            }
        }
        if ($isCache && $result) {
            $offset = ($page - 1) * $limit;
            $result[Application_Constant_Global::KEY_DATA] = array_slice(
                $result[Application_Constant_Global::KEY_DATA],
                $offset,
                $limit
            );
        }
        return $result;
    }


    /** get detail
     * @param string $productIdentify
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getDetail($productIdentify)
    {
        $productIdentify = trim($productIdentify);
        return $this->_dao->getDetail($productIdentify);
    }

    /**
     * @param $page
     * @param $limit
     * @param $keyWord
     * @param $categoryId
     * @param null $facility
     * @param null $priceBegin
     * @param null $priceEnd
     * @return array
     */
    public function searchQuery($page, $limit, $keyWord, $categoryId, $facility = null, $priceBegin = null, $priceEnd = null, $province, $district, $object = null, $beginArea = 0, $endArea = 0, $flagMap = false, $lat = null, $lng=null)
    {
        $page = intval($page);
        $facility = intval($facility);
        $priceBegin = intval($priceBegin);
        $priceEnd = intval($priceEnd);
        return $this->_dao->searchQuery($page, $limit, $keyWord, $categoryId, $facility, $priceBegin, $priceEnd, $province, $district, $object, $beginArea, $endArea, $flagMap, $lat, $lng);
    }

    /**
     * @param $lat
     * @param $lng
     * @param $radius
     * @param $limit
     * @return array
     */
    public function searchAround($lat, $lng, $radius, $limit)
    {
        $lat = trim($lat);
        $lng = trim($lng);
        $radius = trim($radius);
        $limit = intval($limit);
        return $this->_dao->searchAround($lat, $lng, $radius, $limit);
    }

    /**
     * @param $id
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getById($id)
    {
        $id = intval($id);
        return Admin_Model_Product::getInstance()->getById($id);
    }


    public function getListingByProvince($page, $limit, $province, $district)
    {

        $page = intval($page);
        $limit = intval($limit);
        $district = intval($district);

        $isCache = $page <= Application_Constant_Cache::CACHE_PAGE;
        $result = array();
        $key = Application_Cache_Default::getInstance()->productListingProvince($province, $district);
        if ($isCache) {
            $result = Application_Cache_Default::getInstance()->load($key);
        }
        if (!$result) {
            $paramsPage = $isCache ? 0 : $page;
            $paramsLimit = $isCache ? Application_Constant_Cache::CACHE_PAGE * $limit : $limit;
            $data = $this->_dao->getListingByProvince($paramsPage, $paramsLimit, $province, $district);
            if ($data) {
                $total = isset($data[Application_Constant_Global::KEY_TOTAL]) ? $data[Application_Constant_Global::KEY_TOTAL] : 0;
                if ($total) {
                    $data[Application_Constant_Global::KEY_DATA] = $data[Application_Constant_Global::KEY_DATA]->toArray();
                    if ($isCache) {
                        Application_Cache_Default::getInstance()->save($data, $key, Application_Constant_Cache::PRODUCT_LISTING_PROVINCE_LIFE_TIME, array(Application_Constant_Global::KEY_COMPONENT_PRODUCT));
                    }
                    $result = $data;
                }
            }
        }
        if ($isCache && $result) {
            $offset = ($page - 1) * $limit;
            $result[Application_Constant_Global::KEY_DATA] = array_slice(
                $result[Application_Constant_Global::KEY_DATA],
                $offset,
                $limit
            );
        }
        return $result;
    }

    /**
     * @param $page
     * @param $limit
     * @param $customerId
     * @return array
     */
    public function getProductByCustomer($page, $limit, $customerId)
    {
        return $this->_dao->getProductByCustomer($page, $limit, $customerId);

    }

    /**
     * @param $id
     * @param $thumbNail
     * @return null|string
     */
    public function updateImage($id, $thumbNail)
    {
        $result = null;
        try {
            $params = array(
                DbTable_Product::COL_PRODUCT_THUMB_NAIL => $thumbNail
            );
            $where = sprintf('%s IN (%s)', DbTable_Product::COL_PRODUCT_ID, $this->_dao->getAdapter()->quote($id));
            $this->_dao->update($params, $where);

        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;

    }

    /**
     * @param $id
     * @param $active
     * @param $priority
     * @return null|string
     */
    public function updatePosition($id, $active, $priority)
    {
        $result = null;
        try {
            $params = array(
                DbTable_Product::COL_FK_CONFIG_STATUS => $active,
                DbTable_Product::COL_PRODUCT_PRIORITY => $priority
            );
            $where = sprintf('%s IN (%s)', DbTable_Product::COL_PRODUCT_ID, $this->_dao->getAdapter()->quote($id));
            $this->_dao->update($params, $where);

        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;

    }

    /**
     * @param int $id
     * @return null|string
     */
    public function updatePageView($id)
    {
        $result = null;
        try {
            $params = array(
                DbTable_Product::COL_PRODUCT_PAGEVIEW => new Zend_Db_Expr(DbTable_Product::COL_PRODUCT_PAGEVIEW . ' + 1'),

            );
            $where = sprintf('%s IN (%s)', DbTable_Product::COL_PRODUCT_ID, $this->_dao->getAdapter()->quote($id));
            $this->_dao->update($params, $where);

        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;

    }

    /**
     * @param $page
     * @param $limit
     * @param $ownerId
     * @return array
     */
    public function getProductByOwner($page, $limit, $ownerId)
    {
        return $this->_dao->getProductByOwner($page, $limit, $ownerId);

    }

    /**
     * @param $customerId
     * @param $cookie
     * @return null|string
     */
    public function updateCustomer($customerId, $cookie)
    {
        $result = null;
        try {
            $params = array(
                DbTable_Product::COL_FK_CUSTOMER => $customerId
            );
            $where = sprintf('%s = %s', DbTable_Product::COL_CUSTOMER_COOKIE, $this->_dao->getAdapter()->quote($cookie));
            $this->_dao->update($params, $where);

        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $result;

    }

}