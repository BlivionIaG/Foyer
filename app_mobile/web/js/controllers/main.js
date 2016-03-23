'use strict';

angular.module('foyerApp.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$http', 'loginService', 'sessionService', 'CONFIG', '$location', function($rootScope, $scope, $http, loginService, sessionService, CONFIG, $location) {
  
  $scope.apiUrl = CONFIG.API_URL;

  $rootScope.$on(function () {
    $rootScope.login = '';
  });
  // Logout
  $rootScope.logout = function() {
    loginService.logout();
  };

  //recuperation du panier
  var cart = JSON.parse(sessionService.get('cart'));
  if(cart){
    $rootScope.cart = cart;
  }else{
    $rootScope.cart = new Array();
  }

  //ajout d'un produit au panier
  $scope.addCart = function(items) {
    //fix pour les boutons simples
    if(!angular.isArray(items)){
      items = [items];
    }
    angular.forEach(items, function(item) {
      var exist = false;
      //si vide on init array
      if(!$scope.cart){
        $rootScope.cart = new Array();
      }
      else{
        //on regarde si il existe pas deja pour ajouter quantité
        angular.forEach($scope.cart, function(value, key) {
          if(value.id_product === item.id_product){
            $rootScope.cart[key].quantity ++;
            exist = true;
          }
        });
      }
      //sinon on l'ajoute
      if(!exist){
        item.quantity = 1;
        $rootScope.cart.push(item);
      }
    });

    //enregistrement du panier
    sessionService.set('cart', JSON.stringify($rootScope.cart));
  };

  //suppression d'un produit du panier
  $scope.deleteCart = function(items) {
    //fix pour les boutons simples
    if(!angular.isArray(items)){
      items = [items];
    }
    angular.forEach(items, function(item) {
      //on parcours le tableau pour l'enlever
      angular.forEach($rootScope.cart, function(value, key) {
        if(value.id_product === item.id_product){
          //si c'est pas le dernier quantité -1
          if(value.quantity > 1){
            $rootScope.cart[key].quantity --;
          }
          //sinon on le supprimer
          else{
            //delete $rootScope.cart[key];
            $rootScope.cart.splice(key, 1);
          }
        }
      });
    });

    //enregistrement du panier
    sessionService.set('cart', JSON.stringify($rootScope.cart));
  };

  $scope.go = function ( path ) {
    $location.path( path );
  };
}])

.controller('errorController', function() {})

.controller('identificationController', ['$rootScope', '$scope', '$http', 'loginService', function($rootScope, $scope, $http, loginService) {
  $scope.username = '';
  $scope.password = '';
  $scope.login = function(login) {
    loginService.login(login, $scope);
  };
}]);
