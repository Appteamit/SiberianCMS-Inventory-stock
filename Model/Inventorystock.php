<?php 

/**
 * Class Inventorystock_Model_Inventorystock
 * @package Fooddelivery\Model
 */
class Inventorystock_Model_Inventorystock extends Core_Model_Default
{
    /**
     * @var null
     */
    public static $acl = null;

 
    /**
     * Inventorystock_Model_Inventorystock constructor.
     * @param array $datas
     */
    public function __construct($datas = [])
    {
        parent::__construct($datas);
        $this->_db_table = 'Inventorystock_Model_Db_Table_Inventorystock';
    }

    /**
     * @param $valueId
     * @return array|bool
     */
    public function getInappStates($valueId)
    {
        
        $inAppStates = [
            [
                "state" => "inventorystock-home",
                "offline" => false,
                "params" => []             
            ],
        ];

        return $inAppStates;
    }

    /**
     * @return null
     */
    public static function getCurrentValueId()
    {
        $app = self::getApplication();
        if ($app) {
            $options = $app->getOptions();
            foreach ($options as $option) {
                if ($option->getCode() === "inventorystock") {
                    return $option->getId();
                }
            }
        }
        return null;
    }

    /**
     * @return null
     */
    public static function getCurrent()
    {
        $app = self::getApplication();
        if ($app) {
            $options = $app->getOptions();
            foreach ($options as $option) {
                if ($option->getCode() === "inventorystock") {
                    return $option;
                }
            }
        }
        return null;
    }
 
}