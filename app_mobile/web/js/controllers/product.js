'use strict';

angular.module('foyerApp.controllers')
//controlleur de la page product
.controller('productsController', ['$scope', '$http','$document', 'CONFIG', 'loginService', function($scope, $http, $document, CONFIG, loginService) {

  $scope.apiUrl = CONFIG.API_URL;

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
.controller('productController', ['$scope', '$http', '$routeParams', '$document', '$location', 'CONFIG', 'loginService', function($scope, $http, $routeParams, $document, $location, CONFIG, loginService) {
  $scope.apiUrl = CONFIG.API_URL;

  loginService.isLogged().then(function() {
    //recuperation du produit
    if ($routeParams.id_product) {
      $http.get(CONFIG.API_URL+'product/id_product/'+$routeParams.id_product).success(function(data){
        $scope.product = data;
        $scope.loaded = true;
      });
    }
  });

}]);
