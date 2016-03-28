'use strict';

angular.module('foyerApp.controllers')

.controller('homeController', ['$rootScope', '$scope', '$http', 'CONFIG', 'loginService', function($rootScope, $scope, $http, CONFIG, loginService) {

  $rootScope.title = 'Accueil';

  loginService.isLogged().then(function() {
    //recuperation de la bannière
    $http.get(CONFIG.API_URL+'banniere/').success(function(data){
      $scope.banniere = data;
      $scope.loaded = true;
    });
  });
}]);