'use strict';

angular.module('foyerApp.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$http', '$location', 'loginService', function($rootScope, $scope, $http, $location, loginService) {
  $rootScope.$on(function () {
    $scope.search = '';
    $rootScope.login = '';
  });
  // Logout
  $rootScope.logout = function() {
    loginService.logout();
  };
}])

.controller('ErrorController', function() {

})

.controller('identificationController', ['$rootScope', '$scope', '$http', 'loginService', function($rootScope, $scope, $http, loginService) {
  $scope.login = '';
  $scope.password = '';
  $scope.login = function(login) {
    loginService.login(login, $scope);
  };
}]);