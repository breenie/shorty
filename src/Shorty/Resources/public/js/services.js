//angular.module('shortyService', ['ngResource', 'urlFilters']).factory('Url', ['$resource', function ($resource) {
//    return $resource('/api/urls/:id.json', {}, {
//        query: {
//            method: 'GET',
//            isArray: false,
//            transformResponse: function (data, headers) {
//
//                var results = angular.fromJson(data);
//
//                return results;
//            }
//    }});
//}]);

(function (angular) {
    'use strict';

    angular.module('shortyApp.services', ['ngResource']).factory('Url', ['$resource', service]);

    function service($resource) {
        return $resource('/api/urls/:id.json', {}, {
            query: {
                method: 'GET',
                isArray: false/*,
                 transformResponse: function (data, headers) {

                 var results = angular.fromJson(data);

                 return results.results;
                 }*/
            },
            get: {
                method: 'GET',
                isArray: false
            }
        });
    }
})(angular);
