app.controller('usersController',function($scope,$rootScope,UserFactory,$http){
    // Obtener Usuarios de inventario
    UserFactory.getUsers().then(function(response){
        $scope.users = response.data;
    });
    $scope.editUser=[];

    //show modal
    $scope.toggle = function (modalstate, id){
        $scope.modalstate = modalstate;

        UserFactory.getPlants().then(function(response){
            $scope.plants = response.data;
        });
        UserFactory.getSectors().then(function(response){
            $scope.sectors = response.data;
        });
        UserFactory.getPrinters().then(function(response){
           $scope.printers = response.data;
        });
        switch (modalstate)
        {
            case 'add':
                $scope.form_title = "Agregar Usuario";
                $scope.newUser = true;
                $http.get('/iaserver/public/inventario/configurar/usuarios/getUsers/fromIAServer')
                    .success(function(response){
                        $scope.usersFromIAServer = response;
                    });
                break;
            case 'edit':

                $scope.newUser = false;
                $scope.form_title = "Editar Usuario";
                $scope.id = id;
                $http.get('/iaserver/public/inventario/configurar/usuarios/getUsers/'+id+'/edit')
                    .success(function(response){
                        $scope.editUser = response;
                    });
                break;
            default:
                break;
        }
        $('#myModal').modal('show');
    };

    //save new record / update existing record
    $scope.save = function(modalstate, id) {
        var url = '/iaserver/public/inventario/configurar/usuarios/getUsers';
        //append employee id to the URL if the form is in edit mode
        var Mensaje = 'Usuario agregado!!';
        $scope.isRequired = false;
        if (modalstate === 'edit'){
            Mensaje = 'Perfil Actualizado Correctamente';
            url += "/update";
            $scope.isRequired = true;
        }
        console.log($scope.editUser);
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.editUser),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            if (response == 'exito')
                alert(Mensaje);
            else
                alert('Ocurrió un problema al intentar actualizar el registros');
            location.reload();
        }).error(function(response) {
            alert('Ocurrió un problema al intentar actualizar el registros');
        });
    };

    //delete record
    $scope.confirmDelete = function(id) {
        var isConfirmDelete = confirm('Seguro que desea eliminar el Usuario?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: '/iaserver/public/inventario/configurar/usuarios/getUsers/' + id
            }).
            success(function(data) {
                location.reload();
            }).
            error(function(data) {
                alert('Imposible borrar el usuario');
            });
        } else {
            return false;
        }
    };

    $scope.formatUsers = function(id){
        UserFactory.getRoles(id).then(function(response){
            return response.data;
        });
    };

    $scope.getProfileData = function(){
        var userId = $scope.editUser.perfil.user_id;
        UserFactory.getProfileData(userId).then(function(response){
            $scope.profileData = response.data;
            $scope.editUser = {"perfil": {
                user_id:userId,
                nombre: $scope.profileData.nombre,
                apellido: $scope.profileData.apellido
            }};
        });
    };
});