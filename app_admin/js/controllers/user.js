'use strict';

angular.module('foyerApp.controllers')
.controller('userController',['$scope', '$http', 'CONFIG', '$window', 'loginService', function($scope, $http, CONFIG, $window, loginService) {
  
  loginService.isLogged().then(function() {
    //recuperation des users
    $http.get(CONFIG.API_URL+'user/').success(function(data){
      $scope.logins = data;
      $scope.loaded = true;
    });

    //recuperation des notifications
    $http.get(CONFIG.API_URL+'notification/').success(function(data){
      $scope.notifications = data;
      $scope.loaded = true;
    });
  });

  $scope.delete = function(item){
    //suppression par login
    if(item !== undefined){
      $http.delete(CONFIG.API_URL+'notification/login/'+item).success(function() {
        $window.location.reload();
      });
    }
    //suppression du broadcast
    else{
      $http.delete(CONFIG.API_URL+'notification/method/1').success(function() {
        $window.location.reload();
      });
    }
  };
}])

.controller('notificationPopupController',['$scope', '$http', 'CONFIG', 'ngDialog','$window','$document', function($scope, $http, CONFIG, ngDialog, $window, $document) {

  if($scope.ngDialogData !== undefined){
    $scope.notification = $scope.ngDialogData;
    $scope.notification.method = 2;
  }
  else{
    $scope.notification = $scope.$new;
    $scope.notification.method = 1;
  }

  //ngDialog
  $scope.open = function() {
    ngDialog.open({ template: 'notification', controller: 'notificationPopupController', scope:$scope });
  };

  $scope.submitForm = function(){
    delete $scope.notification.ngDialogId;
    $http.post(CONFIG.API_URL+'notification/', $scope.notification).success(function() {
      $window.location.reload();
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
}]);