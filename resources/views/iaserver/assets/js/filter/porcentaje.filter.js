app.filter('porcentaje', ['$window', function ($window) {
    return function (input, decimals, suffix) {
        decimals = angular.isNumber(decimals)? decimals :  3;
        if(suffix!='') {
            suffix = suffix || '%';
        }
        if ($window.isNaN(input)) {
            return '';
        }
        return Math.round(input * Math.pow(10, decimals + 2))/Math.pow(10, decimals) + suffix
    };
}]);
