'use strict';

angular.module('foyerApp.services', [])
.factory('loginService', ['$http', '$q', '$location', 'sessionService', 'CONFIG', '$rootScope', function($http, $q, $location, sessionService, CONFIG, $rootScope) {
  return {
    login: function(user, $scope) {
      $rootScope.connect = true;
      $http.post(CONFIG.API_URL+'cas/', user).success(function(data) {
        sessionService.set('uid', data.status.success);
        $location.path('home');
      }).error(function(data) {
        $scope.loginMessage = data.status.error;
        $rootScope.connect = false;
      });
    },
    logout: function() {
      $http.get(CONFIG.API_URL+'logout/').success(function() {
        sessionService.destroy('uid');
        $location.path('identification');
        $rootScope.isLogged = false;
        $rootScope.login = false;
      });
    },
    isLogged: function() {
      var defer = $q.defer();
      $http.get(CONFIG.API_URL+'cas/').then(function(response) {
        $rootScope.login = response.data.login;
        $rootScope.isLogged = true;
        $http.defaults.headers.common['Authorization'] = 'Basic '+response.data.key;
        defer.resolve('done');

      }).catch(function() {
        $location.path('identification');
        $rootScope.isLogged = false;
        $rootScope.login = false;
        defer.reject();
      });
      return defer.promise;
    }
  };
}])

.factory('sessionService', function() {
  return {
    set: function(key, value) {
      return window.sessionStorage.setItem(key, value);
    },
    get: function(key) {
      return window.sessionStorage.getItem(key);
    },
    destroy: function(key) {
      return window.sessionStorage.removeItem(key);
    }
  };
});