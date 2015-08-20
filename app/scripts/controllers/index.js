/**
 * Created by chris on 10/07/15.
 */
/**
 * Created by chris on 30/06/15.
 */
(function (angular) {
  'use strict';

  var IndexController = function ($scope, $http, $location, Url, flash) {

    $scope.createUrl = function () {

      $http({
        method: 'POST',
        url: '/api/urls.json',
        data: {form: $scope.formData},
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}
      }).success(function (data, status) {

        if (201 !== status) {
          flash.fatal = 'Boo, it done broke it :(.';
          return;
        }

        if (data.id) {
          $location.path(data.id + '/details');
          flash.success = 'Created new shorty URL ' + data.id;
        }
      });
    };

  };

  IndexController.$inject = ['$scope', '$http', '$location', 'Url', 'flash'];

  angular.module('shortyApp').controller('IndexController', IndexController);

})(angular);
