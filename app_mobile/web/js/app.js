'use strict';

angular.module('foyerApp', [
  'onsen',
  'ngRoute',
  'ngSanitize',
  'foyerApp.directives',
  'foyerApp.filters',
  'foyerApp.services',
  'foyerApp.controllers'
])

.constant('CONFIG', {
  'API_URL': 'http://isenclub.fr/foyer/api/'
})

//gestion des routes
.config(['$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {

  $locationProvider.html5Mode({
    //enabled: true,
    //requireBase: false
  });
  $routeProvider
  .when('/', {
    templateUrl : 'partials/home.html',
    controller  : 'homeController',
    activetab   : 'home'
  })
  .when('/identification', {
    templateUrl : 'partials/identification.html',
    controller  : 'identificationController',
    activetab   : 'identification'
  })
  .when('/product', {
    templateUrl : 'partials/products.html',
    controller  : 'productsController',
    activetab   : 'product'
  })
  .when('/cart', {
    templateUrl : 'partials/cart.html',
    controller  : 'cartController',
    activetab   : 'cart'
  })
  .when('/product/:id_product', {
    templateUrl : 'partials/product.html',
    controller  : 'productController',
    activetab   : 'product'
  })
  .when('/command', {
    templateUrl : 'partials/commands.html',
    controller  : 'commandsController',
    activetab   : 'command'
  })
  .when('/command/:id_command', {
    templateUrl : 'partials/command.html',
    controller  : 'commandController',
    activetab   : 'command'
  })
  .when('/notification', {
    templateUrl : 'partials/notifications.html',
    controller  : 'notificationsController',
    activetab   : 'notification'
  })
  .otherwise({
    redirectTo: '/'
  });

  //ajout de headers des requetes
  $httpProvider.defaults.transformRequest = function(data){
    if (data === undefined) {
      return data;
    }
    return jQuery.param(data);
  };
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
  $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
}])

// Route permissions
.run(['$rootScope', '$location', function($rootScope, $location) {
  //Routes qui n'ont pas besoin d'être connecté
  var routeAllowed = ['/identification'];
  $rootScope.$on('$routeChangeStart', function() {
    if(routeAllowed.indexOf($location.path()) === -1);
  });
}]);
