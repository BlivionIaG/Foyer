'use strict';

angular.module('starter.controllers')

.controller('commandsController', ['$rootScope', '$scope', '$http', 'CONFIG', function($rootScope, $scope, $http, CONFIG) {

  //recuperation des commandes
  $http.get(CONFIG.API_URL+'command/login/'+$rootScope.login).success(function(data) {
    $scope.commands = data;
    $scope.loaded = true;
  });

  $scope.reloadCommands = function() {
    //recuperation des commandes
    $http.get(CONFIG.API_URL+'command/login/'+$rootScope.login).success(function(data) {
      $scope.commands = data;
    }).finally(function() {
      // Stop the ion-refresher from spinning
      $scope.$broadcast('scroll.refreshComplete');
    });
  };

}])

.controller('commandController', ['$scope', '$http', '$stateParams', 'CONFIG', function($scope, $http, $stateParams, CONFIG) {

  //recuperation de la commande
  if ($stateParams.commandId) {
    $http.get(CONFIG.API_URL+'command/id_command/'+$stateParams.commandId).success(function(data) {
      $scope.command = data;
      $scope.loaded = true;
    });
  }

  $scope.reloadCommand = function() {

    //recuperation de la commande
    if ($stateParams.commandId) {
      $http.get(CONFIG.API_URL+'command/id_command/'+$stateParams.commandId).success(function(data) {
        $scope.command = data;
      }).finally(function() {
        $scope.$broadcast('scroll.refreshComplete');
      });
    }
  };

}]);