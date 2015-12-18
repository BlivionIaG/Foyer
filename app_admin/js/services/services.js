'use strict';

/* Services */

angular.module('foyerApp.services', [])
  .factory('loginService', ['$http', '$location', 'sessionService', 'accesService', function($http, $location, sessionService, accesService) {
    return {
      login: function(user, $scope) {
        $http.post('/data/session_start.php', user).success(function(data) {
          if(data) {
            sessionService.set('uid', data);
            $location.path('/');
          }
          else {
            $scope.loginMessage = 'Erreur identification.';
          }
        });
      },
      lougout: function() {
        sessionService.destroy('uid');
        accesService.destroy('decrypt_allowed');
        $location.path('/identification');
      },
      isLogged: function() {
        var $checkSessionServer = $http.post('/data/session_check.php');
        return $checkSessionServer;
      }
    };
  }])
  .factory('sessionService', ['$http', function($http) {
    return {
      set: function(key, value) {
        return window.sessionStorage.setItem(key, value);
      },
      get: function(key) {
        return window.sessionStorage.getItem(key);
      },
      destroy: function(key) {
        $http.post('/data/session_destroy.php');
        return window.sessionStorage.removeItem(key);
      }
    };
  }])
  .factory('accesService', ['$http', function($http) {
    return {
      set: function(key, value) {
        return window.sessionStorage.setItem(key, value);
      },
      get: function(key) {
        return window.sessionStorage.getItem(key);
      },
      destroy: function(key) {
        return window.sessionStorage.removeItem(key);
      },
      isAllowed: function() {
        var $checkDecryptAllowedServer = $http.post('/data/decrypt_allowed.php');
        return $checkDecryptAllowedServer;
      }
    };
  }]);