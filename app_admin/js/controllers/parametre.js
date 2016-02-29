'use strict';

angular.module('foyerApp.controllers')

//controller des parametres
.controller('parametreController', ['$scope', '$http', '$window', '$document','CONFIG','fileUpload', function($scope, $http, $window, $document, CONFIG, fileUpload) {
  $scope.apiUrl = CONFIG.API_URL;

  //recuperation de la banière
  $http.get(CONFIG.API_URL+'banniere/').success(function(data){
    $scope.banniere = data;
    $scope.loaded = true;
  });

  $scope.submitForm = function() {
    fileUpload.uploadFileToUrl($scope.banniereImage, CONFIG.API_URL+'banniere/');
    $window.location.reload();
  };
}]);
