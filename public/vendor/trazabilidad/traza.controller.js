app.controller("trazaController",function($scope,$rootScope,$http,$interval,$q,IaCore)
{
    $scope.openModal = function(route, title, type) {
        IaCore.modal({
            scope: $scope,
            route:route,
            title: title,
            type: type,
            ignoreloadingbar: false
        });
    }
});
