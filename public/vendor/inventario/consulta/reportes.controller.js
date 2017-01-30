app.controller('reportController',function($scope,reportFactory,$http){
    reportFactory.getPrints().then(function(response){
       $scope.Electronic = response.data;
        console.log($scope.Electronic);
    });
});