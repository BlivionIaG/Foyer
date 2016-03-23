'use strict';

angular.module('foyerApp.controllers')

//controller de commande
.controller('commandsController',['$scope', '$rootScope', '$http', 'CONFIG', 'loginService', function($scope, $rootScope, $http, CONFIG, loginService) {

  $rootScope.title = 'Commandes';

  loginService.isLogged().then(function() {
    //recuperation des commandes
    $http.get(CONFIG.API_URL+'command/login/'+$rootScope.login).success(function(data){
      $scope.commands = data;
      $scope.loaded = true;
    });
  });

}]);