<?php

class Inventorystock_Form_Category extends Siberian_Form_Abstract
{
    
    public function init() {
        parent::init();
        
        $this
            ->setAction(__path("/inventorystock/category/add"))
            ->setAttrib("id", "form-add-category")
            ->addNav("nav-add-category", "Submit");
        
        self::addClass("create", $this); 

        $value_id = $this->addSimpleHidden("value_id");
        $id = $this->addSimpleHidden("id");

        /** category  name */
        $category_name = $this->addSimpleText('category_name', p__('inventorystock', 'Category Name'))->setRequired(true);
   
       /*Status*/
        $this->addSimpleCheckbox('status', p__('inventorystock', 'Active'));
    }
    
    public function setElementValueById($id, $value, $required = false) {
        $element = $this->getElement($id)->setValue($value);
        if( $required ) {
            $element->setRequired(true);
        }
    }  
    
}
?>
 