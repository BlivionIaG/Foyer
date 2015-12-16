var scotchApp = angular.module('foyerApp', ['ngRoute']);

scotchApp.config(function($routeProvider) {
  $routeProvider

  .when('/', {
    templateUrl : 'pages/home.html',
    controller  : 'mainController'
  })

  .when('/product', {
    templateUrl : 'pages/product.html',
    controller  : 'productController'
  })

  .when('/command', {
    templateUrl : 'pages/command.html',
    controller  : 'commandController'
  });
});

scotchApp.controller('mainController', function($scope) {

});

scotchApp.controller('productController', function($scope, $http) {
  $http.get('http://192.168.1.173/Foyer/api/product/').success(function(data){
    $scope.products = data;
  });
});

scotchApp.controller('commandController', function($scope, $http) {
  $http.get('http://192.168.1.173/Foyer/api/command/').success(function(data){
    $scope.commands = data;
  });
});