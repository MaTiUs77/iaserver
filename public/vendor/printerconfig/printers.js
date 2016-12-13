app.controller('printerController',function($scope,$http, API_URL){
    //obtengo toda la lista de impresoras
    $http.get(API_URL + "impresoras")
        .success(function(response){
            $scope.printers = response;
        });
    $http.get(API_URL + "impresoras/tipo")
        .success(function(response)
        {
            $scope.printersType = response;
        });
    //show modal
    $scope.toggle = function (modalstate, id){
        $scope.modalstate = modalstate;

        switch (modalstate)
        {
            case 'add':
                $scope.form_title = "Agregar nueva impresora";
                break;
            case 'edit':

                $scope.form_title = "Editar";
                $scope.id = id;
                $http.get(API_URL + "impresoras/" +id)
                    .success(function(response){
                        console.log(response);
                        //$scope.printer_config = response;
                        //$scope.printer_address = $scope.printer_config[0]["printer_address"];
                        //
                        //console.log($scope.printer_address);
                    });
                break;
            default:
                break;
        }
        console.log(id);
        $('#myModal').modal('show');
    }

    //save new record / update existing record
    $scope.save = function(modalstate, id) {
        var url = API_URL + "impresoras";

        //append employee id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }

        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.printer),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            //console.log(response);
            location.reload();
        }).error(function(response) {
            //console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    //delete record
    $scope.confirmDelete = function(id) {
        var isConfirmDelete = confirm('Seguro que desea eliminar la impresora?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: API_URL + 'impresoras/' + id
            }).
            success(function(data) {
                console.log(data);
                location.reload();
            }).
            error(function(data) {
                console.log(data);
                alert('Imposible borrar la impresora');
            });
        } else {
            return false;
        }
    }

});