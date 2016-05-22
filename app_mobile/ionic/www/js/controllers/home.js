'use strict';

angular.module('starter.controllers')

.controller('homeController', ['$scope', '$http', 'CONFIG', function($scope, $http, CONFIG) {

  //recuperation de la bannière
  $http.get(CONFIG.API_URL+'banniere/').success(function(data){
    $scope.banniere = data;
    $scope.loaded = true;
  });

}]);