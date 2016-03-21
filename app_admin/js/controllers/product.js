'use strict';

angular.module('foyerApp.controllers')
//controlleur de la page product
.controller('productController', ['$scope', '$http','$document', 'CONFIG', 'loginService', function($scope, $http, $document, CONFIG, loginService) {

  $scope.apiUrl = CONFIG.API_URL;

  loginService.isLogged().then(function() {
    //recuperation des produits
    $http.get(CONFIG.API_URL+'product/').success(function(data){
      $scope.products = data;
      $scope.loaded = true;
    });
  });

  //suppression d'un produit
  $scope.delete = function(item) {
    $http.delete(CONFIG.API_URL+'product/'+item.id_product).success(function() {
      item.available = 0;
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };

  //plus en stock d'un produit
  $scope.stock = function(item) {
    $http.put(CONFIG.API_URL+'product/'+item.id_product+'/available/2').success(function() {
      item.available = 2;
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };

  //disponiblité d'un produit
  $scope.disponible = function(item) {
    $http.put(CONFIG.API_URL+'product/'+item.id_product+'/available/1').success(function() {
      item.available = 1;
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}])

//controlleur du form de produit
.controller('productFormController', ['$scope', '$http', '$routeParams', '$document', '$location', 'CONFIG', 'fileUpload', 'loginService', function($scope, $http, $routeParams, $document, $location, CONFIG, fileUpload, loginService) {
  $scope.product = {};
  $scope.action = 'add';
  $scope.apiUrl = CONFIG.API_URL;

  loginService.isLogged().then(function() {
    //recuperation du produit
    if ($routeParams.id_product) {
      $http.get(CONFIG.API_URL+'product/id_product/'+$routeParams.id_product).success(function(data){
        $scope.product = data;
        $scope.action = 'edit';
      });
    }
  });

  //Post du formulaire
  $scope.submitForm = function() {
    //edit
    if($scope.action === 'edit'){
      $http.put(CONFIG.API_URL+'product/'+$scope.product.id_product, $scope.product).success(function() {
        fileUpload.uploadFileToUrl($scope.productImage, CONFIG.API_URL+'product/img/'+data.status.success).success(function(data){
          $location.path('product');
        });
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'product/', $scope.product).success(function(data) {
        fileUpload.uploadFileToUrl($scope.productImage, CONFIG.API_URL+'product/img/'+data.status.success).success(function(data){
          $location.path('product');
        });
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
  };
  //reinitialisation du form
  $scope.reinitialiser = function() {
    $scope.product = null;
  };
  //suppression du produit
  $scope.delete = function() {
    $http.delete(CONFIG.API_URL+'product/'+$scope.product.id_product).success(function() {
      $location.path('product');
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}]);
