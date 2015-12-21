'use strict';

angular.module('foyerApp.controllers')
//controlleur de la page product
.controller('productController', ['$scope', '$http', '$window','$document', 'CONFIG', function($scope, $http, $window, $document, CONFIG) {
  //recuperation des produits
  $http.get(CONFIG.API_URL+'product/').success(function(data){
    $scope.products = data;
    $scope.loaded = true;
  });
  //suppression d'un produit
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'product/'+item).success(function(data) {
      $window.location.reload();
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}])

//controlleur du form de produit
.controller('productFormController', ['$scope', '$http', '$window', '$routeParams', '$document', '$location','CONFIG', function($scope, $http, $window, $routeParams, $document, $location, CONFIG) {
  $scope.product = {};
  $scope.action = 'add';

  //recuperation du produit
  if ($routeParams.id_product) {
    $http.get(CONFIG.API_URL+'product/id_product/'+$routeParams.id_product).success(function(data){
      $scope.product = data;
      $scope.action = 'edit';
    });
  }

  //Post du formulaire
  $scope.submitForm = function(item, event) {
    //edit
    if($scope.action == 'edit'){
      delete $scope.product.hash_image;
      $http.put(CONFIG.API_URL+'product/'+$scope.product.id_product, $scope.product).success(function(data) {
        $location.path('product');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'product/', $scope.product).success(function(data) {
        $location.path('product');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
  };
  //reinitialisation du form
  $scope.reinitialiser = function(item, event) {
    $scope.product = null;
  };
  //suppression du produit
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'product/'+$scope.product.id_product).success(function(data) {
      $location.path('product');
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}]);
