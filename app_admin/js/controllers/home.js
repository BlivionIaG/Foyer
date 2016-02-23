'use strict';

angular.module('foyerApp.controllers')

.controller('homeController', ['$scope', '$http', 'CONFIG', function($scope, $http, CONFIG) {
  //recuperation des commandes à valider
  $http.get(CONFIG.API_URL+'command/state/1').success(function(data){
    $scope.commandsValidate = data;
  });
  $http.get(CONFIG.API_URL+'command/stats/').success(function(data){
    $scope.commandStats = data;
  });
}]);