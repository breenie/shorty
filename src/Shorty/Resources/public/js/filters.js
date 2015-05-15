angular.module('urlFilters', []).filter('remove_scheme', function() {
    return function(input) {
        return input.replace(/^(https?|ftp):\/\//, '');
    };
});