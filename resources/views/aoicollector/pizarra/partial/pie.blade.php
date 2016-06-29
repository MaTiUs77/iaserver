<?php
    $prodchart = 'prodchart_'.$turno.rand(0,99999);
?>
<div id="{{ $prodchart }}" style="height: 400px"></div>
<script>
        $(function () {
            $('#{{ $prodchart }}').highcharts({
                chart: {
                    type: 'pie',
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
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    data: [
                        @if(count($resume->byOp))
                            @foreach($resume->byOp as $op => $item)
                                @if(count($item->periodo[$turno]))
                                    @if($turno=='M')
                                        ['{{ $op }}', {{ $item->produccionM }}],
                                    @else
                                        ['{{ $op }}', {{ $item->produccionT }}],
                                    @endif
                                @endif
                            @endforeach

                            @if(count($item->periodo[$turno]))
                                @if($turno=='M')
                                    @if(($resume->proyectado->cone->M - $resume->produccion->aoi->M)>0)
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
                        @endif
                    ]
                }]
            });
        });
    </script>

