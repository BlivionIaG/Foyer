'use strict';

angular.module('foyerApp.controllers', [])

.controller('mainController', ['$rootScope', '$scope', '$http', '$location', 'loginService', 'sessionService', function($rootScope, $scope, $http, $location, loginService, sessionService) {

  $rootScope.$on('$locationChangeStart', function (event, next, current) {
    $scope.search = '';
    var connected  = loginService.isLogged();
    if(connected)
      connected.then(function(msg) {
        if(!msg.data) {
          $rootScope.isLogged = false;
          $rootScope.user = false;
        }
        else {
          $rootScope.isLogged = true;
        }
      });
    else{
      $rootScope.isLogged = false;
      $rootScope.user = false;
    }
  });
    // Logout
    $rootScope.logout = function() {
      loginService.lougout();
    };
    // Active menu
    $rootScope.isActive = function (viewLocation) {
      var explodeURL = $location.path().slice(1).split('/');
      return explodeURL.indexOf(viewLocation) === 0;
    };
    $rootScope.showAppSwitcher = ($location.absUrl().indexOf('.local') > 0 ? true : false);
  }])

.controller('ErrorCtrl', ['$rootScope', '$scope', '$http', function($rootScope, $scope, $http) {

}])

.controller('identificationController', ['$rootScope', '$scope', '$http', 'loginService', function($rootScope, $scope, $http, loginService) {
  $scope.login = function(user) {
    loginService.login(user, $scope);
  };
}]);