<?php
/**
 *
 * Schema definition for 'inventorystock_products'
 *
 * Last update: 2020-04-26
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['inventorystock_products'] = [
    'id' => [
        'type' => 'int(11) unsigned',
        'auto_increment' => true,
        'primary' => true,
    ],
    'value_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'application_option_value',
            'column' => 'value_id',
            'name' => 'FK_INVENTORY_PRODUCT_VID',
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',
        ],
        'index' => [
            'key_name' => 'value_id',
            'index_type' => 'BTREE',
            'is_null' => false,
            'is_unique' => false,
        ],
    ],
    'customer_id' => [
        'type' => 'int(11)',
        'is_null' => true,
    ],
    'store_id' => [
        'type' => 'int(11)',
        'is_null' => true,
    ],
    'category_id' => [
        'type' => 'int(11)',
        'is_null' => true,
    ],
    'ean_number' => [
        'type' => 'varchar(255)',
        'is_null' => false,
    ],
    'qty' => [
        'type' => 'int(11)',
        'is_null' => false,
    ],    
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ],
];
