'use strict';

angular.module('starter.controllers')

.controller('cartController', ['$rootScope', '$scope', '$http', '$ionicPopup', 'CONFIG', function($rootScope, $scope, $http, $ionicPopup, CONFIG) {

	$scope.panier = {};

	$scope.valideCart = function(){
		var error = null;

		//reset de la date
		var date = new Date();
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);
		date.setMilliseconds(0);

		//check des champs
		if($scope.panier.date === undefined || $scope.panier.date < date){

			error = 'Vous devez choisir une date suppérieur ou égale à celle d\'ajourdhui.';
		}else if($scope.panier.periode_debut === undefined || $scope.panier.periode_fin === undefined || $scope.panier.periode_fin <= $scope.panier.periode_debut ){

			error = 'Veuillez revoir vos heures de récupération de commande.';
		}else{

			//formatage des heures
			$scope.panier.periode_debut = addZero($scope.panier.periode_debut.getHours())+'h'+addZero($scope.panier.periode_debut.getMinutes());
			$scope.panier.periode_fin = addZero($scope.panier.periode_fin.getHours())+'h'+addZero($scope.panier.periode_fin.getMinutes());
			$scope.panier.date = $scope.panier.date.getFullYear()+'-'+($scope.panier.date.getMonth() + 1)+'-'+$scope.panier.date.getDate();

			//ajout des donnees
			$scope.panier.product = $rootScope.cart;
			$scope.panier.login = $rootScope.login;
			$scope.panier.state = 1;

			$http.post(CONFIG.API_URL+'command/', $scope.panier).success(function() {
				$ionicPopup.alert({
					title: 'Succès',
					template: 'Votre commande a été envoyée'
				})
				.then(function() {
					$rootScope.deleteAllCart();
					$rootScope.$ionicGoBack();
				});
			}).error(function(data) {
				error = data;
			});

		}
		if(error !== null){
			$ionicPopup.alert({
				title: 'Erreur',
				template: error
			});
		}
	};
}]);