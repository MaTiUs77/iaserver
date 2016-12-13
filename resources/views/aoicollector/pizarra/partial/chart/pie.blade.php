<?php
    $prodchart = 'pie_chart'.rand(0,999999);
    /*
        Variables en uso
        $turno
        $resume->produccion->aoi->M
        $resume->proyectado->cone->M
        $resume->byOp
    */
?>
<div id="{{ $prodchart }}" style="height: 400px"></div>
<script>
        $(function () {
            $('#{{ $prodchart }}').highcharts({
                chart: {
                    type: 'pie'
                },
                credits: {
                    enabled: false
                },
                title: {
                    @if($turno=='M')
                        text: 'Resumen turno Mañana'
                    @else
                        text: 'Resumen turno Tarde'
                    @endif
                },
                subtitle: {
                    @if($turno=='M')
                        text: 'Producido: {{ $resume->produccion->aoi->M  }} / Proyectado: {{ $resume->proyectado->cone->M   }}'
                    @else
                        text: 'Producido: {{ $resume->produccion->aoi->T  }} / Proyectado: {{ $resume->proyectado->cone->T   }}'
                    @endif
                },
                tooltip: {
                    pointFormat: '<b>{point.y} Placas / {point.percentage:.1f}% </b>'
                },

                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.y} Placas</b>  <b>{point.percentage:.1f} %</b>',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    type: 'pie',
                    data: [
                        @if(count($resume->byOp))
                            // Mientras tenga OP
                            @foreach($resume->byOp as $op => $item)
                                @if($turno=='M')
                                    @if(isset($item->produccion->M) && $item->produccion->M > 0)
                                        ['{{ $op }}', {{ $item->produccion->M }}],
                                    @endif
                                @endif

                                @if($turno=='T')
                                    @if(isset($item->produccion->T) && $item->produccion->T > 0)
                                         ['{{ $op }}', {{ $item->produccion->T }}],
                                    @endif
                                @endif
                            @endforeach

                            @if($turno=='M')
                                @if(isset($resume->proyectado->cone->M) && isset($resume->produccion->aoi->M) && ($resume->proyectado->cone->M - $resume->produccion->aoi->M)>0)
                                    {
                                        name: 'Produccion faltante',
                                        y: {{  $resume->proyectado->cone->M  - $resume->produccion->aoi->M }},
                                        color: '#FF0000',
                                        sliced: true,
                                        selected: true
                                    }
                                @endif
                            @else
                                @if(($resume->proyectado->cone->T - $resume->produccion->aoi->T)>0)
                                {
                                    name: 'Produccion faltante',
                                    y: {{  $resume->proyectado->cone->T  - $resume->produccion->aoi->T }},
                                    color: '#FF0000',
                                    sliced: true,
                                    selected: true
                                }
                                @endif
                            @endif
                        @endif
                    ]
                }]
            });
        });
    </script>

