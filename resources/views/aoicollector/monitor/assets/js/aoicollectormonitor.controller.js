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
function ($scope,$http, $interval, toasty) {
    $scope.aoiList = [];
    $scope.nodejserror = true;
    $scope.runtime = "";

    const io = ws('arushap34:3333', {});

    const client = io.channel('aoicollectormonitor');
    client.connect(function (error, connected) {
        if (error) {
            console.log(error);
            $scope.nodejserror = true;
            return
        }

        $scope.nodejserror = false;
        console.log('AoicollectorMonitor Connected');
        client.emit('AoicollectorMonitorSubscribe');
        $scope.$apply();
    });

    client.on('disconnect',function() {
        console.log('AoicollectorMonitor Disconnected');
        $scope.nodejserror = true;

        $scope.$apply();
    });

    client.on('AoicollectorMonitorChannel', function (message) {
        var points = 10;
        var server = JSON.parse(message);

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

                var found = false;
                for(var i = 0; i < $scope.aoiList.length; i++) {
                    if ($scope.aoiList[i].aoibarcode == server.aoibarcode) {

                        // Existe dato, actualizo historial de tiempos
                        server.runtimeHistory = $scope.aoiList[i].runtimeHistory;
                        server.runtimeHistory.push(server.tiempoEjecucion);

                        // Si el tiempo actual es mayor al tiempo maximo lo actualizo
                        if(server.tiempoEjecucion > $scope.aoiList[i].tiempoEjecucionMax) {
                            server.tiempoEjecucionMax = server.tiempoEjecucion;
                        } else {
                            // Es necesario definir el tiempo maximo del socket actual con el tiempo maximo anterior
                            // porque el objeto no lo trae definido
                            server.tiempoEjecucionMax = $scope.aoiList[i].tiempoEjecucionMax;
                        }

                        // Si tengo mas de X puntos en el array, quito el primero y obtengo el tiempo maximo del rango actual
                        if(server.runtimeHistory.length>points)
                        {
                            server.runtimeHistory = server.runtimeHistory.slice(1, 11);
                            server.tiempoEjecucionMax = Math.max.apply(null, server.runtimeHistory);
                        }

                        $scope.aoiList[i] = server;
                        found = true;
                        break;
                    }
                }

                if(!found) {
                    server.runtimeHistory =[0,server.tiempoEjecucion];
                    server.tiempoEjecucionMax = server.tiempoEjecucion;
                    $scope.aoiList.push(server);
                }
            break;
        }


        $scope.$apply();
    });
}]);


