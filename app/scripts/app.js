(function (angular) {
  'use strict';

  angular.module(
    'shortyApp',
    [
      'ngRoute',
      'ngResource',
      'angular-flash.service',
      'angular-flash.flash-alert-directive',
      'tagged.directives.infiniteScroll',
      'shortyApp.services'
    ]
  );

  function config($routeProvider/*, $locationProvider*/) {
    //$locationProvider.html5Mode({enabled: true});
    $routeProvider
      .when('/statistics', {
        templateUrl: 'views/statistics.html',
        controller: 'StatisticsController'
      })
      .when('/:id/details', {
        templateUrl: 'views/details.html',
        controller: 'DetailsController'
      })
      .when('/', {
        templateUrl: 'views/index.html',
        controller: 'IndexController'
      })
      .otherwise({
        templateUrl: 'views/index.html',
        controller: 'IndexController'
      });
  }
  angular.module('shortyApp')
    .config(['$routeProvider', '$locationProvider', config]);

})(angular);

// 'use strict';
//
//var shortyApp = angular.module('shortyApp', [
//    'ngRoute',
//    'ngResource',
//    'shortyApp.services',
//    'shortyControllers'
//]);
//
//shortyApp.config(['$routeProvider', '$locationProvider',
//    function($routeProvider, $locationProvider) {
//        $locationProvider.html5Mode({enabled: true, requireBase: false});
//        $routeProvider.
//            when('/statistics', {
//                templateUrl: '/partials/statistics.html',
//                controller: 'ShortyController'
//            }).
//            when('/:id/details', {
//                templateUrl: '/partials/details.html',
//                controller: 'ShortyDetailsController'
//            }).
//            otherwise({
//                templateUrl: '/partials/index.html'
//            });
//    }]);

