var app = angular.module('Cogiscan',['ngRoute']);

app.factory('WebService',['',function($soap){

}]);

app.controller('MainCtrl',function($scope){
    //WebService.getProductionAndConsumptionData('OP-97486-TOP','date,toolId,recipe','rawMatPN').then(function(response){
    //    $scope.response = "Hola";
    //});
    $scope.showTable=false;
    $scope.codigoArecuperar="";
    $scope.showModal=false;
    $scope.showRecoverButton = false;
    $scope.find = function() {
        $scope.showTable = true;
        $scope.info = [{
            attributes:{
                id:"ARRIS"
            },
            RawMat: [{
                pn: "12345",
                consumed: "100",
                mispick:"0",
                notPlaced:"0"
            }, {
                pn: "1456",
                qty: "200"
            }, {
                codigo: "asdfg",
                qty: "45"
            }, {
                codigo: "qwertyt",
                qty: "5060"
            }]
        },
        {
            attributes:{
                id:"ARRIS"
            },
            RawMat: [{
                codigo: "12345",
                qty: "100"
            }, {
                codigo: "1456",
                qty: "200"
            }, {
                codigo: "asdfg",
                qty: "45"
            }, {
                codigo: "qwertyt",
                qty: "5060"
            }]
        }];
        $scope.showRecoverButton = true;
    }
    $scope.Modal = function($codigo){
        $scope.codigoArecuperar=$codigo;
        $scope.showModal= true;
    }
    $scope.hideModal = function(){
        $scope.showModal=!$scope.showModal;
    }
});

app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/',{
                templateUrl:'view/main.html'
            })
    }]);

app.directive('modal',function(){
    return {
        template:'<div class="modal fade">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"">&times;</button>' +
        '<h4 class="modal-title">{{ title }}</h4>' +
        '</div>' +
        '<div class="modal-body" ng-transclude></div>' +
        '</div>' +
        '</div>' +
        '</div>',
        restrict:'E',
        transclude:true,
        replace:true,
        scope:true,
        link: function postLink(scope, element, attrs) {
            scope.title = attrs.title;

            scope.$watch(attrs.visible, function(value){
                if(value == true)
                    $(element).modal('show');
                else
                    $(element).modal('hide');
            });

            $(element).on('shown.bs.modal', function(){
                scope.$apply(function(){
                    scope.$parent[attrs.visible] = true;
                });
            });

            $(element).on('hidden.bs.modal', function(){
                scope.$apply(function(){
                    scope.$parent[attrs.visible] = false;
                });
            });
        }

    }

});