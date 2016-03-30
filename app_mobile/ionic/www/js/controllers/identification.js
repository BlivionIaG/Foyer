'use strict';

angular.module('starter.controllers')

.controller('identificationController', ['$scope', '$ionicPopup', 'loginService', function($scope, $ionicPopup, loginService) {

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
	};
}]);