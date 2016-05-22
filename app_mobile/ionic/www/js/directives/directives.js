'use strict';

angular.module('starter.directives', [])

.directive('onFinishRender', function ($timeout) {
	return {
		restrict: 'A',
		link: function (scope) {
			if (scope.$last === true) {
				$timeout(function () {
					scope.$emit('ngRepeatFinished');
				});
			}
		}
	};
});