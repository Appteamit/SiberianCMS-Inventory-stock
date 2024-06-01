/**
 * Inventorystock factory
 */
angular
    .module('starter')
    .factory('Inventorystock', function ($state, $pwaRequest, $session, $rootScope, $log) {
        var factory = {};
        factory.value_id = null;
        factory.cart = {};

        factory.setCart = function (cart) {
            factory.cart = cart;
            return factory;
        };
 
        factory.getCart = function () {
            return factory.cart;
        };
      
        factory.setDefaultStore = function (default_store) {
           return localStorage.setItem("default_store", JSON.stringify(default_store));
        };

        factory.getDefaultStore = function () {
            return JSON.parse(localStorage.getItem("default_store"));
        };

         factory.setValueId = function (valueId) {
            factory.value_id = valueId;
            return factory;
        };

        factory.getValueId = function () {
            return factory.value_id;
        };

        factory.findAll = function (store_id) {           
            return $pwaRequest.post('/inventorystock/mobile_view/findall', {
                urlParams: {
                    value_id: factory.value_id,
                    store_id: store_id                   
                },
                cache: false,
                refresh: true
            });
        };

        factory.searchProduct = function (store_id, searchText) {           
            return $pwaRequest.post('/inventorystock/mobile_view/search-product', {
                urlParams: {
                    value_id: factory.value_id,
                    store_id: store_id,
                    q: searchText                
                },
                cache: false,
                refresh: true
            });
        };

        factory.saveProduct = function (param) {           
            return $pwaRequest.post('/inventorystock/mobile_view/save-product', {
                urlParams: {
                    value_id: factory.value_id                   
                },
                data: param,
                cache: false,
                refresh: true
            });
        };
 
        return factory;
    });