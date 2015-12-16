  var scotchApp = angular.module('foyerApp', ['ngRoute']);

  scotchApp.config(function($routeProvider) {
    console.log($routeProvider);
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
    $scope.message = 'Everyone come and see how good I look!';
  });

  scotchApp.controller('productController', function($scope, $http) {
    $http.get('http://192.168.1.173/Foyer/api/product/').success(function(response){
      $scope.products = response;
    });
  });

  scotchApp.controller('commandController', function($scope) {
    $scope.message = 'Contact us! JK. This is just a demo.';
  });