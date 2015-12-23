'use strict';

angular.module('foyerApp.controllers')
.controller('notificationController',['$scope', '$http', 'CONFIG', function($scope, $http, CONFIG) {
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

    $scope.delete = function(NotificationId){
      $http.delete(CONFIG.API_URL+'notification/'+NotificationId).success(function(data) {
        $scope.closeThisDialog();
      });
    };

}])

.controller('notificationPopupController',['$scope', '$http', 'CONFIG', 'ngDialog','$window', function($scope, $http, CONFIG, ngDialog, $window) {

    $scope.notification = $scope.ngDialogData;
    $scope.notification.method = 2;

    //ngDialog
    $scope.open = function() {
      ngDialog.open({ template: 'notification', controller: 'notificationPopupController', scope:$scope });
    };

    delete $scope.notification.ngDialogId;
    $scope.submitForm = function(){
      $http.post(CONFIG.API_URL+'notification/', $scope.notification).success(function(data) {
        $window.location.reload();
      });
    };

}]);