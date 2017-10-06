/**
 * Created by chris on 30/06/15.
 */
(function (angular) {
    'use strict';

    var StatisticsController = function ($scope, $http) {

        $scope.total = 0;
        $scope.offset = 0;
        $scope.limit = 50;
        $scope.items = [];
        $scope.fetching = false;
        $scope.disabled = false;

        $scope.getMore = function () {

            var offset = $scope.offset;
            $scope.offset += $scope.limit;

            if (0 !== $scope.total && $scope.items.length >= $scope.total) {
                return;
            }

            $scope.fetching = true;

            $http.get('/api/urls', {params: {limit: $scope.limit, offset: offset}}).then(function (response) {
                $scope.fetching = false;
                $scope.items = $scope.items.concat(response.data.results);
                $scope.total = response.data.total;
            });
        };
    };

    StatisticsController.$inject = ['$scope', '$http'];

    angular.module('shortyApp').controller('StatisticsController', StatisticsController);
})(angular);
