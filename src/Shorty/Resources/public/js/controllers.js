(function(angular) {
    'use strict';

    angular.module('shorty')
        .controller('Urls', Urls);

    Urls.$inject = ['$scope', '$http', '$location', 'Url'];

    function Urls($scope, $http, $location, Url) {
        $http.get('/api/urls.json').success(function(data) {
            $scope.urls = data.results;
        });

        //var result = Url.query();
        //
        //$scope.urls = result.then(function(result) {
        //    return result.results;
        //});

        $scope.hostname = $location.host() + (80 == $location.port() ? '' : ':' + $location.port());
    }
})(angular);

var shortyControllers = angular.module('shortyControllers', []);

shortyControllers.controller('ShortyController', ['$scope', '$http', '$location', 'Url',
    function($scope, $http, $location, Url) {
        $http.get('/api/urls.json').success(function(data) {
            $scope.urls = data.results;
        });

        //var result = Url.query();
        //
        //$scope.urls = result.then(function(result) {
        //    return result.results;
        //});

        $scope.hostname = $location.host() + (80 == $location.port() ? '' : ':' + $location.port());
    }]);


shortyControllers.controller('ShortyDetailsController', ['$scope', '$routeParams', '$location', 'Url',
    function($scope, $routeParams, $location, Url) {

        Url.get({id: $routeParams.id}, function (data) {
            $scope.details = data;
        });

        $scope.hostname = $location.host() + (80 == $location.port() ? '' : ':' + $location.port());
    }]);
