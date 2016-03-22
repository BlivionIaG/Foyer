'use strict';

angular.module('foyerApp.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$http', 'loginService', 'sessionService', 'CONFIG', function($rootScope, $scope, $http, loginService, sessionService, CONFIG) {

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
    $scope.cart = cart;
  }else{
    $scope.cart = new Array();
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
        $scope.cart = new Array();
      }
      else{
        //on regarde si il existe pas deja pour ajouter quantité
        angular.forEach($scope.cart, function(value, key) {
          if(value.id_product === item.id_product){
            $scope.cart[key].quantity ++;
            exist = true;
          }
        });
      }
      //sinon on l'ajoute
      if(!exist){
        item.quantity = 1;
        $scope.cart.push(item);
      }
    });

    //enregistrement du panier
    sessionService.set('cart', JSON.stringify($scope.cart));
  };

  //suppression d'un produit du panier
  $scope.deleteCart = function(items) {
    //fix pour les boutons simples
    if(!angular.isArray(items)){
      items = [items];
    }
    angular.forEach(items, function(item) {
      //on parcours le tableau pour l'enlever
      angular.forEach($scope.cart, function(value, key) {
        if(value.id_product === item.id_product){
          //si c'est pas le dernier quantité -1
          if(value.quantity > 1){
            $scope.cart[key].quantity --;
          }
          //sinon on le supprimer
          else{
            //delete $scope.cart[key];
            $scope.cart.splice(key, 1);
          }
        }
      });
    });

    //enregistrement du panier
    sessionService.set('cart', JSON.stringify($scope.cart));
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
