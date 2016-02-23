'use strict';


angular.module('foyerApp.services', [])
.factory('loginService', ['$http', '$location', 'sessionService', 'CONFIG', '$rootScope', function($http, $location, sessionService, CONFIG, $rootScope) {
  return {
    login: function(user, $scope) {
      $http.post(CONFIG.API_URL+'login/', user).success(function(data) {
        sessionService.set('uid', data.status.success);
        $location.path('home');
      }).error(function(data) {
        $scope.loginMessage = "Erreur d'identification.";
      });
    },
    logout: function() {
      $http.get(CONFIG.API_URL+'logout/').success(function(data) {
        sessionService.destroy('uid');
        $location.path('identification');
        location.reload();
      });
    },
    isLogged: function() {
      $http.get(CONFIG.API_URL+'login/').success(function(data) {
        $rootScope.isLogged = true;
        $rootScope.login = data.login;
        $rootScope.password = data.password;
        $http.defaults.headers.common['Authorization'] = 'Basic '+data.password;
      })
      .error( function (){
        $rootScope.isLogged = false;
        $rootScope.login = false;
        $location.path('identification');
      });
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
}])
.service('fileUpload', ['$http', function ($http) {
  this.uploadFileToUrl = function(file, uploadUrl){
    var fd = new FormData();
    fd.append('file', file);
    $http.post(uploadUrl, fd, {
      transformRequest: angular.identity,
      headers: {'Content-Type': undefined}
    })
    .success(function(data){
//      console.log(data);
    })
    .error(function(data){
//      console.log(data);
    });
  };
}]);
