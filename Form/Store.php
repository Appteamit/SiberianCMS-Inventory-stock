<?php

class Inventorystock_Form_Store extends Siberian_Form_Abstract
{
    
    public function init() {
        parent::init();
        
        $this
            ->setAction(__path("/inventorystock/store/add"))
            ->setAttrib("id", "form-add-store")
            ->addNav("nav-add-store", "Submit");
        
        self::addClass("create", $this); 

        $value_id = $this->addSimpleHidden("value_id");
        $store_id = $this->addSimpleHidden("store_id");

        /** Store name */
        $store_name = $this->addSimpleText('store_name', p__('inventorystock', 'Store Name'))->setRequired(true);

        /** address */
        $address = $this->addSimpleText('address', p__('inventorystock', 'Address'))->setRequired(false);

        /** City name */
        $city = $this->addSimpleText('city', p__('inventorystock', 'City'))->setRequired(false);

        /** Textara with description */
        $description = $this->addSimpleTextarea('description',  p__('inventorystock', 'Description'));

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
 