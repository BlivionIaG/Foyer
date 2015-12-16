var App = angular
.module('foyerApp', ['ngRoute'])
.constant('CONFIG', {
  'API_URL': 'http://192.168.1.173/Foyer/api/'
});

App.config(function($routeProvider) {
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

App.controller('mainController', function($scope) {

});

App.controller('productController', function($scope, $http, CONFIG) {
  $http.get(CONFIG.API_URL+'product/').success(function(data){
    $scope.products = data;
  });
});

App.controller('commandController', function($scope, $http, CONFIG) {
  $http.get(CONFIG.API_URL+'command/').success(function(data){
    $scope.commands = data;
  });
});