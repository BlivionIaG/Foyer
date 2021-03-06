'use strict';

angular.module('foyerApp', [
  'ngRoute',
  'ngDialog',
  'ngSanitize',
  'ui.bootstrap',
  'angular.morris-chart',
  'checklist-model',
  'foyerApp.directives',
  'foyerApp.filters',
  'foyerApp.services',
  'foyerApp.controllers'
])

.constant('CONFIG', {
  'API_URL': 'http://foyer.api.isenclub.fr/'
})

//gestion des routes
.config(['$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {

  $locationProvider.html5Mode({
    enabled: true,
    requireBase: false
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
  .when('/command/edit/:id_command', {
    templateUrl : 'partials/command-form.html',
    controller  : 'commandFormController'
  })
  .when('/command/add', {
    templateUrl : 'partials/command-form.html',
    controller  : 'commandFormController'
  })
  .when('/parametre', {
    templateUrl : 'partials/parametre.html',
    controller  : 'parametreController'
  })
  .when('/user', {
    templateUrl : 'partials/user.html',
    controller  : 'userController'
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
}]);
