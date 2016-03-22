'use strict';

angular.module('foyerApp.controllers')

.controller('cartController', ['$scope', '$http', 'CONFIG', function($scope, $http, CONFIG) {

  //total d'une commande
  $scope.getCartTotal = function() {
    var total = 0;
    angular.forEach($scope.cart, function(item, key) {
      total += item.quantity * item.price;
    });
    return total;
  };

  $scope.deleteCartAll = function() {
    delete $scope.cart;
    //enregistrement du panier
    sessionService.destroy('cart');
  };
}]);