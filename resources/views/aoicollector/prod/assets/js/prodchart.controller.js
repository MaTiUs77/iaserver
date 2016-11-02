app.controller("prodChartController",[
    "$rootScope",
    function($rootScope) {
    $rootScope.renderPeriodChart = function()
    {
        if($rootScope.aoiService.produccion.period!=undefined)
        {
            var allOp = $rootScope.aoiService.produccion.period.map(function(obj) { return obj.op; });
            var opserie = allOp.filter(function(v,i) { return allOp.indexOf(v) == i; });

            $.each(opserie,function(index, op)
            {
                var points = $rootScope.aoiService.produccion.period.filter(function(obj,index) {
                    if(obj.op==op)
                    {
                        return obj;
                    }
                });

                var serieData = [];
                $.each(points,function(index, p)
                {
                    var now = new Date();
                    serieData.push([Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), p.periodo.split(':')[0]), p.total]);
                });

                // Verifica si ya se creo la serie en el chart
                var mapSeries = prodchart.series.map(function(obj){ return obj.name; });
                var serieindex = mapSeries.indexOf(op);

                // La serie no exite, se crea con sus datos adjuntos
                if(serieindex<0)
                {
                    prodchart.addSeries({
                        name: op,
                        data: serieData
                    });
                } else
                {
                    //              var lastcolor = prodchart.series[serieindex].color;
                    //                prodchart.series[serieindex].remove();
                    // La serie existe, actualizo datos
                    prodchart.series[serieindex].setData(serieData);
                    /*prodchart.addSeries({
                     name: op,
                     color: lastcolor,
                     data: serieData
                     });*/
                }

                /* prodchart.addSeries({
                 name: op,
                 color: lastcolor,
                 data: serieData
                 });*/
            });
        }
    }
}]);
