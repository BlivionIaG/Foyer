'use strict';


angular.module('foyerApp.services', [])
.factory('loginService', ['$http', '$location', 'sessionService', 'CONFIG', function($http, $location, sessionService, CONFIG) {
  return {
    login: function(user, $scope) {
      $http.post(CONFIG.API_URL+'login/', user).success(function(data) {
        sessionService.set('uid', data.status.success);
        $location.path('#home');
      }).error(function(data) {
        $scope.loginMessage = "Erreur d'identification.";
      });
    },
    logout: function() {
      $http.get(CONFIG.API_URL+'logout/').success(function(data) {
        sessionService.destroy('uid');
        $location.path('#identification');
      });
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
      return window.sessionStorage.removeItem(key);
    }
  };
}]);