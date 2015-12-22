'use strict';

angular.module('foyerApp.controllers')

//controller de commande
.controller('commandController',['$scope', '$http', '$window','$document', 'CONFIG', function($scope, $http, $window, $document, CONFIG) {
  //recuperation des commandes
  $http.get(CONFIG.API_URL+'command/').success(function(data){
    $scope.commands = data;
    $scope.loaded = true;
  });

  //total d'une commande
  $scope.getTotal = function(item, event) {
    var total = 0;
    for(var i = 0; i < $scope.cart.products.length; i++){
        total += item.product.price * item.product.quantity;
    }
    return total;
  };

  //suppression d'une commande
  $scope.delete = function(item, event) {
    item.state = 0;
    $http.delete(CONFIG.API_URL+'command/'+item.id_commande).success(function(data) {
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };

  //confirmation d'une commande
  $scope.confirm = function(item, event) {
    item.state = 2;
    delete item.$$hashKey;
    $http.put(CONFIG.API_URL+'command/'+item.id_commande,item).success(function(data) {
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };

  //finalisation d'une commande
  $scope.final = function(item, event) {
    item.state = 3;
    delete item.$$hashKey;
    $http.put(CONFIG.API_URL+'command/'+item.id_commande,item).success(function(data) {
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}])

//controller de form commande
.controller('commandFormController', ['$scope', '$http', '$window', '$routeParams', '$document', '$location','CONFIG', function($scope, $http, $window,$routeParams, $document, $location, CONFIG) {
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
        $location.path('command');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'command/', $scope.command).success(function(data) {
        $location.path('command');
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
      $location.path('command');
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}]);