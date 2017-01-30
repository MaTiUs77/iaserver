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

app.controller("servidorMonitorController",
    ["$scope","$http", "$interval","toasty",
    function ($scope,$http, $interval, toasty) {

    $scope.serverList = [];

    var socket = io.connect('http://ARUSHAP34:8081');

    socket.on('connect_error', function(err){
        console.log("Error de conexion, servidor caido",err);
    });

    socket.on('redisError', function(message){
        console.log(message);
    });

    socket.on('disconnect', function () {
        console.log("Conexion finalizada");
    });

    socket.on('connect', function(){
        console.log("Conectado");
        socket.emit('subscribe', 'servermonitor');
    });

    socket.on('subscribeResponse', function(message){
        console.log(message);
    });

    socket.on('message', function(message){
        var server = JSON.parse(message);

        var found = false;
        for(var i = 0; i < $scope.serverList.length; i++) {
            if ($scope.serverList[i].nombre == server.nombre) {
                $scope.serverList[i] = server;
                found = true;
                break;
            }
        }

        if(!found)
        {
            $scope.serverList.push(server);
        }

        $scope.$apply();

    });
}]);

