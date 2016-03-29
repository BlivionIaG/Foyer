'use strict';

angular.module('starter.controllers')

.controller('identificationController', ['$rootScope', '$scope', '$ionicPopup', 'loginService', 'CONFIG', function($rootScope, $scope, $ionicPopup, loginService, CONFIG) {

	$scope.user = {};

	$scope.login = function(){
		if($scope.user.username && $scope.user.password){
			loginService.login($scope.user, $ionicPopup);
		}else{
			$ionicPopup.alert({
				title: 'Erreur',
				template: 'Veuillez remplir les champs'
			});
		}
	}
}]);