'use strict';

angular.module('foyerApp.controllers')

//controller de commande
.controller('commandController',['$scope', '$http', '$window', 'CONFIG', function($scope, $http, $window, CONFIG) {
  //recuperation des commandes
  $http.get(CONFIG.API_URL+'command/').success(function(data){
    $scope.commands = data;
    $scope.loaded = true;
  });

  //suppression d'une commande
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'command/'+item).success(function(data) {
      $window.location.reload();
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}])

//controller de form commande
.controller('commandFormController', ['$scope', '$http', '$window', '$routeParams', '$document', '$location','CONFIG', function($scope, $http, $routeParams, $document, $location, CONFIG) {
  $scope.command = {};
  $scope.action = 'add';

  //recuperation des users
  $http.get(CONFIG.API_URL+'user/').success(function(data){
    $scope.users = data;
  });

  //recuperation de la commande
  if ($routeParams.id_commande) {
    $http.get(CONFIG.API_URL+'command/id_commande/'+$routeParams.id_commande).success(function(data){
      $scope.command = data;
      $scope.action = 'edit';
    });
  }

  //Post du formulaire
  $scope.submitForm = function(item, event) {
    //edit
    if($scope.action == 'edit'){
      $http.put(CONFIG.API_URL+'command/'+$scope.command.id_commande, $scope.command).success(function(data) {
        console.log(data);
        $location.path('#command');
      }).error(function(data) {
        console.log(data);
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'command/', $scope.command).success(function(data) {
        $location.path('#command');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
  };
  //reinitialisation du form
  $scope.reinitialiser = function(item, event) {
    $scope.command = null;
  };
  //supression de la commande
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'command/'+$scope.command.id_commande).success(function(data) {
      $location.path('#command');
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}]);