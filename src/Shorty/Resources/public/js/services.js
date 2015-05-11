angular.module('ShortyService', ['ngResource', 'urlFilters']).factory('Url', ['$resource', function ($resource) {
    return $resource('/api/urls/:id');
}]);
