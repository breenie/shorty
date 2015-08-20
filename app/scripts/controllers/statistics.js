/**
 * Created by chris on 30/06/15.
 */
(function (angular) {
  'use strict';

  var StatisticsController = function ($scope, $http, $location) {
    $http.get('/api/urls.json').success(function (data) {
      $scope.urls = data.results;
      console.log(data);
      $scope.totalItems = data.total;

    });

    $scope.currentPage = 1;

    $scope.setPage = function (pageNo) {
      $scope.currentPage = pageNo;
    };

    $scope.pageChanged = function() {
      // $log.log('Page changed to: ' + $scope.currentPage);
    };

    $scope.maxSize = 5;
    $scope.bigTotalItems = 175;
    $scope.bigCurrentPage = 1;
    //var result = Url.query();
    //
    //$scope.urls = result.then(function(result) {
    //    return result.results;
    //});

    $scope.hostname = $location.host() + (80 === $location.port() ? '' : ':' + $location.port());
  };

  StatisticsController.$inject = ['$scope', '$http', '$location'];

  angular.module('shortyApp')
    .controller('StatisticsController', StatisticsController);

  angular.module('shortyApp').controller('TodoController', function ($scope) {
    $scope.filteredTodos = [];
    $scope.currentPage = 1;
    $scope.numPerPage = 10;
    $scope.maxSize = 5;

    $scope.makeTodos = function() {
      $scope.todos = [];
      for (var i=1;i<=1000;i++) {
        $scope.todos.push({ text:'todo '+i, done:false});
      }
    };
    $scope.makeTodos();

    $scope.$watch('currentPage + numPerPage', function() {
      var begin = (($scope.currentPage - 1) * $scope.numPerPage), end = begin + $scope.numPerPage;

      $scope.filteredTodos = $scope.todos.slice(begin, end);
    });
  });

})(angular);
