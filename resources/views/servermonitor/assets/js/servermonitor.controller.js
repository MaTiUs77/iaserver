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
                normalRangeMax: 80,
                normalRangeColor: '#e5e5e5'
            });
        }
    }
});

// CON ADONIS
app.controller("servidorMonitorController",
["$scope","$http", "$interval","toasty",
function ($scope,$http, $interval, toasty) {
    $scope.serverList = [];
    $scope.nodejserror = true;

    const io = ws('arushap34:3333', {});

    const client = io.channel('servermonitor');
    client.connect(function (error, connected) {
        if (error) {
            console.log(error);
            $scope.nodejserror = true;
            return
        }

        $scope.nodejserror = false;
        console.log('ServerMonitor Connected');
        client.emit('ServerMonitorSubscribe');
    });

    client.on('disconnect',function() {
        console.log('ServerMonitor Disconnected');
        $scope.nodejserror = true;

        $scope.$apply();
    });

    client.on('ServerMonitorChannel', function (message) {
        var server = JSON.parse(message);

        var found = false;
        for(var i = 0; i < $scope.serverList.length; i++) {
            if ($scope.serverList[i].nombre == server.nombre) {
                $scope.serverList[i] = server;
                found = true;
                break;
            }
        }

        if(!found) {
            $scope.serverList.push(server);
        }

        $scope.$apply();
    });
}]);


