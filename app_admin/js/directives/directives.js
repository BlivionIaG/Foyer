'use strict';

angular.module('foyerApp.directives', [])
//ajout de la directive ng-confirm-click
.directive('ngConfirmClick', [
  function(){
    return {
      priority: -1,
      restrict: 'A',
      link: function(scope, element, attrs){
        element.bind('click', function(e){
          var message = attrs.ngConfirmClick;
          if(message && !window.confirm(message)){
            e.stopImmediatePropagation();
            e.preventDefault();
          }
        });
      }
    };
  }
]);