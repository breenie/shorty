/**
 * Created by chris on 30/06/15.
 */
(function (angular) {
  'use strict';

  var StatisticsController = function ($scope, $http, $location) {

    $scope.total = 0;
    $scope.offset = 0;
    $scope.limit = 50;
    $scope.items = [];
    $scope.fetching = false;
    $scope.disabled = false;

    $scope.getMore = function () {

      var offset = $scope.offset;
      $scope.offset += $scope.limit;
      $scope.fetching = true;

      $http.get('/api/urls.json', {params: {limit: $scope.limit, offset: offset}}).then(function (response) {
        $scope.fetching = false;

        //if (response.data.length) {
          //console.log($scope.offset);
          $scope.items = $scope.items.concat(response.data.results);
          //console.log(response.data.request);
          $scope.total = response.data.total;
        //} else {
        //  $scope.disabled = true;
        //}
      });
    };

    $scope.hostname = $location.host() + (80 === $location.port() ? '' : ':' + $location.port());
  };

  StatisticsController.$inject = ['$scope', '$http', '$location'];

  angular.module('shortyApp')
    .controller('StatisticsController', StatisticsController);

})(angular);
