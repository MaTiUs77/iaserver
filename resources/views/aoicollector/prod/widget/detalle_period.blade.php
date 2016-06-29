{!! IAScript('assets/highchart/js/highcharts.js') !!}


<div id="container" style="width: 95%;height:300px;"></div>

<script>
    $(function () {
        var prodchart = null;
        var prodchartoptions = {
            chart: {
                renderTo: 'container',
                type: 'column',
                zoomType: 'x'
            },
            title: {
                text: 'Declaraciones en la interfaz'
            },
            xAxis: {
                type: 'datetime',
                tickInterval: 3600 * 1000,
                title: {
                    text: 'Fecha'
                }
            },
            yAxis: {
                title: {
                    text: 'Total'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y}'
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        borderRadius: 5,
                        backgroundColor: 'rgba(252, 255, 197, 0.7)',
                        borderWidth: 1,
                        borderColor: '#AAA',
                        y: -6
                    }
                }
            },
            series: [{
                name: 'Declaraciones',
                data: [
                    @foreach($period as $fecha)
                        [Date.UTC({{ $fecha->anio }}, {{ $fecha->mes }}, {{ $fecha->dia }}, {{ $fecha->hora }}), {{ $fecha->total }}],
                    @endforeach
                ]
            }]
        }
    });
</script>