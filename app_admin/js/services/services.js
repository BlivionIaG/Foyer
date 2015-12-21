'use strict';


angular.module('foyerApp.services', [])
.factory('loginService', ['$http', '$location', 'sessionService', 'accesService', 'CONFIG', function($http, $location, sessionService, accesService, CONFIG) {
  return {
    login: function(user, $scope) {
      $http.post(CONFIG.API_URL+'login/', user).success(function(data) {
        console.log(data);
        if(data) {
          sessionService.set('uid', data.status.success);
          $location.path('#command');
        }
        else {
          $scope.loginMessage = 'Erreur identification.';
        }
      });
    },
    lougout: function() {
      sessionService.destroy('uid');
      accesService.destroy('decrypt_allowed');
      $location.path('identification');
    },
    isLogged: function() {
      var $checkSessionServer = $http.get(CONFIG.API_URL+'login/');
      return $checkSessionServer;
    }
  };
}])
.factory('sessionService', ['$http','CONFIG', function($http, CONFIG) {
  return {
    set: function(key, value) {
      return window.sessionStorage.setItem(key, value);
    },
    get: function(key) {
      return window.sessionStorage.getItem(key);
    },
    destroy: function(key) {
      $http.get(CONFIG.API_URL+'logout/');
      return window.sessionStorage.removeItem(key);
    }
  };
}])

.factory('accesService', ['$http', 'CONFIG', function($http, CONFIG) {
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
        var $checkDecryptAllowedServer = $http.get(CONFIG.API_URL+'login/');
        return $checkDecryptAllowedServer;
      }
    };
  }]);