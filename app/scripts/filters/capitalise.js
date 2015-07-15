(function (angular) {
  'use strict';

  angular.module('shortyApp').filter('capitalise', function() {
    return function(input) {
      input = input || '';
      //return input.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
      return input.charAt(0).toUpperCase() + input.substr(1).toLowerCase();
    };
  });
})(angular);
