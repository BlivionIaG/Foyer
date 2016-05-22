'use strict';

angular.module('starter.controllers')

.controller('cartController', ['$rootScope', '$scope', '$http', '$ionicPopup', 'CONFIG', 'outils', function($rootScope, $scope, $http, $ionicPopup, CONFIG, outils) {

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
		if($scope.panier.date_picker === undefined || $scope.panier.date_picker < date){

			error = 'Vous devez choisir une date suppérieur ou égale à celle d\'ajourdhui.';
		}else if($scope.panier.periode_debut_picker === undefined || $scope.panier.periode_fin_picker === undefined || $scope.panier.periode_fin_picker <= $scope.panier.periode_debut_picker ){

			error = 'Veuillez revoir vos heures de récupération de commande.';
		}else{

			//formatage des heures
			$scope.panier.periode_debut = outils.addZero($scope.panier.periode_debut_picker.getHours())+'h'+outils.addZero($scope.panier.periode_debut_picker.getMinutes());
			$scope.panier.periode_fin = outils.addZero($scope.panier.periode_fin_picker.getHours())+'h'+outils.addZero($scope.panier.periode_fin_picker.getMinutes());
			$scope.panier.date = $scope.panier.date_picker.getFullYear()+'-'+outils.addZero($scope.panier.date_picker.getMonth() + 1)+'-'+outils.addZero($scope.panier.date_picker.getDate());

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