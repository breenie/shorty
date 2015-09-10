/**
 * Created by chris on 30/06/15.
 */
(function(angular) {
    'use strict';

    var DetailsController = function($scope, $routeParams, Url) {

        Url.get({id: $routeParams.id}, function (data) {
            $scope.details = data;
        });
    };

    angular.module('shortyApp')
        .controller('DetailsController', ['$scope', '$routeParams', 'Url', DetailsController]);

})(angular);
