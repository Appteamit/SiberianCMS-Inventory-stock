<?php

/**
 * Class Inventorystock_CategoryController
 */
class Inventorystock_CategoryController extends Application_Controller_Default
{
    
     /**
     *
     */
    public function addAction()
    {   
        $payload = array();

        if ($data = $this->getRequest()->getPost()) {

            try {
                $form = new Inventorystock_Form_Category();
                if ($form->isValid($data)) {
                    $data['status'] = ($data['status'] == 0) ? 'inactive' : 'active';
                    
                    if (!empty($data['value_id'])) {
                        $category = new Inventorystock_Model_Category();
                        $category
                            ->find($data['id'])
                            ->setCategoryName($data['category_name'])
                            ->setValueId($data['value_id'])
                            ->setStatus($data['status'])
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
        
        if ($id = $this->getRequest()->getParam("id")) {
            try {
                 
                $categoryModel = new Inventorystock_Model_Category();
                $categoryModel->find($id);
                if($categoryModel->getId()) {
                    $data = $categoryModel->getData();
                    $data['status'] = $data['status'] == 'active' ? 1 : 0;

                    $form = new Inventorystock_Form_Category();
                    $form->populate($data);
                    $form->setElementValueById('value_id', $this->getCurrentOptionValue()->getId());
                    $form->addNav("edit-nav-category", "Save", false); 
                    $form->removeNav("nav-add-category");              
                    $form->setElementValueById('id', $categoryModel->getId());
                    
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

                $categoryModel = new Inventorystock_Model_Category();
                $categoryModel->find($data['id']);
                if($categoryModel->getId()) {
                    $categoryModel->setStatus('deleted');
                    $categoryModel->save();
              
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