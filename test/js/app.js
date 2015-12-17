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

  .when('/command/edit/:id_commande', {
    templateUrl : 'pages/command-form.html',
    controller  : 'commandFormController'
  })

  .when('/command/add', {
    templateUrl : 'pages/command-form.html',
    controller  : 'commandFormController'
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
    $scope.loaded = true;
  });
})

.controller('productFormController', function($scope, $http, CONFIG, $routeParams, $document, $location) {
  $scope.product = {};
  $scope.action = 'add';

  if ($routeParams.id_product) {
    $http.get(CONFIG.API_URL+'product/id_product/'+$routeParams.id_product).success(function(data){
      $scope.product = data;
      $scope.action = 'edit';
    });
  }

  // Post du formulaire
  $scope.submitForm = function(item, event) {
      //edit
      if($scope.action == 'edit'){
        $http.put(CONFIG.API_URL+'product/'+$scope.product.id_product, $scope.product).success(function(data) {
          $location.path('#product');
        }).error(function(data) {
          $scope.alert = data;
          $document.scrollTop(0, 250);
        });
      }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'product/', $scope.product).success(function(data) {
        $location.path('#product');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
  };
})

.controller('commandController', function($scope, $http, CONFIG) {
  $http.get(CONFIG.API_URL+'command/').success(function(data){
    $scope.commands = data;
    $scope.loaded = true;
  });
})

.controller('commandFormController', function($scope, $http, CONFIG, $routeParams, $document, $location) {
  $scope.command = {};
  $scope.action = 'add';

  $http.get(CONFIG.API_URL+'user/').success(function(data){
    $scope.users = data;
  });

  if ($routeParams.id_commande) {
    $http.get(CONFIG.API_URL+'command/id_commande/'+$routeParams.id_commande).success(function(data){
      $scope.command = data;
      $scope.action = 'edit';
    });
  }

  // Post du formulaire
  $scope.submitForm = function(item, event) {
      //edit
      if($scope.action == 'edit'){
        $http.put(CONFIG.API_URL+'command/'+$scope.command.id_commande, $scope.command).success(function(data) {
          $location.path('#command');
        }).error(function(data) {
          $scope.alert = data;
          $document.scrollTop(0, 250);
        });
      }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'command/', $scope.command).success(function(data) {
        $location.path('#command');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
  };
});