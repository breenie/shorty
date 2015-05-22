angular.module('ShortyService', ['ngResource', 'urlFilters']).factory('Url', ['$resource', function ($resource) {
    return $resource('/api/urls/:id.json', {}, {
        query: {
            method: 'GET',
            isArray: false,
            transformResponse: function (data, headers) {

                var results = angular.fromJson(data);
                return results;
            }
    }});
}]);
