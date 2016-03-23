'use strict';

angular.module('foyerApp.controllers')

.controller('homeController', ['$rootScope', '$scope', '$http', 'CONFIG', 'loginService', function($rootScope, $scope, $http, CONFIG, loginService) {

  $rootScope.title = 'Accueil';

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
}]);