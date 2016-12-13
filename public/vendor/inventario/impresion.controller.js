app.controller('PrintCtrl',["$scope","InventarioFactory",function($scope,InventarioFactory){
    $scope.checked=true;
    $scope.hibrido = 'HIBRIDO';
    $scope.notFound=false;
    $scope.ShowModal = false;
    $scope.partNumber ="";
    $scope.isDisabledPN = false;
    $scope.isDisabledQty = false;
    $scope.material=[];
    $scope.qty=null;
    $scope.udm = null;
    $scope.RBT = 1;
    $scope.printDisabled=true;
    $scope.userInfo=[];
    $scope.material = [];

    $scope.showModal=function(){
        $scope.ShowModal = true;
        $scope.notFound=false;
        $scope.udmList = $scope.udmFromDB;
        InventarioFactory.getUdmFromDB().then(function(responseUdm) {
            $scope.udmList = responseUdm.data;
        });
        $('#myModal').modal('show');
    };
    $scope.selectedRtb=function(opcion){
        if (opcion==1){
            $scope.RBT = 1;
            $scope.notFound=false;
            $scope.partNumber="";
            $scope.qty="";
            $scope.isDisabledPN=false;
            $scope.isDisabledQty=false;
            $scope.printDisabled=true;
        }
        else{
            $scope.RBT = 2;
            $scope.notFound=false;
            $scope.partNumber = "HIBRIDO";
            $scope.qty=0;
            $scope.isDisabledPN=true;
            $scope.isDisabledQty = true;
            $scope.printDisabled=false;
        }
    };
    $scope.focusOut=function(event){
        ruta = event.target.attributes.ruta.value +"/"+ $scope.partNumber;
        $scope.material=[];
        if ($scope.partNumber!="") {
            InventarioFactory.getPartNumberInfo(ruta).then(function (responsePN) {
                if (responsePN.data != "") {
                    $scope.material.PN = responsePN.data[0]["codigo"];
                    $scope.material.desPartNumber = responsePN.data[0]["descripcion"];
                    $scope.material.udm = responsePN.data[0]["unidad_m{dida"];
                }
                else {
                    $scope.notFound = true;
                }
            });
        }
    };
    $scope.focusIn=function(){
        $scope.notFound=false;
        $scope.printMessage="";
    };
    $scope.toPrint=function(){

        InventarioFactory.getSession().then(function(response){
            $scope.sessionName = response.data;
            console.log($scope.sessionName);
            InventarioFactory.getUserInfo($scope.sessionName).then(function(response){
                $scope.userInfo.user = response.data.user;
                $scope.userInfo.ip = response.data.ip;
                $scope.userInfo.zona = response.data.id_sector;
                $scope.userInfo.planta = response.data.id_planta;
                InventarioFactory.getPrinterType($scope.userInfo.ip).then(function(responseIp){
                    $scope.userInfo.printerType= responseIp.data.printerType;
                    if (($scope.material.desPartNumber==null)||($scope.material.desPartNumber=="")){
                        $scope.material.desPartNumber = " ";
                        $scope.material.udm="";
                        $scope.material.partNumber="HIBRIDO";
                        $scope.material.qty=0;
                    }

                    InventarioFactory.insertPrintedLabel($scope.partNumber,$scope.qty,$scope.userInfo.zona,$scope.userInfo.user,$scope.userInfo.planta).then(function(responseInsert){
                        if (responseInsert.data=="Exito")
                        {
                            InventarioFactory.getLastId().then(function(responseId){
                                $scope.material.lastId = responseId.data;
                                InventarioFactory.print($scope.partNumber,$scope.qty,$scope.RBT,$scope.material.desPartNumber,$scope.material.lastId,$scope.material.udm,$scope.userInfo.planta,$scope.userInfo.zona,$scope.userInfo.ip,$scope.userInfo.printerType).then(function(responseFromPrint) {
                                    $scope.printMessage = "Exito";
                                    $scope.printMessageStyle = "color:#5cb85c;";
                                    $scope.RBT=1;
                                    $scope.checked=true;
                                    $scope.isDisabledPN=false;
                                    $scope.isDisabledQty=false;
                                    $scope.partNumber="";
                                    $scope.qty="";
                                    $scope.material.desPartNumber="";
                                    $scope.material.udm="";
                                    $scope.printDisabled=true;
                                });

                            })

                        }
                        else{
                            $scope.printMessage = "Error";
                            $scope.printMessageStyle = "color:#d9534f;";
                            console.log(responseInsert.data);
                        }
                    });
                });
            });
        });
    };
    $scope.validateQty = function(){
        if ($scope.qty > 0)
        {
            $scope.printDisabled=false;
        }
        else
        {
            $scope.printDisabled=true;
        }
    };
    $scope.toggle = function (){

        InventarioFactory.getUdmFromDB().then(function(responseUdm) {

            $scope.udmList = responseUdm.data;
            console.log($scope.udmList);
        });

        $('#myModal').modal('show');
    }
}]);
