/**
 * Created by chris on 30/06/15.
 */
(function(angular) {
    'use strict';

    var DetailsController = function($scope, $routeParams, $location, Url) {

        Url.get({id: $routeParams.id}, function (data) {
            $scope.details = data;
        });

        $scope.hostname = $location.host() + (80 === $location.port() ? '' : ':' + $location.port());
    };

    angular.module('shortyApp')
        .controller('DetailsController', ['$scope', '$routeParams', '$location', 'Url', DetailsController]);

})(angular);
