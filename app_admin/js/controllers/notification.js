'use strict';

angular.module('foyerApp.controllers')
  .controller('notificationController',['$scope', '$http', 'CONFIG', function($scope, $http, CONFIG) {
  //recuperation des users
  $http.get(CONFIG.API_URL+'user/').success(function(data){
    $scope.logins = data;
    $scope.loaded = true;
  });

  }]);