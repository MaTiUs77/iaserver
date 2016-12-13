app.controller("prodController",
    ["$scope","$rootScope","$http","$timeout","$interval", "IaCore", "Aoi", "Stocker", "Panel", "toasty", "cfpLoadingBar",
    function($scope,$rootScope,$http,$timeout,$interval, IaCore, Aoi, Stocker, Panel, toasty, cfpLoadingBar) {
    $rootScope.configprod = {
        aoibarcode : IaCore.storage({name:'aoibarcode'}),
        socketio: ':8080'
    };

    $rootScope.aoiService = {};
    $rootScope.stockerService = {};
    $rootScope.userService = {};

    $rootScope.printError = function(title,result,modal)
    {
        if(result.error!=undefined) { result = result.error; }

        if(result) {
            switch (modal) {
                case 'modal':
                    IaCore.modalError($scope, result);
                    break;
                default:
                    toasty.error({
                        title: title,
                        msg: result,
                        timeout: 5000
                    });
                    break;
            }
        }
    };

    var socket = io.connect($rootScope.configprod.socketio);

    socket.on('connect_error', function(){
        toasty.error({
            title: "Produccion",
            msg: "Error de conexion, servidor caido",
            timeout: 5000
        });
        $scope.$apply();
    });

    socket.on('disconnect', function () {
        toasty.warning({
            title: "Produccion",
            msg: "Conexion finalizada"
        });

        $scope.$apply();
    });

    socket.on('connect', function(){
        socket.emit('produccion', $rootScope.configprod.aoibarcode);

        toasty.success({
            title: "Produccion",
            msg: "Descargando informacion"
        });

        $scope.$apply();
    });

    socket.on('waitForGetProduction', function () {
        cfpLoadingBar.start();
        $scope.$apply();
    });

    Stocker.nodeInit(socket);

    socket.on('getProduccionResponse', function (data) {
        $rootScope.aoiService = data;

        if(data.produccion.inspector!= undefined)
        {
            $rootScope.userService = data.produccion.inspector;
        } else
        {
            $rootScope.userService = null;
        }

        if(data.error==undefined) {
            $rootScope.aoiService = data;

            try
            {
                $rootScope.renderPeriodChart();
            } catch (err)
            {
                console.error(err);
            }

            if(data.produccion) {
                $rootScope.stockerService.stocker = data.produccion.stocker;
                Stocker.autoscroll($rootScope.stockerService.stocker.paneles);
                /*if(data.produccion.stocker.barcode)
                {
                    socket.emit('stockerInfo',data.produccion.stocker.barcode);
                }*/
            }
        }

        cfpLoadingBar.complete();

        $rootScope.$digest();

//        $scope.$apply();
    });

    socket.on('getProduccionResponseError', function (message) {
        toasty.error({
            title: "Produccion",
            msg: "Error: "+message
        });

        cfpLoadingBar.complete();
        $scope.$apply();
    });

    $rootScope.restartAoiData = function(changeAoi)
    {
        if(changeAoi!=undefined)
        {
            IaCore.storage({
                name: 'aoibarcode',
                value: changeAoi
            });
            $rootScope.configprod.aoibarcode = changeAoi;
        }

        $rootScope.aoiService = {};
        $rootScope.stockerService = {};
        $rootScope.userService = {};

        socket.emit('produccion', $rootScope.configprod.aoibarcode);
    };

    var onScanner = $rootScope.$on('scannerEvent:enter',function(event,data) {
        $rootScope.UserScanned(data.value);
        Stocker.add(data.value);
        Stocker.panelAdd(data.value);
    });

    $rootScope.UserScanned = function(scannedValue) {
        // ENVIA EL BARCODE
        if(
            (
                scannedValue.indexOf("LOGIN") === 0 ||
                scannedValue.indexOf("DLOGIN") === 0
            ) &&
            scannedValue.length > 5 &&
            $rootScope.aoiService.produccion.barcode
        ) {
            var userId = scannedValue.match( /\d+/ );
            if(userId)
            {
                userId = userId[0];
            }

            var userBarcode = scannedValue.replace("DLOGIN", "").replace("LOGIN", "");
            var userName = userBarcode.replace(userId, "");

            toasty.info({
                title: "Inspector",
                msg: "Buscando datos de inspector",
                timeout: 5000
            });

            var credentials = {
                name : userName,
                userid : userId,
                aoibarcode: $rootScope.aoiService.produccion.barcode
            };

            $http({method:'POST',url:'prod/user/login',params:credentials})
            .then(function(result) {
                result = result.data;
                if(result) {
                    if(result.error) {
                        $rootScope.printError('Stocker',result,'modal');
                    } else {
                        toasty.success({
                            title: "Inspector",
                            msg: "Operacion completa",
                            timeout: 5000
                        });

                        $rootScope.userService = result;

                        $timeout(function() {
                            //window.location.reload();
                        },2000);
                    }
                }
            }, function (error) {
                if(error) {
                    if(error.error != undefined) { error = error.error; }
                    toasty.error({
                        title: "Atencion",
                        msg: error,
                        timeout: 5000
                    });
                }
            });
        }
    };
}]);
