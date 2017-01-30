app.controller('LabelCtrl',function($scope,InventarioFactory) {
    $scope.label=[];
    $scope.buscar=null;
    $scope.isDisabled=true;
    $scope.editarEtiqueta=false;
    $scope.updLabelStatus=null;
    $scope.buscarEtiqueta = function() {
        $scope.updLabelStatus=null;
        InventarioFactory.getLabelInfo($scope.buscar).then(function (response) {
            if(response.data.length == 0)
            {
                $scope.statusStyle = "color:red;";
                $scope.error = "No existe ninguna etiqueta con ese ID";
                $scope.label.id_etiqueta = null;
                $scope.label.lpn = null;
                $scope.label.partNumber = null;
                $scope.label.descripcion = null;
                $scope.label.planta = null;
                $scope.label.descZona = null;
                $scope.label.responsable = null;
                $scope.label.cantidadContada = null;
                $scope.label.cantidadSegConteo = null;
                $scope.label.cantidadTerConteo = null;
                $scope.label.zona = null;
                $scope.buscar = null;
                $scope.isDisabled = true;
            }else {
                $scope.error = null;
                $scope.label.id_etiqueta = response.data[0]['id_etiqueta'];
                $scope.label.lpn = response.data[0]['lpn'];
                $scope.label.partNumber = response.data[0]['codigo'];
                $scope.label.descripcion = response.data[0]['descripcion'];
                $scope.label.planta = response.data[0]['id_planta'];
                $scope.label.descZona = response.data[0]['descripcionZona'];
                $scope.label.responsable = response.data[0]['id_responsable_imp'];
                $scope.label.cantidadContada = response.data[0]['cant_agregada'];
                $scope.label.cantidadSegConteo = response.data[0]['seg_conteo'];
                $scope.label.cantidadTerConteo = response.data[0]['ter_conteo'];
                if($scope.label.cantidadContada > 0)
                {
                    $scope.pconteoDisable = true;
                    console.log($scope.pconteoDisable);
                }else{
                    $scope.pconteoDisable = false;
                }
                if($scope.label.cantidadSegConteo > 0)
                {
                    $scope.sconteoDisable = true;
                    console.log($scope.sconteoDisable);
                }else{
                    $scope.sconteoDisable = false;
                }
                if($scope.label.cantidadTerConteo > 0)
                {
                    $scope.tconteoDisable = true;
                    console.log($scope.tconteoDisable);
                }else{
                    $scope.tconteoDisable = false;
                }
                $scope.buscar = null;
                $scope.isDisabled = false;
            }
        });
    };
    $scope.enterCheck=function($event){
        var keyCode = $event.wich || $event.keyCode;
        if(keyCode === 13){
            $scope.buscarEtiqueta();
        }
    };
    $scope.editar=function(){
        $scope.updLabelStatus=null;
        $scope.editarEtiqueta=true;
    };
    $scope.reprint=function(){
        InventarioFactory.rePrint($scope.label).then(function(responseRePrint){
            console.log(responseRePrint.data);
            if(responseRePrint.data == 'Exitos')
            {
                $scope.statusStyle = "color:#5cb85c;";
                $scope.updLabelStatus = "Se imprimio correctamente";
            }else{
                $scope.statusStyle = "color:#d9534f;";
                $scope.updLabelStatus = "Ocurrio un error al imprimir"
            }

        });
    };
    $scope.updLabelQty=function() {
        InventarioFactory.updLabel($scope.label).then(function (response) {
            if (response.statusText == "OK")
            {
                $scope.statusStyle = "color:#5cb85c;";
                $scope.updLabelStatus = "Actualizado Correctamente"
                location.reload();
            }
            else
            {
                $scope.statusStyle = "color:#d9534f;";
                $scope.updLabelStatus="Ocurrio un problema al actualizar"
            }
        });
    };
    $scope.togglee = function (){

        InventarioFactory.getUdmFromDB().then(function(responseUdm) {
            $scope.udmList = responseUdm.data;
            console.log($scope.udmList);
        });

        $('#myModal').modal('show');
    }
});