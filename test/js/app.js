var App = angular

.module('foyerApp', ['ngRoute'])

.constant('CONFIG', {
  'API_URL': 'http://192.168.1.173/Foyer/api/'
})

.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  //$locationProvider.html5Mode(true);
  $routeProvider

  .when('/', {
    templateUrl : 'pages/home.html',
    controller  : 'mainController'
  })

  .when('/product', {
    templateUrl : 'pages/product.html',
    controller  : 'productController'
  })

  .when('/product/edit/:id_product', {
    templateUrl : 'pages/product-form.html',
    controller  : 'productFormController'
  })

  .when('/product/add', {
    templateUrl : 'pages/product-form.html',
    controller  : 'productFormController'
  })

  .when('/command', {
    templateUrl : 'pages/command.html',
    controller  : 'commandController'
  })

  .otherwise({
    redirectTo: '/404'
  });
}])

.config(['$httpProvider', function($httpProvider) {
  $httpProvider.defaults.transformRequest = function(data){
    if (data === undefined) {
      return data;
    }
    return jQuery.param(data);
  };
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
}])

.controller('mainController', function($scope) {

})

.controller('productController', function($scope, $http, CONFIG) {
  $http.get(CONFIG.API_URL+'product/').success(function(data){
    $scope.products = data;
  });
})

.controller('productFormController', function($scope, $http, CONFIG, $routeParams) {
  $scope.product = {};
  $scope.product.action = 'add';

  if ($routeParams.id_product) {
    $http.get(CONFIG.API_URL+'product/id_product/'+$routeParams.id_product).success(function(data){
      $scope.product = data;
      $scope.product.action = 'edit';
    });
  }
})

.controller('commandController', function($scope, $http, CONFIG) {
  $http.get(CONFIG.API_URL+'command/').success(function(data){
    $scope.commands = data;
  });
});