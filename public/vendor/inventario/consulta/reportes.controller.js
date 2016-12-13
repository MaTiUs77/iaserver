app.controller('reportController',function($scope,reportFactory,$http){
    reportFactory.getPrints().then(function(response){
       $scope.impresiones = response.data;
    });
});