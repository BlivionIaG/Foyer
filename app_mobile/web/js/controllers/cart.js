'use strict';

angular.module('foyerApp.controllers')

.controller('cartController', ['$rootScope', '$scope', '$http', 'CONFIG', 'sessionService', 'loginService', function($rootScope, $scope, $http, CONFIG, sessionService, loginService) {

  $rootScope.title = 'Panier';

  //total du panier
  $scope.getCartTotal = function() {
    var total = 0;
    angular.forEach($scope.cart, function(item, key) {
      total += item.quantity * item.price;
    });
    return total;
  };

  $scope.deleteCartAll = function() {
    $rootScope.cart = new Array();
    //enregistrement du panier
    sessionService.set('cart', JSON.stringify($rootScope.cart));
  };

  loginService.isLogged().then(function() {
  });

}]);