'use strict';

angular.module('foyerApp.filters', [])

// Converts the default MySQL datetime into the right format
.filter('dateToISO', function() {
  return function(badTime) {
    var goodTime = badTime.replace(/(.+) (.+)/, "$1T$2+0100");
    return goodTime;
  };
})

.filter('nl2br', function() {
  return function(text) {
    return text?text.replace(/\n/g, '<br/>'):'';
  };
});