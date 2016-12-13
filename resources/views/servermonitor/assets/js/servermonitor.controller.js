app.controller("servidorMonitorController",
    ["$scope","$http", "$interval",
    function ($scope,$http, $interval) {
    $("input[dynamicknob]").knob();

    $scope.serverList = [];
    $scope.osmonitor = [];
    $scope.memoriamonitor = [];

    $scope.nodejserror = false;

    $scope.getMonitorStatus = function()
    {
        /*$http.get("servermonitor/redis").then(function(res){
            $scope.serverList = res.data;
            $scope.nodejserror = false;

        }, function errorCallback(response) {
            $scope.serverList = [];
            $scope.nodejserror = true;
        });*/

        $http.get("servermonitor/redis").then(function(res){

            $scope.serverList = res.data.status;

            //$("input[dynamicknob]").val(res.data.status[0].cpu);

            /*
            $scope.osmonitor.push(res.data.cpu[0]);
            $scope.memoriamonitor.push(res.data.memoria.porcent);

            $("input[dynamicknob]").val(res.data.cpu[0]);

            $("[data='cpumonitor']").sparkline($scope.osmonitor, {
                type: 'line',
                height: '30',
                width: '120',
                barWidth: 8,
                barSpacing: 3,
                barColor: '#65edae',
                negBarColor: '#ff5656'
            });

            $("[data='memoriamonitor']").sparkline($scope.memoriamonitor, {
                type: 'line',
                height: '30',
                width: '120',
                barWidth: 8,
                barSpacing: 3,
                barColor: '#65edae',
                negBarColor: '#ff5656'
            });
            */

        }, function errorCallback(response) {
        });
    };

    $interval(function(){
        $scope.getMonitorStatus();
    }, 5000);

    $scope.getMonitorStatus();
}]);