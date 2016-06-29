app.controller("statController",function($scope,$http)
{
    $scope.showLoading = function() {

    }

    $scope.makeGauge = function(ide,valor)
    {
        var canva = document.getElementById(ide);

        if(valor<=0){
            valor = null;
        }

        var opts = {
            lines: 12, // The number of lines to draw
            angle: 0, // The length of each line
            lineWidth: 0.3, // The line thickness
            pointer: {
                length: 0.9, // The radius of the inner circle
                strokeWidth: 0.035, // The rotation offset
                color: '#000000' // Fill color
            },
            limitMax: 'false',   // If true, the pointer will not go past the end of the gauge

            strokeColor: '#E0E0E0',   // to see which ones work best for you
            generateGradient: true,
            percentColors: [[0.0, "#CF3636"], [0.50, "#f9c802"], [1.0, "#249C00"]]
        };

        selectGaguge1 = new Gauge(canva).setOptions(opts);
        selectGaguge1.maxValue = 100;
        selectGaguge1.set(valor);
    }
});