app.controller("p2iController",function($scope,$rootScope,$http,$interval,$q,$timeout,IaCore)
{
    $scope.showCreateForm = false;
    $scope.last_monomero = '';
    $scope.last_monomero_loading = false;
    $scope.route = '';

    $scope.autoCamara = function(camara)
    {
        $scope.camara = camara;
        $scope.onSelectCamara();
    };

    $scope.onSelectCamara = function() {
        if($scope.camara) {
            $scope.lastMonomero();
        } else {
            $scope.showCreateForm = false;
        }
    };

    $scope.getLastMonomero = function(route) {
        var timeout = 10;
        var result = $q.defer();
        IaCore.http({
            url: route,
            method: 'GET',
            timeout: timeout
        }).result.promise.then(function (data)
        {
            result.resolve(data);
        }, function (error)
        {
            result.reject(error);
        });
        return result.promise;
    };

    $scope.lastMonomero = function()
    {
        $scope.last_monomero = '';
        $scope.last_monomero_loading = true;

        $scope.getLastMonomero($scope.route+'/last_monomero/'+$scope.camara+'?json').then(function(data)
        {
            $scope.last_monomero_loading = false;
            $scope.showCreateForm = true;
            $scope.last_monomero = data.monomero;
            if(data.monomero)
            {
                $scope.monomero = $scope.last_monomero;
                $scope.monomero_start = data.monomero_start;
            } else {
                $scope.monomero = '';
                $scope.monomero_start = '';
            };
        });
    };

    $scope.changeMonomero = function()
    {
        $scope.last_monomero = '';
        $scope.monomero = '';
        $scope.monomero_start = '';
        $timeout(function() {
            $('input.focus').focus();
        });
    }
});
