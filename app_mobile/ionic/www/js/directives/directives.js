'use strict';

function SimplePubSub() {
	var events = {};
	return {
		on: function(names, handler) {
			names.split(' ').forEach(function(name) {
				if (!events[name]) {
					events[name] = [];
				}
				events[name].push(handler);
			});
			return this;
		},
		trigger: function(name, args) {
			angular.forEach(events[name], function(handler) {
				handler.call(null, args);
			});
			return this;
		}
	};
}

function addZero(i){
	if(i < 10){
		i = "0" + i;
	}
	return i;
}

angular.module('starter.directives', [])
.directive('onFinishRender', function ($timeout) {
	return {
		restrict: 'A',
		link: function (scope, element) {
			if (scope.$last === true) {
				$timeout(function () {
					scope.$emit('ngRepeatFinished');
				});
			}
		}
	}
});
