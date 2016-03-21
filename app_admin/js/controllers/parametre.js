'use strict';

angular.module('foyerApp.controllers')

//controller des parametres
.controller('parametreController', ['$scope', '$http', '$window', '$document', 'CONFIG', 'fileUpload', 'loginService', function($scope, $http, $window, $document, CONFIG, fileUpload, loginService) {
  $scope.apiUrl = CONFIG.API_URL;

  loginService.isLogged().then(function() {
    //recuperation de la banière
    $http.get(CONFIG.API_URL+'banniere/').success(function(data){
      $scope.banniere = data;
      $scope.loaded = true;
    });
  });

  $scope.submitForm = function() {
    $scope.submitted = true;
    if($scope.banniereImage){
      fileUpload.uploadFileToUrl($scope.banniereImage, CONFIG.API_URL+'banniere/').success(function(data){
        $window.location.reload();
      });
    }else{
      $window.location.reload();
    }
  };
}]);
