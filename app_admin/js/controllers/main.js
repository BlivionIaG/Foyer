'use strict';

angular.module('foyerApp.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$http', 'loginService', function($rootScope, $scope, $http, loginService) {
  $rootScope.$on(function () {
    $rootScope.login = '';
  });
  // Logout
  $rootScope.logout = function() {
    loginService.logout();
  };
}])

.controller('ErrorController', function() {})

.controller('identificationController', ['$rootScope', '$scope', '$http', 'loginService', function($rootScope, $scope, $http, loginService) {
  $scope.login = '';
  $scope.password = '';
  $scope.login = function(login) {
    loginService.login(login, $scope);
  };
}]);
