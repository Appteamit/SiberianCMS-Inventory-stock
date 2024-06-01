<?php

/**
 * Class Inventorystock_Form_Category_Delete
 */
class Inventorystock_Form_Category_Delete extends Siberian_Form_Abstract {

    public function init() {
        parent::init();

        $this
            ->setAction(__path("/inventorystock/category/delete"))
            ->setAttrib("id", "form-delete-inventorystock-category")
            ->setConfirmText("You are about to remove this Category! Are you sure ?");
        ;

        /** Bind as a delete form */
        self::addClass("delete", $this);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
            ->from('inventorystock_category')
            ->where('inventorystock_category.id = :value')
        ;

        $store_id = $this->addSimpleHidden("id", p__('inventorystock', 'Category'));
        $store_id->addValidator("Db_RecordExists", true, $select);
        $store_id->setMinimalDecorator();

        $mini_submit = $this->addMiniSubmit();
    }
}