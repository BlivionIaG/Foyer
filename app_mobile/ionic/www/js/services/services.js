angular.module('starter.services', [])

.factory('sessionService', function() {
	return {
		set: function(key, value) {
			if(typeof value === 'object') value = JSON.stringify(value);
			return window.sessionStorage.setItem(key, value);
		},
		get: function(key) {
			try{
			  	return  JSON.parse(window.sessionStorage.getItem(key));
			}catch(e){
				return window.sessionStorage.getItem(key);
			}
		},
		destroy: function(key) {
			return window.sessionStorage.removeItem(key);
		}
	};
});