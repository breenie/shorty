(function(angular) {
    'use strict';

    var ShortyController = function($scope, $http, $location, Url, flash) {
        $http.get('/api/urls.json').success(function(data) {
            $scope.urls = data.results;
        });

        //var result = Url.query();
        //
        //$scope.urls = result.then(function(result) {
        //    return result.results;
        //});

        $scope.hostname = $location.host() + (80 == $location.port() ? '' : ':' + $location.port());

        $scope.formData = {};

        $scope.createUrl = function() {

            $http({
                method: 'POST',
                url: '/api/urls.json',
                data: {form: $scope.formData},
                headers : { 'Content-Type': 'application/json', 'Accept': 'application/json' }
            }).success(function (data, status) {

                if (201 != status) {
                    flash.fatal = 'Boo, it done broke it :(.';
                    return;
                }

                if (data.id) {
                    $location.path('/app/' + data.id + '/details');
                    flash.success = 'Created new shizzle';
                }
            });
        };

    };

    ShortyController.$inject = ['$scope', '$http', '$location', 'Url', 'flash'];

    angular.module('shortyApp')
        .controller('ShortyController', ShortyController);


    var ShortyDetailsController = function($scope, $routeParams, $location, Url) {

        Url.get({id: $routeParams.id}, function (data) {
            $scope.details = data;
        });

        $scope.hostname = $location.host() + (80 == $location.port() ? '' : ':' + $location.port());
    };

    angular.module('shortyApp')
        .controller('ShortyDetailsController', ['$scope', '$routeParams', '$location', 'Url', ShortyDetailsController]);

})(angular);
