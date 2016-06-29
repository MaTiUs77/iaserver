app.controller("ipcController",function($scope,$rootScope,$http,$interval,$q,$timeout,IaCore)
{
    $scope.profile_search_path = '';

    $scope.autocompleteSearch = [];

    $scope.dateChanged = function(d){
        if(d)
        {
            var fecha = moment(d).add(2,'year').format('MM/YYYY');
            $scope.fecha_caducidad = fecha;
        } else {
            $scope.fecha_caducidad = '';
        }
    };

    $scope.searchPerfil = function() {
        IaCore.http({
            url: $scope.profile_search_path,
            method: 'POST',
            data: {
                json: 1,
                search: $scope.profile_search
            }
        }).then(function (data)
        {
            $scope.autocompleteSearch = [];

            if(data.error)
            {
                alert(data.error);
            } else
            {
                angular.forEach(data, function(item) {
                    $scope.autocompleteSearch.push(item);
                });

                $scope.searchPerfilFlag=true;
            }
        }, function (error)
        {
            alert(error.message);
        });
    };
});
