'use strict';


angular.module('foyerApp.services', [])
.factory('loginService', ['$http', '$q', '$location', 'sessionService', 'CONFIG', '$rootScope', function($http, $q, $location, sessionService, CONFIG, $rootScope) {
  return {
    login: function(user, $scope) {
      $http.post(CONFIG.API_URL+'login/', user).success(function(data) {
	sessionService.set('uid', data.status.success);
        sessionService.set('login', data.status.login);
        sessionService.set('key', data.status.key);
        $location.path('home');
      }).error(function() {
        $scope.loginMessage = "Erreur d'identification.";
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
      /*$http.get(CONFIG.API_URL+'login/').then(function(response) {
	$rootScope.login = response.data.login;
        $rootScope.isLogged = true;
        $http.defaults.headers.common['Authorization'] = 'Basic '+response.data.key;
        defer.resolve('done');
      }).catch(function() {
        $location.path('identification');
        $rootScope.isLogged = false;
        $rootScope.login = false;
        defer.reject();
      });*/
      
      $rootScope.login = sessionService.get('login');
      $rootScope.isLogged = true;
      $http.defaults.headers.common['Authorization'] = 'Basic '+sessionService.get('key');

      defer.resolve('done');
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
})

.service('fileUpload', ['$http', function ($http) {
  this.uploadFileToUrl = function(file, uploadUrl){
    var fd = new FormData();
    fd.append('file', file);
    return $http.post(uploadUrl, fd, {
      transformRequest: angular.identity,
      headers: {'Content-Type': undefined}
    });
  };
}]);
