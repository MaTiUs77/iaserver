app.controller("prodHeaderController",[
    "$scope", "$rootScope", "IaCore",
    function($scope, $rootScope , IaCore) {

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
}]);

// STOCKER
app.controller("promptStockerSetController",[
    "$scope", "$rootScope", "$timeout", "Stocker",
    function($scope, $rootScope, $timeout, Stocker) {
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
}]);

app.controller("promptStockerRemoveController",[
    "$scope", "$rootScope", "$timeout", "Stocker",
    function($scope, $rootScope, $timeout, Stocker) {
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
}]);

// PANEL
app.controller("promptStockerAddPanelController",[
    "$scope", "$rootScope", "$timeout", "Stocker",
    function($scope, $rootScope, $timeout, Stocker) {
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
}]);

app.controller("promptStockerRemovePanelController",[
    "$scope", "$rootScope", "$timeout", "Stocker",
    function($scope, $rootScope, $timeout, Stocker) {
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
}]);

// AOI
app.controller("promptAoiSetController",[
    "$scope", "$rootScope", "$timeout", "IaCore", "Aoi",
    function($scope, $rootScope, $timeout, IaCore, Aoi) {
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
}]);

app.controller("btnFormSelectOpController",[
    "$scope", "$rootScope", "$timeout", "IaCore", "Aoi",
    function($scope, $rootScope, $timeout, IaCore, Aoi) {
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
}]);

