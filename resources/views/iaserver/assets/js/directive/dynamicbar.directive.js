/*
    Depende de la libreria SparkLine
        'adminlte/plugins/sparkline/jquery.sparkline.min.js'
 */
app.directive('dynamicbar', function() {
    return {
        scope: {
            data: '='
        },
        link: function(scope, element) {
            element.sparkline(scope.data, {
                type: 'line',
                height: '30',
                width: '120',
                barWidth: 8,
                barSpacing: 3,
                barColor: '#65edae',
                negBarColor: '#ff5656'
            });
        }
    }
});


app.directive('dynamicknob', function() {
    return {
        link: function(scope, element) {
            element.knob();
        }
    }
});