/**
 * ANGULAR - COMMON
 * Por defecto la mayoria de los proyectos en angular, van a usar estas librerias.
 *
*/
var app = angular.module("app", ['ngRoute','chieffancypants.loadingBar','ngAnimate','ui.bootstrap','angular-toasty','cfp.loadingBar']);

app.config(['cfpLoadingBarProvider',function(cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = false;
}]);

app.config(['toastyConfigProvider', function(toastyConfigProvider) {
    toastyConfigProvider.setConfig({
        sound: true,
        shake: false,
        showClose: false,
        clickToClose: true,
        theme: "bootstrap"
    });
}]);

/**
 * Configuracion base para datapicker
 */
app.controller("datapickerController",function ($scope) {
    $scope.open = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.datepickerOpened = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
});

/**
 * Emite el evento prompt:enter luego de ingresar el dato solicitado en el prompter
 */
app.controller("promptController",function($scope,$http,$rootScope  ){
    var modal;

    var onShow = $rootScope.$on('modal:show',function(event,value)
    {
        modal = value;
        // Destruyo el evento listener modal:show
        onShow();
    });

    var onHide = $rootScope.$on('modal:hide',function(event,value)
    {
        // Destruyo el evento listener modal:hide
        onHide();
    });

    $scope.promptEnter = function(value)
    {
        if(value)
        {
            modal.prompt_value = value;
            $scope.$emit("prompt:enter",modal);
        }
    };
});


app.controller("scannerController",function($scope, $rootScope)
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
});
