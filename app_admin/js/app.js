'use strict';

angular.module('foyerApp', [
  'ngRoute',
  'ngDialog',
  'angular.morris-chart',
  'foyerApp.directives',
  'foyerApp.filters',
  'foyerApp.services',
  'foyerApp.controllers'
  ])

.constant('CONFIG', {
  'API_URL': 'http://p4ul.tk/Foyer/api/'
})

//gestion des routes
.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  $locationProvider.html5Mode({
    //enabled: true,
    //requireBase: false
  });
  $routeProvider

  .when('/', {
    templateUrl : 'partials/home.html',
    controller  : 'homeController'
  })

  .when('/identification', {
    templateUrl : 'partials/identification.html',
    controller  : 'identificationController'
  })

  .when('/product', {
    templateUrl : 'partials/product.html',
    controller  : 'productController'
  })

  .when('/product/edit/:id_product', {
    templateUrl : 'partials/product-form.html',
    controller  : 'productFormController'
  })

  .when('/product/add', {
    templateUrl : 'partials/product-form.html',
    controller  : 'productFormController'
  })

  .when('/command', {
    templateUrl : 'partials/command.html',
    controller  : 'commandController'
  })

  .when('/command/edit/:id_commande', {
    templateUrl : 'partials/command-form.html',
    controller  : 'commandFormController'
  })

  .when('/command/add', {
    templateUrl : 'partials/command-form.html',
    controller  : 'commandFormController'
  })

  .when('/notification', {
    templateUrl : 'partials/notification.html',
    controller  : 'notificationController'
  })

  .otherwise({
    redirectTo: '/'
  });
}])
//ajout de headers des requetes
.config(['$httpProvider', function($httpProvider) {
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
.run(function($rootScope, $location, loginService) {
  var routeAllowed = ['/identification']; // Route that not required login
  $rootScope.$on('$routeChangeStart', function() {
    if(routeAllowed.indexOf($location.path()) == -1) {
      var connected  = loginService.isLogged();
      if(connected)
        connected.error( function (){
          $location.path('identification');
      });
    }
  });
});
