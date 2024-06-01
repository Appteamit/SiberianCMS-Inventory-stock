<?php

use Siberian\Assets;
use Siberian\Translation;
use Siberian_Module as Module;

$init = function($bootstrap) {
    Assets::registerScss([
        '/app/local/modules/Inventorystock/features/inventorystock/scss/inventorystock.scss'
    ]);
    Translation::registerExtractor(
        'inventorystock',
        'Inventorystock',
        '/app/local/modules/Inventorystock/resources/translations/default/inventorystock.po');
    
};

