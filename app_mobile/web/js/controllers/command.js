'use strict';

angular.module('foyerApp.controllers')

//controller de commande
.controller('commandsController',['$scope', '$http','$document', 'CONFIG', 'loginService', function($scope, $http, $document, CONFIG, loginService) {
 //liste des etats des commandes
 $scope.roles = {
    0: 'Supprimée',
    1: 'En cours de validation',
    2: 'Validée',
    3: 'Servie',
  };
  $scope.states = ['1','2'];

  $scope.filterCheckBox = function(item) {
    for (var i = 0; i < $scope.states.length; i++) {
      if($scope.states[i] === item.state){
        return true;
      }
    }
    return false;
  };

  loginService.isLogged().then(function() {
    //recuperation des commandes
    $http.get(CONFIG.API_URL+'command/').success(function(data){
      $scope.commands = data;
      $scope.loaded = true;
    });
  });

  //total d'une commande
  $scope.getTotal = function(item) {
    var total = 0;
    for(var i = 0; i < $scope.cart.products.length; i++){
        total += item.product.price * item.product.quantity;
    }
    return total;
  };

}]);