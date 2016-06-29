<?php
    $prodchart = 'prodchart_'.$turno.rand(0,99999);
?>

    <div style="text-align: center">
    @if($turno=='M')

        @if($resume->proyectado->cone->reporteIncompleto->M > 0)
            @if($resume->proyectado->cone->reporteIncompleto->M == 1)
                <span class="label label-danger">Se detecto <b>({{  $resume->proyectado->cone->reporteIncompleto->M }})</b> reporte incompleto</span>
            @else
                <span class="label label-danger">Se detectaron <b>({{  $resume->proyectado->cone->reporteIncompleto->M }})</b> reportes incompletos</span>
            @endif
        @endif
    @else

        @if($resume->proyectado->cone->reporteIncompleto->T > 0)
            @if($resume->proyectado->cone->reporteIncompleto->T == 1)
                <span class="label label-danger">Se detecto <b>({{  $resume->proyectado->cone->reporteIncompleto->T }})</b> reporte incompleto</span>
            @else
                <span class="label label-danger">Se detectaron <b>({{  $resume->proyectado->cone->reporteIncompleto->T }})</b> reportes incompletos</span>
            @endif
        @endif
    @endif
    </div>

    @include('aoicollector.pizarra.partial.pie',[
        'turno' => $turno
    ])

    <div id="{{ $prodchart }}container2" style="width: 95%;height:300px;"></div>
    <div id="{{ $prodchart }}container" style="width: 95%;height:{{ isset($height) ? $height : '300' }}px;"></div>

    <script>
        $(function () {
            var tooltip_a = {
                useHTML: true,
                backgroundColor: null,
                borderWidth: 0,
                shadow: false,
                formatter: function () {
                    var s = '<b>' + Highcharts.dateFormat('%b %e', this.x) + '</b> ';
                    var hora = parseInt(Highcharts.dateFormat('%H', this.x));
                    s += ' de ' + hora + ':00 a ' + (hora + 1)+':00' ;

                    $.each(this.points, function () {
                        s += '<br/><span style="color:'+this.series.color+'" class="glyphicon glyphicon glyphicon-record"></span> '+this.series.name+': <b>' + this.y + '</b>';
                    });

                    var div = '<div style="background-color:#fffef2; padding: 5px; border-radius: 5px; box-shadow: 2px 2px 2px;" > ' + s + '</div>';
                    return div;
                }
            };
            var tooltip_b = {
                useHTML: true,
                backgroundColor: null,
                borderWidth: 0,
                shadow: false,
                formatter: function () {
                    var s = '<b>' + Highcharts.dateFormat('%b %e', this.x) + '</b> ';
                    var hora = parseInt(Highcharts.dateFormat('%H', this.x));
                    s += ' de ' + hora + ':00 a ' + (hora + 1)+':00' ;

                    $.each(this.points, function () {
                        s += '<br/><span style="color:'+this.series.color+'" class="glyphicon glyphicon glyphicon-record"></span> '+this.series.name+': <b>' + this.y + '%</b>';
                    });

                    var div = '<div style="background-color:#fffef2; padding: 5px; border-radius: 5px; box-shadow: 2px 2px 2px;" > ' + s + '</div>';
                    return div;
                }
            };

            var option_series = [
                {
                    name: 'Proyectado',
                    type: 'area',
                    color: '#ffb6b5',
                    marker: {
                        enabled: true,
                        radius: 4,
                        lineColor: '#FF0000',
                        lineWidth: 1
                    },
                    data: [
                        @foreach($resume->byTurn[$turno] as $item)
                            @if(isset($item->cone->proyectado))
                            {
                                x: moment("{{ \Carbon\Carbon::now()->year }}-{{ \Carbon\Carbon::now()->month }}-{{ \Carbon\Carbon::now()->day }} {{ $item->hora }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                                y: {{ $item->cone->proyectado}}
                            },
                            @endif
                        @endforeach
                    ]
                },
                {
                    name: 'Operador',
                    type: 'area',
                    color: '#ffead6',
                    data: [
                        @foreach($resume->byTurn[$turno] as $item)
                            @if(isset($item->cone->produccion))
                                {
                                    x: moment("{{ \Carbon\Carbon::now()->year }}-{{ \Carbon\Carbon::now()->month }}-{{ \Carbon\Carbon::now()->day }} {{ $item->hora }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                                    y: {{ $item->cone->produccion}}
                                },
                            @endif
                        @endforeach
                    ]
                },

                // Obtengo todas las OP producidas
                @foreach($resume->byOp as $op => $item)
                    @if(count($item->periodo[$turno]))

                {
                    name: '{{ $op }}',
                    type: 'column',
                    dataLabels: {
                        enabled: true,
                        borderRadius: 2,
                        backgroundColor: 'rgba(252, 255, 197, 0.7)',
                        borderWidth: 1,
                        borderColor: '#AAA',
                        y: -6
                    },
                    data: [
                        @foreach($item->periodo[$turno] as $hora => $total)
                            {
                                x: moment("{{ \Carbon\Carbon::now()->year }}-{{ \Carbon\Carbon::now()->month }}-{{ \Carbon\Carbon::now()->day }} {{ $hora }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                                y: {{ $total }}
                            },
                        @endforeach
                    ]
                },
                    @endif
                @endforeach
            ];

            var option_series2 = [
                {
                    name: 'Produccion',
                    type: 'area',
                    color: '#FFE1D2',

                    dataLabels: {
                        rotation: -90,
                        y: -20,
                        color: '#FFFFFF',
                        align: 'left',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        },
                        enabled: false,
                        formatter: function () {
                            return this.y + '%';
                        }
                    },
                    data: [
                        @foreach($resume->byTurn[$turno] as $item)
                            @if(isset($item->cone->porcentaje))
                                {
                                    marker : {
                                        enabled : true,
                                        radius : 5,
                                        symbol: 'circle',
                                        lineWidth: 2,
                                        lineColor: '#FFE1D2'
                                    },

                                    @if($item->cone->porcentaje <80)
                                        color: '#ff0202', // rojo
                                    @else
                                        @if($item->cone->porcentaje >90)
                                            color: '#6af260', // verde
                                        @else
                                            color: '#ffff77', // amarillo
                                        @endif
                                    @endif
                                    x: moment("{{ \Carbon\Carbon::now()->year }}-{{ \Carbon\Carbon::now()->month }}-{{ \Carbon\Carbon::now()->day }} {{ $item->hora }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                                    y: {{ $item->cone->porcentaje}}
                                },
                            @endif
                        @endforeach
                    ]
                },
                {
                    name: 'Aoi',
                    type: 'column',
                    stack: 'none',
                    color: '#434348',
                    dataLabels: {
                        rotation: -90,
                        color: '#FFFFFF',
                        y: 20,
                        align: 'left',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        },
                        enabled: true,
                        formatter: function () {
                            return this.y + '%';
                        }
                    },
                    data: [
                        @foreach($resume->byHour as $item)
                            @if($item->turno == $turno && isset($item->aoi))
                                {
                                    marker : {
                                        enabled : true,
                                        radius : 5,
                                        symbol: 'circle',
                                        lineWidth: 1,
                                        lineColor: '#434348'
                                    },
                                    @if($item->aoi->porcentaje <80)
                                        color: '#ff0202',
                                    @else
                                        @if($item->aoi->porcentaje >90)
                                            color: '#6af260', // verde
                                        @else
                                            color: '#ffff77', // amarillo
                                        @endif
                                    @endif
                                    x: moment("{{ \Carbon\Carbon::now()->year }}-{{ \Carbon\Carbon::now()->month }}-{{ \Carbon\Carbon::now()->day }} {{ $item->hora }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),
                                    y: {{ $item->aoi->porcentaje }}
                                },
                            @endif
                        @endforeach
                    ]
                },
            ];

            var chart = chartController('{{ $title }}','{{ $prodchart }}container',option_series,tooltip_a, true, false);
            chart.draw();

            var chart2 = chartController('Eficiencia de linea','{{ $prodchart }}container2',option_series2,tooltip_b, true, false);
            chart2.draw();

        });
    </script>

