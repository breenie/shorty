angular.module('ShortyService', ['ngResource']).factory('Url', ['$resource', function ($resource) {
    return $resource('/api/urls/:id');
}]);