<?php
    $prodchart = 'prodchart'.rand(0,99999);
?>

<div id="{{ $prodchart }}container" style="width: 95%;height:{{ isset($height) ? $height : '400' }}px;"></div>

<script>
    $(function () {
        Highcharts.setOptions({
            lang: {
                drillUpText: 'Volver',
                weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
            },
            global: {
                useUTC: true
            }
        });

        var series = [
            {
                name: 'Proyectado',
                type: 'area',
                color: '#ffb6b5',
                dataLabels: {
                    enabled: false,
                },
                marker: {
                    enabled: true,
                    radius: 4,
                    lineColor: '#FF0000',
                    lineWidth: 1
                },
                data: [
                    @foreach($resume->proyectadoCone->where('turno',$turno)->groupBy('chartPeriodo') as $periodo => $items)
                        {
                            x: moment("{{ $periodo }} -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                            y: {{ $items->sum('proyectado') }}
                        },
                    @endforeach
                ]
            },
            {
                name: 'Operador',
                type: 'area',
                color: '#ffead6',
                dataLabels: {
                    enabled: false,
                },
                data: [
                    @foreach($resume->proyectadoCone->where('turno',$turno)->groupBy('chartPeriodo') as $periodo => $items)
                        {
                            x: moment("{{ $periodo }} -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                            y: {{ $items->sum('p_real') }}
                        },
                    @endforeach
                ]
            },

            // Obtengo todas las OP producidas
            @foreach($resume->byOp as $op => $items)
            { // Op serie
                name: '{{ $op }}',
                type: 'column',
                stacking: 'normal',
                data: [
                    @foreach($items->where('turno',$turno)->groupBy('chartPeriodo') as $periodo => $placas)
                        {
                            x: moment("{{ $periodo }} -0000", "YYYY-MM-DD HH:mm:ss Z").valueOf(),
                            y: {{ count($placas) }}
                        },
                    @endforeach
                ]
            },
            @endforeach
        ];

        var chart =  new Highcharts.Chart({
            chart: {
                renderTo: '{{ $prodchart }}container',
                type: 'column'
            },
            title: {
                text: 'Produccion por hora'
            },
            subtitle: {
                text: 'Inspecciones unicas por AOI'
            },
            xAxis: {
                type: "datetime",
                /* dateTimeLabelFormats: {
                 day: '%H'
                 },*/
                tickInterval: moment.duration(1, 'hour').asMilliseconds()
            },
            yAxis: {
                title: {
                    text: 'Produccion'
                }
                /*,
                stackLabels: {
                    enabled: true, // This is ignored <<<<<<
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }*/
            },
            legend: {
                enabled: true
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true,
                useHTML: true,

                formatter: function() {
                    var s = [];

                    var dateFormat = '<b>' + Highcharts.dateFormat('%b %e', this.x) + '</b> ';
                    var hora = parseInt(Highcharts.dateFormat('%H', this.x));
                    dateFormat += ' de ' + hora + ':00 a ' + (hora + 1)+':00' ;

                    s.push(dateFormat);

                    $.each(this.points, function(i, point) {
                        s.push('<span style="color:'+this.series.color+'" class="glyphicon glyphicon glyphicon-record"></span> '+ point.series.name +' : <b>'+point.y+'</b>');
                    });

                    return s.join(' <br> ');
                },

            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        color: '#FFFFFF',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        },
                        backgroundColor: 'rgba(0,0,0,0.3)',
                        borderRadius: 7,
                        padding: 4,
                        formatter: function() {
                            if (this.y != 0) {
                                return this.y;
                            } else {
                                return null;
                            }
                        }
                    }
                }
            },
            series: series
        });
    });
</script>
