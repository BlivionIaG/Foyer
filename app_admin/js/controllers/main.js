'use strict';

angular.module('foyerApp.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$http', '$location', 'loginService', 'sessionService', function($rootScope, $scope, $http, $location, loginService, sessionService) {

  $rootScope.$on('$locationChangeStart', function (event, next, current) {
    $scope.search = '';
    $rootScope.user = '';
    var connected  = loginService.isLogged();
    if(connected){
      connected.then(function(msg) {
        if(!msg.data) {
          $rootScope.isLogged = false;
          $rootScope.user = false;
        }
        else {
          $rootScope.isLogged = true;
          $rootScope.user = 'ksidor18';
        }
      });
    }
    else{
      $rootScope.isLogged = false;
      $rootScope.user = false;
    }
  });
  // Logout
  $rootScope.logout = function() {
    loginService.logout();
  };
}])

.controller('ErrorController', ['$rootScope', '$scope', '$http', function($rootScope, $scope, $http) {

}])

.controller('identificationController', ['$rootScope', '$scope', '$http', 'loginService', function($rootScope, $scope, $http, loginService) {
  $scope.login = '';
  $scope.password = '';
  $scope.login = function(user) {
    loginService.login(user, $scope);
  };
}]);