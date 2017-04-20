app.controller("molineteController",
["$scope","$http", "$interval","toasty",
function ($scope,$http, $interval, toasty) {
    $scope.nodejserror = true;

    $scope.lastTest = [];

    const io = ws('arushap34:3333', {});

    const client = io.channel('molinete');
    client.connect(function (error, connected) {
        if (error) {
            console.log(error);
            $scope.nodejserror = true;
            return
        }

        $scope.nodejserror = false;
        console.log('Molinete Connected');
        client.emit('MolineteSubscribe');
        $scope.$apply();
    });

    client.on('disconnect',function() {
        console.log('Molinete Disconnected');
        $scope.nodejserror = true;
        $scope.$apply();
    });

    client.on('MolineteChannel', function (message) {
        var server = JSON.parse(message);
		console.log(server);

        $scope.lastTest.push(server);

        $scope.nodejserror = false;
        $scope.$apply();
    });
}]);


