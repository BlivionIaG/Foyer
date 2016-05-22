'use strict';

angular.module('starter.controllers')

.controller('productsController', ['$scope', '$http', 'CONFIG', 'sessionService', function($scope, $http, CONFIG, sessionService) {

  //recuperation des produits
  $http.get(CONFIG.API_URL+'product/').success(function(data) {
    $scope.products = data;
    $scope.loaded = true;
  });

  $scope.realoadProducts = function() {
    //recuperation des produits
    $http.get(CONFIG.API_URL+'product/').success(function(data) {
      $scope.products = data;
    }).finally(function() {
      // Stop the ion-refresher from spinning
      $scope.$broadcast('scroll.refreshComplete');
    });
  };

}])

.controller('productController', ['$scope', '$http', '$stateParams', 'CONFIG', 'sessionService', function($scope, $http, $stateParams, CONFIG, sessionService) {

  //recuperation du produit
  if ($stateParams.productId) {
    $http.get(CONFIG.API_URL+'product/id_product/'+$stateParams.productId).success(function(data) {
      $scope.product = data;
      $scope.loaded = true;
    });
  }

  $scope.realoadProduct = function() {
    //recuperation du produit
    if ($stateParams.productId) {
      $http.get(CONFIG.API_URL+'product/id_product/'+$stateParams.productId).success(function(data) {
        $scope.product = data;
      }).finally(function() {
        $scope.$broadcast('scroll.refreshComplete');
      });
    }
  };
}]);