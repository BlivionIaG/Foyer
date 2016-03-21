'use strict';

angular.module('foyerApp.controllers')

.controller('homeController', ['$scope', '$http', 'CONFIG', 'loginService', function($scope, $http, CONFIG, loginService) {
  loginService.isLogged().then(function() {
    //recuperation des commandes à valider
    $http.get(CONFIG.API_URL+'command/state/1').success(function(data){
      $scope.commandsValidate = data;
    });
    //recuperation des stats pour le graph
    $http.get(CONFIG.API_URL+'command/stats/').success(function(data){
      $scope.commandStats = data;
    });
  });
}]);