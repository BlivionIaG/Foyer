'use strict';

angular.module('starter.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$ionicPopup', 'CONFIG', 'sessionService', '$http', 'loginService', '$ionicListDelegate', function($rootScope, $scope, $ionicPopup, CONFIG, sessionService, $http, loginService, $ionicListDelegate) {

  loginService.isLogged();

  $scope.apiUrl = CONFIG.API_URL;

  //recuperation du panier
  $rootScope.cart = sessionService.get('cart');
  //recuperation des favoris
  $rootScope.favoris = sessionService.get('favoris');
  if($rootScope.favoris === null){
    $rootScope.favoris = new Array();
  }
  //ajout d'un produit au panier
  $scope.addCart = function(item) {

    var exist = false;
    //si vide on init array
    if(!$rootScope.cart){
      $rootScope.cart = new Array();
    }
    //on regarde si il existe pas deja pour ajouter quantité
    else{
      angular.forEach($rootScope.cart, function(value, key) {
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

    //enregistrement du panier
    sessionService.set('cart', $rootScope.cart);
  };

  //suppression d'un produit du panier
  $rootScope.deleteCart = function(item) {

      //on parcours le tableau pour l'enlever
      angular.forEach($rootScope.cart, function(value, key) {
        if(value.id_product === item.id_product){
          //si c'est pas le dernier quantité -1
          if(value.quantity > 1){
            $rootScope.cart[key].quantity --;
          }
          //sinon on le supprimer
          else{
            $rootScope.cart.splice(key, 1);
          }
        }
      });

    //enregistrement du panier
    sessionService.set('cart', $rootScope.cart);
  };

  //suppression du panier
  $rootScope.deleteAllCart = function() {

    $rootScope.cart = new Array();

    //enregistrement du panier
    sessionService.set('cart', $rootScope.cart);
  };

  //total quantite panier
  $scope.getCartQuantity = function() {
    var quantity = 0;
    angular.forEach($rootScope.cart, function(item) {
      quantity += item.quantity;
    });
    return quantity;
  };

  //total du panier
  $scope.getCartTotal = function() {
    var total = 0;
    angular.forEach($rootScope.cart, function(item) {
      total += item.quantity * item.price;
    });
    return total;
  };

  //supprimer une commande
  $scope.deleteCommand = function(item){

    var confirmPopup = $ionicPopup.confirm({
     title: 'Commande',
     template: 'Êtes vous sûr de vouloir supprimer la commande n°'+item.id_command+' ?'
   });

    confirmPopup.then(function(res) {
       if(res) {
        $http.put(CONFIG.API_URL+'command/'+item.id_command+'/state/0').success(function() {
          item.state = "0";
        });
      }
    }).finally(function(){
        $ionicListDelegate.closeOptionButtons();
    });
  };

  $scope.logout = function(){

    var confirmPopup = $ionicPopup.confirm({
      title: 'Déconnexion',
      template: 'Êtes vous sûr de vouloir vous déconnecter ?'
    });

    confirmPopup.then(function(res) {
      if(res) {
        loginService.logout();
      }
    }); 
  };

  $scope.addFavoris = function(item) {
    //ajout du produit aux favoris
    $rootScope.favoris.push(item);
    sessionService.set('favoris', $rootScope.favoris);
  };

  $scope.deleteFavoris = function(item) {
    //suppression du produit des favoris
    $rootScope.favoris.splice($rootScope.favoris.indexOf(item), 1);
    sessionService.set('favoris', $rootScope.favoris);
  };

}]);