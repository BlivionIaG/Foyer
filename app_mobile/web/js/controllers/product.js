'use strict';

angular.module('foyerApp.controllers')
//controlleur de la page product
.controller('productsController', ['$rootScope', '$scope', '$http', 'CONFIG', 'loginService', function($rootScope, $scope, $http, CONFIG, loginService) {
  
  $rootScope.title = 'Produits';

  loginService.isLogged().then(function() {
    //recuperation des produits
    $http.get(CONFIG.API_URL+'product/').success(function(data){
      $scope.products = data;
      $scope.loaded = true;
    });
    //recuperation de la bannière
    $http.get(CONFIG.API_URL+'banniere/').success(function(data){
      $scope.banniere = data;
      $scope.loaded = true;
    });
  });
}])

//controlleur du form de produit
.controller('productController', ['$scope', '$http', '$routeParams', 'CONFIG', 'loginService', function($scope, $http, $routeParams, CONFIG, loginService) {

  loginService.isLogged().then(function() {
    //recuperation du produit
    if ($routeParams.id_product) {
      $http.get(CONFIG.API_URL+'product/id_product/'+$routeParams.id_product).success(function(data){
        $scope.product = data;
        $scope.loaded = true;
        console.log(data);
      });
    }
  });

}]);
