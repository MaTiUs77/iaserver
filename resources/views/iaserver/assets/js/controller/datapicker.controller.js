/**
 * Configuracion base para datapicker
 */
app.controller("datapickerController",["$scope", function ($scope) {
    $scope.open = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.datepickerOpened = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
}]);