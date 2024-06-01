<?php

class Inventorystock_Model_Product extends Core_Model_Default {

    /**
     * @var null
     */
    public static $acl = null;

    /**
     * @var string
     */
    protected $_db_table = Inventorystock_Model_Db_Table_Product::class;

     /**
     * @param $datas
     * @return mixed
     */
    public function getProducts($value_id, $params = [])
    {
        return $this->getTable()->getProducts($value_id, $params);
    }

    /**
     * @param $datas
     * @return mixed
     */
    public function countAllForApp($value_id, $params = [])
    {
        return $this->getTable()->countAllForApp($value_id, $params);
    }

   
}