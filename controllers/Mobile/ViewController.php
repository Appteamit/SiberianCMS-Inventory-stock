<?php
use Siberian\Exception; 
use Siberian\File;
use Siberian\Json;

/**
 * Class Inventorystock_Mobile_ViewController
 */
class Inventorystock_Mobile_ViewController extends Application_Controller_Mobile_Default
{

	public function findallAction()
    {
        $payload = array(); 

        try{             
            if($value_id = $this->getRequest()->getParam('value_id')) {  
                $store_id = $this->getRequest()->getParam('store_id');


                $stores = (new Inventorystock_Model_Store())->findAll(['value_id' => $value_id, 'status = ?' => 'active'])->toArray();
                
                $categories = (new Inventorystock_Model_Category())->findAll(['value_id' => $value_id, 'status = ?' => 'active'])->toArray();

                $products = array();
                if($store_id > 0){
                    $query = ["limit" =>  15 , "offset" => 0, 'store_id' => $store_id];
                    $products = (new Inventorystock_Model_Product())->getProducts($value_id, $query); 
                }                

                $payload = [
                    'success' => true,
                    'page_title' => $this->getCurrentOptionValue()->getTabbarName(),
                    'stores' => $stores,
                    'categories' => $categories,
                    'total_stores' => count($stores),
                    'products' => $products,
                    'welcome_message' => p__('inventorystock', 'Welcome to %s.<br>Set the default store.',  $this->getApplication()->getName())
                ];
            
            } else{
                 $payload = [
                    "error" => true,
                    "message" =>  p__('inventorystock', 'Param requird!') 
                ];
            }

        } catch (\Exception $e) {
            $payload = [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }

        $this->_sendJson($payload);
    }

    public function searchProductAction()
    {
        $payload = array(); 

        try{             
            if($value_id = $this->getRequest()->getParam('value_id')) {  
                $store_id = $this->getRequest()->getParam('store_id');


                $stores = (new Inventorystock_Model_Store())->findAll(['value_id' => $value_id, 'status = ?' => 'active'])->toArray();
                
                $categories = (new Inventorystock_Model_Category())->findAll(['value_id' => $value_id, 'status = ?' => 'active'])->toArray();

                $products = array();
                if($store_id > 0){
                    $query = ["limit" =>  15 , "offset" => 0, 'store_id' => $store_id];
                    if($search = $this->getRequest()->getParam('q')){
                        $query['search'] = $search;
                    }
                    $products = (new Inventorystock_Model_Product())->getProducts($value_id, $query); 
                }                

                $payload = [
                    'success' => true,
                    'products' => $products,
               ];
            
            } else{
                 $payload = [
                    "error" => true,
                    "message" =>  p__('inventorystock', 'Param requird!') 
                ];
            }

        } catch (\Exception $e) {
            $payload = [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }

        $this->_sendJson($payload);
    }


    public function saveProductAction()
    {
        $payload = array(); 

        try{             
            if($param = $this->getRequest()->getBodyParams()){   
                $value_id = $this->getRequest()->getParam('value_id');
                $customerId = $this->_getCustomerId();

                $inventorystock = (new Inventorystock_Model_Product())
                            ->find(['id' => $param['id']])
                            ->setCategoryId($param['category_id']) 
                            ->setCustomerId($customerId)
                            ->setEanNumber($param['ean_number']) 
                            ->setQty($param['qty']) 
                            ->setStoreId($param['store_id'])                              
                            ->setValueId($value_id)
                            ->save();

                $products = array();
                if($param['store_id'] > 0){
                   $query = ["limit" =>  15 , "offset" => 0, 'store_id' => $param['store_id']];
                    $products = (new Inventorystock_Model_Product())->getProducts($value_id, $query); 
                } 

                $payload = [
                    'success' => true,
                    'products' => $products,
                    'welcome_message' => p__('inventorystock', 'Item save successfully')
                ];
            
            } else{
                 $payload = [
                    "error" => true,
                    "message" =>  p__('inventorystock', 'Param requird!') 
                ];
            }

        } catch (\Exception $e) {
            $payload = [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }

        $this->_sendJson($payload);
    }

        /**
     * @param bool $throw
     * @return mixed|null
     * @throws Exception
     * @throws Zend_Session_Exception
     */
    private function _getCustomerId($throw = true)
    {
        $request = $this->getRequest();
        $session = $this->getSession();
        $customerId = $session->getCustomerId();
        if ($throw && empty($customerId)) {
            throw new Exception(p__('inventorystock', 'Customer login required!'));
        }
        return $customerId;
    }
 

  
}

