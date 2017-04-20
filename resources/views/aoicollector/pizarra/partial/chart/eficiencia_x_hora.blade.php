<?php
$prodchart = 'eficienciachart'.rand(0,99999);
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
                name: 'Produccion',
                type: 'area',
                color: '#ffead6',
                dataLabels: {
                    enabled: false,
                },
                data: [

                    @foreach($resume->byPeriod->where('turno',$turno) as $periodo => $data)
                        @if($data->eficiencia->cone>0)
                        {
                            x: moment("{{ $periodo }} -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                            y: {{ $data->eficiencia->cone }},

                            marker : {
                                enabled : true,
                                radius : 5,
                                symbol: 'circle',
                                lineWidth: 2,
                                lineColor: '#FFE1D2'
                            },


                        @if($data->eficiencia->cone < 80)
                            color: '#ff0202',
                        @else
                            @if($data->eficiencia->cone >90)
                                color: '#6af260', // verde
                            @else
                                color: '#ffff77', // amarillo
                            @endif
                        @endif
                    },
                    @endif

                    @endforeach
                ],
            },
            {
                name: 'Aoi',
                type: 'column',
                stack: 'none',
                color: '#434348',
                dataLabels: {
                    enabled: true,
                    //align: 'right',
                    inside: true,
                    rotation: -90,

                    //verticalAlign: 'middle', // Position them vertically in the middle
                    //align: 'left',

                    color: '#FFFFFF',
                    //align: 'left',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    },
/*
                    backgroundColor: 'rgba(0,0,0,0.3)',
                    borderRadius: 7,
                    padding: 4,
*/
                    formatter:function() {
                        var eficiencia = Highcharts.numberFormat(this.point.y,1);
                        if(eficiencia>0) {
                            return  eficiencia + '%';
                        } else {
                            return null;
                        }
                    }
                },
                data: [
                    @foreach($resume->byPeriod->where('turno',$turno) as $periodo => $data)
                    {
                        x: moment("{{ $periodo }} -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                        y: {{ $data->eficiencia->aoi }},
                        @if($data->eficiencia->aoi <80)
                            color: '#ff0202',
                        @else
                            @if($data->eficiencia->aoi >90)
                                color: '#6af260', // verde
                            @else
                                color: '#ffff77', // amarillo
                            @endif
                        @endif
                    },
                    @endforeach
                ]
            },
        ];

        var chart =  new Highcharts.Chart({
            chart: {
                renderTo: '{{ $prodchart }}container',
                type: 'column'
            },
            title: {
                text: 'Eficiencia de produccion'
            },
            subtitle: {
                text: 'Cruza proyeccion horaria contra y produccion en aoi'
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
                    text: 'Eficiencia'
                }
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
                        s.push('<span style="color:'+this.series.color+'" class="glyphicon glyphicon glyphicon-record"></span> '+ point.series.name +' : <b>'+Highcharts.numberFormat(this.point.y,1)+'%</b>');
                    });

                    return s.join(' <br> ');
                },

            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,

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
        },function(chart){


        });
    });
</script>
