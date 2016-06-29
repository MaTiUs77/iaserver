app.controller("prodController",function($scope,$rootScope,$http,$timeout,$interval, IaCore, Aoi, Stocker, Panel, toasty)
{
    $rootScope.configprod = {
        refresh_time : 4,
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

    $scope.renderPeriodChart = function()
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

    $rootScope.getAoiData = function() {
        Aoi.info($rootScope.configprod.aoibarcode,$scope).then(function(data) {
            $rootScope.aoiService = data;
            $rootScope.userService = data.produccion.inspector;

            var produccion = [];

            $scope.renderPeriodChart();

            if(data.produccion) {
                $rootScope.stockerService = data.produccion.stocker;
                if (
                    $rootScope.stockerService != undefined &&
                    $rootScope.stockerService.paneles != undefined) {
                    Stocker.autoscroll($rootScope.stockerService.paneles);
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
    };
    $rootScope.restartAoiData = function(changeAoi)
    {
        Aoi.cancel();
        $rootScope.interval("stop");

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

        prodchartReset();

        $rootScope.getAoiData();
        $rootScope.interval();
    };

    // Intervalo de ejecucion, cada cierto tiempo, refresca datos de pantalla.
    $rootScope.interval = function(stop)  {
        if(stop=="stop")  {
            $interval.cancel($rootScope.useInterval);
        } else {
            $rootScope.useInterval = $interval($rootScope.getAoiData, $rootScope.configprod.refresh_time * 1000);
        }
    };

    // Inicio intervalo
    $rootScope.interval();
    $rootScope.getAoiData();

    var onScanner = $rootScope.$on('scannerEvent:enter',function(event,data) {
        $rootScope.UserScanned(data.value);
        $rootScope.StockerScanned(data.value);
        $rootScope.PanelScanned(data.value);
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
                        console.log(result);
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

    $rootScope.StockerScanned = function(scannedValue) {
        scannedValue = scannedValue.toUpperCase();
        if(Stocker.valid(scannedValue)) {
            $rootScope.interval("stop");
            Aoi.cancel();

            Stocker.set(
                scannedValue,
                $rootScope.configprod.aoibarcode
            ).then(function(result) {

                if(result) {
                    if(result.error) {
                        $rootScope.printError('Stocker',result,'modal');
                    } else {
                        toasty.success({
                            title: "Stocker",
                            msg: "Agregado correctamente",
                            timeout: 5000
                        });
                    }
                }
                $rootScope.stockerService = {};

                // Reanudo datos de AOI
                $rootScope.getAoiData();
                $rootScope.interval();
            },function(result){
                $rootScope.printError('Stocker',result);
            });
        }
    };

    $rootScope.PanelScanned = function(scannedValue) {
        // Verifico si se escaneo una placa
        if(Panel.valid(scannedValue)) {
            $rootScope.interval("stop");
            Aoi.cancel();

            Panel.add(
                scannedValue,
                $rootScope.configprod.aoibarcode
            ).then(function(result) {
                if(result) {
                    if(result.error) {
                        $rootScope.printError('Panel',result,'modal');
                    } else {
                        toasty.success({
                            title: "Pannel",
                            msg: "Agregado correctamente",
                            timeout: 5000
                        });
                    }
                }
                // Reanudo datos de AOI
                $rootScope.getAoiData();
                $rootScope.interval();
            },function(result){
                $rootScope.printError('Panel',result);
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
app.controller("promptStockerSetController",function($scope, $rootScope , $timeout)
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
        $rootScope.StockerScanned(modal.prompt_value);
        modal.dialog.close();
    });
});

app.controller("promptStockerRemoveController",function($scope, $rootScope , $timeout, IaCore, Stocker, Aoi, toasty)
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
        scannedValue = modal.prompt_value.toUpperCase();
        if(Stocker.valid(scannedValue)) {
            $rootScope.interval("stop");
            Aoi.cancel();

            Stocker.remove(scannedValue).then(function(result) {
                if(result) {
                    if(result.error) {
                        $rootScope.printError('Liberar Stocker',result,'modal');
                    } else {
                        toasty.success({
                            title: "Stocker",
                            msg: "Liberado correctamente",
                            timeout: 5000
                        });
                    }
                }
                // Reanudo datos de AOI
                $rootScope.getAoiData();
                $rootScope.interval();
            },function(result){
                $rootScope.printError('Liberar Stocker',result);
            });
        }

        modal.dialog.close();
    });
});

// PANEL
app.controller("promptStockerAddPanelController",function($scope, $rootScope , $timeout)
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
        $rootScope.PanelScanned(modal.prompt_value);
        modal.dialog.close();
    });
});

app.controller("promptStockerRemovePanelController",function($scope, $rootScope , $timeout,  Panel, Aoi, toasty)
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
        scannedValue = modal.prompt_value.toUpperCase();
        if(Panel.valid(scannedValue)) {
            $rootScope.interval("stop");
            Aoi.cancel();

            Panel.remove(scannedValue).then(function(result) {
                if(result) {
                    if(result.error) {
                        $rootScope.printError('Liberar Panel',result,'modal');
                    } else {
                        toasty.success({
                            title: "Panel",
                            msg: "Removido correctamente",
                            timeout: 5000
                        });
                    }
                }
                // Reanudo datos de AOI
                $rootScope.getAoiData();
                $rootScope.interval();
            },function(result){
                if(result) {
                    $rootScope.printError('Liberar Panel',result);
                }
            });
        }

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
