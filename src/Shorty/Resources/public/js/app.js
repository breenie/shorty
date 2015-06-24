(function (angular) {
    'use strict';

    angular.module(
        'shortyApp',
        [
            'ngRoute',
            'ngResource',
            'angular-flash.service',
            'angular-flash.flash-alert-directive',
            'shortyApp.services'
        ]
    );

    angular.module('shortyApp')
        .config(['$routeProvider', '$locationProvider', config]);

    function config($routeProvider, $locationProvider) {
        $locationProvider.html5Mode({enabled: true});
        $routeProvider
            .when('/app/statistics', {
                templateUrl: 'partials/statistics.html',
                controller: 'ShortyController'
            })
            .when('/app/:id/details', {
                templateUrl: 'partials/details.html',
                controller: 'ShortyDetailsController'
            })
            .when('/app', {
                templateUrl: 'partials/index.html',
                controller: 'ShortyController'
            })
            .otherwise({
                redirectTo: '/app'
                //templateUrl: '/partials/index.html',
                //controller: 'ShortyController'
            });
    }
})(angular);

////'use strict';
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