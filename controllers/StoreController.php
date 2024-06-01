<?php

/**
 * Class Inventorystock_StoreController
 */
class Inventorystock_StoreController extends Application_Controller_Default
{
    
     /**
     *
     */
    public function addAction()
    {   
        $payload = array();

        if ($data = $this->getRequest()->getPost()) {

            try {
                $form = new Inventorystock_Form_Store();
                if ($form->isValid($data)) {
                    $data['status'] = ($data['status'] == 0) ? 'inactive' : 'active';
                    
                    if (!empty($data['value_id'])) {
                        $store = new Inventorystock_Model_Store();
                        $store
                            ->find($data['store_id'])
                            ->setStoreName($data['store_name'])
                            ->setValueId($data['value_id'])
                            ->setDescription($data['description'])
                            ->setStatus($data['status'])
                            ->setAddress($data['address'])
                            ->setCity($data['city'])
                            ->save();

                    } else {
                        throw new Siberian_Exception(p__('inventorystock', 'Something went wrong with the update, will retry later.'));
                    }

                    $payload = array(
                        'success' => true,
                        'success_message' => p__('inventorystock', 'Information successfully saved'),
                        'message_timeout' => 2,
                        'message_button' => 0,
                        'message_loader' => 0
                    );
                } else {
                    /** Do whatever you need when form is not valid */
                    $payload = array(
                        "error" => 1,
                        "message" => $form->getTextErrors(),
                        "errors" => $form->getTextErrors(true),
                    );
                }

            } catch (Exception $e) {
                $payload = array(
                    'error' => true,
                    'message' => $e->getMessage()
                );
            }
        }
       $this->_sendJson($payload);
    }



    public function loadformAction(){
        
        if ($store_id = $this->getRequest()->getParam("store_id")) {
            try {
                 
                $storeModel = new Inventorystock_Model_Store();
                $storeModel->find($store_id);
                if($storeModel->getStoreId()) {
                    $data = $storeModel->getData();
                    $data['status'] = $data['status'] == 'active' ? 1 : 0;

                    $form = new Inventorystock_Form_Store();
                    $form->populate($data);
                    $form->setElementValueById('value_id', $this->getCurrentOptionValue()->getId());
                    $form->addNav("edit-nav-store", "Save", false); 
                    $form->removeNav("nav-add-store");              
                    $form->setElementValueById('store_id', $storeModel->getStoreId());
                    
                    $payload = array(
                        "check" => 'ready for use',
                        "success"   => true,
                        "form"      => $form->render(),
                        "message"   => p__('inventorystock', "Success."),
                    );

                }else{
                    $payload = array(
                        "error"     => true,
                        "message"   => p__('inventorystock', 'Store you are trying to edit does not exists.'),
                    );
                }              

            } catch (Exception $e) {
                $payload = array(
                    'error' => true,
                    'message' => $e->getMessage()
                );
            }
        }

        $this->_sendHtml($payload);
    }


    /**
     *
     */
    public function deleteAction()
    {   
        $payload = array();

        if ($data = $this->getRequest()->getPost()) {

            try {

                $storeModel = new Inventorystock_Model_Store();
                $storeModel->find($data['id']);
                if($storeModel->getStoreId()) {
                    $storeModel->setStatus('deleted');
                    $storeModel->save();
              
                    $payload = array(
                        'success' => true,
                        'success_message' => p__('inventorystock', 'Deleted successfully'),
                        'message_timeout' => 2,
                        'message_button' => 0,
                        'message_loader' => 0
                    );

                }

            } catch (Exception $e) {
                $payload = array(
                    'error' => true,
                    'message' => $e->getMessage()
                );
            }           
        }

        $this->_sendJson($payload);
    }
     
}