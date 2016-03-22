'use strict';

angular.module('foyerApp.controllers')

.controller('notificationsController', ['$rootScope', '$scope', '$http', 'CONFIG', 'loginService', function($rootScope, $scope, $http, CONFIG, loginService) {
  loginService.isLogged().then(function() {
    //recuperation des commandes
    $http.get(CONFIG.API_URL+'notification/login/'+$rootScope.login).success(function(data){
      $scope.notifications = data;
      $scope.loaded = true;
    });
  });
}]);