<?php

class Inventorystock_Model_Db_Table_Product extends Core_Model_Db_Table {
    protected $_name                    = "inventorystock_products";
    protected $_primary                 = "id";



         /**
     * @param $app_id
     * @param int $limit
     * @return array
     */
    public function getProducts($value_id, $params = []) {
        $select = $this->_db->select()
            ->from(['main' => $this->_name], [
                "id",
                "ean_number",
                "qty",
                "category_id",
                "customer_id",
                "store_id",
                "created_at"
            ]);
 
           $select->joinLeft(['c' => 'inventorystock_category'], 'c.id = main.category_id', ['c.category_name']);
            $select->joinLeft(['a' => 'customer'], 'a.customer_id = main.customer_id', ['a.firstname', 'a.lastname']);  
          	$select->joinLeft(['s' => 'inventorystock_store'], 's.store_id = main.store_id', ['s.store_name']); 
            
            if (array_key_exists("limit", $params) && array_key_exists("offset", $params)) {
                $select->limit($params["limit"], $params["offset"]);
            }

            if (array_key_exists("sorts", $params) && !empty($params["sorts"])) {
                $orders = [];
                foreach ($params["sorts"] as $key => $dir) {
                    $order = ($dir == -1) ? "DESC" : "ASC";
                    if($key == 'firstname'){
                        $orders = "a.{$key} {$order}";
                    }elseif ($key == 'store_name') {
                        $orders = "s.{$key} {$order}";
                    }elseif ($key == 'category_name') {
                        $orders = "c.{$key} {$order}";
                    }else {
                        $orders = "main.{$key} {$order}";
                    }
                }              
                $select->order($orders);
            } else {
                $select->order('main.created_at DESC');
            }

            if (array_key_exists("filter", $params)) {
            $select->where("(c.category_name LIKE ? OR a.firstname LIKE ? OR a.lastname LIKE ? OR s.store_name LIKE ? OR main.ean_number LIKE ?)", "%" . $params["filter"] . "%");
            }

            if (array_key_exists("category", $params) && !empty($params["category"])) {
                  $select->where("main.category_id = ?",  $params["category"]);
            }

            if (array_key_exists("startdate", $params) && !empty($params["startdate"])) {
                  $select->where("main.created_at >= ?",  $params["startdate"]);
            }

            if (array_key_exists("enddate", $params) && !empty($params["enddate"])) {
                  $select->where("main.created_at <= ?",  $params["enddate"]);
            }

            $select->where("main.value_id = ?", $value_id);
            if($params['store_id'] > 0) {
                $select->where("main.store_id = ?", $params['store_id']);
            }           
            
         
        return   $this->_db->fetchAll($select) ;
    }

    /**
     * @param $value_id
     */
    public function countAllForApp($value_id, $params = [])
    {
        $select =$this->_db->select()
            ->from(['main' => $this->_name], [ 
                 'COUNT(main.id)'
                ])
            ->where('main.value_id = ?', $value_id);
       
        $select->joinLeft(['c' => 'inventorystock_category'], 'c.id = main.category_id', ['c.category_name']);
        $select->joinLeft(['a' => 'customer'], 'a.customer_id = main.customer_id', ['a.firstname', 'a.lastname']);  
        $select->joinLeft(['s' => 'inventorystock_store'], 's.store_id = main.store_id', ['s.store_name']); 
      
        if (array_key_exists("filter", $params)) {
            $select->where("(c.category_name LIKE ? OR a.firstname LIKE ? OR a.lastname LIKE ? OR s.store_name LIKE ?)", "%" . $params["filter"] . "%");
        }

       if (array_key_exists("startdate", $params) && !empty($params["startdate"])) {
              $select->where("main.created_at >= ?",  $params["startdate"]);
        }

        if (array_key_exists("enddate", $params) && !empty($params["enddate"])) {
              $select->where("main.created_at <= ?",  $params["enddate"]);
        }

        if($params['store_id'] > 0) {
            $select->where("main.store_id = ?", $params['store_id']);
        }


        return $this->_db->fetchCol($select);
    }

     
}