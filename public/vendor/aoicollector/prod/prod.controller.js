app.controller("prodChartController",function($rootScope)
{
    $rootScope.renderPeriodChart = function()
    {
        if($rootScope.aoiService.produccion.period!=undefined)
        {
            var allOp = $rootScope.aoiService.produccion.period.map(function(obj) { return obj.op; });
            var opserie = allOp.filter(function(v,i) { return allOp.indexOf(v) == i; });

            $.each(opserie,function(index, op)
            {
                var points = $rootScope.aoiService.produccion.period.filter(function(obj,index) {
                    if(obj.op==op)
                    {
                        return obj;
                    }
                });

                var serieData = [];
                $.each(points,function(index, p)
                {
                    var now = new Date();
                    serieData.push([Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), p.periodo.split(':')[0]), p.total]);
                });

                // Verifica si ya se creo la serie en el chart
                var mapSeries = prodchart.series.map(function(obj){ return obj.name; });
                var serieindex = mapSeries.indexOf(op);

                // La serie no exite, se crea con sus datos adjuntos
                if(serieindex<0)
                {
                    prodchart.addSeries({
                        name: op,
                        data: serieData
                    });
                } else
                {
                    //              var lastcolor = prodchart.series[serieindex].color;
                    //                prodchart.series[serieindex].remove();
                    // La serie existe, actualizo datos
                    prodchart.series[serieindex].setData(serieData);
                    /*prodchart.addSeries({
                     name: op,
                     color: lastcolor,
                     data: serieData
                     });*/
                }

                /* prodchart.addSeries({
                 name: op,
                 color: lastcolor,
                 data: serieData
                 });*/
            });
        }
    }
});

app.controller("prodController",function($scope,$rootScope,$http,$timeout,$interval, IaCore, Aoi, Stocker, Panel, toasty, cfpLoadingBar)
{
    $rootScope.configprod = {
        aoibarcode : IaCore.storage({name:'aoibarcode'})
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

    var socket = io.connect(':8080');

    // I expect this event to be triggered
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
                $rootScope.stockerService = data.produccion.stocker;
                if (
                    $rootScope.stockerService != undefined &&
                    $rootScope.stockerService.paneles != undefined) {
                    Stocker.autoscroll($rootScope.stockerService.paneles);
                }
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

            var userBarcode = scannedValue.replace("DLOGIN", "");
            var userBarcode = userBarcode.replace("LOGIN", "");
            var userName = userBarcode.replace(userId, "");

            toasty.info({
                title: "Inspector",
                msg: "Buscando datos de inspector",
                timeout: 5000
            });

//            userSplit = userBarcode.split('SPLIT');

            var credentials = {
                name : userName,
                userid : userId,
                aoibarcode: $rootScope.aoiService.produccion.barcode
            };

            var authUser = $http({method:'POST',url:'prod/user/login',params:credentials});

            authUser.then(function(result)
            {
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
});

app.controller("prodHeaderController",function($scope, $rootScope , IaCore) {

    $scope.promptAoiSet = function(route)
    {
        IaCore.modal({
            scope: $scope,
            route:route,
            title: 'AOI en produccion',
            type: 'primary',
            controller: 'promptAoiSetController'
        });
    };

    $scope.btnFormSelectOp = function(route)
    {
        if(!$rootScope.btnFormSelectOpProccessing)
        {
            $rootScope.btnFormSelectOpProccessing = true;

            IaCore.modal({
                scope: $scope,
                route: route,
                title: 'Informacion de OP',
                type: 'primary',
                controller: 'btnFormSelectOpController'
            });
        }
    };

    $scope.promptStockerSet = function(route)
    {
        IaCore.modal({
            scope: $scope,
            route:route,
            title: 'Nuevo stocker',
            type: 'info',
            controller: 'promptStockerSetController'
        });
    };

    $scope.promptStockerAddPanel = function(route)
    {
        IaCore.modal({
            scope: $scope,
            route:route,
            title: 'Asignar panel a stocker',
            type: 'warning',
            controller: 'promptStockerAddPanelController'
        });
    };

    $scope.promptStockerReset = function(route)
    {
        IaCore.modal({
            scope: $scope,
            route:route,
            title: 'Liberar stocker',
            type: 'success',
            controller: 'promptStockerRemoveController'
        });
    };

    $scope.promptStockerRemovePanel = function(route)
    {
        IaCore.modal({
            scope: $scope,
            route:route,
            title: 'Remover panel de stocker',
            type: 'danger',
            controller: 'promptStockerRemovePanelController'
        });
    };
});

// STOCKER
app.controller("promptStockerSetController",function($scope, $rootScope, $timeout, Stocker)
{
    var onHide = $rootScope.$on("modal:hide",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = true;
        });
        onHide(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    var onShow = $rootScope.$on("modal:show",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = false;
        });
        onShow(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    $scope.$on("prompt:enter",function(event,modal)
    {
        Stocker.add(modal.prompt_value);
        modal.dialog.close();
    });
});

app.controller("promptStockerRemoveController",function($scope, $rootScope, $timeout, Stocker)
{
    var onHide = $rootScope.$on("modal:hide",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = true;
        });
        onHide(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    var onShow = $rootScope.$on("modal:show",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = false;
        });
        onShow(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    $scope.$on("prompt:enter",function(event,modal)
    {
        Stocker.remove(modal.prompt_value);
        modal.dialog.close();
    });
});

// PANEL
app.controller("promptStockerAddPanelController",function($scope, $rootScope, $timeout,Stocker)
{
    var onHide = $rootScope.$on("modal:hide",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = true;
        });
        onHide(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    var onShow = $rootScope.$on("modal:show",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = false;
        });
        onShow(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    $scope.$on("prompt:enter",function(event,modal)
    {
        Stocker.panelAdd(modal.prompt_value);
        modal.dialog.close();
    });
});

app.controller("promptStockerRemovePanelController",function($scope, $rootScope, $timeout, Stocker)
{
    var onHide = $rootScope.$on("modal:hide",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = true;
        });
        onHide(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    var onShow = $rootScope.$on("modal:show",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = false;
        });
        onShow(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    $scope.$on("prompt:enter",function(event,modal)
    {
        Stocker.panelRemove(modal.prompt_value);
        modal.dialog.close();
    });
});

// AOI
app.controller("promptAoiSetController",function($scope, $rootScope, $timeout, IaCore, Aoi)
{
    var onHide = $rootScope.$on("modal:hide",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = true;
        });
        onHide(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    var onShow = $rootScope.$on("modal:show",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = false;
        });
        onShow(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    $scope.$on("prompt:enter",function(event,modal)
    {
        $rootScope.restartAoiData(modal.prompt_value);
        modal.dialog.close();
    });
});

app.controller("btnFormSelectOpController",function($scope, $rootScope, $timeout, IaCore, Aoi)
{
    var onHide = $rootScope.$on("modal:hide",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = true;
            $rootScope.btnFormSelectOpProccessing = false;

        });
        onHide(); // Es necesario volver a llamar el metodo, para destruirlo
    });

    var onShow = $rootScope.$on("modal:show",function(event,modal)
    {
        $timeout(function() {
            $rootScope.scannerListener = false;
            $rootScope.btnFormSelectOpProccessing = false;
        });
        onShow(); // Es necesario volver a llamar el metodo, para destruirlo
    });
});

