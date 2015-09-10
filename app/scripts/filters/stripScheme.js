/**
 * Created by chris on 10/09/15.
 */

(function (angular) {
    'use strict';

    angular.module('shortyApp').filter('stripScheme', function() {
        return function(input) {
            input = input || '';
            return input.replace(/.*?:\/\//g, '');
        };
    });
})(angular);
