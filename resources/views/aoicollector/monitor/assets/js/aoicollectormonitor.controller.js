app.directive('dynchart', function() {
    return {
        scope: {
            data: '='
        },
        link: function(scope, element) {
            //var min = scope.dataMin;
            //var max = scope.dataMax;
            element.sparkline(scope.data, {
                type: 'line',
                width: '100%',
                height: '50',
                lineWidth: 2,
                maxSpotColor: '#ff0000',
                spotRadius: 3,
                normalRangeMin: 0,
                normalRangeMax: 6,
                normalRangeColor: '#e5e5e5'
            });
        }
    }
});

Array.prototype.max = function() {
    return Math.max.apply(null, this);
};

Array.prototype.min = function() {
    return Math.min.apply(null, this);
};

// CON ADONIS
app.controller("aoicollectorController",
["$scope","$http", "$interval","toasty",
function ($scope, $http, $interval, toasty) {
    var io;
    var client;

    $scope.aoiList = [];
    $scope.prodinfoList = [];
    $scope.totalruntime = 0;

    $scope.nodejserror = true;
    $scope.runtime = "";

    $scope.socketserver = 'arushap34:3333';
//    $scope.socketserver = 'localhost:3333';

    io = ws($scope.socketserver, {});
    client = io.channel('aoicollectormonitor');
    client.connect(function (error, connected) {
        if (error) {
            console.log(error);
            $scope.nodejserror = true;
            return
        }

        $scope.nodejserror = false;
        console.log('AoicollectorMonitor Connected');
        client.emit('aoicollector:monitor:subscribe');
        client.emit('aoicollector:prodinfo:subscribe');
        $scope.$apply();
    });

    client.on('disconnect',function() {
        console.log('AoicollectorMonitor Disconnected');
        $scope.nodejserror = true;

        $scope.$apply();
    });

    $scope.findIndexProdinfo = function(barcode) {
        var index = $.map($scope.prodinfoList, function(item, index) {
            if(item.barcode == barcode) {
                return index;
            }
        })[0];

        return index;
    };

    setInterval(function(){

        $scope.aoiList.forEach(function(item, index){
            var lastText = item.lastUpdate.fromNow();
            $scope.aoiList[index].lastUpdateText = lastText;
        });
    },1000*5);

    client.on('aoicollector:monitor:channel', function (message) {
        var points = 10;
        var server = JSON.parse(message);

        $scope.totalruntime = server.total;

        switch(server.mode)
        {
            case 'runtime':
                if(server.total==0) {
                    $scope.runtime = "No hay inspecciones";
                } else {
                    $scope.runtime = "Procesando "+server.current +" de "+ server.total;
                }
            break;
            default:
                // de milisegundos a segundos
                server.tiempoEjecucion = (server.tiempoEjecucion / 1000).toFixed(2);

                // Find monitor
                var findMonitorIndex = $.map($scope.aoiList, function(item, index) {
                    if(item.aoibarcode == server.aoibarcode) {
                        return index;
                    }
                })[0];

                if(findMonitorIndex!=undefined) {
                    // Update monitor

                    var monitor = $scope.aoiList[findMonitorIndex];

                    // Existe dato, actualizo historial de tiempos
                    server.runtimeHistory = monitor.runtimeHistory;
                    server.runtimeHistory.push(server.tiempoEjecucion);

                    // Si el tiempo actual es mayor al tiempo maximo lo actualizo
                    if(server.tiempoEjecucion > monitor.tiempoEjecucionMax) {
                        server.tiempoEjecucionMax = server.tiempoEjecucion;
                    } else {
                        // Es necesario definir el tiempo maximo del socket actual con el tiempo maximo anterior
                        // porque el objeto no lo trae definido
                        server.tiempoEjecucionMax = monitor.tiempoEjecucionMax;
                    }

                    // Si tengo mas de X puntos en el array, quito el primero y obtengo el tiempo maximo del rango actual
                    if(server.runtimeHistory.length>points) {
                        server.runtimeHistory = server.runtimeHistory.slice(1, 11);
                        server.tiempoEjecucionMax = Math.max.apply(null, server.runtimeHistory);
                    }

                    server.prodinfo = $scope.prodinfoList[$scope.findIndexProdinfo(server.aoibarcode)];

                    if(monitor.lastUpdateText==undefined) {
                        console.log("Error",monitor);
                    }

                    server.lastUpdateText = monitor.lastUpdate.fromNow();
                    server.lastUpdate = moment();

                    $scope.aoiList[findMonitorIndex] = server;
                } else {
                    // Add monitor
                    server.runtimeHistory =[0,server.tiempoEjecucion];
                    server.tiempoEjecucionMax = server.tiempoEjecucion;
                    server.lastUpdate = moment();
                    server.lastUpdateText = server.lastUpdate.fromNow();

                    server.prodinfo = $scope.prodinfoList[$scope.findIndexProdinfo(server.aoibarcode)];

                    $scope.aoiList.push(server);
                }
            break;
        }

        $scope.$apply();
    });

    client.on('aoicollector:prodinfo:channel', function (message) {
        var server = JSON.parse(message);

        // Find prod
        var prodInfoIndex = $scope.findIndexProdinfo(server.produccion.barcode);

        if(prodInfoIndex!=undefined) {
            // Update prod
            $scope.prodinfoList[prodInfoIndex] = server.produccion;
        } else {
            // Add prod
            $scope.prodinfoList.push(server.produccion);
        }

        $scope.$apply();
    });
}]);


