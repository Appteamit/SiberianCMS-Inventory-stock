<?php
/**
 * Class Inventorystock_ApplicationController
 */
class Inventorystock_ApplicationController extends Application_Controller_Default
{
    /**
     * Load editor feature page
     */
    public function editAction()
    {
        parent::editAction();
    }
   
    /**
     * Load products 
     */
    public function productsAction()
    {
        $this->loadPartials();
    }

    /**
     * fetch products
     */
     public function fetchProductsAction() {
        
        try {
            $request = $this->getRequest();
            $limit = $request->getParam("perPage", 100);
            $offset = $request->getParam("offset", 0);
            $sorts = $request->getParam("sorts", []);
            $queries = $request->getParam("queries", []);
            

            $filter = null;
            $startdate = null;
            $enddate = null;

            if (array_key_exists("search", $queries)) {
                $filter = $queries["search"];
            }
            if (array_key_exists("from", $queries)) {
                $startdate = date('Y-m-d', strtotime($queries["from"]));
            }
            if (array_key_exists("to", $queries)) {
                $enddate = date('Y-m-d', strtotime($queries["to"]));
            }
            if (array_key_exists("category", $queries)) {
                $category =  $queries["category"];
            }

            
            $params = [
                "limit" => $limit,
                "offset" => $offset,
                "sorts" => $sorts,
                "filter" => trim($filter),
                "startdate" => $startdate,
                "enddate" => $enddate,
                "category" => trim($category)
            ];
           
            $value_id = (new Inventorystock_Model_Inventorystock())->getCurrentValueId();
            $application = $this->getApplication();
            
            $products = (new Inventorystock_Model_Product())
                ->getProducts($value_id, $params);

            $countAll = (new Inventorystock_Model_Product())->countAllForApp($value_id);
            $countFiltered =   (new Inventorystock_Model_Product())->countAllForApp($value_id, $params);

            $productsJson = [];
            foreach ($products as $product) {
                $data = $product;               
                $data["created_at"] = datetime_to_format($data["created_at"]);                 
                $productsJson[] = $data;
            }

            $payload = [
                "records" => $productsJson,
                "queryRecordCount" => $countFiltered[0],
                "totalRecordCount" => $countAll[0]
            ];
        } catch (\Exception $e) {
            $payload = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }

        $this->_sendJson($payload);
    }

     /**
     * export products
     */
     public function exportCsvAction() {
        
        try {
            $request = $this->getRequest();
            $sorts = $request->getParam("sorts", []);
            $queries = $request->getParam("queries", []);
			$category = $request->getParam("category", '');
			$from = $request->getParam("from", '');
			$to = $request->getParam("to", '');
			$search = $request->getParam("search", '');
		    $startdate = null;
            $enddate = null;
 
            if (!empty($from)) {
                $startdate = date('Y-m-d', strtotime($from));
            }
            if (!empty($to)) {
                $enddate = date('Y-m-d', strtotime($to));
            }           

            
            $params = [
                "sorts" => $sorts,
                "filter" => trim($search),
                "startdate" => $startdate,
                "enddate" => $enddate,
                "category" => trim($category)
            ];
           
            $value_id = (new Inventorystock_Model_Inventorystock())->getCurrentValueId();
            $application = $this->getApplication();
            
            $products = (new Inventorystock_Model_Product())
                ->getProducts($value_id, $params);
 
            $csv_string = "EAN,Qty,Categoty,Store,Created At\n";
            foreach ($products as $product) { 
                $csv_string .= $product['ean_number'].",".$product['qty'].",".$product['category_name'].",".$product['store_name'].",".date('d.m.Y - H:i' , strtotime($product['created_at']))."\n";                
           }

            $date = date("Y-m-d_H-i-s");
            $filename = "product_export".$date.".csv";
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            echo $csv_string;
            exit();
        } catch (\Exception $e) {
			if(APPLICATION_ENV === "development") {
			    Zend_Debug::dump($e);
			}
			return false;
        }
         
    }
   
}