'use strict';

angular.module('foyerApp.controllers')

.controller('cartController', ['$rootScope', '$scope', '$http', 'CONFIG', 'sessionService', 'loginService', function($rootScope, $scope, $http, CONFIG, sessionService, loginService) {

  $rootScope.title = 'Panier';

  //total d'une commande
  $scope.getCartTotal = function() {
    var total = 0;
    angular.forEach($scope.cart, function(item, key) {
      total += item.quantity * item.price;
    });
    return total;
  };

  $scope.deleteCartAll = function() {
    delete $rootScope.cart;
    //enregistrement du panier
    sessionService.destroy('cart');
  };

  loginService.isLogged().then(function() {
  });

}]);