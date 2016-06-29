app.controller('requestController', function ($scope, $http, API_URL)
{
    $http.get(API_URL)
        .success(function(response){
            $scope.CogiscanPedidos = response;
        });
    $scope.toggle = function(modalstate,item_code){
        $scope.modalstate = modalstate;

        switch (modalstate){
            case 'add':
                $scope.form_title = "add new partnumber";
                break;
            case 'edit':
                $scope.form_title = "pedir de nuevo";
                $scope.item_code = item_code;
                $http.get(API_URL + '/pedir/' + item_code)
                    .success(function(response){
                        console.log(response);
                        $scope.CogiscanPedidos= response;
                    });
                break;
            default:
                break;
        }
        console.log(item_code);
        $('#myModal').modal('show');
    }
    $scope.save = function(modalstate, item_code){

        var url = API_URL + "/pedir";
        if (modalstate === 'edit'){
            url += "/" + item_code;
        }
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.XXE_WMS_COGISCAN_PEDIDOS),
            headers: {'Content-type':'application/x-www-form-urlencoded'}
        }).success(function(response){
            console.log(response);
            //location.reload();
        }).error(function(response){
            console.log(response);
            alert('algo salio como el orto!!!!!');
        });
    }

});