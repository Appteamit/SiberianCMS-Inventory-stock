<?php
/**
 *
 * Schema definition for 'inventorystock_category'
 *
 * Last update: 2020-04-24
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['inventorystock_category'] = [
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
            'name' => 'FK_INVENTORY_CATEGORY_STORE_VID',
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
    'category_name' => [
        'type' => 'varchar(255)',
        'is_null' => false,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'status' => [
        'type' => 'enum(\'active\',\'inactive\', \'deleted\')',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ], 
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ],
];
