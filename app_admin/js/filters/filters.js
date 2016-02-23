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
})

.filter('unique', function () {
  return function (items, filterOn) {
    if (filterOn === false) {
      return items;
    }
    if ((filterOn || angular.isUndefined(filterOn)) && angular.isArray(items)) {
      var newItems = [];
      var extractValueToCompare = function (item) {
        if (angular.isObject(item) && angular.isString(filterOn)) {
          return item[filterOn];
        } else {
          return item;
        }
      };

      angular.forEach(items, function (item) {
        var valueToCheck, isDuplicate = false;
        for (var i = 0; i < newItems.length; i++) {
          if (angular.equals(extractValueToCompare(newItems[i]), extractValueToCompare(item))) {
            isDuplicate = true;
            break;
          }
        }
        if (!isDuplicate) {
          newItems.push(item);
        }
      });
      items = newItems;
    }
    return items;
  };
});