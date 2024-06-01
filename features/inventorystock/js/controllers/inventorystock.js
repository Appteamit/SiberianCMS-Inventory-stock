/**
 * Inventorystock Home version 1 controllers
 */
angular.module('starter')
    .controller('InventorystockController', function (Application, Dialog, Loader, $timeout, $filter, Inventorystock, $ionicModal, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, $cordovaBarcodeScanner) {
        $scope.value_id = Inventorystock.value_id = $stateParams.value_id;
        $scope.is_logged_in = Customer.isLoggedIn();
        $scope.is_store_available = false;
        $scope.is_loading = false;
        $scope.is_submitting = false;
        $scope.page_title = '';
        $scope.default_store = {};
        $scope.products = {};
        $scope.searchText = '';           
        $scope.is_searching = false;

        $scope.forTranslate = function (text) {
               return $translate.instant(text, "inventorystock");
        };

        $rootScope.$on(SB.EVENTS.AUTH.loginSuccess, function () {
            $scope.loadContent();
            $scope.is_logged_in = Customer.isLoggedIn();
            $scope.customer = Customer.customer;
        });

        $rootScope.$on(SB.EVENTS.AUTH.logoutSuccess, function () {
            $scope.loadContent();
            $scope.is_logged_in = Customer.isLoggedIn();
            $scope.customer = Customer.customer;
        });
        
        /**
         *login
         */
        $scope.login = function(){
            Customer.loginModal($scope);
        }

        $scope.loadContent = function() {
      
            if(!Customer.isLoggedIn()) return false;      
            $scope.is_loading = true;
            $scope.store_id = 0;
            $scope.default_store = Inventorystock.getDefaultStore();

            if($scope.default_store){
                $scope.store_id  = $scope.default_store.store_id;
            }

            Inventorystock.findAll($scope.store_id).success(function (data) {

	            $scope.page_title = data.page_title;
                $scope.payout = data;
                $scope.products = data.products;

                if(data.total_stores == 0){
                    $scope.is_store_available = false;
                
                }else{
                    $scope.default_store = Inventorystock.getDefaultStore();                    
                    if(!$scope.default_store){
                        if(data.total_stores == 1){
                            Inventorystock.setDefaultStore(data.stores[0]);
                            $scope.is_store_available = true;                            
                        } 
                    }else{
                     var oldStore = $filter('filter')(data.stores , {store_id: $scope.default_store.store_id });
                     console.log("oldStore", oldStore);
                     if(oldStore.length == 0){
                            Inventorystock.setDefaultStore({});
                            Dialog.alert($translate.instant("Error", "inventorystock") , $scope.default_store.store_name+' '+$translate.instant("is not available, Please change the default store.", "inventorystock"), "OK", -1, "inventorystock");
                            $scope.is_store_available = false;
                     }else{
                         $scope.is_store_available = true;
                     }                    
                    }                   
                }
	                     
	        }).error(function () {
	            $scope.is_loading = false;
	        }).finally(function () {
	            $scope.is_loading = false;
	        });

        }


        /**
         *Store change
         */
        $scope.changeStore = function() {
            $ionicModal.fromTemplateUrl('features/inventorystock/assets/templates/l1/modal/store.html', {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.stores = $scope.payout.stores;
                $scope.storeModal = modal;
                $scope.storeModal.show();
            });
        };

         /**
         * close modal 
         */
        $scope.closeChangeStore = function (){
             $scope.storeModal.hide();
        }

        /**
         *info save
         */
        $scope.setDefaultStore = function (store) {
                Inventorystock.setDefaultStore(store);
                $scope.loadContent();
                $scope.closeChangeStore();
        }
      
         /**
         *Add Product
         */
        $scope.openProductModal = function(ean_number = '') {
            $scope.is_loading = true;
            $ionicModal.fromTemplateUrl('features/inventorystock/assets/templates/l1/modal/add.html', {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.cart = {};
                $scope.cart = Inventorystock.getCart();                    
                $scope.cart.ean_number  = ean_number;
                $scope.cart.qty  = '';
                $scope.cart.id  = '';
                
                Inventorystock.setCart($scope.cart);                
                $scope.is_loading = false;
                $scope.addProductModal = modal;
                $scope.addProductModal.show();
            });
        };

        /**
         *Add Product
         */
        $scope.openEditProductModal = function(param) {
            $scope.is_loading = true;
            $ionicModal.fromTemplateUrl('features/inventorystock/assets/templates/l1/modal/add.html', {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.cart = {};
                $scope.cart = Inventorystock.getCart();                    
                $scope.cart.ean_number  = parseInt(param.ean_number);
                $scope.cart.qty  = parseInt(param.qty);
                $scope.cart.id  = parseInt(param.id);
                $scope.cart.category_id = parseInt(param.category_id);
                $scope.cart.category_name = param.category_name;

                $scope.is_loading = false;
                $scope.addProductModal = modal;
                $scope.addProductModal.show();
            });
        };

         /**
         * close modal 
         */
        $scope.closeAddProduct = function (){
             $scope.addProductModal.hide();
        }

        /**
         *Save Product
         */
        $scope.saveProduct = function () {
            $scope.default_store = Inventorystock.getDefaultStore();   
            $scope.cart.store_name = $scope.default_store.store_name;
            $scope.cart.store_id = $scope.default_store.store_id;   
            
            $scope.is_submitting = true;
            Inventorystock.saveProduct($scope.cart).success(function (data) {
                $scope.products = data.products;
                $scope.closeAddProduct();
                $scope.is_submitting = false;
            }).error(function () {
                $scope.is_submitting = false;
            }).finally(function () {
                $scope.is_submitting = false;
            });
        }

        /**
         *Category
         */
        $scope.openCategoryModal = function() {
            $scope.is_loading = true;
            $ionicModal.fromTemplateUrl('features/inventorystock/assets/templates/l1/modal/category.html', {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function(modal) {
                $scope.is_loading = false;
                $scope.categories = $scope.payout.categories;
                $scope.addCategoryModal = modal;
                $scope.addCategoryModal.show();
            });
        };

         /**
         * close modal 
         */
        $scope.closeCategoryModal = function (){
             $scope.addCategoryModal.hide();
        }

        /**
         *Save Product
         */
        $scope.selectCategory = function (category) {
            $scope.cart.category_id = category.id;
            $scope.cart.category_name = category.category_name;
            Inventorystock.setCart($scope.cart);  
            $scope.closeCategoryModal();
        }

          /**
         *search Product
         */
        $scope.searchProduct = function () {
            console.log('searchText', $scope.searchText);
            $scope.default_store = Inventorystock.getDefaultStore();   
            $scope.is_searching = true;
            Inventorystock.searchProduct($scope.default_store.store_id, $scope.searchText).success(function (data) {
                $scope.products = data.products;
                $scope.is_searching = false;
            }).error(function () {
                $scope.is_searching = false;
            }).finally(function () {
                $scope.is_searching = false;
            });
        }



         $scope.showScanCamera = function () {
            if (!Application.is_webview) {

                $cordovaBarcodeScanner.scan().then(function (barcodeData) {  
                    if (!barcodeData.cancelled && (barcodeData.text !== '')) {
                        $timeout(function () {
                            if(barcodeData.format === 'EAN_13' || barcodeData.format === "EAN_8"){
                                var barcodeDataText = barcodeData.text;
                                $scope.openProductModal(parseInt(barcodeDataText));
                            }else{
                                 Dialog.alert('Error', 'Invalid code.', 'OK', -1);
                            }                            
                        });

                    }else{
                        Dialog.alert($translate.instant("Error", "inventorystock") , $translate.instant("Unreadable QRCode, sorry", "inventorystock"), "OK", -1, "inventorystock");
                    }
                    
                }, function (error) {
                    Dialog.alert('Error', 'An error occurred while reading the code.', 'OK', -1);
                });

             } else {
                Dialog.alert($translate.instant("Info", "inventorystock") , $translate.instant("This will open the code scan camera on your device", "inventorystock"), $translate.instant("Ok", "inventorystock"), -1);
            }
        };


        $scope.loadContent();
 
});
