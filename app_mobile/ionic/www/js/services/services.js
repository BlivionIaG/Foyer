angular.module('starter.services', [])

.factory('loginService', ['$http', '$location', 'sessionService', 'CONFIG', '$rootScope', function($http, $location, sessionService, CONFIG, $rootScope) {
	return {
		login: function(user, $ionicPopup) {
			$http.post(CONFIG.API_URL+'cas/', user).success(function(data) {

				sessionService.set('apiKey', data.status.key);
				sessionService.set('username', user.username);

				$rootScope.login = user.username;
				$rootScope.isLogged = true;
				$http.defaults.headers.common['Authorization'] = 'Basic '+data.status.key;
				
				$location.path('/tab/home');
				
			}).error(function(data) {
				$ionicPopup.alert({
					title: 'Erreur',
					template: data.status.error
				});
			});
		},
		logout: function() {
			$http.get(CONFIG.API_URL+'logout/').success(function() {

				sessionService.destroy('apiKey');
				sessionService.destroy('username');
				$rootScope.isLogged = false;
				$rootScope.login = null;

				$location.path('/identification');
			});
		},
		isLogged: function() {
			$rootScope.login = sessionService.get('username');

			if($rootScope.login){
				$rootScope.isLogged = true;
				$http.defaults.headers.common['Authorization'] = 'Basic '+sessionService.get('apiKey');
			}else{
				$location.path('/identification');
				$rootScope.isLogged = false;
			}
		}
	};
}])

.factory('sessionService', function() {
	return {
		set: function(key, value) {
			if(typeof value === 'object') value = JSON.stringify(value);
			return window.sessionStorage.setItem(key, value);
		},
		get: function(key) {
			try{
				return JSON.parse(window.sessionStorage.getItem(key));
			}catch(e){
				return window.sessionStorage.getItem(key);
			}
		},
		destroy: function(key) {
			return window.sessionStorage.removeItem(key);
		}
	};
});