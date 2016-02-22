'use strict';

angular.module('foyerApp.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$http', '$location', 'loginService', 'sessionService', function($rootScope, $scope, $http, $location, loginService, sessionService) {
  $rootScope.$on('$locationChangeStart', function (event, next, current) {
    $scope.search = '';
    $rootScope.login = '';

    var connected  = loginService.isLogged();
    if(connected){
      connected.then(function(msg) {
        if(!msg.data) {
          $rootScope.isLogged = false;
          $rootScope.login = false;
        }
        else {
          $rootScope.isLogged = true;
          $rootScope.login = msg.data.login;
          $rootScope.user = msg.data.user;
          $rootScope.password = msg.data.password;
        }
      });
    }
    else{
      $rootScope.isLogged = false;
      $rootScope.login = false;
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
  $scope.login = function(login) {
    loginService.login(login, $scope);
  };
}]);