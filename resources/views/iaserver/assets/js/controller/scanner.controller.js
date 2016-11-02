app.controller("scannerController",["$scope","$rootScope", function($scope, $rootScope)
{
    console.log("ScannerController Loaded");
    $rootScope.scannerListener = true;
    $scope.scannerValue = '';

    $scope.scannerReset = false;

    $scope.scannerEvent = function(e)
    {
        if($rootScope.scannerListener) {
            var code = (e.keyCode ? e.keyCode : e.which);
            switch(code) {
                case 13:
                    $scope.$emit("scannerEvent:enter",{
                        value: $scope.scannerValue
                    });

                    if($scope.scannerReset) {
                        $scope.scannerValue = "";
                    }
                    break;
                default:
                    // Continua agregando caracteres a scannerValue
                    if(code!=16) {
                        var stringChar = String.fromCharCode(code);
                        $scope.scannerValue += stringChar;
                    }
                    break;
            }
            $scope.scannerReset = true;
        }
    };
}]);
