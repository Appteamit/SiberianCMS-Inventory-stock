<?php

/**
 * Class Inventorystock_Form_Store_Delete
 */
class Inventorystock_Form_Store_Delete extends Siberian_Form_Abstract {

    public function init() {
        parent::init();

        $this
            ->setAction(__path("/inventorystock/store/delete"))
            ->setAttrib("id", "form-delete-inventorystock-store")
            ->setConfirmText("You are about to remove this Store ! Are you sure ?");
        ;

        /** Bind as a delete form */
        self::addClass("delete", $this);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
            ->from('inventorystock_store')
            ->where('inventorystock_store.store_id = :value')
        ;

        $store_id = $this->addSimpleHidden("id", p__('inventorystock', 'Store'));
        $store_id->addValidator("Db_RecordExists", true, $select);
        $store_id->setMinimalDecorator();

        $mini_submit = $this->addMiniSubmit();
    }
}