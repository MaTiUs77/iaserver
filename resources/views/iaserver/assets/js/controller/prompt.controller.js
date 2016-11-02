/**
 * Emite el evento prompt:enter luego de ingresar el dato solicitado en el prompter
 */
app.controller("promptController",["$scope","$http","$rootScope", function($scope,$http,$rootScope  ){
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
}]);
