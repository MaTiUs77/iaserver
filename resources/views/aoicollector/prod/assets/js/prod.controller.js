app.controller("prodController",
    ["$scope","$rootScope","$http","$timeout","$interval", "IaCore", "Aoi", "Stocker", "Panel","Inspector", "toasty", "cfpLoadingBar",
    function($scope,$rootScope,$http,$timeout,$interval, IaCore, Aoi, Stocker, Panel, Inspector, toasty, cfpLoadingBar) {

	var io;
	var client;
	
    $rootScope.lastScannerCommand = { cmd:'', toastId:0 };
    $rootScope.configprod = {
        aoibarcode : IaCore.storage({name:'aoibarcode'})
    };
	
    $rootScope.aoiService = {};
    $rootScope.stockerService = {};
    $rootScope.inspectorService = {};

//    $rootScope.socketserver = 'arushap34:3333';
    $rootScope.socketserver = 'localhost:3333';

    io = ws($rootScope.socketserver, {});
    client = io.channel('inspectordash');
    client.connect(function (error, connected) {
        if (error) {
            console.log(error);
            return
        }

        console.log('InspectorDash Connected');

        toasty.wait({
            title: "Produccion",
            msg: "Descargando informacion",
            timeout: false,
            onAdd: function(){
                client.emit('start',$rootScope.configprod.aoibarcode,this.id);
            }
        });

        $scope.$apply();
    });

    client.on('start:response',function(result,toastId) {
        console.log('start:response',result);

        if(result.trycatch)
        {
            toasty.clear(toastId);
            toasty.error({
                title: "Produccion",
                msg: result.trycatch,
                timeout: 3000
            });
        } else
        {
            toasty.clear(toastId);

            if(result.error)
            {
                toasty.error({
                    title: "Produccion",
                    msg: result.error,
                    timeout: 2000
                });
            } else {
                toasty.success({
                    title: "Produccion",
                    msg: "Descarga completa",
                    timeout: 2000
                });
            }

            $rootScope.aoiService = result;
            $rootScope.stockerService = result.produccion.stocker;
            $rootScope.inspectorService = result.produccion.inspector;

           // client.emit('subscribe:stocker',$rootScope.stockerService.barcode);
            //client.emit('subscribe:prodinfo',$rootScope.aoiService.produccion.barcode);

            Stocker.autoscroll($rootScope.stockerService.paneles);
        }


        setInterval(function(){
            client.emit('prod:info',$rootScope.configprod.aoibarcode);
        },1000);

        $scope.$apply();
    });

    client.on('stocker:channel:response',function(result) {
        result = JSON.parse(result);
        console.log('stocker:channel:response',result);
        $rootScope.stockerService = result;

        Stocker.autoscroll($rootScope.stockerService.paneles);

        $scope.$apply();
    });

    client.on('prodinfo:channel:response',function(result) {
        result = JSON.parse(result);
        console.log('prodinfo:channel:response',result);

        $rootScope.aoiService = result;
        $rootScope.stockerService = result.produccion.stocker;
        $rootScope.inspectorService = result.produccion.inspector;

        Stocker.autoscroll($rootScope.stockerService.paneles);

        $scope.$apply();
    });

    client.on('prod:info:response',function(result) {
        console.log('prod:info:response',result);

        if(result.trycatch)
        {
            toasty.error({
                title: "Produccion",
                msg: result.trycatch,
                timeout: 3000
            });
        } else
        {
            if(result.error)
            {
                toasty.error({
                    title: "Produccion",
                    msg: result.error,
                    timeout: 2000
                });
            }

            $rootScope.aoiService = result;
            $rootScope.stockerService = result.produccion.stocker;
            $rootScope.inspectorService = result.produccion.inspector;

            Stocker.autoscroll($rootScope.stockerService.paneles);
        }

        $scope.$apply();
    });

    /*
     client.on('channel:data',function(msg) {
     console.log(msg.channel,msg.data);
     switch(msg.channel) {
     case 'inspectordash:reproceso:stocker':
     $rootScope.stockerService = msg.data;

     console.log(msg.data);
     break;
     }

     $scope.$apply();
     });
     */

    client.on('disconnect',function() {
        console.log("InspectorDash Disconnected");
        toasty.warning({
            title: "Produccion",
            msg: "Desconectado del servidor"
        });

        $scope.$apply();
    });

    client.on('connect_error', function(){
        toasty.error({
            title: "Produccion",
            msg: "Error de conexion, servidor caido",
            timeout: 5000
        });
        $scope.$apply();
    });

    Stocker.nodeInit(client);
    Inspector.nodeInit(client);

    $rootScope.printError = function(title,result,modal) {
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

        /*

    client.on('getProduccionResponse', function (data) {
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
            }
        }

        cfpLoadingBar.complete();

        $rootScope.$digest();
    });

        client.on('getProduccionResponseError', function (message) {
        toasty.error({
            title: "Produccion",
            msg: "Error: "+message
        });

        cfpLoadingBar.complete();
        $scope.$apply();
    });
 */

    $rootScope.restartAoiData = function(changeAoi)
    {
        toasty.wait({
            title: "Configuracion",
            msg: "Aplicando cambios...",
            timeout: false,
            onAdd: function(){
                /*$rootScope.lastScannerCommand = {
                    cmd : data.value.toUpperCase(),
                    toastId: this.id
                };*/
            }
        });

        if(changeAoi!=undefined)
        {
            IaCore.storage({
                name: 'aoibarcode',
                value: changeAoi
            });
            $rootScope.configprod.aoibarcode = changeAoi;
        }

        $rootScope.aoiService = {};

        client.emit('start', $rootScope.configprod.aoibarcode);
    };

    var onScanner = $rootScope.$on('scannerEvent:enter',function(event,data) {

        switch($rootScope.lastScannerCommand.cmd)
        {
            case 'CMDSTKREM':
                Stocker.remove(data.value);
                $rootScope.lastScannerCommand.cmd = '';

                toasty.clear($rootScope.lastScannerCommand.toastId);

                break;
            case 'CMDPANREM':
                Stocker.panelRemove(data.value);
                $rootScope.lastScannerCommand.cmd = '';

                toasty.clear($rootScope.lastScannerCommand.toastId);
                break;
            default:
                if($rootScope.lastScannerCommand.cmd=='' && data.value.toUpperCase().indexOf("CMD") === 0) {

                    toasty.wait({
                        title: "Comando de etiqueta",
                        msg: "Esperando escaneo del elemento",
                        timeout: false,
                        onAdd: function(){
                            $rootScope.lastScannerCommand = {
                                cmd : data.value.toUpperCase(),
                                toastId: this.id
                            };
                        }
                    });

                }

                Inspector.auth(data.value);
                Stocker.add(data.value);
                Stocker.panelAdd(data.value);
            break;
        }

    });
}]);
