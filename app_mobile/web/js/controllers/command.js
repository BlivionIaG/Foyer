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

}])

.controller('commandController', ['$scope', '$http', '$routeParams', 'CONFIG', 'loginService', function($scope, $http, $routeParams, CONFIG, loginService) {

  loginService.isLogged().then(function() {
    //recuperation de la commande
    if ($routeParams.id_command) {
      $http.get(CONFIG.API_URL+'command/id_command/'+$routeParams.id_command).success(function(data){
        $scope.command = data;
        $scope.loaded = true;
      });
    }
  });
}]);