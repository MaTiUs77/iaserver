    app.controller('ConfigCtrl',function($scope,$rootScope,UserFactory){
    $scope.Plants=[];
    $scope.plantModel=null;
    $scope.zoneModel=null;
    $scope.ipForModify=null;
    $scope.Zones=[];
    $scope.showPlantSelected=false;
    $scope.dropDownDisabled=true;
    $scope.isDisabled=true;
    UserFactory.getSession().then(function(response){
        $rootScope.sessionName = response.data;
        UserFactory.getUserInfo($rootScope.sessionName).then(function(response){
            $rootScope.userInfo = response.data;
            $scope.ipForModify=$rootScope.userInfo.ip;
        });
    });
    UserFactory.getPlant().then(function(responsePlant){
        $scope.Plants = responsePlant.data;
    });
    $scope.plantSelected = function(planta,descripcion){
        $scope.dropDownDisabled=false;
        $scope.plantModel = planta;
        $scope.descPlanta = descripcion;
        $scope.descZona = null;
        if ((planta ==3)||(planta ==5)||(planta ==6)){
            $scope.dropDownDisabled=true;
        }
        $scope.zoneModel=null;
        $scope.showPlantSelected=true;
        UserFactory.getZones($scope.plantModel).then(function(responseZone){
            $scope.Zones = responseZone.data;
        });
    };
    $scope.zoneSelected=function(zona,descripcion){
        $scope.zoneModel = zona;
        $scope.descZona = descripcion;
        $scope.isDisabled=false;
    };
    $scope.updUserInfo = function(){
        UserFactory.updUserInfo($rootScope.userInfo.user,$scope.plantModel,$scope.zoneModel,$scope.ipForModify).then(function(responseUpd){
            if (responseUpd.data == "Exito")
            {
                $scope.messageStyle = "color:#5cb85c;";
                $scope.messageUpd = "Actualizado Correctamente";
                $scope.userInfo.id_planta = $scope.plantModel;
                $scope.userInfo.id_sector = $scope.zoneModel;
                $scope.userInfo.ip = $scope.ipForModify;
            }
            else
            {
                $scope.messageStyle = "color:#d9534f;";
                $scope.messageUpd = "Ocurri� un problema";
            }
        })
    }
});