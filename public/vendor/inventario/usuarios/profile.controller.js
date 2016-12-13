app.controller('profileController',function($scope,$rootScope,UserFactory,$http){

    $scope.user =[];
    UserFactory.getPlants().then(function(response){
        $scope.plants = response.data;
    });
    UserFactory.getSectors().then(function(response){
        $scope.sectors = response.data;
    });
    UserFactory.getPrinters().then(function(response){
        $scope.printers = response.data;
    });
    UserFactory.getSessionData().then(function(response){
        $scope.user= response.data;
        $http.get('/iaserver/public/inventario/configurar/usuarios/getUsers/'+$scope.user.id+'/edit')
            .success(function(response){
                $scope.editUser = response;
            });
    });

    $scope.save = function(modalstate, id) {
        var url = '/iaserver/public/inventario/configurar/usuarios/getUsers';
            url += "/update";
            $scope.isRequired = true;
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.editUser),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            if (response == 'exito')
                alert('Perfil Actualizado Correctamente');
            else
                alert('Ocurrió un problema al intentar actualizar el registros');
            location.reload();
        }).error(function(response) {
            alert('Ocurrió un problema al intentar actualizar el registros');
        });
    };


});