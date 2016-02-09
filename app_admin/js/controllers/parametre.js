'use strict';

angular.module('foyerApp.controllers')

//controller des parametres
.controller('parametreController', ['$scope', '$http', '$window', '$routeParams', '$document', '$location','CONFIG','fileUpload', function($scope, $http, $window, $routeParams, $document, $location, CONFIG, fileUpload) {
  $scope.api_url = CONFIG.API_URL;

  //recuperation de la banière
  $http.get(CONFIG.API_URL+'banniere/').success(function(data){
    $scope.banniere = data;
    $scope.loaded = true;
  });

  $scope.submitForm = function(item, event) {
    fileUpload.uploadFileToUrl($scope.banniereImage, CONFIG.API_URL+'banniere/');
    $window.location.reload();
  }
}]);