angular.module('urlFilters', []).filter('remove_scheme', function() {
    return function(input) {
        return input.replace(/^(https?|ftp):\/\//, '');
    };
});

//angular.module('stringFilters', []).filter('capitalize', function() {
//    return function(input, arg) {
//        return input.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
//    };
//});